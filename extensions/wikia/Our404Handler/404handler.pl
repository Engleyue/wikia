#!/usr/bin/perl -w

package main;

#
# Imager version
#
# @todo -- thumbnails for movies are broken, move to ffmpegthumbnailer 2.0.4
#

#
# available options:
# --http -- will use http get insted of NFS local file read
#

use strict;
use feature ":5.10";

use URI;
use FCGI;
use FCGI::ProcManager;
use Imager;
use Image::LibRSVG;
use File::LibMagic;
use File::Basename;
use File::Path;
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

#
# constant
#
use constant FFMPEG   => "/usr/bin/ffmpeg";
use constant OGGTHUMB => "/usr/bin/oggThumb";


sub real404 {
	my $request_uri  = shift;
	print "HTTP/1.0 404 Not Found\r\n";
	print "Cache-control: max-age=30\r\n";
	print "Connection: close\r\n";
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

sub real503 {
	my $request_uri  = shift;
	print "HTTP/1.1 503 Service Unavailable\r\n";
	print "Cache-control: max-age=30\r\n";
	print "Retry-After: 30\r\n";
	print "Connection: close\r\n";
	print "X-Thumbnailer-Error: backend not responding\r\n";
	print "Content-Type: text/plain; charset=utf-8\r\n\r\n";
	print "Backend for getting original file $request_uri is not responding\n";
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
# do not make zombies
#

no warnings; # avoid "Possible attempt to separate words with commas"
my @tests = qw(
	/b/blazblue/images/thumb/c/cf/MakotoChibi.png/82px-0%2C182%2C0%2C182-MakotoChibi.png
	/c/carnivores/images/thumb/5/59/Padlock.svg/120px-Padlock.svg.png
	/y/yugioh/images/thumb/a/ae/Flag_of_the_United_Kingdom.svg/700px-Flag_of_the_United_Kingdom.svg.png
	/a/answers/images/thumb/8/84/Play_fight_of_polar_bears_edit_1.avi.OGG/mid-Play_fight_of_polar_bears_edit_1.avi.OGG.jpg
	/g/gw/images/thumb/archive/7/78/20090811221502!Nicholas_the_Traveler_location_20090810_2.PNG/120px-Nicholas_the_Traveler_location_20090810_2.PNG
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
	/s/sartrans/ru/images/thumb/3/3e/%D0%94%D0%B0%D1%87%D0%BD%D0%B0%D1%8F_%D0%BB%D0%B8%D0%BD%D0%B8%D1%8F.svg/82px-155%2C900%2C0%2C744-%D0%94%D0%B0%D1%87%D0%BD%D0%B0%D1%8F_%D0%BB%D0%B8%D0%BD%D0%B8%D1%8F.svg.png
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
my $debug       = $ENV{ "DEBUG"    } || 1;
my $test        = $ENV{ "TEST"     } || 0;
my $pidfile     = $ENV{ "PIDFILE"  } || "/var/run/thumbnailer/404handler.pid";
my $use_http    = 0;
my $use_devel   = 0;

#
# overwrite some settings with getopt
#
GetOptions( "http" => \$use_http, "devel" => \$use_devel );

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
		if( index( $last, $origname ) == -1 ) {
			$last_status = 404;
			say STDERR "$origname not found in $last" if $debug;
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
				substr( $thumbnail, 0, length( $basepath ), $baseurl );
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
					say STDERR "Reading remote $remote, content-length: $content_length, time: $t_elapsed" if $debug;
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
				print STDERR "Reading local $original, content-length: $content_length, time: $t_elapsed\n" if $debug;
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
				print STDERR "$original $thumbnail $mimetype $imgtype $request_uri $referer, time: $t_elapsed\n" if $debug;

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
						# read width & height of SVG file
						#
						$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
						my $xmlp = XMLin( $content );
						my $origw = $xmlp->{ 'width' };
						my $origh = $xmlp->{ 'height' };
						$origw = to_float( $origw ) unless is_float( $origw );
						$origh = to_float( $origh ) unless is_float( $origh );

						unless( $origw && $origh ) {
							#
							# http://www.w3.org/TR/SVG/coords.html#ViewBoxAttribute
							#
							say STDERR "There's no width and height defined for SVG file, checking viewbox" if $debug > 2;
							my $viewBox = $xmlp->{ "viewBox" };
							if( $viewBox && $viewBox =~/\d+[\s|,]*\d+[\s|,]*(\d+)[\s|,]*(\d+)/ ) {
								$origw = $1;
								$origh = $2;
							}
						}

						my $height = scaleHeight( $origw, $origh, $width, $test );
						$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
						say STDERR "reading svg as xml (for size checking) $origw x $origh, time: $t_elapsed" if $debug > 2;

						#
						# RSVG thumbnailer
						#
						my $rsvg = new Image::LibRSVG;

						#
						# there is stupid bug (typo) in Image::LibRSVG so we have to
						# define hash with dimension and dimesion
						#

						my $args = { "dimension" => [$width, $height], "dimesion" => [$width, $height] };
						$rsvg->loadImageFromString( $content, 0, $args );
						$transformed = 1;
						$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
						say STDERR "reading svg as image (for transforming), time: $t_elapsed" if $debug > 2;

						use bytes;
						my $output = $rsvg->getImageBitmap();
						my $output_length = length( $output );

						if( $output_length ) {
							print "HTTP/1.1 200 OK\r\n";
							print "Cache-control: max-age=30\r\n";
							print "Content-Length: $output_length\r\n";
							print "Last-Modified: $last_modified\r\n";
							print "Connection: keep-alive\r\n";
							print "Content-type: image/png\r\n\r\n";
							print $output unless $test;
							$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
							say STDERR "File $thumbnail served, time: $t_elapsed" if $debug;
							$transformed = 1;
						}
						else {
							$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
							say STDERR "SVG conversion from $original to $thumbnail failed, time: $t_elapsed" if $debug;
						}
						no bytes;
						undef $rsvg;
						undef $xmlp;
					}
				}
				elsif( $mimetype =~ m!application/ogg! ) {
					#
					# check what frame we should get...
					#
					my $seek = ( $width eq "mid" ) ? 1 : $width;

					videoThumbnail( $original, $thumbnail, $seek );
					$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
					print STDERR "Creating thumbnail for video file $original, time: $t_elapsed\n";

					$transformed = 1;
					if( -f $thumbnail ) {
						chmod 0664, $thumbnail;
						$mimetype = $flm->checktype_filename( $thumbnail );
						print "HTTP/1.1 200 OK\r\n";
						print "X-LIGHTTPD-send-file: $thumbnail\r\n";
						print "Cache-control: max-age=30\r\n";
						print "Content-type: $mimetype\r\n\r\n";
						$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
						print STDERR "File $thumbnail created, time: $t_elapsed\n" if $debug;
					}
					else {
						$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
						print STDERR "Thumbnailer from $original to $thumbnail failed, time: $t_elapsed\n" if $debug;
						#
						# serve original file
						#
						print "HTTP/1.1 200 OK\r\n";
						print "X-LIGHTTPD-send-file: $original\r\n";
						print "Cache-control: max-age=30\r\n";
						print "Content-type: $mimetype\r\n\r\n";
					}
				}
				else {
					#
					# for other else use Graphics::Magick
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
							say STDERR "Cropping into $x1 $x2 x $y1 $y2, time: $t_elapsed" if $debug > 2;
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
					say STDERR "Original size $origw x $origh, time: $t_elapsed" if $debug > 2;
					if( $origw && $origh ) {
						#
						# not bigger than original
						#
						my $height = scaleHeight( $origw, $origh, $width, $test );
						if( $width < $origw ) {
							$image = $image->scale( xpixels => $width, ypixels => $height, qtype => 'mixing' );
						}
						if( $cropped ) {
							#
							# for cropped images thumbnail which is smaller
							# than requested we add white border and put
							# thumbnail into it
							#
							my $crop_width = $image->getwidth();
							my $crop_height = $image->getheight();
							say STDERR "Size after cropping: $crop_width x $crop_height, requested size: $width x $height" if $debug > 2;
							if( $crop_width < $width ) {
								say STDERR "crop smaller than requested width: $crop_width < $width" if $debug > 2;
								#
								# create base image with white background
								# count where to place (gravity => center from IM)
								#
								my $background = Imager->new( xsize => $width, ysize => $height );
								$background = $background->box( filled => 1, color => "white" );
								say STDERR "Crop size $width x $height" if $debug > 2;
								my $offsetx = $width/2 - $crop_width/2;
								my $offsety = $height/2 - $crop_height/2;
								$background->paste( src => $image, left => $offsetx, top => $offsety );
								$image = $background;
							}
						}

						$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
						say STDERR "Resizing into $thumbnail, time: $t_elapsed" if $debug;

						my $output = undef;
						$image->write( data => \$output, type => $imgtype, jpegquality => 90 );
						use bytes;
						my $output_length = length( $output );
						if( $output_length ) {
							#
							# serve file if is ready to serve
							#
							print "HTTP/1.1 200 OK\r\n";
							print "Cache-control: max-age=30\r\n";
							print "Content-Length: $output_length\r\n";
							print "Last-Modified: $last_modified\r\n";
							print "Connection: keep-alive\r\n";
							print "Content-type: $mimetype\r\n\r\n";
							print $output unless $test;

							$t_elapsed = tv_interval( $t_start, [ gettimeofday() ] );
							say "File $thumbnail served, time: $t_elapsed" if $debug;
							$transformed  = 1;
						}
						no bytes;
						undef $image;
					}
				}
			}
			else {
				say STDERR "$thumbnail original file $original does not exists" if $debug > 1;
			}
		}
	}

	if( ! $transformed ) {
		given( $last_status ) {
			when( 404 ) { real404( $request_uri ) }
			default     { real503( $request_uri ) }
		};
	}

	$transformed = 0;
	$manager->pm_post_dispatch() unless $test;
}

$manager->pm_remove_pid_file() unless $test;

#
# if test display results
#
#testResults( $basepath, \@done ) if $test;
