<?php

/**
 * SponsorshipDashboard
 * @author Jakub "Szeryf" Kurcek
 *
 * Main switch for Sponsorship Dashboard
 */

class SponsorshipDashboard extends SpecialPage {

	const TEMPLATE_EMPTY_CHART = 'emptychart';
	const TEMPLATE_ERROR = 'error';
	const TEMPLATE_CHART = 'chart';
	const TEMPLATE_SAVE_SUCCESFUL = 'editor/saveSuccess';

	const ADMIN = 'admin';

	protected $adminTabs = array( 'ViewInfo', 'ViewReports', 'ViewGroups', 'ViewUsers' );

	protected $currentReport = '';
	protected $currentGroup = '';
	protected $currentReports = '';
	protected $currentGroups = '';

	protected $popularCityHubs = array();
	protected $chartCounter = 0;
	protected $hiddenSeries = array();
	protected $dataMonthly = false;
	protected $tagDependent = false;
	protected $currentCityHub = false;
	protected $allowed = false;
	protected $fromYear = 2004;

	function  __construct() {
		
		$oUser = F::app()->getGlobal('wgUser');
		$this->allowed = $oUser->isAllowed('wikimetrics');
		
		parent::__construct( 'SponsorshipDashboard', 'sponsorship-dashboard', $this->allowed);
	}

	public function isAllowed() {
		
		return $this->allowed;
	}

	function execute( $subpage = false ) {

		global $wgSupressPageSubtitle;
		
		$wgOut = F::app()->getGlobal('wgOut');

		$wgOut->setHTMLTitle( wfMsg( 'sponsorship-dashboard-default-page-title' ) );
		$subPageParams = explode( '/', $subpage );
		$wgSupressPageSubtitle = true;
		
		// admin panel

		if ( $this->isAllowed() && $subPageParams[0] == self::ADMIN ){

			if ( ( !isset( $subPageParams[1] ) || $subPageParams[1] == 'ViewInfo' ) ) {
				$this->HTMLViewInfo();
				return true;
			}

			if ( $subPageParams[1] == 'EditReport' ) {
				$this->HTMLEditReport(
					( isset( $subPageParams[2] ) ) ? $subPageParams[2] : 0
				);
				return true;
			}

			if ( $subPageParams[1] == 'EditGroup' ) {
				$this->HTMLEditGroup(
					( isset( $subPageParams[2] ) ) ? $subPageParams[2] : 0
				);
				return true;
			}

			if ( $subPageParams[1] == 'EditUser' ) {
				$this->HTMLEditUser(
					( isset( $subPageParams[2] ) ) ? $subPageParams[2] : 0
				);
				return true;
			}

			if ( $subPageParams[1] == 'ViewReports' ) {
				$this->HTMLViewReports();
				return true;
			}

			if ( $subPageParams[1] == 'ViewUsers' ) {
				$this->HTMLViewUsers();
				return true;
			}

			if ( $subPageParams[1] == 'ViewReport' ) {
				$this->HTMLViewReport(
					( isset( $subPageParams[2] ) ) ? $subPageParams[2] : 0
				);
				return true;
			}

			if ( $subPageParams[1] == 'CSVReport' ) {
				$this->HTMLCSVReport(
					( isset( $subPageParams[2] ) ) ? $subPageParams[2] : 0
				);
				return true;
			}

			if ( $subPageParams[1] == 'ViewGroups' ) {
				$this->HTMLViewGroups();
				return true;
			}

			$this->HTMLViewInfo();
			return true;
		}

		// user panel

		if ( $this->getTabs( $subpage ) ) {
			
			$outputType = ( isset( $subPageParams[2] ) ) ? $subPageParams[2] : 'html' ;
			switch ( $outputType ){
				case 'csv': $this->CSVReport(); break;
				default: $this->HTMLReport(); break;
			}
			return true;
		} else {
			$this->HTMLerror();
			return false;
		}
	}

