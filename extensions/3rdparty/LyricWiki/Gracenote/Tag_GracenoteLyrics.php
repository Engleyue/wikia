<?php

# Gracenote licensing-compliant lyrics parser extension for MediaWiki.
# This extension allows for copy-protection during display as well as for
# appropriate tracking-tags for the current song and required Gracenote branding.
# Written by Sean Colombo, 9 September 2009.
#
# Based heavily on the simple lyric parser extension for mediawiki.
# Written by Trevor Peacock, 1 June 2006
#
#
# Features:
#  * Allows basic lyric notation
#  * Optional CSS styling embedded in every page
#  * CSS styling not embedded in meta tage, rather @import-ed from extension file
#
# To install, copy this file into "extensions" directory, and add
# the following line to the end of LocalSettings.php
# (above the  ? >  )
#
#   require("extensions/LyricWiki/Tag_GracenoteLyrics.php");
#

// ISSUES TO KEEP IN MIND AND DISCUSS WITH WIKIA:
// TRACKING:
//  - We need to install the DynamicPageLists extension (now in SVN).
//  - I have to add the code below (the #vardefine... etc.) into the Song template but only after DPL is installed - http://lyrics.wikia.com/index.php?title=Template:Song&action=edit
//	- This tracking requires the advanced "ga.js" code which Google explicitly says not to use on the same page as "urchin.js" code which is there now. They say this can lead to errors.
//	  The documentation touches on a way to do both at once but it's just in the form of a vaguely documented function: http://code.google.com/intl/en-US/apis/analytics/docs/gaJS/gaJSApiUrchin.html#_gat.GA_Tracker_._setRemoteServerMode
//	- The existing Tag_Lyric code should be updated from SVN also (if there were changes since it was grabbed from SVN, they may need to be merged).
// COPY PROTECTION:
//	- One of the requirements specified is "Save As" but it appears that there aren't even futile methods to do this.

// TODO: Copy protection
//		TODO: No-cache headers: http://www.codeave.com/html/code.asp?u_log=5080 (check with Artur to see if this will destroy Varnish's ability to cache the site). My guess is that the intention is just to prevent cached pages from being used instead of ads (but the ads aren't cached) so unless they actually think a user could dig through their browser's cache to find the page more easily than just searching for it again...
// TODO: FOR TOR :)
//		TODO: In LocalSettings.php, protect the Gracenote namespace from editing: http://www.mediawiki.org/wiki/Manual:$wgNamespaceProtection
//		TODO: Move the values from the top of Gracenote.php to LocalSettings (it will be obvious from the comments which lines need to be moved).
# Copy-prevention measures in this extension:
#		Clear-clipboard script is enabled for IE 6.  No modern browsers support it in a way that would prevent print-screens.
#		Print CSS should disabled and some text which is normally hidden which will explain this issue when printed (or print previewed).
#		No CTRL-A script.
#		No text-select script for all 'lyricbox' divs on page.
#		Robots.txt doesn't appear to be a real requirement (probably because the competitors listed don't have bots). For example, this is Metrolyrics' robots file: http://www.metrolyrics.com/robots.txt
#		Transform text into encoded unicode values for the content of the tag so that View Source will look very unhelpful.

// Tracking
//		The tracking code requires this in the Song template (it is in there right now)
//{{#vardefine:gracenoteid|{{#ifexist: Gracenote:{{PAGENAME}}|
//{{#dpl:title=Gracenote:{{PAGENAME}}
//|mode=userformat
//|allowcachedresults=true
//|includepage={GracenoteHeader}:gracenoteid
//}}}}
//}}
//<div id='gracenoteid'>{{#if:{{#var:gracenoteid}}|{{#var:gracenoteid}}|{{PAGENAME}}}}</div>

// The following style or something similar should be in MediaWiki:Common.css for this extension to work as intended:
///* Gracenote page header boxes */
//.gracenote-header {
//   background-color:#ffff80;
//   border:2px #000 solid;
//   padding:15px;
//   font-weight:bold;
//   text-align:center;
//   width:auto;
//   margin:0 auto;
//}
///* The line for Gracenote songwriter and publisher credits. */
//.gracenote-credits {
//   font-weight:bold;
//   color:#888;
//   background-color:#eee;
//}
///* Just used to hold Gracenote ID so that JS can pick it up & send it later. */
//#gracenoteid{
//display:none;
//}
//
//.print-disabled-notice{display:none;}
//@media print{
//.lyricbox{display:none;}
//.print-disabled-notice{display:table;}
//}
//


################################################################################
# Functions
#
# This section has no configuration, and can be ignored.
#

# uncomment for local testing
#include( 'extras.php' );
include_once "$IP/extensions/3rdparty/LyricWiki/extras.php";
include_once 'Gracenote.php';

################################################################################
# Extension Credits Definition
#
# This section has no configuration, and can be ignored.
#

