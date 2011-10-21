#!/usr/bin/perl -w

package main;

#
# Imager version
#
# @todo -- thumbnails for movies are broken, move to ffmpegthumbnailer 2.0.4
#

#
# available switches:
# --http   -- will use http get insted of NFS local file read
# --store  -- will store generated file on disc
# --syslog -- will use syslog instead of STDERR for debug messages
# --devel  -- will switch on some additional information and behaviour typical for
#             environments

use common::sense;

use URI;
use Sys::Hostname;
use FCGI;
use FCGI::ProcManager;
use Imager;
use Image::LibRSVG;
use Image::Info qw(image_info);
use File::LibMagic;
use File::Basename;
use File::Path qw(make_path);
use File::Copy;
use File::Slurp;
use XML::Simple;
use Data::Types qw(:all);
use Math::Round qw(round);
use Getopt::Long;
use Time::HiRes qw(gettimeofday tv_interval);
use Getopt::Long;
use LWP::UserAgent;
use DateTime;
use Cwd;
use Try::Tiny;
use Sys::Syslog;


#
# constants
#
use constant FFMPEG   => "/usr/bin/ffmpeg";
use constant OGGTHUMB => "/usr/bin/oggThumb";
use constant SVG_DEFAULT_WIDTH => 512;
use constant SVG_DEFAULT_HEIGHT => 512;

#
# globals (flags mainly)
#
our $hostname = hostname;
our $debug    = $ENV{ "DEBUG" } || 1;
our $use_http    = 0;
our $use_devel   = 0;
our $use_store   = 0;
our $use_syslog  = 0;


#
# change new files group permissions (g+w)
#
umask(0002);

#
# print out 404 page
#
sub real404 {
	my $request_uri  = shift;
	print "HTTP/1.0 404 Not Found\r\n";
	print "Cache-control: max-age=30\r\n";
	print "Connection: close\r\n";
	print "X-Thumbnailer-Hostname: $hostname\r\n";
	print "Content-Type: text/html; charset=utf-8\r\n\r\n";
	print qq{
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>Error 404: Page not found</title></head>
<body style="font-family: sans-serif; font-size: 10pt;">
<h1 style="font-size: large;">Error 404: Page not found</h1>
<hr style="border-top: 1px solid black;" />
	Please move along people, nothing to see here. Especially, there is no <strong>$request_uri</strong> file. But still, you can
	go for example to our <a href="http://www.wikia.com/">Main Page</a>.
</body>
</html>
		};
}

#
# print out error page
#
sub real503 {
	my ( $request_uri, $original )  = @_;

	print "HTTP/1.1 503 Service Unavailable\r\n";
	print "Cache-control: max-age=30\r\n";
	print "Retry-After: 30\r\n";
	print "Connection: close\r\n";
	print "X-Thumbnailer-Error: backend not responding\r\n";
	print "X-Thumbnailer-Hostname: $hostname\r\n";
	print "Content-Type: text/plain; charset=utf-8\r\n\r\n";
	print "Backend for getting original file for $request_uri thumbnail is not responding\n";
}

#
# go trough tests and show results
#
sub testResults {
	my( $basepath, $tests ) = @_;
	for my $test ( @$tests ) {
		print $basepath.$test."\n";
	}
}

#
# scaleHeight function compatible with MediaWiki
#
sub scaleHeight {
	my( $srcWidth, $srcHeight, $dstWidth, $test ) = @_;
	my $dstHeight;
	if( $srcWidth == 0  )  {
		$dstHeight = 0;
	}
	else {
		$dstHeight = round( $srcHeight * $dstWidth /  $srcWidth );
	}
	print qq/$srcWidth x $srcHeight -> $dstWidth x $dstHeight\n/ if( $test );
	return $dstHeight;
}

#
# taken from ImageFunctions.php
#
sub scaleSVGUnit {
	my( $size ) = @_;

	return 0 unless defined $size;

	my %units = (
		"px" => 1.0,
		"pt" => 1.25,
		"pc" => 15.0,
		"mm" => 3.543307,
		"cm" => 35.43307,
		"in" => 90.0,
		"em" => 16.0, # fake it?
		"ex" => 12.0, # fake it?
	);

	if( $size =~ /^\s*(\d+(?:\.\d+)?)(em|ex|px|pt|pc|cm|mm|in|%|)\s*$/ ) {
		$size = to_float( $1 );
		my $u = $2;

		if( $u eq "%" ) {
			$size = $size * 0.01 * SVG_DEFAULT_WIDTH;
		}
		elsif( exists( $units{ $u } ) ) {
			$size = $size * $units{ $u };
		}
	}

	$size = to_float( $size );

	return $size;
}

