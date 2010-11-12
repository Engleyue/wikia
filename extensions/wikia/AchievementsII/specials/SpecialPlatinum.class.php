<?php

class SpecialPlatinum extends SpecialPage {

	function __construct() {
		wfLoadExtensionMessages('AchievementsII');
		parent::__construct('Platinum', 'platinum', true /* listed */);
	}

	function execute($par) {
		wfProfileIn(__METHOD__);

		global $wgOut, $wgExtensionsPath, $wgStyleVersion, $wgJsMimeType, $wgUser;

		if(!$this->userCanExecute($wgUser)) {
			$this->displayRestrictionError();
			return;
		}

		// set basic headers
		$this->setHeaders();

		// include resources (css and js)
		$wgOut->addExtensionStyle("{$wgExtensionsPath}/wikia/AchievementsII/css/platinum.css?{$wgStyleVersion}\n");
		$wgOut->addStyle(wfGetSassUrl('extensions/wikia/AchievementsII/css/oasis.scss'));
		$wgOut->addScript("<script type=\"{$wgJsMimeType}\" src=\"{$wgExtensionsPath}/wikia/AchievementsII/js/jquery.aim.js?{$wgStyleVersion}\"></script>\n");
		$wgOut->addScript("<script type=\"{$wgJsMimeType}\" src=\"{$wgExtensionsPath}/wikia/AchievementsII/js/platinum.js?{$wgStyleVersion}\"></script>\n");

		// call service to get needed data
		$badges = AchPlatinumService::getList();

		// pass data to template
		$template = new EasyTemplate(dirname(__FILE__).'/templates');
		$template->set_vars(array('badges' => $badges));

		// render template
		$wgOut->addHTML($template->render('SpecialPlatinum'));

		wfProfileOut(__METHOD__);
	}
}