	protected function getTabs( $tabName ) {

		if ( $this->isAllowed() ) {
			return $this->getTabsForStaff( $tabName );
		}
		
		$wgUser = F::app()->getGlobal( 'wgUser' );

		if ( $wgUser->isAnon() ) {
			return false;
		}

		$SDUser = SponsorshipDashboardUser::newFromUserId( $wgUser->getId() );
		if ( empty( $SDUser ) ) return false;

		$SDUser->loadUserParams();

		$SDGroups = F::build( 'SponsorshipDashboardGroups' );
		$SDUserGroups = $SDGroups->getUserData( $SDUser->id );
		$this->currentGroups = $SDUserGroups;

		$exploded = explode( '/', $tabName );
		$catId = $exploded[0];

		if ( isset( $exploded[1] ) ) {
			$repId = $exploded[1];
		} else {
			$repId = 0;
		}

		if ( empty( $catId ) ) {
			$catKeys = array_keys( $this->currentGroups );
			if ( !isset( $catKeys[0] ) ) {
				return false;
			}
			$catId = $catKeys[0];
			$repId = 0;
		} else {
			if ( !in_array( $catId, array_keys( $this->currentGroups ) ) ) {
				return false;
			}
		}

		$currentGroup = $this->currentGroups[ $catId ];

		if ( empty( $repId ) ) {
			$aKeys = array_keys( $currentGroup->reports );
			if ( !isset( $aKeys[0] ) ) {
				return false;
			}
			$repId = $aKeys[0];
		}

		if( !isset( $currentGroup->reports[ $repId ] ) ) {
			return false;
		}

		$this->currentReports = $currentGroup->reports;
		$this->currentGroup = $catId;
		$this->currentReport = $currentGroup->reports[ $repId ];

		return true;
	}

	/*
	 * Provides all groups with reports.
	 *
	 * @param int $tabName group id.
	 *
	 * @return boolean. There is no such group / report returns false;
	 */

	function getTabsForStaff( $tabName ) {

		$SDGroups = F::build( 'SponsorshipDashboardGroups' );
		$SDGData = $SDGroups->getObjArray( true );
		$this->currentGroups = $SDGData;

		$exploded = explode( '/', $tabName );
		$catId = $exploded[0];
		if ( isset( $exploded[1] ) ) {
			$repId = $exploded[1];
		} else {
			$repId = 0;
		}

		$SDR = new SponsorshipDashboardGroup( $catId );

		if ( !$SDR->exist() ) {
			$catId = 0;
		}

		if ( empty( $catId ) ) {
			$catKeys = array_keys( $SDGData );
			if ( !isset( $catKeys[0] ) ) {
				return false;
			}
			$catId = $catKeys[0];
		}

		$SDGroup = F::build( 'SponsorshipDashboardGroup', array( $catId ) );
		$SDGroup->loadGroupParams();
		if( !isset( $SDGroup->reports[ $repId ] ) ) {
			$aKeys = array_keys( $SDGroup->reports );
			if ( !isset( $aKeys[0] ) ) {
				return false;
			}
			$repId = $aKeys[0];
		}

		$this->currentReports = $SDGroup->reports;
		$this->currentGroup = $catId;
		$this->currentReport = $SDGroup->reports[ $repId ];

		return true;
	}

	/*
	 * Displays user header
	 *
	 * @return void
	 */

	protected function displayHeader() {

		$wgOut = F::app()->getGlobal('wgOut');
		$wgTitle = F::app()->getGlobal('wgTitle');

		wfProfileIn( __METHOD__ );

		$wgOut->addStyle( AssetsManager::getInstance()->getSassCommonURL('extensions/wikia/SponsorshipDashboard/css/SponsorshipDashboard.scss'));

		$oTmpl = new EasyTemplate( dirname( __FILE__ ) . "/templates/" );
		$oTmpl->set_vars(
			array(
				"groupId"		=> $this->currentGroup,
				"groups"		=> $this->currentGroups,
				"path"			=> $wgTitle->getFullURL(),
				"reports"		=> $this->currentReports,
				"report"		=> $this->currentReport
			)
		);
		$wgOut->addHTML( $oTmpl->execute( "header" ) );

		wfProfileOut( __METHOD__ );
	}