#
# video thumbnail, by default oggThumb will be used.
# as for now $seek is ignored
#
sub videoThumbnail {
	my ( $original, $thumbnail, $seek ) = @_;

	#
	# create folder for thumbnail if doesn't exists
	#
	my $thumbdir = dirname( $thumbnail );
	unless( -d $thumbdir ) {
		eval { mkpath( $thumbdir, { mode => 0775 } ) };
	}

	my $useFfmpeg = 0;

	if( $useFfmpeg ) {
		#
		# use ffmpeg
		#
		my @cmd = ();
		push @cmd, qw(-ss 1);
		push @cmd, "-an";
		push @cmd, qw(-vframes 1);
		push @cmd, "-y";
		push @cmd, "-i", $original;
		push @cmd, qw(-f mjpeg);
		push @cmd, $thumbnail;

		open( CMD, "-|", FFMPEG, @cmd );
		close( CMD );

		unless( -f $thumbnail ) {
			#
			# get first stream
			#
			unshift @cmd, qw(-map 0:1);
			open( CMD, "-|", FFMPEG, @cmd );
			close( CMD );
		}
	}
	else {
		#
		# use oggThumb, but first change current working directory to /tmp
		#
		my $pwd = getcwd();
		chdir( "/tmp" );

		my @cmd = ();
		push @cmd, qw(-o jpg);
		push @cmd, qw(-t 0);
		push @cmd, $original;
		open( CMD, "-|", OGGTHUMB, @cmd );
		my @result = <CMD>;
		close( CMD );

		#
		# check result for thumbnail name, in future version it will
		# be parametrized
		#
		my $out = join "", @result;
		my ( $file ) = $out =~ m/writing (.+)/;
		move( $file, $thumbnail );
		chdir( $pwd );
	}
}

#
# write file to disc
#
sub store_file {
	my( $path, $data ) = @_;

	##
	# first create path if doesn't exists
	#
	my $errstr = "";
	my $dir = dirname( $path );
	unless( -d $dir ) {
		unless( make_path( $dir, { err => \$errstr } ) ) {
			__debug( "Could not create folder for $dir: $errstr", 2 );
		}
	}

	#
	# then slurp file
	#
	unless( write_file( $path, {binmode => ':raw' }, $data ) ) {
		__debug( "Could not write thumbnail $path", 2 );
	}
}

##
#
# @brief debug log small routine

# @param string $info -- debug info
# @param integer $level -- severity
#
# also used
#
# @global integer $debug -- global serverity level
# @global integer $use_syslog -- use STDERR or syslog
#
sub __debug {
	my( $info, $level ) = @_;

	$level ||= 1;

	if( $level <= $debug ) {
		if( $use_syslog ) {
			syslog( "info", $info );
		}
		else {
			say STDERR $info
		}
	}
}

