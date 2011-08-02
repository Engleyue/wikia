<?php

class SkinChooser {

	public static function onGetPreferences($user, &$defaultPreferences) {
		global $wgEnableAnswers, $wgForceSkin, $wgAdminSkin, $wgDefaultSkin, $wgDefaultSkin, $wgSkinPreviewPage, $wgOasis2010111, $wgSkipSkins, $wgSkipOldSkins;

		// hide default MediaWiki skin fieldset
		unset($defaultPreferences['skin']);		
		
		$mSkin  = $user->getOption('skin');

		// hacks for Answers
		if (!empty($wgEnableAnswers)) {
			$mSkin = 'answers';
		}

		// no skin settings at all when skin is forced
		if(!empty($wgForceSkin)) {
			return true;
		}

		if(!empty($wgAdminSkin)) {
			$defaultSkinKey = $wgAdminSkin;
		} else if(!empty($wgDefaultTheme)) {
			$defaultSkinKey = $wgDefaultSkin . '-' . $wgDefaultTheme;
		} else {
			$defaultSkinKey = $wgDefaultSkin;
		}
		
		// load list of skin names
		$validSkinNames = Skin::getSkinNames();

		// and sort them
		foreach($validSkinNames as $skinkey => &$skinname) {
			if(isset($skinNames[$skinkey]))  {
				$skinname = $skinNames[$skinkey];
			}
		}
		asort($validSkinNames);

		$validSkinNames2 = $validSkinNames;
		
		$previewtext = wfMsg('skin-preview');
		if(isset($wgSkinPreviewPage) && is_string($wgSkinPreviewPage)) {
			$previewLinkTemplate = Title::newFromText($wgSkinPreviewPage)->getLocalURL('useskin=');
		} else {
			$mptitle = Title::newMainPage();
			$previewLinkTemplate = $mptitle->getLocalURL('useskin=');
		}		

		$oldSkinNames = array();
		foreach($validSkinNames as $skinKey => $skinVal) {
			if ( $skinKey == 'oasis' || ((in_array($skinKey, $wgSkipSkins) || in_array($skinKey, $wgSkipOldSkins)) && !($skinKey == $mSkin))) {
				continue;
			}
			$oldSkinNames[$skinKey] = $skinVal;
		}

		$skins = array();
		$skins[wfMsg('new-look')] = 'oasis';
				
		// display radio buttons for rest of skin
		if(count($oldSkinNames) > 0) {
			foreach($oldSkinNames as $skinKey => $skinVal) {
				$previewlink = ' <a target="_blank" href="'.htmlspecialchars($previewLinkTemplate.$skinKey).'">'.$previewtext.'</a>';
				$skins[$skinVal.$previewlink.($skinKey == $defaultSkinKey ? ' ('.wfMsg('default').')' : '')] = $skinKey;
			}
		}

		$defaultPreferencesTemp = array();
		
		foreach($defaultPreferences as $k => $v) {
			$defaultPreferencesTemp[$k] = $v;
			if($k == 'oldsig') {

				$defaultPreferencesTemp['skin'] = array(
					'type' => 'radio',
					'options' => $skins,
					'label' => '&nbsp;',
					'section' => 'personal/layout',
				);

				$defaultPreferencesTemp['showAds'] = array(
					'type' => 'toggle',
					'label-message' => 'tog-showAds',
					'section' => 'personal/layout',
				);

			}
		}
		
		$defaultPreferences = $defaultPreferencesTemp;

		return true;
	}

	static private $wgAllowUserSkinOriginal;

	/**
	 * Generate proper key for user option
	 *
	 * This allow us to use different user preferences for answers / recipes / other wikis
	 */
	private static function getUserOptionKey($option) {
		global $wgEnableAnswers;
		wfProfileIn(__METHOD__);

		if (!empty($wgEnableAnswers)) {
			$key = "answers-{$option}";
		}
		else {
			$key = $option;
		}

		wfProfileOut(__METHOD__);
		return $key;
	}

	/**
	 * Get given option from user preferences
	 */
	private static function getUserOption($option) {
		global $wgUser, $wgEnableAnswers;
		wfProfileIn(__METHOD__);

		$val = $wgUser->getOption(self::getUserOptionKey($option));

		// fallback to non-answers option (RT #54087)
		if (!empty($wgEnableAnswers) &&  $val == '') {
			wfDebug(__METHOD__ . ": '{$option}' fallbacked\n");

			$val = $wgUser->getOption($option);
		}

		wfDebug(__METHOD__ . ": '{$option}' = {$val}\n");

		wfProfileOut(__METHOD__);
		return $val;
	}

	/**
	 * Set given option in user preferences
	 */
	private static function setUserOption($option, $value) {
		global $wgUser;
		wfProfileIn(__METHOD__);

		$key = self::getUserOptionKey($option);

		$wgUser->setOption($key, $value);
		self::log(__METHOD__, "{$key} = {$value}");

		/* debugging skin leak, -uber */
		if($key=='skin') { #yes, i do mean to check key and not option here
			global $wgCityId;
			$wgUser->setOption('skin-set', implode('|', array('SkinChooser', $wgCityId, time()) ) );
		}
		/* end debug */

		wfProfileOut(__METHOD__);
	}

