<?php
/**
 * Author: Sean Colombo
 * Date: 20100127
 *
 * Internationalization file for Interstitials extension.
 */

$messages = array();

$messages['en'] = array(
	// If we were displaying interstitials but there is no campaign code, this would be an egregious error.
	// An extremely friendly message is probably much better than a blank interstitial.  At least we get to tell them
	// how we feel for X seconds.
	"interstitial-default-campaign-code" => "Wikia Loves You!",
	"interstitial-skip-ad" => "Skip this ad",

	"interstitial-already-logged-in-no-link" => "You are already logged in and there is no destination set.",
	"interstitial-disabled-no-link" => "There is no destination set and interstitials are not enabled on this wiki.",
	"interstitial-link-away" => "There is nothing to see here!<br /><br />Would you like to go to the [[{{MediaWiki:Mainpage}}|Main Page]] or perhaps a [[Special:Random|random page]]?",

	// oasis Exitstitial
	"exitstitial-title" => "Leaving ", // @todo FIXME: no trailing whitespace support in Translate extension. Needs parameter or hard coded space.
	"exitstitial-register" => "<a href=\"#\" class=\"register\">Register</a> or <a href=\"#\" class=\"login\">Login</a> to skip ads.",
	"exitstitial-button" => "Skip This Ad"
);
