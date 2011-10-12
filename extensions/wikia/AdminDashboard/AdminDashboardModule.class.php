<?php

class AdminDashboardModule extends Module {
	
	var $wordmarkText;
	var $wordmarkType;
	var $wordmarkSize;
	var $tab;
	var $adminDashboardUrl;
	var $adminDashboardUrlGeneral;
	var $adminDashboardUrlAdvanced;
	
	// Render the Admin Dashboard chrome
	public function executeChrome () {
		global $wgRequest, $wgTitle;
		
		$adminDashboardTitle = Title::newFromText('AdminDashboard', NS_SPECIAL);
		$this->isAdminDashboard = $wgTitle->getText() == $adminDashboardTitle->getText();
		
		$this->tab = $wgRequest->getVal("tab", "");
		if(empty($this->tab) && $this->isAdminDashboard) {
			$this->tab = 'general';
		} else if(AdminDashboardLogic::isGeneralApp(SpecialPage::resolveAlias($wgTitle->getDBKey()))) {
			$this->tab = 'general';
		} else if(empty($this->tab)) {
			$this->tab = 'advanced';
		}

		$this->wg->Out->addStyle(AssetsManager::getInstance()->getSassCommonURL('extensions/wikia/AdminDashboard/css/AdminDashboard.scss'));
		
		$this->wg->Out->addScriptFile($this->wg->ExtensionsPath . '/wikia/AdminDashboard/js/AdminDashboard.js');
		
		$this->adminDashboardUrl = Title::newFromText('AdminDashboard', NS_SPECIAL)->getFullURL("tab=$this->tab");
		$this->adminDashboardUrlGeneral = Title::newFromText('AdminDashboard', NS_SPECIAL)->getFullURL("tab=general");
		$this->adminDashboardUrlAdvanced = Title::newFromText('AdminDashboard', NS_SPECIAL)->getFullURL("tab=advanced");
		
	}
	
}
