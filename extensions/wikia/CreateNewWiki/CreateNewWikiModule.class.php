<?php
class CreateNewWikiModule extends Module {

	// global imports
	var $IP;
	var $isUserLoggedIn;
	var $wgLanguageCode;
	var $wgOasisThemes;
	var $wgSitename;
	var $wgExtensionsPath;

	// form fields
	var $aCategories;
	var $aTopLanguages;
	var $aLanguages;

	// form field values
	var $wikiName;
	var $wikiDomain;
	var $wikiLanguage;
	var $wikiCategory;
	var $params;

	// state variables
	var $currentStep;
	var $skipWikiaPlus;

	// ajax call response - previously: 'response' (conflicting name with WikiaController::response)
	var $res;

	var $app;

	const DAILY_USER_LIMIT = 2;

	public function __construct($app) {
		$this->app = $app;
	}

	public function executeIndex() {
		global $wgSuppressWikiHeader, $wgSuppressPageHeader, $wgSuppressFooter, $wgSuppressAds, $wgSuppressToolbar, $fbOnLoginJsOverride, $wgRequest, $wgPageQuery, $wgUser;
		wfProfileIn( __METHOD__ );

		// hide some default oasis UI things
		$wgSuppressWikiHeader = true;
		$wgSuppressPageHeader = true;
		$wgSuppressFooter = false;
		$wgSuppressAds = true;
		$wgSuppressToolbar = true;

		// fbconnected means user has gone through step 2 to login via facebook.
		// Therefore, we need to reload some values and start at the step after signup/login
		$fbconnected = $wgRequest->getVal('fbconnected');
		$fbreturn = $wgRequest->getVal('fbreturn');
		if((!empty($fbconnected) && $fbconnected === '1') || (!empty($fbreturn) && $fbreturn === '1')) {
			$this->executeLoadState();
			$this->currentStep = 'DescWiki';
		} else {
			$this->currentStep = '';
		}
		$wgPageQuery[] =

		// form field values
		$hubs = WikiFactoryHub::getInstance();
                $this->aCategories = $hubs->getCategories();

                $this->aTopLanguages = explode(',', wfMsg('autocreatewiki-language-top-list'));
		$this->aLanguages = wfGetFixedLanguageNames();
		asort($this->aLanguages);

		$useLang = $wgRequest->getVal('uselang', $wgUser->getOption( 'language' ));

                // falling back to english (BugId:3538)
                if ( !array_key_exists($useLang, $this->aLanguages) ) {
                    $useLang = 'en';
                }
		$this->params['wikiLanguage'] = empty($this->params['wikiLanguage']) ? $useLang: $this->params['wikiLanguage'];
		$this->params['wikiLanguage'] = empty($useLang) ? $this->wgLanguageCode : $useLang;  // precedence: selected form field, uselang, default wiki lang

		// facebook callback overwrite on login.  CreateNewWiki re-uses current login stuff.
		$fbOnLoginJsOverride = 'WikiBuilder.fbLoginCallback();';

		// export info if user is logged in
		$this->isUserLoggedIn = $wgUser->isLoggedIn();

		// remove wikia plus for now for all languages
		$this->skipWikiaPlus = true;

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Ajax call to validate domain.
	 * Called via moduleproxy
	 */
	public function executeCheckDomain() {
		wfProfileIn(__METHOD__);
		global $wgRequest;

		$name = $wgRequest->getVal('name');
		$lang = $wgRequest->getVal('lang');
		$type  = $wgRequest->getVal('type');

		$this->res = AutoCreateWiki::checkDomainIsCorrect($name, $lang, $type);

		wfProfileOut(__METHOD__);
	}

	/**
	 * Ajax call for validate wiki name.
	 */
	public function executeCheckWikiName() {
		wfProfileIn(__METHOD__);

		$wgRequest = $this->app->getGlobal('wgRequest');

		$name = $wgRequest->getVal('name');
		$lang = $wgRequest->getVal('lang');

		$this->res = $this->app->runFunction('AutoCreateWiki::checkWikiNameIsCorrect', $name, $lang);

		wfProfileOut(__METHOD__);
	}

	/**
	 * Creates wiki
	 */
	public function executeCreateWiki() {
		wfProfileIn(__METHOD__);
		$wgRequest = $this->app->getGlobal('wgRequest');
		$wgDevelDomains = $this->app->getGlobal('wgDevelDomains');

		$params = $wgRequest->getArray('data');

		if ( !empty($params) && 
			!empty($params['wikiName']) &&
			!empty($params['wikiDomain']) )
		{
			// log if called with old params
			trigger_error("CreateWiki called with old params." . $params['wikiName'] . " " . $params['wikiDomain'] . " " . wfGetIP(), E_USER_WARNING);
		}

		if ( empty($params) ||
			empty($params['wikiaName']) ||
			empty($params['wikiaDomain']) ||
			empty($params['wikiaLanguage']) ||
			empty($params['wikiaCategory']) )
		{
			// do nothing
			$this->status = 'error';
			$this->statusMsg = $this->app->runFunction('wfMsg', 'cnw-error-general');
			$this->statusHeader = $this->app->runFunction('wfMsg', 'cnw-error-general-heading');
		} else {
			$wgUser = $this->app->getGlobal('wgUser');

			// check if user is blocked
			if ( $wgUser->isBlocked() ) {
				$this->status = 'error';
				$this->statusMsg = $this->app->wf->msg( 'cnw-error-blocked', $wgUser->blockedBy(), $wgUser->blockedFor(), $wgUser->getBlockId() );
				$this->statusHeader = $this->app->wf->msg( 'cnw-error-blocked-header' );
				return;
			}

			// check if user is a tor node
			if ( class_exists( 'TorBlock' ) && TorBlock::isExitNode() ) {
				$this->status = 'error';
				$this->statusMsg = $this->app->wf->msg( 'cnw-error-torblock' );
				$this->statusHeader = $this->app->wf->msg( 'cnw-error-blocked-header' );
				return;
			}

			// check if user created more wikis than we allow per day
			$numWikis = $this->countCreatedWikis($wgUser->getId());
			if($numWikis > self::DAILY_USER_LIMIT && $wgUser->isPingLimitable() ) {
				$this->status = 'wikilimit';
				$this->statusMsg = $this->app->runFunction('wfMsg', 'cnw-error-wiki-limit', self::DAILY_USER_LIMIT);
				$this->statusHeader = $this->app->runFunction('wfMsg', 'cnw-error-wiki-limit-header');
				return;
			}

			$createWiki = F::build('CreateWiki', array($params['wikiaName'], $params['wikiaDomain'], $params['wikiaLanguage'], $params['wikiaCategory']));
			$error_code = $createWiki->create();
			$this->cityId = $createWiki->getWikiInfo('city_id');
			if(empty($this->cityId)) {
				$this->status = 'backenderror';
				$this->statusMsg = $this->app->runFunction('wfMsg', 'cnw-error-database', $error_code).
					'<br>'.
					$this->app->runFunction('wfMsg', 'cnw-error-general');
				$this->statusHeader = $this->app->runFunction('wfMsg', 'cnw-error-general-heading');
				trigger_error("Failed to create new wiki: $error_code " . $params['wikiaName'] . " " . $params['wikiaLanguage'] . " " . wfGetIP(), E_USER_WARNING);
			} else {
				$this->status = 'ok';
				$this->siteName = $createWiki->getWikiInfo('sitename');
				$finishCreateTitle = F::build('GlobalTitle', array("FinishCreate", NS_SPECIAL, $this->cityId), 'newFromText');
				$this->finishCreateUrl = empty($wgDevelDomains) ? $finishCreateTitle->getFullURL() : str_replace('.wikia.com', '.'.$wgDevelDomains[0], $finishCreateTitle->getFullURL());
			}
		}


		wfProfileOut(__METHOD__);
	}

	/**
	 * Loads params from cookie.
	 */
	public function executeLoadState() {
		wfProfileIn(__METHOD__);
		if(!empty($_COOKIE['createnewwiki'])) {
			$this->params = json_decode($_COOKIE['createnewwiki'], true);
		} else {
			$this->params = array();
		}
		wfProfileOut(__METHOD__);
	}

	/**
	 * Checks if WikiPayment is enabled and handles fetching PayPal token - if disabled, displays error message
	 *
	 * @author Maciej B?aszkowski <marooned at wikia-inc.com>
	 */
	public function executeUpgradeToPlus() {
		global $wgRequest;
		wfProfileIn( __METHOD__ );

		$cityId = $wgRequest->getVal('cityId');

		if (method_exists('SpecialWikiPayment', 'fetchPaypalToken')) {
			$data = SpecialWikiPayment::fetchPaypalToken($cityId);
			if (empty($data['url'])) {
				$this->status = 'error';
				$this->caption = wfMsg('owb-step4-error-caption');
				$this->content = wfMsg('owb-step4-error-token-content');
			} else {
				$this->status = 'ok';
				$this->data = $data;
			}
		} else {
			$this->status = 'error';
			$this->caption = wfMsg('owb-step4-error-caption');
			$this->content = wfMsg('owb-step4-error-upgrade-content');
		}

		wfProfileOut( __METHOD__ );
	}

	public function executePhalanx() {
		global $wgRequest;
		wfProfileIn( __METHOD__ );

		$text = $wgRequest->getVal('text','');
		$blockedKeywords = array();

		$filters = Phalanx::getFromFilter( Phalanx::TYPE_CONTENT );
		foreach( $filters as $filter ) {
			$result = Phalanx::isBlocked( $text, $filter );
			if($result['blocked']) {
				$blockedKeywords[] = $result['msg'];
			}
		}

		$this->msgHeader = '';
		$this->msgBody = '';
		if(count($blockedKeywords) > 0) {
			$keywords = '';
			for ($i = 0; $i < count($blockedKeywords); $i++) {
				if($i != 0) {
					$keywords .= ', ';
				}
				$keywords .= $blockedKeywords[$i];
			}
			$this->msgHeader = wfMsg('cnw-badword-header');
			$this->msgBody = wfMsg('cnw-badword-msg', $keywords);
		}

		wfProfileOut( __METHOD__ );
	}

	public static function setupCreateNewWiki() {
		F::addClassConstructor('CreateNewWikiModule', array(F::app()));
	}

	/**
	 * get number of created Wikis for current day
	 * note: copied from autocreatewiki
	 */
	private function countCreatedWikis($iUser = 0) {
		global $wgExternalSharedDB;
		wfProfileIn( __METHOD__ );

		$dbr = wfGetDB( DB_SLAVE, array(), $wgExternalSharedDB );
		$where = array( "date_format(city_created, '%Y%m%d') = date_format(now(), '%Y%m%d')" );
		if ( !empty($iUser) ) {
			$where[] = "city_founding_user = '{$iUser}' ";
		}
		$oRow = $dbr->selectRow(
			"city_list",
			array( "count(*) as count" ),
			$where,
			__METHOD__
		);

		wfProfileOut( __METHOD__ );
		return intval($oRow->count);
	}

}
