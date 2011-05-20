<?php

class WikiaLabsSpecial extends SpecialPage {

	//private $mpa = null;
	protected $app = null;

	function __construct() {
		$this->app = WF::build( 'App' );
		$this->out = $this->app->getGlobal('wgOut');
		$this->user = $this->app->getGlobal('wgUser');
		parent::__construct( 'WikiaLabs' );
	}

	function execute( $par ) {
		$wgExtensionsPath = $this->app->getGlobal('wgExtensionsPath');
		
		if ( $this->user->isAnon() ) {
			$this->displayRestrictionError($this->user);
			return ;
		}
		
		$feedbackAdded = intval($this->app->getGlobal('wgRequest')->getText('feedbackAdded'));
		if( 1 === $feedbackAdded ) {
			NotificationsModule::addConfirmation($this->app->runFunction('wfMsg', 'wikialabs-feedback-validator-notification-ok'));
		}
		
		//wikialabs-list-project-warning-box-no-admin
		$oTmpl = WF::build( 'EasyTemplate', array( dirname( __FILE__ ) . "/templates/" ) );
		$projects = WF::build( 'WikiaLabsProject')->getList(array("graduated" => false, "active" => true  )  );
		
		$userId = $this->user->getId();

		$cityId = $this->app->getGlobal( 'wgCityId' );

		/*sync with WF */
		foreach($projects as $value) {
			$val = $this->app->runFunction( 'WikiFactory::getVarValueByName',  $value->getExtension(), $cityId);
			if( $value->isEnabled($cityId) != $val  ) {
				if($val) {
					$value->setEnabled($cityId);
				} else {
					$value->setDisabled($cityId);
				}
				$value->update();
			}
		}

		$this->isAdmin = false;

		if( $this->user->isAllowed( 'wikialabsuser' ) ) {
			$this->isAdmin = true;
		}

		$oTmpl->set_vars( array(
			'projects' => $projects,
			'cityId' => $this->app->getGlobal( 'wgCityId' ),
			'userId' => $userId,
			'isAdmin' => $this->isAdmin,
			'contLang' => $this->app->getGlobal( 'wgContLang' ),
			'lang' => $this->app->getGlobal( 'wgLang' ),
			'wgExtensionsPath' => $wgExtensionsPath,
		) );

		$this->out->addStyle(AssetsManager::getInstance()->getSassCommonURL('extensions/wikia/WikiaLabs/css/wikialabs.scss'));
		$this->out->addHTML( $oTmpl->render("wikialabs-main") );
		$this->out->addScriptFile( $wgExtensionsPath."/wikia/WikiaLabs/js/main.js" );
		$this->out->setPageTitle( $this->app->runFunction('wfMsg', 'wikialabs-list-project-title') );
		return ;
	}
}