	private static function getToggle( $tname, $trailer = false, $disabled = false ) {
		global $wgLang;

		$ttext = $wgLang->getUserToggle( $tname );

		$checked = self::getUserOption( $tname ) == 1 ? ' checked="checked"' : '';
		$disabled = $disabled ? ' disabled="disabled"' : '';
		$trailer = $trailer ? $trailer : '';
		return "<div class='toggle'><input type='checkbox' value='1' id=\"$tname\" name=\"wpOp$tname\"$checked$disabled />" .
			" <span class='toggletext'><label for=\"$tname\">$ttext</label>$trailer</span></div>\n";
	}

	/**
	 * Select proper skin and theme based on user preferences / default settings
	 */
	public static function onGetSkin($user) {
		global $wgCookiePrefix, $wgCookieExpiration, $wgCookiePath, $wgCookieDomain, $wgCookieSecure, $wgDefaultSkin, $wgDefaultTheme;
		global $wgVisitorSkin, $wgVisitorTheme, $wgOldDefaultSkin, $wgSkinTheme, $wgOut, $wgForceSkin, $wgRequest, $wgHomePageSkin, $wgTitle;
		global $wgAdminSkin, $wgSkipSkins, $wgArticle, $wgRequest, $wgDevelEnvironment;
		$isOasisPublicBeta = $wgDefaultSkin == 'oasis';
		
		wfProfileIn(__METHOD__);
		
		/*
		 * This is needed by skeleskin project for development reasons and will be removed on completion
		 * Please contact the Mobile Team for further information
		 */
		if( $wgRequest->getVal('useskin') == 'skeleskin' && $wgDevelEnvironment ) {
			$user->mSkin = &Skin::newFromKey(  $wgRequest->getVal('useskin') );
			wfProfileOut(__METHOD__);
			return false;	
		}
		
		/**
		 * check headers sent by varnish, if X-Skin is send force skin
		 * @author eloy, requested by artur
		 */	
		if( function_exists( 'apache_request_headers' ) ) {
			$headers = apache_request_headers();
			if( isset( $headers[ "X-Skin" ] ) && in_array( $headers[ "X-Skin" ], array( "monobook", "oasis", "wikia", "wikiaphone", "wikiaapp" ) ) ) {
				$user->mSkin = &Skin::newFromKey( $headers[ "X-Skin" ] );
				wfProfileOut(__METHOD__);
				return false;
			}
		}
			
		if(!($wgTitle instanceof Title) || in_array( self::getUserOption('skin'), $wgSkipSkins )) {
			$user->mSkin = &Skin::newFromKey(isset($wgDefaultSkin) ? $wgDefaultSkin : 'monobook');
			wfProfileOut(__METHOD__);
			return false;
		}

		// change to custom skin for home page
		if( !empty( $wgHomePageSkin ) ) {
			$overrideSkin = false;
			$mainPrefixedText = Title::newMainPage()->getPrefixedText();
			if ( $wgTitle->getPrefixedText() === $mainPrefixedText ) {
				// we're on the main page
				$overrideSkin = true;
			} elseif ( $wgTitle->isRedirect() && $wgRequest->getVal( 'redirect' ) !== 'no' ) {
				// not on main page, but page is redirect -- check where we're going next
				$tempArticle = new Article( $wgTitle );
				if ( !is_null( $tempArticle ) ) {
					$rdTitle = $tempArticle->getRedirectTarget();
					if ( !is_null( $rdTitle ) && $rdTitle->getPrefixedText() == $mainPrefixedText ) {
						// current page redirects to main page
						$overrideSkin = true;
					}
				}
			}
			if ( $overrideSkin ) {
				$user->mSkin = &Skin::newFromKey( $wgHomePageSkin );
				wfProfileOut(__METHOD__);
				return false;
			}
		}

		// only allow useskin=wikia for beta & staff.
		global $wgUser;
		if( $wgRequest->getVal('useskin') == 'wikia' ) {
			$wgRequest->setVal('useskin', 'oasis');
		}
		if(!empty($wgForceSkin)) {
			$wgForceSkin = $wgRequest->getVal('useskin', $wgForceSkin);
			$elems = explode('-', $wgForceSkin);
			$userSkin = ( array_key_exists(0, $elems) ) ? $elems[0] : null;
			$userTheme = ( array_key_exists(1, $elems) ) ? $elems[1] : null;

			$user->mSkin = &Skin::newFromKey($userSkin);
			$user->mSkin->themename = $userTheme;

			self::log(__METHOD__, "forced skin to be {$wgForceSkin}");

			wfProfileOut(__METHOD__);
			return false;
		}

		if(!empty($wgVisitorTheme) && $wgVisitorSkin == 'quartz') {
			$wgVisitorSkin .= $wgVisitorTheme;
		}

		# Get rid of 'wgVisitorSkin' variable, but sometimes create new one 'wgOldDefaultSkin'
		if($wgDefaultSkin == 'monobook' && substr($wgVisitorSkin, 0, 6) == 'quartz') {
			$wgOldDefaultSkin = $wgDefaultSkin;
			$wgDefaultSkin = $wgVisitorSkin;
		}
		unset($wgVisitorSkin);
		unset($wgVisitorTheme);

		if(strlen($wgDefaultSkin) > 7 && substr($wgDefaultSkin, 0, 6) == 'quartz') {
			$wgDefaultTheme=substr($wgDefaultSkin, 6);
			$wgDefaultSkin='quartz';
		}

		# Get skin logic
		wfProfileIn(__METHOD__.'::GetSkinLogic');

		if(!$user->isLoggedIn()) { # If user is not logged in
			if($wgDefaultSkin == 'oasis') {
				$userSkin = $wgDefaultSkin;
				$userTheme = null;
			} else if(!empty($wgAdminSkin) && !$isOasisPublicBeta) {
				$adminSkinArray = explode('-', $wgAdminSkin);
				$userSkin = isset($adminSkinArray[0]) ? $adminSkinArray[0] : null;
				$userTheme = isset($adminSkinArray[1]) ? $adminSkinArray[1] : null;
			} else {
				$userSkin = $wgDefaultSkin;
				$userTheme = $wgDefaultTheme;
			}
		} else {
			$userSkin = self::getUserOption('skin');
			$userTheme = self::getUserOption('theme');

			//RT:81173 Answers force hack.  It's in here because wgForceSkin is overwritten in CommonExtensions to '', most likely due to allowing admin skins and themes.  This will force answers and falls through to admin skin and theme logic if there is one.
			if(!empty($wgDefaultSkin) && $wgDefaultSkin == 'answers') {
				$userSkin = 'answers';
			}

			if(empty($userSkin)) {
				if(!empty($wgAdminSkin)) {
					$adminSkinArray = explode('-', $wgAdminSkin);
					$userSkin = isset($adminSkinArray[0]) ? $adminSkinArray[0] : null;
					$userTheme = isset($adminSkinArray[1]) ? $adminSkinArray[1] : null;
				} else {
					$userSkin = 'oasis';
				}
			} else if(!empty($wgAdminSkin) && $userSkin != 'oasis' && $userSkin != 'monobook' && $userSkin != 'wowwiki' && $userSkin != 'lostbook') {
				$adminSkinArray = explode('-', $wgAdminSkin);
				$userSkin = isset($adminSkinArray[0]) ? $adminSkinArray[0] : null;
				$userTheme = isset($adminSkinArray[1]) ? $adminSkinArray[1] : null;
			}

		}
		wfProfileOut(__METHOD__.'::GetSkinLogic');

		 global $wgEnableAnswers;


		$useskin = $wgRequest->getVal('useskin', $userSkin);
		$elems = explode('-', $useskin);
		$userSkin = ( array_key_exists(0, $elems) ) ? ( (empty($wgEnableAnswers) && $elems[0] == 'answers') ? 'oasis' : $elems[0]) : null;
		$userTheme = ( array_key_exists(1, $elems) ) ? $elems[1] : $userTheme;
		$userTheme = $wgRequest->getVal('usetheme', $userTheme);
	
		//Fix RT#133364 and makes crazy mobile users get the correct one
		if( $userSkin == 'smartphone' ){
			$userSkin = 'wikiaphone';
		}
		
		//SkeleSkin is a devbox-only experiment
		if( $userSkin == 'skeleskin' && !$wgDevelEnvironment ){
			$userSkin = 'wikiaphone';
		}

		$user->mSkin = &Skin::newFromKey($userSkin);

		$normalizedSkinName = substr(strtolower(get_class($user->mSkin)),4);

		self::log(__METHOD__, "using skin {$normalizedSkinName}");

		# Normalize theme name and set it as a variable for skin object.
		if(isset($wgSkinTheme[$normalizedSkinName])){
			wfProfileIn(__METHOD__.'::NormalizeThemeName');
			if(!in_array($userTheme, $wgSkinTheme[$normalizedSkinName])){
				if(in_array($wgDefaultTheme, $wgSkinTheme[$normalizedSkinName])){
					$userTheme = $wgDefaultTheme;
				} else {
					$userTheme = $wgSkinTheme[$normalizedSkinName][0];
				}
			}

			$user->mSkin->themename = $userTheme;

			# force default theme on monaco and oasis when there is no admin setting
			if( $normalizedSkinName == 'oasis' && (empty($wgAdminSkin) && $isOasisPublicBeta) ) {
				$user->mSkin->themename = $wgDefaultTheme;
			}

			self::log(__METHOD__, "using theme {$userTheme}");
			wfProfileOut(__METHOD__.'::NormalizeThemeName');
		}

		// FIXME: add support for oasis themes
		if ($normalizedSkinName == 'oasis') {
			$user->mSkin->themename = $wgRequest->getVal('usetheme');
		}

		wfProfileOut(__METHOD__);
		return false;
	}

	private static function log($method, $msg) {
		wfDebug("{$method}: {$msg}\n");
	}

}