	/*
	 * Link where to go after saving report
	 *
	 * @return string link
	 */

	public function reportSaved() {

		return SpecialPage::getTitleFor('SponsorshipDashboard')->getInternalURL()."/".self::ADMIN."/ViewReports/";

	}

	/**
	 * HTMLerror - displays error subpage.
	 */

	public function HTMLerror() {

		$wgOut = F::app()->getGlobal('wgOut');
		
		$oTmpl = new EasyTemplate( dirname( __FILE__ ) . "/templates/" );
		$wgOut->addHTML( $oTmpl->execute( self::TEMPLATE_ERROR ) );

		return false;
	}

	/*
	 * Displays admin header
	 *
	 * @return void
	 */

	protected function HTMLAdminHeader( $subpage ) {

		global $wgExtensionsPath, $wgScriptPath, $wgStyleVersion, $wgJsMimeType;

		$wgOut = F::app()->getGlobal('wgOut');
		$wgTitle = F::app()->getGlobal('wgTitle');
		$wgRequest = F::app()->getGlobal('wgRequest');

		$subpage = ( !in_array( $subpage, $this->adminTabs ) ) ? $this->adminTabs[0] : $subpage;

		$oTmpl = new EasyTemplate( dirname( __FILE__ ) . "/templates/" );
		$oTmpl->set_vars(
			array(
				"tab"		=> $subpage,
				"tabs"		=> $this->adminTabs,
				"path"		=> $wgTitle->getFullURL()
			)
		);

		$wgOut->addHTML(
			$oTmpl->execute( 'admin/adminHeader' )
		);
	}

	protected function HTMLEditReport( $id ) {

		global $wgExtensionsPath, $wgScriptPath, $wgStyleVersion, $wgJsMimeType;

		$wgOut = F::app()->getGlobal('wgOut');
		$wgRequest = F::app()->getGlobal('wgRequest');

		$oTmpl = new EasyTemplate( dirname( __FILE__ ) . "/templates/" );

		$wgOut->addScript("<script type=\"{$wgJsMimeType}\" src=\"{$wgExtensionsPath}/wikia/SponsorshipDashboard/js/SponsorshipDashboardEditor.js?{$wgStyleVersion}\" ></script>\n");
		$wgOut->addScript( "<!--[if IE]><script type=\"{$wgJsMimeType}\" src=\"/skins/common/jquery/excanvas.min.js?{$wgStyleVersion}\"></script><![endif]-->\n" );
		$wgOut->addScript( "<script type=\"{$wgJsMimeType}\" src=\"/skins/common/jquery/jquery.flot.js?{$wgStyleVersion}\"></script>\n" );
		$wgOut->addScript( "<script type=\"{$wgJsMimeType}\" src=\"/skins/common/jquery/jquery.flot.selection.js?{$wgStyleVersion}\"></script>\n" );
		$wgOut->addStyle( AssetsManager::getInstance()->getSassCommonURL( 'extensions/wikia/SponsorshipDashboard/css/SponsorshipDashboardEditor.scss' ) );
		$wgOut->addStyle( AssetsManager::getInstance()->getSassCommonURL( 'extensions/wikia/SponsorshipDashboard/css/SponsorshipDashboard.scss' ) );
		$this->HTMLAdminHeader( 'ViewReports' );

		$report = F::build( 'SponsorshipDashboardReport', array( $id ) );
		$report->loadReportParams();

		$menuItems = $report->getMenuItemsHTML();
		$reportParams = $report->getReportParams();

		$oTmpl->set_vars(
			array(
			    'menuItems' => $menuItems,
			    'reportParams' => $reportParams,
			    'reportEditorPath' => SpecialPage::getTitleFor('SponsorshipDashboard')->getInternalURL().'/'.self::ADMIN.'/ViewReports'
			)
		);

		$wgOut->addHTML(
			$oTmpl->execute( 'admin/editReport' )
		);

		return false;
	}

