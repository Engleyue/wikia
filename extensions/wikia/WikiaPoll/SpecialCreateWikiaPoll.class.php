<?php

class SpecialCreateWikiaPoll extends SpecialPage {

	function SpecialCreateWikiaPoll() {
		SpecialPage::SpecialPage("CreatePoll", "", false);
	}

	public function execute ($subpage) {
		global $wgOut, $wgUser, $wgBlankImgUrl, $wgJsMimeType, $wgExtensionsPath, $wgStylePath, $wgStyleVersion;

		// Boilerplate special page permissions
		if ($wgUser->isBlocked()) {
			$wgOut->blockedPage();
			return;
		}
		if (wfReadOnly() && !wfAutomaticReadOnly()) {
			$wgOut->readOnlyPage();
			return;
		}
		if (!$wgUser->isAllowed('createpage') || !$wgUser->isAllowed('edit')) {
			$this->displayRestrictionError();
			return;
		}

		$wgOut->addScript('<script src="'.$wgStylePath.'/common/jquery/jquery-ui-1.8.14.custom.js"></script>');
		$wgOut->addScript('<script src="'.$wgExtensionsPath.'/wikia/WikiaPoll/js/CreateWikiaPoll.js"></script>');

		$wgOut->addStyle(AssetsManager::getInstance()->getSassCommonURL('/extensions/wikia/WikiaPoll/css/CreateWikiaPoll.scss'));

		if( $subpage != '' ) {
			// We came here from the edit link, go into edit mode
			$wgOut->addHtml(wfRenderModule('WikiaPoll', 'SpecialPageEdit', array('title' => $subpage)));
		} else {
			$wgOut->addHtml(wfRenderModule('WikiaPoll', 'SpecialPage'));
		}
	}
}
?>