if(isset($wgScriptPath))
{
$wgExtensionCredits["parserhook"][]=array(
  'name' => 'Gracenote Lyric-Display Extension',
  'version' => '0.0.1',
  'url' => '',
  'author' => '[http://about.peacocktech.com/trevorp/ Trevor Peacock] for original Lyric Extension, [http://www.seancolombo.com Sean Colombo] for Gracenote version.',
  'description' => 'Adds features allowing easy display of Gracenote lyrics in MediaWiki with all required tracking and copy-protection required by licensing agreement.' );
}

################################################################################
# Lyric Render Section
#
# This section has no configuration, and can be ignored.
#
# This section renders <lyric> tags. It forces a html break on every line,
# and styles the section with a css id.
# this id can either be in the mediawiki css files, or defined by the extension
#

if(isset($wgScriptPath))
{
	#Instruct mediawiki to call gracenoteLyricsTag to initialise new extension
	$wgExtensionFunctions[] = "gracenoteLyricsTag";
	$wgHooks['BeforePageDisplay'][] = "gracenoteLyricsTagCss";

	// This only needs to be included once between the Lyrics tag and the GracenoteLyrics tag.
	$wgHooks['BeforePageDisplay'][] = "gracenote_installCopyProtection";
	$wgHooks['BeforePageDisplay'][] = "gracenote_disableEdit";
	//$wgHooks['getUserPermissionsErrorsExpensive'][] = "gracenote_disableEditByPermissions";
}

#Install extension
function gracenoteLyricsTag()
{
  #install hook on the element <lyric>
  global $wgParser;
  $wgParser->setHook("gracenotelyrics", "renderGracenoteLyricsTag");

  // Keep track of whether this is the first <gracenotelyrics> tag on the page - this is to prevent too many Ringtones ad links.
  GLOBAL $wgFirstLyricTag;
  $wgFirstLyricTag = true;
}


function gracenoteLyricsTagCss($out)
{
	$css = <<<DOC
.lyricbox
{
	padding: 1em;
	border: 1px solid silver;
	color: black;
	background-color: #ffffcc;
}
DOC
;
	$out->addScript("<style type='text/css'>/*<![CDATA[*/\n".$css."\n/*]]>*/</style>");

	return true;
}

function renderGracenoteLyricsTag($input, $argv, $parser)
{
  #make new lines in wikitext new lines in html
  $transform = str_replace(array("\r\n", "\r","\n"), "<br />", trim($input));

  $isInstrumental = (strtolower(trim($transform)) == "{{instrumental}}");

  // If appropriate, build ringtones links.
  GLOBAL $wgFirstLyricTag;
  $ringtoneLink = "";
  // NOTE: we put the link here even if wfAdPrefs_doRingtones() is false since ppl all share the article-cache, so the ad will always be in the HTML.
  // If a user has ringtone-ads turned off, their CSS will make the ad invisible.
  if($wgFirstLyricTag){ 
	GLOBAL $wgTitle, $wgUploadPath;
	$artist = $wgTitle->getDBkey();
	$colonIndex = strpos("$artist", ":");
	$songTitle = $wgTitle->getText();
	$artistLink = $artist;
	$songLink = $songTitle;
	if($colonIndex !== false){
		$artist = substr($artist, 0, $colonIndex);
		$songTitle = substr($songTitle, $colonIndex+1);
		
		$artistLink = str_replace(" ", "+", $artist);
		$songLink = str_replace(" ", "+", $songTitle);
	}
	$href = "<a href='http://www.ringtonematcher.com/co/ringtonematcher/02/noc.asp?sid=WILWros&amp;artist=".urlencode($artistLink)."&amp;song=".urlencode($songLink)."' target='_blank'>";
	$ringtoneLink = "";
	$ringtoneLink.= "<div class='rtMatcher'>";
	$ringtoneLink.= "$href<img src='$wgUploadPath/phone_left.gif' alt='phone' width='16' height='17'/></a> ";
	$ringtoneLink.= $href."Send \"$songTitle\" Ringtone to your Cell</a>";
	$ringtoneLink.= " $href<img src='$wgUploadPath/phone_right.gif' alt='phone' width='16' height='17'/></a>";
	$ringtoneLink.= "</div>";
	$wgFirstLyricTag = false;
  }

	#parse embedded wikitext
	$retVal = "";

	$transform = $parser->parse($transform, $parser->mTitle, $parser->mOptions, false, false)->getText();

	$retVal.= gracenote_getNoscriptTag();
	$retVal.= "<div class='lyricbox'>";
	$retVal.= ($isInstrumental?"":$ringtoneLink)."\n"; // if this is an instrumental, just a ringtone link on the bottom is plenty.
	$retVal.= gracenote_obfuscateText($transform);
	$retVal.= "\n$ringtoneLink";
	$retVal.= "</div>";
	$retVal.= gracenote_getPrintDisabledNotice();

	// Required Gracenote branding.
	$retVal.= gracenote_getBrandingHtml();

	// Google Analytics code which will let us track traffic by song.
	$retVal .= gracenote_getAnalyticsHtml(GRACENOTE_VIEW_GRACENOTE_LYRICS);

	return $retVal;
}

?>