	protected function HTMLCSVReport( $id ) {

		$id = (int)$id;

		if ( !empty( $id ) ){

			$this->currentReport = F::build( 'SponsorshipDashboardReport', array( $id ) );
			$this->currentReport->setId( $id );
			$this->currentReport->loadReportParams();
			$this->currentReport->loadSources();
			$this->currentReport;
			$this->CSVReport();
		}
		$this->HTMLerror();
	}

	protected function HTMLViewReport( $id ) {

		global $wgExtensionsPath, $wgScriptPath, $wgStyleVersion, $wgJsMimeType;

		$wgOut = F::app()->getGlobal('wgOut');
		$wgRequest = F::app()->getGlobal('wgRequest');

		$oTmpl = new EasyTemplate( dirname( __FILE__ ) . "/templates/" );

		$this->HTMLAdminHeader( 'ViewReports' );

		$report = F::build('SponsorshipDashboardReport' , array( $id ) );
		$report->setId( $id );
		$report->loadReportParams();
		$report->loadSources();

		$chart = SponsorshipDashboardOutputChart::newFromReport( $report );
		$table = SponsorshipDashboardOutputTable::newFromReport( $report );

		$wgOut->addHTML(
			$chart->getHTML()
		);
		
		$wgOut->addHTML(
			$table->getHTML()
		);
	}

	protected function HTMLViewInfo() {

		$wgOut = F::app()->getGlobal('wgOut');
		
		$oTmpl = new EasyTemplate( dirname( __FILE__ ) . "/templates/" );

		$this->HTMLAdminHeader( 'ViewInfo' );
		$wgOut->addStyle( AssetsManager::getInstance()->getSassCommonURL( 'extensions/wikia/SponsorshipDashboard/css/SponsorshipDashboard.scss' ) );
		$wgOut->addHTML(
			$oTmpl->execute( 'admin/viewInfo' )
		);

		return true;
	}

	protected function HTMLEditGroup( $id ) {

		global $wgExtensionsPath, $wgScriptPath, $wgStyleVersion, $wgJsMimeType;

		$wgOut = F::app()->getGlobal('wgOut');
		$wgRequest = F::app()->getGlobal('wgRequest');

		$oTmpl = new EasyTemplate( dirname( __FILE__ ) . "/templates/" );

		$this->HTMLAdminHeader( 'ViewGroups' );

		$group = F::build('SponsorshipDashboardGroup', array( $id ) );
		$group->loadGroupParams();

		$aReports = F::build('SponsorshipDashboardReports');
		$aUsers = F::build('SponsorshipDashboardUsers');
		$oTmpl->set_vars(
			array(
			    'groupParams' => $group->getGroupParams(),
			    'reports' => $aReports->getData(),
			    'users' => $aUsers->getData(),
			    'path' => SpecialPage::getTitleFor('SponsorshipDashboard')->getInternalURL(),
			    'groupEditorPath' => SpecialPage::getTitleFor('SponsorshipDashboard')->getInternalURL().'/'.self::ADMIN.'/ViewGroups'
			)
		);

		$wgOut->addScript("<script type=\"{$wgJsMimeType}\" src=\"{$wgExtensionsPath}/wikia/SponsorshipDashboard/js/SponsorshipDashboardGroupEditor.js?{$wgStyleVersion}\" ></script>\n");
		$wgOut->addStyle( AssetsManager::getInstance()->getSassCommonURL( 'extensions/wikia/SponsorshipDashboard/css/SponsorshipDashboard.scss' ) );
		$wgOut->addStyle( AssetsManager::getInstance()->getSassCommonURL( 'extensions/wikia/SponsorshipDashboard/css/SponsorshipDashboardEditor.scss' ) );
		$wgOut->addHTML(
			$oTmpl->execute( 'admin/editGroup' )
		);
	}

