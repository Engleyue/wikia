<?php
/**
 * @author Sean Colombo
 * @date 20111001
 *
 * Special page to wrap API Gate
 *
 * API Gate is a library (written at Wikia, but intended NOT to be MediaWiki-specific) which
 * allows for creation and management of API keys.  It allows administrators to ban keys, and
 * provides basic charts of request rates for each key.  This special page is just meant as a
 * MediaWiki wrapper for the ApiGate functionality.
 *
 * In addition to this extension, the /lib/ApiGate/ApiGate_Config.php file is used as a bridge
 * to let ApiGate work with our MediaWiki deployment.
 *
 * WARNING: Since it was much faster, we currently depend on the charting in SponsorshipDashboard
 * which is partially written to be re-used but hasn't fully been separated yet. Therefore,
 * DO NOT DISABLE SponsorshipDashboard ON THE API WIKI! (or pages with charts will fatal-error).
 *
 * @ingroup SpecialPage
 */

/**
 * @ingroup SpecialPage
 */
class SpecialApiGate extends SpecialPage {
	const SUBPAGE_NONE = ""; // basically, the main dashboard
	const SUBPAGE_CHECK_KEY = "checkKey";
	const SUBPAGE_REGISTER = "register";
	const SUBPAGE_ALL_KEYS = "allKeys";
	const SUBPAGE_AGGREGATE_STATS = "aggregateStats";
	const SUBPAGE_USER_KEYS = "userKeys";
	const SUBPAGE_KEY = "key";
	const API_WIKI_CITYID = "97439";

	public function __construct() {
		parent::__construct( 'ApiGate' );
	}

