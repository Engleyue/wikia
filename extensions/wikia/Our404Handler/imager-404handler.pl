#!/usr/bin/perl -w

package main;
#
# Imager version
#

use strict;
use URI;
use FCGI;
use FCGI::ProcManager::MaxRequests;
use Imager;
use Image::LibRSVG;
use File::LibMagic;
use IO::File;
use File::Basename;
use File::Path;
use XML::Simple;

sub real404 {
	my $request_uri  = shift;
	print "Status: 404\r\n";
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

#
# initialization
#
# configurable via environmet variables
#
my $maxrequests = $ENV{ "REQUESTS" } || 1000;
my $clients     = $ENV{ "CHILDREN" } || 10;
my $listen      = $ENV{ "SOCKET" }   || "127.0.0.1:39393";
my $debug       = $ENV{ "DEBUG" }    || 1;

#
# fastcgi request
#
my %env;
my $socket      = FCGI::OpenSocket( $listen, 100 ) or die "failed to open FastCGI socket; $!";
my $request     = FCGI::Request( \*STDIN, \*STDOUT, \*STDOUT, \%env, $socket, ( &FCGI::FAIL_ACCEPT_ON_INTR ) );
my $manager     = FCGI::ProcManager::MaxRequests->new({ n_processes => $clients, max_requests => $maxrequests });
my $basepath    = "/images";
my $flm         = new File::LibMagic;
my $maxwidth    = 3000;



#
# if thumbnail was really generated
#
my $transformed = 0;
my $mimetype    = "text/plain";
my $imgtype     = undef;
umask( 022 );

$manager->pm_manage();
while( $request->Accept() >= 0 ) {
	$manager->pm_pre_dispatch();
	my $redirect_to = "";
	my $request_uri = "";
	my $referer = "";

=pod examples
	$request_uri = "/c/central/images/thumb/b/bf/Wiki_wide.png/155px-Wiki_wide.png";
	$request_uri = "/s/silenthill/de/images/thumb/8/85/Heather_%28Konzept4%29.jpg/420px-Heather_%28Konzept4%29.jpg";
	$request_uri = "/g/gw/images/thumb/archive/7/78/20090811221502!Nicholas_the_Traveler_location_20090810_2.PNG/120px-Nicholas_the_Traveler_location_20090810_2.PNG";
	$request_uri = "/m/meerundmehr/images/thumb/1/17/Mr._Icognito.svg/150px-Mr._Icognito.svg.png";
	$request_uri = "/c/central/images/thumb/8/8c/The_Smurfs_Animated_Gif.gif/200px-The_Smurfs_Animated_Gif.gif";
=cut

	#
	# get last part of uri, remove first slash if exists
	#
	$request_uri = $env{"REQUEST_URI"} if $env{"REQUEST_URI"};
	$referer     = $env{"HTTP_REFERER"} if $env{"HTTP_REFERER"};

	my $uri = URI->new( $request_uri );
	my $path  = $uri->path;
	$path =~ s/^\///;
	$path =~ s/%([0-9A-Fa-f]{2})/chr(hex($1))/eg;

	#
	# if path has single letter on beginning it's already new directory layout
	#
	if( $path !~ m!^\w/! ) {
		$path = substr( $path, 0, 1 ) . '/' . $path;
	}
	my $thumbnail = $basepath . '/' . $path;

	my @parts = split( "/", $path );
	my $last = pop @parts;


	#
	# if last part of $request_uri is \d+px-\. it is probably thumbnail
	#
	my ( $width ) = $last =~ /^(\d+)px\-.+\w$/;
	if( $width ) {
		$width = $maxwidth if ( $width > $maxwidth );
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
		if( index( $last, $original ) != -1 ) {
			print STDERR "$origname not found in $last\n" if $debug;
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
			#
			# then find proper thumbnailer for file, first check if this is svg
			# thumbnail request. mimetype will be used later in header
			#
			if( -f $original ) {
				$mimetype = $flm->checktype_filename( $original );
				( $imgtype ) = $mimetype =~ m![^/+]/(\w+)!;
				print STDERR "$thumbnail $mimetype $imgtype REQUEST_URI=$request_uri HTTP_REFERER=$referer\n" if $debug;

				#
				# create folder for thumbnail if doesn't exists
				#
				my $thumbdir = dirname( $thumbnail );
				unless( -d $thumbdir ) {
					eval { mkpath( $thumbdir ) };
					if( $@ ) {
						print STDERR "Creating of $thumbdir folder failed\n" if $debug;
					}
					else {
						print STDERR "Folder $thumbdir created\n" if $debug;
					}
				}

				#
				# read original file, thumbnail it, store on disc
				# file2 has old mimetype database, it thinks that svg file is just
				# xml file
				#
				if( $mimetype =~ m!^image/svg\+xml! || $mimetype =~ m!text/xml! ) {
					#
					# read width & height of SVG file
					#
					my $xmlp = XMLin( $original );
					my $origw = $xmlp->{'width'};
					my $origh = $xmlp->{'height'};
					my $height = $width;
					if( $origw && $origh ) {
						$height = $width * $origh / $origw;
					}
					#
					# RSVG thumbnailer
					#
					my $rsvg = new Image::LibRSVG;
					$rsvg->convertAtMaxSize( $original, $thumbnail, $width, $height );

					if( -f $thumbnail ) {
						$mimetype = $flm->checktype_filename( $thumbnail );
						$transformed = 1;
						print "HTTP/1.1 200 OK\r\n";
						print "X-LIGHTTPD-send-file: $thumbnail\r\n";
						print "Content-type: $mimetype\r\n\r\n";
						print STDERR "File $thumbnail created\n" if $debug;
					}
					else {
						print STDERR "SVG conversion from $original to $thumbnail failed\n";
					}
					undef $rsvg;
					undef $xmlp;
				}
				else {
					#
					# for other else use Image::Magick
					#
					#my $image = new Imager;
					my @in = Imager->read_multi( file => $original );

					#
					# animated gifs have more frames, but so far we use first
					#
					my $image = shift @in;

					my $origw  = $image->getwidth;
					my $origh  = $image->getheight;
					if( $origw && $origh ) {
						my $height = $width * $origh / $origw;
						my $thumb = $image->scale( xpixels => $width, ypixels => $height );
						if( $mimetype =~ m!image/gif! ) {
							# so far nothing
						}
						$thumb->write( file => $thumbnail, type => $imgtype );

						if( -f $thumbnail ) {
							#
							# serve file if is ready to serve
							#
							$transformed = 1;
							print "HTTP/1.1 200 OK\r\n";
							print "X-LIGHTTPD-send-file: $thumbnail\r\n";
							print "Content-type: $mimetype\r\n\r\n";
							print STDERR "File $thumbnail created\n" if $debug;
						}
						else {
							print STDERR "ImageMagick thumbnailer from $original to $thumbnail failed\n" if $debug;
						}
						undef $image;
					}
				}
			}
			else {
				print STDERR "$thumbnail original file $original does not exists\n" if $debug > 1;
			}
		}
	}
	if( ! $transformed ) {
		real404( $request_uri )
	}

	$transformed = 0;
	$manager->pm_post_dispatch();
}