	protected function HTMLEditUser( $id ) {

		global $wgExtensionsPath, $wgScriptPath, $wgStyleVersion, $wgJsMimeType;

		$wgOut = F::app()->getGlobal('wgOut');
		$wgRequest = F::app()->getGlobal('wgRequest');

		$oTmpl = new EasyTemplate( dirname( __FILE__ ) . "/templates/" );

		$this->HTMLAdminHeader( 'ViewUsers' );

		$oUser = F::build('SponsorshipDashboardUser', array( $id ));
		$oUser->loadUserParams();

		$aGroups = F::build('SponsorshipDashboardGroups');
		$aGroupUserData = $aGroups->getUserData( $id );

		$aUserReports = array();
		foreach ( $aGroupUserData as $group ) {
			foreach ( $group->reports as $report  ) {
				$aUserReports[ $report->id ] = $report;
			}
		}

		$oTmpl->set_vars(
			array(
			    'userParams' => $oUser->getUserParams(),
			    'groups' => $aGroupUserData,
			    'reports' => $aUserReports,
			    'path' => SpecialPage::getTitleFor('SponsorshipDashboard')->getInternalURL(),
			    'userEditorPath' => SpecialPage::getTitleFor('SponsorshipDashboard')->getInternalURL().'/'.self::ADMIN.'/ViewUsers',
			    'allowedTypes' => $oUser->allowedTypes
			)
		);

		$wgOut->addScript("<script type=\"{$wgJsMimeType}\" src=\"{$wgExtensionsPath}/wikia/SponsorshipDashboard/js/SponsorshipDashboardUserEditor.js?{$wgStyleVersion}\" ></script>\n");
		$wgOut->addStyle( AssetsManager::getInstance()->getSassCommonURL( 'extensions/wikia/SponsorshipDashboard/css/SponsorshipDashboard.scss' ) );
		$wgOut->addStyle( AssetsManager::getInstance()->getSassCommonURL( 'extensions/wikia/SponsorshipDashboard/css/SponsorshipDashboardEditor.scss' ) );
		$wgOut->addHTML(
			$oTmpl->execute( 'admin/editUser' )
		);
	}

	protected function HTMLViewReports() {

		global $wgTitle, $wgOut, $wgRequest, $wgExtensionsPath, $wgScriptPath, $wgStyleVersion, $wgJsMimeType;

		$wgOut = F::app()->getGlobal('wgOut');
		$wgRequest = F::app()->getGlobal('wgRequest');
		$wgTitle = F::app()->getGlobal('wgTitle');

		$oTmpl = new EasyTemplate( dirname( __FILE__ ) . "/templates/" );

		$wgOut->addScript("<script type=\"{$wgJsMimeType}\" src=\"{$wgExtensionsPath}/wikia/SponsorshipDashboard/js/SponsorshipDashboardList.js?{$wgStyleVersion}\" ></script>\n");
		$wgOut->addStyle( AssetsManager::getInstance()->getSassCommonURL( 'extensions/wikia/SponsorshipDashboard/css/SponsorshipDashboardList.scss' ) );
		$this->HTMLAdminHeader( 'ViewReports' );

		$aReports = F::build( 'SponsorshipDashboardReports' );
		$oTmpl->set_vars(
			array(
				"data" => $aReports->getData(),
				"path" => $wgTitle->getFullURL()
			)
		);

		$a = new OutputPage;
		$wgOut->addHTML(
			$oTmpl->execute( 'admin/viewReports' )
		);

		return false;
	}

