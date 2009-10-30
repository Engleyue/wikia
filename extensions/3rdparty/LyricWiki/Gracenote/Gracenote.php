<?php
////
// Author: Sean Colombo
// Date: 20090913
//
// This file contains methods for dealing with Gracenote integration which
// are intended to be used in more than one extension.
////


define('NS_GRACENOTE', 220);
define('NS_GRACENOTE_TALK', 221);
$wgExtraNamespaces[NS_GRACENOTE] = "Gracenote";
$wgExtraNamespaces[NS_GRACENOTE_TALK] = "Gracenote_talk";
$wgGroupPermissions['*']['editgracenote'] = false;
$wgGroupPermissions['staff']['editgracenote'] = true;

// Definitions for which type of page to track with GoogleAnalytics.
define('GRACENOTE_VIEW_GRACENOTE_LYRICS', 'ViewGracenote');
define('GRACENOTE_VIEW_OTHER_LYRICS', 'ViewOther');
define('GRACENOTE_VIEW_NOT_LYRICS', 'NotLyrics');
define('GOOGLE_ANALYTICS_ID', "UA-10496195-1"); // lyrics.wikia.com ID

GLOBAL $wgGracenoteView;
$wgGracenoteView = GRACENOTE_VIEW_NOT_LYRICS;

////
// Returns HTML which outputs the required branding for Gracenote.
// This includes an icon that is 30 pixels tall or more and a specific
// sentence (no links are required on this attribution).
////
function gracenote_getBrandingHtml(){
	return "<div id='gracenote-branding'>".
			"<img src='http://images1.wikia.nocookie.net/lyricwiki/images/6/66/Logo-gracenote.gif' border='0'/><br/>".
			"Lyrics Provided by Gracenote (<a href='http://lyrics.wikia.com/Gracenote:EULA' rel='nofollow'>Lyrics Terms of Use</a>)</div>\n";
} // end gracenote_getBrandingHtml()

////
// Given a string, returns an obfuscated version so that the text cannot
// easily be copied when doing "View Source" in a browser.
////
function gracenote_obfuscateText($text){
	require_once 'utf8ToUnicode.php';

	// Copy-protection: encode the contents of each line.  Will not encode anything inside of "<" and ">" characters (because that would break any HTML).
	$LINE_BREAK = "<br />"; // this is the format in which it comes out of the parser.
	$LT_UNICODE = 60;
	$GT_UNICODE = 62;
	$lines = explode($LINE_BREAK, $text);
	$lyrics = "";
	$isInsideTag = false;
	foreach($lines as $oneLine){
		$charsFromLyrics = utf8ToUnicode($oneLine);
		foreach($charsFromLyrics as $unicodeValue){
			if($isInsideTag){
				$unicodeAsArray = array($unicodeValue); // assigned so it can be passed by reference.
				$lyrics .= unicodeToUtf8($unicodeAsArray);
				if($GT_UNICODE == $unicodeValue){
					$isInsideTag = false;
				}
			} else {
				if($LT_UNICODE == $unicodeValue){
					$lyrics .= "<";
					$isInsideTag = true;
				} else {
					$lyrics .= "&#$unicodeValue;";
				}
			}
		}
		$lyrics .= $LINE_BREAK;
	}
	return substr($lyrics, 0, strlen($lyrics) - strlen($LINE_BREAK));
} // end gracenote_obfuscateText()