	/**
	 * @param $subpage Mixed: string if any subpage provided, else null
	 */
	public function execute( $subpage ) {
		global $wgOut, $wgRequest, $IP, $wgUser, $API_GATE_DIR;
		wfProfileIn( __METHOD__ );

		// NOTE: We can't include CSS from the /lib directry (there is no symlink to /lib from the document-root on the apaches). We'll have to separate the CSS later when we can.
		//global $wgStyleVersion;
		//$wgOut->addStyle('../lib/ApiGate/css/apiGate.css?'.$wgStyleVersion ); // since this is outside of wgExtensionsPath, it doesn't have the cachebuster embedded in it automatically
		global $wgExtensionsPath;
		$wgOut->addStyle( $wgExtensionsPath . '/wikia/SpecialApiGate/css/apiGate.css', 'screen' );

		$wgOut->setPagetitle( wfMsg('apigate-h1') );

		// Box the main content of the text into a left-column so that a custom menu can be put on the right (below).
		$wgOut->addWikiText( "<mainpage-leftcolumn-start />");

// TODO: Make sure that all subpages (EXCEPT checkKey!) redirect to Api wiki if they're on another wiki (needed for that right-rail template to work & still be community editable - including the images on it).
// TODO: Make sure that all subpages (EXCEPT checkKey!) redirect to Api wiki if they're on another wiki (needed for that right-rail template to work & still be community editable - including the images on it).

		$useTwoColLayout = true; // main column & right rail on most forms, but no columns for chart pages since SponsorshipDashboard charts are too wide (and not resizable yet).
		$mainSectionHtml = "";
		$apiKey = $wgRequest->getVal( 'apiKey' );
		switch($subpage){
			case self::SUBPAGE_CHECK_KEY:

				// TODO: LATER Fill this out so that we can do per-method permissions (there can probably be a static helper-function in ApiGate to assist).
				$requestData = array();

				// Will output headers and a descriptive body-message.
				ApiGate::isRequestAllowed_endpoint( $apiKey, $requestData );

				// This sub-age is just for returning headers (http status-codes), etc.
				exit;

				break;
			case self::SUBPAGE_REGISTER:
				// Users must be logged in to get an API key
				if( !$wgUser->isLoggedIn() ){
					$wgOut->setPageTitle( wfMsg( 'apigate-nologin' ) );
					$wgOut->setHTMLTitle( wfMsg( 'errorpagetitle' ) );
					$wgOut->setRobotPolicy( 'noindex,nofollow' );
					$wgOut->setArticleRelated( false );
					$wgOut->enableClientCache( false );

					$mainSectionHtml .= $this->getLoginBoxHtml();
				} else {
					$mainSectionHtml .= $this->subpage_register();
				}
				break;
			case self::SUBPAGE_ALL_KEYS:
				$mainSectionHtml .= $this->subpage_allKeys();
				break;
			case self::SUBPAGE_AGGREGATE_STATS:
				$useTwoColLayout = false; // use full width so that the charts fit
				$mainSectionHtml .= $this->subpage_aggregateStats();
				break;
			case self::SUBPAGE_USER_KEYS:
				$mainSectionHtml .= $this->subpage_userKeys();
				break;
			case self::SUBPAGE_KEY:
				$apiKeyObject = ApiGate_ApiKey::newFromDb( $apiKey );
				if( is_object($apiKeyObject) ){
					// Determine if the current user can view this key (they must either own it or be an ApiGate admin).
					if($apiKeyObject->canBeViewedByCurrentUser()){
						$useTwoColLayout = false; // use full width so that the charts fit

						// Use standard tabs (from UI Style Guide)
						ob_start();
						?><ul class="tabs">
							<li class="selected" data-tab="apiGate_keyInfo"><a><?= wfMsg('apigate-tab-keyinfo') ?></a></li>
							<li data-tab="apiGate_keyStats"><a><?= wfMsg('apigate-tab-keystats') ?></a></li>
						</ul>
						<div id="apiGate_keyInfo" data-tab-body="apiGate_keyInfo" class="tabBody selected">
							<?= $this->subpage_keyInfo( $apiKeyObject ); ?>
						</div>
						<div id="apiGate_keyStats" data-tab-body="apiGate_keyStats" class="tabBody">
							<?= $this->subpage_keyStats( $apiKey ); ?>
						</div>
						<?php
						$mainSectionHtml .= ob_get_clean();
					} else {
	// TODO: ERROR MESSAGE THAT YOU'RE NOT AUTHORIZED TO VIEW THE KEY
	// TODO: ERROR MESSAGE THAT YOU'RE NOT AUTHORIZED TO VIEW THE KEY
					}
				} else {
	// TODO: ERROR MESSAGE THAT THE KEY WAS NOT FOUND IN THE DB
	// TODO: ERROR MESSAGE THAT THE KEY WAS NOT FOUND IN THE DB
				}
				break;
			case self::SUBPAGE_NONE:
			default:
				$mainSectionHtml .= $this->subpage_landingPage();
				break;
		}

		// If this is the two-column layout, wrap the extra markup around it.
		if( $useTwoColLayout ){
			$wgOut->addHTML( "<div id='specialApiGateMainSection'><div class='module'>
				\n$mainSectionHtml\n
			</div></div>" );

			// End the main column and add the right-rail.
			$wgOut->addWikiText("<mainpage-endcolumn />
								<mainpage-rightcolumn-start />
								{{MenuRail2}}
								<mainpage-endcolumn />");
		} else {
			$wgOut->addHTML( $mainSectionHtml );
		}

		wfProfileOut( __METHOD__ );
	} // end execute()

	private function getLoginBoxHtml(){
		$html = wfMsg('apigate-nologintext') . "<br/><br/><button type='button' data-id='login' class='ajaxLogin'>" . wfMsg('apigate-login-button') . "</button>";
		return $this->wrapHtmlInModuleBox( $html );
	} // end getLoginBoxHtml()

	/**
	 * Given a string of html, returns that same html wrapped inside of a div which is styled as a sub-module
	 * which is a standalone box for rendering in the main column of this page.
	 */
	private function wrapHtmlInModuleBox( $html ){
		return "<div class='sub_module'>$html</div>";
	} // end wrapHtmlInModuleBox()

	/**
	 * State-dependent dashboard for when you hit Special:ApiGate with no subpage specified.
	 *
	 * This performs its function by returning HTML.
	 *
	 * @param data - optional associative array where keys are variable names (and values are the values for that variable) to be exported to templates.
	 * @return 
	 */
	public function subpage_landingPage( $data = array() ){
		wfProfileIn( __METHOD__ );
		global $wgUser, $APIGATE_LINK_ROOT;
		$html = "";
		
		if( !$wgUser->isLoggedIn() ){
			global $wgOut;
			$wgOut->setPageTitle( wfMsg( 'apigate-nologin' ) );
			$wgOut->setHTMLTitle( wfMsg( 'errorpagetitle' ) );
			$wgOut->setRobotPolicy( 'noindex,nofollow' );
			$wgOut->setArticleRelated( false );
			$wgOut->enableClientCache( false );

			$html .= $this->getLoginBoxHtml();
		} else {
			// TODO: Could this be extracted to all be inside of one template in API Gate (index.php template).  We're not doing any funky logic here, are we (just need to chyeck that the subpages aren't)?
			// TODO: Could this be extracted to all be inside of one template in API Gate (index.php template).  We're not doing any funky logic here, are we (just need to chyeck that the subpages aren't)?

			// Show intro-blurb.
			$html .= ApiGate_Dispatcher::renderTemplate( "intro", array( "username" => $wgUser->getName() ) );
			$html .= "<br/>";

			// If the user has at least one API key, show the userKeys subpage.
			$userId = ApiGate_Config::getUserId();
			$keyData = ApiGate::getKeyDataByUserId( $userId );
			if( count($keyData) > 0 ){
				$html .= $this->subpage_userKeys( $userId, $keyData );
			} else {
				// If the user doesn't have any keys yet, show the registration form front-and-center.
				$html .= $this->subpage_register();
			}

			// If this is an admin, show links to Admin subpages.
			if ( ApiGate_Config::isAdmin() ) {
				$links = array(
					array(
						"text" => wfMsg('apigate-adminlinks-viewkeys'),
						"href" => "$APIGATE_LINK_ROOT/".self::SUBPAGE_ALL_KEYS,
					),
					array(
						"text" => wfMsg('apigate-adminlinks-viewaggregate'),
						"href" => "$APIGATE_LINK_ROOT/".self::SUBPAGE_AGGREGATE_STATS,
					),
				);
				$html .= "<br/>" . ApiGate_Dispatcher::renderTemplate( "adminLinks", array( "links" => $links ) );
			}
		}

		wfProfileOut( __METHOD__ );
		return $html;
	} // end subpage_landingPage()

	public function subpage_register(){
		global $wgUser, $wgOut, $API_GATE_DIR;
		wfProfileIn( __METHOD__ );
		$html = "";

		// Users must be logged in to get an API key
		if( !$wgUser->isLoggedIn() ){
			$html .= "<br/>".$this->getLoginBoxHtml();
		} else {
			$data = array('firstName' => '', 'lastName' => '', 'email_1' => '', 'email_2' => '', 'errorString' => '');

			// If the user's real name is in their profile, split it up and use it to initialize the form.
			$name = $wgUser->getName();
			$index = strpos($name, " ");
			if($index === false){
				$data['firstName'] = $name;
				$data['lastName'] = "";
			} else {
				$data['firstName'] = substr($name, 0, $index);
				$data['lastName'] = substr($name, $index+1);
			}

			// If the user has an email address set, use it to pre-populate the form.
			$data['email_1'] = $wgUser->getEmail();
			$data['email_2'] = $data['email_1'];

			include "$API_GATE_DIR/ApiGate_Register.class.php";
			$registered = ApiGate_Register::processPost( $data );
			if( $registered ) {
				// TODO: Not portable. This works well here to just show the module on this specialpage, but more work would need to be done for API Gate to have a good default behvaior.

				// Display a success message containing the new key.
				$msg = "<h3>".wfMsg( 'apigate-register-success-heading' )."</h3>";
				$msg .= wfMsgExt( 'apigate-register-success', array('parse'), array( $data['apiKey'] ) );
				$msg .= wfMsgExt( 'apigate-register-success-return', array('parse'), array() );
				$html .=  ApiGate_Dispatcher::renderTemplate( "info", array('message' => $msg) ); // intentionally normal style, not success-style.
			} else {
				$html .=  ApiGate_Dispatcher::renderTemplate( "register", $data );
			}
		}
		$html = $this->wrapHtmlInModuleBox( $html );

		wfProfileOut( __METHOD__ );
		return $html;
	}

	/**
	 * Subpage which shows admins some very brief stats on each API key so that they can rate-limit or just see
	 * which apps are interesting.
	 */
	public function subpage_allKeys(){
		wfProfileIn( __METHOD__ );
		global $wgRequest;

		$html = "";
		if ( ApiGate_Config::isAdmin() ) {
			$limit = 100;
			$offset = $wgRequest->getVal( 'offset', '' ); // for pagination
			$keyData = ApiGate::getAllKeyData( $limit, $offset );

			$html .=  ApiGate_Dispatcher::renderTemplate( "listKeys", array('keyData' => $keyData) );
		} else {

			// TODO: IMPLEMENT
			// TODO: IMPLEMENT
		//	$errorString = i18n( 'apigate-mysql-error', $queryString, mysql_error( $dbr ) );
		//	print ApiGate_Dispatcher::renderTemplate( "error", array('message' => $errorString));
		//	//$html = $this->wrapHtmlInModuleBox( $html );

		}

		wfProfileOut( __METHOD__ );
		return $html;
	} // end subpage_allKeys()

	public function subpage_aggregateStats(){

		// TODO: IMPLEMENT
		// TODO: IMPLEMENT

	} // end subpage_aggregateStats()

	/**
	 * Shows a small module of the API keys for the user provided.  If no user is provided, uses the currently logged in user.
	 *
	 * Allows explicitly specifying the keys (for performance reasons, if you've already looked them up).
	 *
	 * @param userId - mixed - (optional) userId of the user whose keys should be shown. If null or not provided, then it will use
	 *                 the currently logged in user.
	 * @param keyData - array - (optional) array of keys and key nicknames for the user in the format provided by ApiGate::getKeyDataByUserId.
	 *               If provided, this will be assumed to be the correct list of keys
	 *               so they will not be looked up from the database using the userId provided (or the default user as described in userId's
	 *               param documentation above.
	 */
	public function subpage_userKeys( $userId=null, $keyData=null ){
		wfProfileIn( __METHOD__ );

		if($userId == null){
			$userId = ApiGate_Config::getUserId();
		}
		if($keyData == null){
			$keyData = ApiGate::getKeyDataByUserId( $userId );
		}

		$data = array(
			'userId' => $userId,
			'keyData' => $keyData,
		);
		$html =  ApiGate_Dispatcher::renderTemplate( "userKeys", $data );

		wfProfileOut( __METHOD__ );
		return $html;
	} // end subpage_userKeys()

	/**
	 * Displays the detailed info about an API key and allows editing of it.  Also, if
	 * a key has been banned, this will show the explanation.
	 *
	 * This function does not check permissions, that is left to the calling-code.
	 */
	public function subpage_keyInfo( $apiKeyObject ){
		wfProfileIn( __METHOD__ );
		global $wgRequest;
		$html = "";

		// Process any updates if they were posted.
		$errorString = ApiGate_ApiKey::processPost();
		$apiKeyObject->reloadFromDb(); // key may have been changed while processing the post... reload it from the database

		$html .=  ApiGate_Dispatcher::renderTemplate( "key", array("apiKeyObject" => $apiKeyObject, "errorString" => $errorString) );

		wfProfileOut( __METHOD__ );
		return $html;
	} // end subpage_keyInfo()

	/**
	 * Displays usage stats (as interactive javscript charts) for a specific API key.  Re-uses
	 * some of our SponsorshipDashboard code, so it's not reusable by ApiGate and isn't very customizable
	 * yet, but using SD saved a TON by getting us a decent amount of features in almost no time.
	 *
	 * The calling code is responsible for checking whether the user should be allowed to see the html
	 * that this function returns.
	 *
	 * @param apiKey - string - api key whose usage stats should be shown.
	 * @param html - string - the html for showing the charts of stats. Can be thrown right into wgOut.
	 */
	public function subpage_keyStats( $apiKey ){
		wfProfileIn( __METHOD__ );
		global $wgCacheBuster;
		$html = "";

		// TODO: LATER: When API Gate has its own charting, use that instead of this SponsorshipDashboard-dependent code.

		// Will just show daily and monthly to users for now (and hourly will just be for admins to detect anything weird).
		if( ApiGate_Config::isAdmin() ){
			$html .= wfMsg('apigate-hourly-admin-only')."<br/><br/>\n"; // to avoid confusion, mention on the page that only admins see the hourly graph

			$metricName = wfMsg( 'apigate-chart-metric-requests' );
			$chartName = wfMsg( 'apigate-chart-name-hourly' );
			$html .= $this->getChartHtmlByPeriod( $apiKey, "hourly", $metricName, $chartName );
		}
		
		$chartName = wfMsg( 'apigate-chart-name-daily' );
		$html .= $this->getChartHtmlByPeriod( $apiKey, "daily", $metricName, $chartName );

		// This chart seems like overkill to display it w/all the other charts on the page. Hourly/daily/monthly seem good to start with. Might remove the concept of weekly stats from API Gate entirely.
		//$chartName = wfMsg( 'apigate-chart-name-weekly' );
		//$html .= $this->getChartHtmlByPeriod( $apiKey, "weekly", $metricName, $chartName );
		
		$chartName = wfMsg( 'apigate-chart-name-monthly' );
		$html .= $this->getChartHtmlByPeriod( $apiKey, "monthly", $metricName, $chartName );

		wfProfileOut( __METHOD__ );
		return $html;
	} // end subpage_keyStats()


	/**
	 * @brief Hook to add API Gate to user links.
	 */
	public static function onPersonalUrls(&$personalUrls, &$title) {
		wfProfileIn( __METHOD__ );
		
		if( SpecialApiGate::shouldShowUserLink() ) {
			$personalUrls['apiGate']['href'] = SpecialPage::getTitleFor("ApiGate")->getFullURL();
			$personalUrls['apiGate']['text'] = wfMsg( 'apigate-userlink' );
		}

		wfProfileOut( __METHOD__ );
		return true;
	} // end onPersonalUrls()
	
	/**
	 * Hook for adding API Gate user link (which was added to personalUrls by onPersonalUrls()) to the Oasis user-links dropdown.
	 */
	public static function onAccountNavigationModuleAfterDropdownItems(&$dropdownItems, $personalUrls){
		wfProfileIn( __METHOD__ );

		if( SpecialApiGate::shouldShowUserLink() ) {
			$dropdownItems[] = 'apiGate';
		}

		wfProfileOut( __METHOD__ );
		return true; // a hook function's way of saying it's okay to continue
	} // end onAccountNavigationModuleAfterDropdownItems()

	/**
	 * The user link only makes sense for users with an API key (with one exception: we'll make it show up for ALL users while they're on the Wikia API wiki).
	 *
	 * @return bool - true if the currently logged in user should see the link to API Gate in their user-links on this page.
	 */
	public static function shouldShowUserLink(){
		global $wgCityId, $wgUser;
		wfProfileIn( __METHOD__ );
		
		$showLink = false;
		if($wgCityId == self::API_WIKI_CITYID){
			$showLink = true;
		} else {
			$apiKeys = ApiGate::getKeysByUserId( $wgUser->getId() );
			if( count( $apiKeys ) > 0 ){
				$showLink = true;
			}
		}

		wfProfileOut( __METHOD__ );
		return $showLink;
	} // end shouldShowUserLink()
	
	
	/**
	 * Returns the HTML for the chart of apiKey requests per time-period provided.
	 *
	 * @param apiKey - string - 
	 * @param period - string - period of time type {hourly, daily, weekly, monthly}.
	 * @param uniqueMemCacheKey - string - key under which the processed results of the query will be cached.
	 * @param metricName - string - the name of the line of data which will be generated by the query (charts could have many lines in some cases).
	 * @param chartName - string - the name of the whole chart.
	 */
	public function getChartHtmlByPeriod( $apiKey, $period, $metricName, $chartName ){
		wfProfileIn( __METHOD__ );

		global $wgCacheBuster;
		$uniqueMemCacheKey = wfMemcKey( "ApiGate:KeyStats:$apiKey:$period:$wgCacheBuster" );
		
		switch($period){
			case "hourly":
				$frequency = SponsorshipDashboardDateProvider::SD_FREQUENCY_HOUR;
				break;
			default:
				$frequency = SponsorshipDashboardDateProvider::SD_FREQUENCY_DAY;
		}

		$queryString = "SELECT hits as number, startOfPeriod as creation_date FROM apiGate_stats_$period WHERE apiKey='$apiKey' ORDER BY startOfPeriod";
		$html = SpecialApiGate::getChartHtmlByQuery( $queryString, $frequency, $uniqueMemCacheKey, $metricName, $chartName );

		wfProfileOut( __METHOD__ );
		return $html;
	} // end getChartHtmlByPeriod()

	/**
	 * Returns HTML for a SponsorshipDashboard chart of the data that queryString gets from the database.
	 *
	 * The query is expected to return "number" and "creation_date" fields in each row.
	 *
	 * @param queryString - string - query to run whose 'number' and 'creation_date' fields will be charted.
	 * @param frequency - enum - defined value of SponsorshipDashboardDateProvider::SD_FREQUENCY_<something>.
	 * @param uniqueMemCacheKey - string - key under which the processed results of the query will be cached.
	 * @param metricName - string - the name of the line of data which will be generated by the query (charts could have many lines in some cases).
	 * @param chartName - string - the name of the whole chart.
	 */
	public static function getChartHtmlByQuery( $queryString, $frequency, $uniqueMemCacheKey, $metricName, $chartName ){
		wfProfileIn( __METHOD__ );

		//lets create data source
		$oSource = F::build( 'SponsorshipDashboardSourceDatabase' , array( $uniqueMemCacheKey ) );

		// this name will be displayed in on a chart
		$oSource->serieName = $metricName;

		// configure the soruce ( source config depends on source type )
		$dbr = wfGetDB( DB_SLAVE, array(), F::app()->wg->externalSharedDB );
		$oSource->setDatabase( $dbr );
		$oSource->setQuery( $queryString );
		$oSource->setFrequency( $frequency );

		// so we have the source, lets configure the report object
		$oReport = F::build( 'SponsorshipDashboardReport' );
		$oReport->name = $chartName;

		// this shows how many steps will be displayed on chart ( counting backwards from now )
		$oReport->setLastDateUnits( 30 );
		// lest choose the time resolution
		$oReport->frequency = $frequency;

		// then pass ready source to report ( you can pass many sources )
		$oReport->tmpSource = $oSource;
		$oReport->acceptSource();

		$oReport->setId( 0 );
		$oReport->lockSources();

		// now is the time for making output. There are chart, raw and table outputs.
		$oOutput = F::build( 'SponsorshipDashboardOutputChart' );
		$oOutput->showActionsButton = false;
		$oOutput->set( $oReport );

		// to get HTML just call
		$html = $oOutput->getHTML();

		wfProfileOut( __METHOD__ );
		return $html;
	} // end getChartHtmlByQuery()

} // end class SpecialApiGate