no warnings; # avoid "Possible attempt to separate words with commas"
my @tests = qw(
	/b/blazblue/images/thumb/c/cf/MakotoChibi.png/82px-0%2C182%2C0%2C182-MakotoChibi.png
	/c/carnivores/images/thumb/5/59/Padlock.svg/120px-Padlock.svg.png
	/y/yugioh/images/thumb/a/ae/Flag_of_the_United_Kingdom.svg/700px-Flag_of_the_United_Kingdom.svg.png
	/a/answers/images/thumb/8/84/Play_fight_of_polar_bears_edit_1.avi.OGG/mid-Play_fight_of_polar_bears_edit_1.avi.OGG.jpg
	/m/meerundmehr/images/thumb/1/17/Mr._Icognito.svg/150px-Mr._Icognito.svg.png
	/c/central/images/thumb/e/e9/CP_c17i4°.svg/250px-CP_c17i4°.svg.png
	/c/central/images/thumb/b/bf/Wiki_wide.png/155px-Wiki_wide.png
	/h/half-life/en/images/thumb/1/1d/Zombie_Assassin.jpg/100px-Zombie_Assassin.jpg
	/h/half-life/en/images/thumb/a/a5/Gene_worm_model.jpg/260px-Gene_worm_model.jpg
	/h/half-life/en/images/thumb/a/a5/Gene_worm_model.jpg/250px-Gene_worm_model.jpg
	/h/half-life/en/images/thumb/b/b1/Alyx_hanging_trailer.jpg/250px-Alyx_hanging_trailer.jpg
	/h/half-life/en/images/thumb/d/d6/Black_Mesa_logo.svg/240px-Black_Mesa_logo.svg.png
	/h/half-life/en/images/thumb/d/d6/Black_Mesa_logo.svg/250px-Black_Mesa_logo.svg.png
	/m/memoryalpha/en/images/thumb/8/88/2390s_Starfleet.svg/300px-2390s_Starfleet.svg.png
	/h/half-life/en/images/thumb/d/d6/Black_Mesa_logo.svg/250px-Black_Mesa_logo.svg.png
	/d/de/images/thumb/3/35/Information_icon.svg/120px-Information_icon.svg.png
	/w/wowwiki/images/thumb/b/b0/Tauren_shaman.jpg/430px-0,100,0,300-Tauren_shaman.jpg
	/l/lyricwiki/images/thumb/7/74/Acid_Drinkers_-_Are_You_a_Rebel%3F.jpg/120px-Acid_Drinkers_-_Are_You_a_Rebel%3F.jpg
	/l/lyricwiki/images/thumb/7/74/Acid_Drinkers_-_Are_You_a_Rebel?.jpg/120px-Acid_Drinkers_-_Are_You_a_Rebel?.jpg
	/r/runescape/images/thumb/4/41/Wardrobe.gif/180px-Wardrobe.gif
	/d/desencyclopedie/images/thumb/5/51/Uri.svg/120px-Uri.svg.png
	/m/muppet/images/thumb/0/0f/Sesamstrasse-Bibo-(Wolfgang-Draeger).jpg/55px-Sesamstrasse-Bibo-(Wolfgang-Draeger).jpg
	/w/wikiality/images/thumb/300px-Kool-Aid2.jpg
	/g/gw/images/thumb/archive/7/78/20090811221502!Nicholas_the_Traveler_location_20090810_2.PNG/120px-Nicholas_the_Traveler_location_20090810_2.PNG
	/b/blazblue/images/thumb/c/cf/MakotoChibi.png/82px-0%2C182%2C0%2C182-MakotoChibi.png
	/l/lfn/images/thumb/b/b6/Flag_of_Lingua_Franca_Nova.svg/82px-1,514,0,512-Flag_of_Lingua_Franca_Nova.svg.png
	/s/sartrans/ru/images/thumb/3/3e/%D0%94%D0%B0%D1%87%D0%BD%D0%B0%D1%8F_%D0%BB%D0%B8%D0%BD%D0%B8%D1%8F.svg/82px-155,900,0,744-%D0%94%D0%B0%D1%87%D0%BD%D0%B0%D1%8F_%D0%BB%D0%B8%D0%BD%D0%B8%D1%8F.svg.png
	/k/kuroshitsuji/images/thumb/c/c8/Snake_Revealed.png/82px-0%2C459%2C0%2C459-Snake_Revealed.png
	/s/science/ru/images/thumb/2/29/Gtk-redo-ltr.svg/17px-Gtk-redo-ltr.svg.png
	/b/biologija/lt/images/thumb/7/7d/Eagle_Owl_IMG_9203.JPG/800px-@commons-Eagle_Owl_IMG_9203.JPG
	/h/half-life/en/images/thumb/1/1c/Eli_proto_physics.jpg/250px-Eli_proto_physics.jpg
	/c/communitytest/images/thumb/5/50/Wiki-background/80px-Wiki-background
);
use warnings;
my @done = ();