////
// Returns the HTML which should be inserted into a page to track the song-specific stats for Gracenote tracking.
//
// The only parameter is the 'action' to pass into the _trackEvent function.  Use one of the predefined values from
// the top of the Gracenote.php file such as GRACENOTE_VIEW_GRACENOTE_LYRICS or GRACENOTE_VIEW_OTHER_LYRICS.
//
// For more info on how this works, see:
// http://code.google.com/intl/en-US/apis/analytics/docs/tracking/eventTrackerGuide.html
////
function gracenote_getAnalyticsHtml($google_action){
	$google_category = "Lyrics";
	
	$trackEventCode = "";
	if($google_action != GRACENOTE_VIEW_NOT_LYRICS){
		$trackEventCode = "pageTracker._trackEvent('$google_category', '$google_action', jsGoogleLabel);";
	}

	$googleAnalyticsId = GOOGLE_ANALYTICS_ID;
	$retVal = <<<GOOGLE_JS
	<script src='http://www.google-analytics.com/ga.js' type='text/javascript'></script>
	<script type="text/javascript">
	try{
	var pageTracker = _gat._getTracker("$googleAnalyticsId");
	var gIdDiv = document.getElementById('gracenoteid');
	var jsGoogleLabel = "Unknown";
	if(gIdDiv){
		jsGoogleLabel = gIdDiv.innerHTML;
	} else {
		var titleElement = document.getElementsByTagName('title')[0];
		if(titleElement){
			jsGoogleLabel = titleElement.innerHTML;
			jsGoogleLabel = jsGoogleLabel.substring(0, jsGoogleLabel.indexOf(" - Lyric Wiki")); // Get just the page-name from the title.
			var gnPrefix  = "Gracenote:"; // If the page name starts with "Gracenote:", get rid of that.
			if(jsGoogleLabel.substring(0, gnPrefix.length) == gnPrefix){
				jsGoogleLabel = jsGoogleLabel.substring(gnPrefix.length);
			}
		}
	}
	$trackEventCode
	pageTracker._trackPageview();
	} catch(err) {}</script>
GOOGLE_JS
;

	return $retVal;
} // end gracenote_getAnalyticsHtml()

////
// Disable view source when trying to "edit" a page in the Gracenote namespace.
////
function gracenote_disableEdit(&$out, &$sk){
	GLOBAL $wgUser,$wgTitle;
	$retVal = true;
	if( ($wgTitle->getNamespace() == NS_GRACENOTE)  && (isset($_GET['action']) && $_GET['action']=="edit") && (!$wgUser->isAllowed( 'editgracenote' )) ) {
		$out->mBodytext = "Sorry, but the source can not be viewed on Gracenote pages due to licensing requirements.";
		$retVal = false;
	}
	return $retVal;
} // end gracenote_disableEdit()


