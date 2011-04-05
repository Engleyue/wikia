<?php

class SpecialCreateNewWiki extends UnlistedSpecialPage {

	public function __construct() {
		wfLoadExtensionMessages('CreateNewWiki');
		parent::__construct('CreateNewWiki', 'createnewwiki');
	}
	
	public function execute() {
		global $wgUser, $wgOut, $wgExtensionsPath;
		wfProfileIn( __METHOD__ );
		
		if (!$wgUser->isAllowed('createnewwiki')) {
			$this->displayRestrictionError();
			wfProfileOut( __METHOD__ );
			return;
		}
		
		wfLoadExtensionMessages('CreateNewWiki');
		
		$wgOut->setPageTitle(wfMsg('cnw-title'));
		$wgOut->addHtml(wfRenderModule('CreateNewWiki'));
		$wgOut->addStyle(AssetsManager::getInstance()->getSassCommonURL('extensions/wikia/CreateNewWiki/css/CreateNewWiki.scss'));
		$wgOut->addScript('<script src="'.$wgExtensionsPath.'/wikia/ThemeDesigner/js/ThemeDesigner.js"></script>');
		$wgOut->addScript('<script src="'.$wgExtensionsPath.'/wikia/AjaxLogin/AjaxLogin.js"></script>');
		$wgOut->addScript('<script src="'.$wgExtensionsPath.'/wikia/CreateNewWiki/js/CreateNewWiki.js"></script>');
		
		wfProfileOut( __METHOD__ );
	}

}