#
# initialization
#
# configurable via environmet variables
#
my $maxrequests = $ENV{ "REQUESTS" } || 1000;
my $basepath    = $ENV{ "IMGPATH"  } || "/images";
my $baseurl     = $ENV{ "BASEURL"  } || "http://images.wikia.com";
my $clients     = $ENV{ "CHILDREN" } || 4;
my $listen      = $ENV{ "SOCKET"   } || "0.0.0.0:39393";
my $test        = $ENV{ "TEST"     } || 0;
my $pidfile     = $ENV{ "PIDFILE"  } || "/var/run/thumbnailer/404handler.pid";

#
# overwrite some settings with getopt
#
GetOptions( "http" => \$use_http, "devel" => \$use_devel, "store" => \$use_store, "syslog" => \$use_syslog );

#
# syslog stream
#
openlog( "thumbnailer", "pid,nofatal", "LOG_LOCAL6" ) if $use_syslog;


#
# fastcgi request
#
my %env;
my( $socket, $request, $manager, $request_uri, $referer, $test_uri );

unless( $test ) {
	$socket     = FCGI::OpenSocket( $listen, 100 ) or die "failed to open FastCGI socket; $!";
	$request    = FCGI::Request( \*STDIN, \*STDOUT, \*STDOUT, \%env, $socket, ( &FCGI::FAIL_ACCEPT_ON_INTR ) );
	$manager    = FCGI::ProcManager->new({ n_processes => $clients });

	$manager->pm_write_pid_file( $pidfile );
	$manager->pm_manage();
}
else {
	$request    = FCGI::Request();
}

my $flm            = new File::LibMagic;
my $maxwidth       = 3000;
my $transformed    = 0;
my $mimetype       = "text/plain";
my $datetime       = undef;
my $imgtype        = undef;
my $remote         = undef; # url to remote original when $use_http is true
my $content        = undef; #
my $content_length = 0;
my $last_modified  = undef;
my $last_status    = undef;