////
// Called at BeforePageDisplay hook, this will let us stuff some javascript into the <head> element to accomplish the
// copy-protection requirements of the Gracenote integration.
////
function gracenote_installCopyProtection(&$out, &$sk){
#	Uncomment this for local testing -- Wikia already loads jquery
#	$out->addScript("<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js\"></script>");
	
	// Disable text-selection in the lyricsbox divs (this only needs to be done once between both the lyrics and gracenotelyrics extensions.
	$DISABLE_TEXT_SELECTION_FUNCTIONS = "
		function preventHighlighting(element){
			if (typeof element.onselectstart!=\"undefined\"){ // IE
				element.onselectstart=function(){return false};
			} else if (typeof element.style.MozUserSelect!=\"undefined\"){ // Moz-based (FireFox, etc.)
				element.style.MozUserSelect=\"none\";
			} else {
				element.onmousedown=function(){return false};
			}
			element.style.cursor = \"default\";
		}
	";
	$DISABLE_TEXT_SELECTION_CODE = "
		$('.lyricbox').each(function (i){
			preventHighlighting(this);
		});
	";

	// Disable CTRL+A's select-all capability.
	$DISABLE_SELECT_ALL = "
		$(window).keypress(function(event) {
			if (!(event.which == 97 && event.ctrlKey)) return true;
			event.preventDefault();
			return false;
		});
	";
	
	// Repeatedly clear clipboard (to attempt to stop Print-Screen, this doesn't work in modern browsers).
	$DISABLE_CLIPBOARD_FUNCTIONS = "
		function no_cp(){
			if($.browser.msie && $.browser.version==\"6.0\"){
				window.clipboardData.setData('text', '');
				setTimeout(\"no_cp()\",500);
			}
		}
		function do_err(){return true}
	";
	$DISABLE_CLIPBOARD_CODE = "
		if($.browser.msie && $.browser.version==\"6.0\"){onerror=do_err;}
		no_cp();
	";

	$DISABLE_RIGHT_CLICK_CODE = "$('body').bind('contextmenu', function(e) {return false;});";

	// Disables text-selection in the text-area on the 'edit' page (which will only show up in normal namespaces anyway).
	// We only want to do this for lyrics pages, so we first determine the page-type.
	$DISABLE_TEXT_SELECTION_IN_EDIT_BOX_CODE = "
		$('#wpTextbox1').select(function(event){
			event.preventDefault();
			//alert($('#gnCopySelectNotice').size());
			if($('#gnCopySelectNotice').size() == 0){
				var noticeDiv = \"<div id='gnCopySelectNotice'>We're sorry, but as part of licensing restrictions text-selection of this box has been disabled on lyrics pages.</div>\";
				$('#wpTextbox1').before(noticeDiv);
			}
			$('#gnCopySelectNotice').show()//.fadeOut(5000);

			// Remove and re-add the text to unselect.
			var backup = $('#wpTextbox1').get(0).value;
			$('#wpTextbox1').get(0).value = '';
			$('#wpTextbox1').get(0).value = backup;
		});
	";

	// TODO: If acceptable, make the DISABLE_RIGHT_CLICK_CODE and DISABLE_SELECT_ALL only apply to Gracenote pages and lyrics pages (what is the Gracenote namespace number?).
	
	// Add the various chunks of javascript that need to be run after the page is loaded.
	$out->addScript("<script type=\"text/javascript\">
		$(document).ready(function() {
			$DISABLE_CLIPBOARD_FUNCTIONS
			$DISABLE_TEXT_SELECTION_FUNCTIONS

			$DISABLE_CLIPBOARD_CODE
			$DISABLE_TEXT_SELECTION_CODE
			$DISABLE_SELECT_ALL

			var ns = wgNamespaceNumber;
			if(wgNamespaceNumber == 220){
				// Code that should only be on Gracenote pages.
				
				
			} else if(wgNamespaceNumber == 0){
				var ALBUM_NS = -1;
				var ARTIST_NS = -2;
				var MAIN_PAGE_NS = -3;
				if(wgPageName.match(/^.*?\([0-9]{4}\)$/)){
					ns = ALBUM_NS;
				} else if(wgPageName == \"Main_Page\"){
					ns = MAIN_PAGE_NS;
				} else if(wgPageName.indexOf(':') == -1){
					ns = ARTIST_NS;
				} else {
					// Code that should only be on (non-Gracenote) Lyrics pages.
					
					
				}
			}

			if($('.lyricbox').size() > 0){
				$DISABLE_RIGHT_CLICK_CODE
			}

			//$('.lyricbox').show();
		});
	</script>");

	return true;
}

////
// Returns a message explaining why print functionality has been disabled.
////
function gracenote_getPrintDisabledNotice(){
	return "<div class='print-disabled-notice'><br/><br/>Unfortunately, the licenses with music publishers require that we disable printing of lyrics.  We're sorry for the inconvenience.<br/><br/></div>";
}

////
// Returns the HTML for a noscript  tag which will hide the lyrics if javascript is disabled and give a message to the end-user explaining what happened.
////
function gracenote_getNoscriptTag(){
	return "<noscript><div class='gracenote-header'>You must enable javascript to view this page.  This is a requirement of our licensing agreement with music Gracenote.</div>
	<style type='text/css'>
		.lyricbox{display:none !important;}
	</style>
	</noscript>\n";
} // end gracenote_getNoscriptTag()

////
// Adds Google Analytics tracking to the bottom of every page.
//
// If there was a Gracenote-licensed song, tracks that as well.
////
function gracenote_outputGoogleAnalytics(&$out, $parserOutput){
	GLOBAL $wgGracenoteView;
	$out->addHTML(gracenote_getAnalyticsHtml($wgGracenoteView));
	return true;
} // end gracenote_outputGoogleAnalytics()

?>