	protected function HTMLViewGroups() {

		global $wgTitle, $wgOut, $wgRequest, $wgExtensionsPath, $wgScriptPath, $wgStyleVersion, $wgJsMimeType;

		$oTmpl = new EasyTemplate( dirname( __FILE__ ) . "/templates/" );

		if ( $wgRequest->getVal( 'action' ) == 'save' ) {
			$oGroup = F::build( 'SponsorshipDashboardGroup' );
			$oGroup->fillFromRequest();
			$oGroup->save();
		}

		$wgOut->addScript("<script type=\"{$wgJsMimeType}\" src=\"{$wgExtensionsPath}/wikia/SponsorshipDashboard/js/SponsorshipDashboardList.js?{$wgStyleVersion}\" ></script>\n");
		$wgOut->addStyle( AssetsManager::getInstance()->getSassCommonURL( 'extensions/wikia/SponsorshipDashboard/css/SponsorshipDashboardList.scss' ) );
		$this->HTMLAdminHeader( 'ViewGroups' );

		$aGroups = F::build( 'SponsorshipDashboardGroups' );
		$oTmpl->set_vars(
			array(
				"data" => $aGroups->getData(),
				"path" => $wgTitle->getFullURL()
			)
		);

		$wgOut->addHTML(
			$oTmpl->execute( 'admin/viewGroups' )
		);

		return false;
	}

	protected function HTMLViewUsers() {

		global $wgTitle, $wgOut, $wgRequest, $wgExtensionsPath, $wgScriptPath, $wgStyleVersion, $wgJsMimeType;

		$oTmpl = new EasyTemplate( dirname( __FILE__ ) . "/templates/" );
		$sMsg = '';

		if ( $wgRequest->getVal( 'action' ) == 'save' ) {
			$oUser = F::build('SponsorshipDashboardUser');
			$oUser->fillFromRequest();
			$bSuccess = $oUser->save();
			$sMsg = ( $bSuccess ) ? '' : wfMsg('sponsorship-dashboard-users-error', $oUser->name );
		}

		$a = new OutputPage();
		$wgOut->addScript("<script type=\"{$wgJsMimeType}\" src=\"{$wgExtensionsPath}/wikia/SponsorshipDashboard/js/SponsorshipDashboardList.js?{$wgStyleVersion}\" ></script>\n");
		$wgOut->addStyle( AssetsManager::getInstance()->getSassCommonURL( '/extensions/wikia/SponsorshipDashboard/css/SponsorshipDashboardList.scss' ) );
		$this->HTMLAdminHeader( 'ViewUsers' );

		$aUsers = F::build('SponsorshipDashboardUsers');
		$oTmpl->set_vars(
			array(
				"data" => $aUsers->getData(),
				"path" => $wgTitle->getFullURL(),
				"errorMsg" => $sMsg
			)
		);

		$wgOut->addHTML(
			$oTmpl->execute( 'admin/viewUsers' )
		);

		return false;
	}

	protected function HTMLReport() {

		global $wgTitle, $wgOut, $wgRequest, $wgExtensionsPath, $wgScriptPath, $wgStyleVersion, $wgJsMimeType;

		$oTmpl = new EasyTemplate( dirname( __FILE__ ) . "/templates/" );

		$wgOut->addStyle( AssetsManager::getInstance()->getSassCommonURL( 'extensions/wikia/SponsorshipDashboard/css/SponsorshipDashboard.scss' ) );
		$this->displayHeader();

		$chart = SponsorshipDashboardOutputChart::newFromReport( $this->currentReport, $this->currentGroup );
		$table = SponsorshipDashboardOutputTable::newFromReport( $this->currentReport );

		$wgOut->addHTML(
			$chart->getHTML()
		);
		$wgOut->addHTML(
			$table->getHTML()
		);

		return false;
	}

	protected function CSVReport() {

		$this->currentReport->loadReportParams();
		$dataFormatter = SponsorshipDashboardOutputCSV::newFromReport( $this->currentReport );
		echo $dataFormatter->getHTML();
		exit;
	}
}