while( $request->Accept() >= 0 || $test ) {
	my $t_start = [ gettimeofday() ];
	$manager->pm_pre_dispatch() unless $test;

	$request_uri = "";
	$referer     = "";

	if( $test ) {
		$request_uri = pop @tests || last;
		push @done, $request_uri;
	}

	#
	# get last part of uri, remove first slash if exists
	#
	$request_uri = $env{"REQUEST_URI"} if $env{"REQUEST_URI"};
	$referer     = $env{"HTTP_REFERER"} if $env{"HTTP_REFERER"};

	my $uri = URI->new( $request_uri );
	my $path  = $uri->path;
	$path =~ s/^\///;
	$path =~ s/%([0-9A-Fa-f]{2})/chr(hex($1))/eg unless $use_http;

	#
	# if path has single letter on beginning it's already new directory layout
	#
	if( $path !~ m!^\w/! ) {
		$path = substr( $path, 0, 1 ) . '/' . $path;
	}

	my $thumbnail = $basepath . '/' . $path;


	my @parts = split( "/", $path );
	my $last = pop @parts;
	$last =~ s/%([0-9A-Fa-f]{2})/chr(hex($1))/eg if $use_http; # (for cropping);


	#
	# if last part of $request_uri is \d+px-\. it is probably thumbnail
	#
	my( $width ) = $last =~ /^(\d+)px\-.+\w$/;

	#
	# for image service x1,y1,x2,y2
	#
	my( $x1, $x2, $y1, $y2 ) = undef;
	if( $last =~ /^\d+px\-(\d+),(\d+),(\d+),(\d+)/ ) {
		( $x1, $x2, $y1, $y2 ) = ( $1, $2, $3, $4 );
	}

	#
	# but ogghandler thumbnails can have seek=\d+ or mid
	#
	( $width ) = $last =~ /^seek=(\d+)\-.+\w$/ unless $width;
	( $width ) = $last =~ /^(mid)\-.+\w$/ unless $width;

	if( $width ) {
		$width = $maxwidth if $width =~ /^\d+$/ && $width > $maxwidth;
		#
		# guess rest of image, last three parts would be image name and two
		# subdirectories
		#
		# there could be two kinds: current image and archive image,
		# archive image has '/archive/' part additionaly
		#
		my $original = join( "/", splice( @parts, -3, 3 ) );

		#
		# we match thumbnail path against this name because we don't want to
		# create false positives (it's not perfect though )
		#
		my $origname = pop @{ [ split( "/" , $original ) ] };
		$origname =~ s/%([0-9A-Fa-f]{2})/chr(hex($1))/eg if $use_http;
		if( index( $last, $origname ) == -1 && index( $thumbnail, "/archive/" ) == -1 ) {
			$last_status = 404;
			__debug( "$origname not found in $last (thumbnail: $thumbnail)", 1 );
		}
		else {
			#
			# now, last part is thumbnails folder, we skip that too
			#
			pop @parts;

			#
			# if thumbnail is for archived image add /archive/ part
			#
			if( index( $thumbnail, "/archive/" ) != -1 ) {
				$parts[ -1 ] = "archive";
			}

			#
			# merge with rest of path
			#
			$original = $basepath . '/' . join( "/", @parts ) . '/' . $original;

			my $t_elapsed = tv_interval( $t_start, [ gettimeofday() ] ) ;

			#
			# read original data,
			# use remote file if --http is used
			# use local file otherwise
			#
			$datetime = DateTime->now();
			use bytes;
			if( $use_http ) {
				$remote = $original;
				substr( $remote, 0, length( $basepath ), $baseurl );
				substr( $thumbnail, 0, length( $basepath ), $baseurl ) unless $use_store;
				my $ua = LWP::UserAgent->new();
				$ua->timeout( 5 );
				$ua->proxy( "http", "http://127.0.0.1:6081/" ) unless $use_devel;
				my $response = $ua->get( $remote );
				$last_modified = $response->header("Last-Modified")
					? $response->header("Last-Modified")
					: $datetime->strftime( "%a, %d %b %Y %T GMT" );
				if( $response->is_success ) {
					$content = $response->content;
					$content_length = length( $content );
					$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
					__debug( "Reading remote $remote, content-length: $content_length, time: $t_elapsed", 1 );
				}
				else {
					$last_status = $response->code();
					$content_length = 0;
					$last_modified = $datetime->strftime( "%a, %d %b %Y %T GMT" );
				}
			}
			else {
				$content = read_file( $original, binmode => ":raw" ) ;
				$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
				$content_length = length( $content );
				$last_modified = $datetime->strftime( "%a, %d %b %Y %T GMT" );
				__debug( "Reading local $original, content-length: $content_length, time: $t_elapsed", 1 );
			}
			no bytes;

			#
			# then find proper thumbnailer for file, first check if this is svg
			# thumbnail request. mimetype will be used later in header
			#
			if( $content_length ) {
				$mimetype = $flm->checktype_contents( $content );
				( $imgtype ) = $mimetype =~ m![^/+]/(\w+)!;
				$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
				__debug( "$original $thumbnail $mimetype $imgtype $request_uri $referer, time: $t_elapsed", 1 );

				#
				# read original file, thumbnail it, store on disc
				# file2 has old mimetype database, it thinks that svg file is just
				# xml file
				#
				# some svg are completely broken so we check extension file as well
				#
				my ( $filext ) = $original =~ /\.(\w+)$/; # extension of original file
				my ( $thbext ) = $thumbnail =~ /\.(\w+)$/; # extension of requested thumbnail
				$filext = lc( $filext );
				if( $mimetype =~ m!^image/svg\+xml! || $mimetype =~ m!text/xml! || $filext eq "svg" ) {
					#
					# for svg files only png thumbnails are valid
					#
					if( lc( $thbext ) eq 'png' ) {
						#
						# default aspect ratio
						#
						my $aspect = 1.0;

						#
						# read width & height of SVG file
						#
						$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
						my $info = image_info( \$content );
						my $origw = scaleSVGUnit( $info->{ 'width' } );
						my $origh = scaleSVGUnit( $info->{ 'height' } );
						unless( $origw && $origh ) {
							#
							# http://www.w3.org/TR/SVG/coords.html#ViewBoxAttribute
							#
							my $xmlp = XMLin( $content );
							__debug( "There's no width and height defined for SVG file, checking viewbox", 3 );
							my $viewBox = $xmlp->{ "viewBox" };
							if( $viewBox && $viewBox =~/\d+[\s|,]*\d+[\s|,]*(\d+)[\s|,]*(\d+)/ ) {
								$origw = $1;
								$origh = $2;
								$aspect = $origw / $origh if $origh;
							}
						}
						else {
							$aspect = $origw / $origh if $origh;
						}

						#
						# still don't have it? use defaults
						#
						unless( $origw && $origh ) {
							$origw = SVG_DEFAULT_WIDTH;
							$origh = $origw / $aspect;
						}

						my $height = scaleHeight( $origw, $origh, $width, $test );
						$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
						__debug( "reading svg as xml (for size checking) $origw x $origh, time: $t_elapsed", 3 );

						#
						# RSVG thumbnailer
						#
						my $rsvg = new Image::LibRSVG;
						my $output = undef;

						my $cropped = 0;
						if( is_int( $x1 ) && is_int( $x2 ) && is_int( $y1 ) && is_int( $y2 ) ) {
							#
							# cut rectangle from original, preserve aspect ratio
							# @todo put into white background when thumbnail size is smaller than requested
							#

							#
							# first create default size bitmap from svg file
							#
							my $w = SVG_DEFAULT_WIDTH;
							my $h = $w / $aspect;
							my $args = { "dimension" => [$w, $h], "dimesion" => [$w, $h] };
							$rsvg->loadImageFromString( $content, 0, $args );
							my $content = $rsvg->getImageBitmap( "png" );

							#
							# transfer bitmap to Imager
							#
							my $image = Imager->new;
							$image->read( data => $content, type => "png" );

							$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
							__debug( "Creating $w x $h preview from svg file for cropping, time: $t_elapsed", 2 );

							$w = $x2 - $x1;
							$h = $y2 - $y1;

							if( $w > 0 && $h > 0 ) {
								$image = $image->crop( left => $x1, top => $y1, right => $x2, bottom => $y2  );
								$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
								__debug( "Cropping into $x1 $x2 x $y1 $y2, time: $t_elapsed", 2 );
								$cropped = 1;
							}

							#
							# always write png
							#
							$image = $image->scale( xpixels => $width, ypixels => $height, qtype => 'mixing' );
							$image->write( data => \$output, type => "png" );
							$transformed = 1;
						}
						else {

							#
							# there is stupid bug (typo) in Image::LibRSVG so we have to
							# define hash with dimension and dimesion
							#
							my $args = { "dimension" => [$width, $height], "dimesion" => [$width, $height] };
							$rsvg->loadImageFromString( $content, 0, $args );
							$transformed = 1;
							$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
							__debug( "reading svg as image (for transforming), time: $t_elapsed", 3 );

							$output = $rsvg->getImageBitmap( "png" );
						}
						use bytes;
						my $output_length = length( $output );

						if( $output_length ) {
							store_file( $thumbnail, $output );
							print "HTTP/1.1 200 OK\r\n";
							print "Cache-control: max-age=30\r\n";
							print "Content-Length: $output_length\r\n";
							print "Last-Modified: $last_modified\r\n";
							print "X-Thumbnailer-Hostname: $hostname\r\n";
							print "Connection: keep-alive\r\n";
							print "Content-type: image/png\r\n\r\n";
							print $output unless $test;

							$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
							__debug( "File $thumbnail served, time: $t_elapsed", 1 );
							$transformed = 1;
						}
						else {
							$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
							__debug( "SVG conversion from $original to $thumbnail failed, time: $t_elapsed", 1 );
						}
						no bytes;
						undef $rsvg;
						undef $info;
					}
					else {
						__debug( "Thumbnail requested for SVG $original is not PNG file", 1 );
					}
				}
				elsif( $mimetype =~ m!application/ogg! ) {
					#
					# check what frame we should get...
					#
					my $seek = ( $width eq "mid" ) ? 1 : $width;

					videoThumbnail( $original, $thumbnail, $seek );
					$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
					__debug( "Creating thumbnail for video file $original, time: $t_elapsed", 1 );

					$transformed = 1;
					if( -f $thumbnail ) {
						chmod 0664, $thumbnail;
						$mimetype = $flm->checktype_filename( $thumbnail );
						print "HTTP/1.1 200 OK\r\n";
						print "X-LIGHTTPD-send-file: $thumbnail\r\n";
						print "Cache-control: max-age=30\r\n";
						print "X-Thumbnailer-Hostname: $hostname\r\n";
						print "Content-type: $mimetype\r\n\r\n";
						$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
						__debug( "File $thumbnail created, time: $t_elapsed", 1 );
					}
					else {
						$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
						__debug( "Thumbnailer from $original to $thumbnail failed, time: $t_elapsed", 1 );
						#
						# serve original file
						#
						print "HTTP/1.1 200 OK\r\n";
						print "X-LIGHTTPD-send-file: $original\r\n";
						print "X-Thumbnailer-Hostname: $hostname\r\n";
						print "Cache-control: max-age=30\r\n";
						print "Content-type: $mimetype\r\n\r\n";
					}
				}
				else {
					#
					# for other else use Imager
					#
					my $image = Imager->new;
					$image->read( data => $content, type => $imgtype );
					$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );

					my $cropped = 0;
					if( is_int( $x1 ) && is_int( $x2 ) && is_int( $y1 ) && is_int( $y2 ) ) {
						#
						# cut rectangle from original, do some checkups first
						#
						my $w = $x2 - $x1;
						my $h = $y2 - $y1;
						if( $w > 0 && $h > 0 ) {
							$image = $image->crop( left => $x1, top => $y1, right => $x2, bottom => $y2  );
							$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
							__debug( "Cropping into $x1 $x2 x $y1 $y2, time: $t_elapsed", 3 );
							$cropped = 1;
						}
					}

					my $origw  = 0;
					my $origh  = 0;

					try {
						$origw  = $image->getwidth();
						$origh  = $image->getheight();
					};
					$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
					__debug( "Original size $origw x $origh, time: $t_elapsed", 3 );
					if( $origw && $origh ) {
						#
						# not bigger than original
						#
						my $height = scaleHeight( $origw, $origh, $width, $test );
						if( $width < $origw ) {
							$image = $image->scale( xpixels => $width, ypixels => $height, qtype => 'mixing', type => 'nonprop' );
						}
						if( $cropped ) {
							#
							# for cropped images thumbnail which is smaller
							# than requested we add white border and put
							# thumbnail into it
							#
							my $crop_width = $image->getwidth();
							my $crop_height = $image->getheight();
							__debug( "Size after cropping: $crop_width x $crop_height, requested size: $width x $height", 3 );
							if( $crop_width < $width ) {
								__debug( "crop smaller than requested width: $crop_width < $width", 3 );
								#
								# create base image with white background
								# count where to place (gravity => center from IM)
								#
								my $background = Imager->new( xsize => $width, ysize => $height );
								$background = $background->box( filled => 1, color => "white" );
								__debug( "Crop size $width x $height", 3 );
								my $offsetx = $width/2 - $crop_width/2;
								my $offsety = $height/2 - $crop_height/2;
								$background->paste( src => $image, left => $offsetx, top => $offsety );
								$image = $background;
							}
						}

						$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
						__debug( "Resizing into $thumbnail, time: $t_elapsed", 1 );

						my $output = undef;
						$image->write( data => \$output, type => $imgtype, jpegquality => 90 );
						use bytes;
						my $output_length = length( $output );
						if( $output_length ) {

							store_file( $thumbnail, $output );

							#
							# serve file if is ready to serve
							#
							print "HTTP/1.1 200 OK\r\n";
							print "Cache-control: max-age=30\r\n";
							print "Content-Length: $output_length\r\n";
							print "Last-Modified: $last_modified\r\n";
							print "Connection: keep-alive\r\n";
							print "X-Thumbnailer-Hostname: $hostname\r\n";
							print "Content-type: $mimetype\r\n\r\n";
							print $output unless $test;

							$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
							__debug( "File $thumbnail served, time: $t_elapsed", 1 );
							$transformed  = 1;
						}
						no bytes;
						undef $image;
					}
				}
			}
			else {
				__debug( "$thumbnail original file $original does not exists", 2 );
			}
		}
	}

	if( ! $transformed ) {
		given( $last_status ) {
			when( 404 ) { real404( $request_uri ) }
			default     { real503( $request_uri, $remote ) }
		};
	}

	$transformed = 0;
	$manager->pm_post_dispatch() unless $test;
}

#
# clean up section
#
$manager->pm_remove_pid_file() unless $test;
closelog() if $use_syslog;

#
# if test display results
#
#testResults( $basepath, \@done ) if $test;
