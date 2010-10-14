<?php

class SpecialWikiActivity extends SpecialPage {
	
	function __construct() {
		wfLoadExtensionMessages('MyHome');
		parent::__construct('WikiActivity', '' /* no restriction */, true /* listed */);
	}
	
	function execute($par) {
		wfProfileIn(__METHOD__);
		global $wgOut, $wgUser, $wgTitle;
		$this->setHeaders();
		
		// not available for skins different then monaco / answers
		$skinName = get_class($wgUser->getSkin());
		if (!in_array($skinName, array('SkinMonaco', 'SkinAnswers', 'SkinOasis'))) {
			$wgOut->addWikiMsg( 'myhome-switch-to-monaco' );
			wfProfileOut(__METHOD__);
			return;
		}
		
		if($par == 'watchlist') {
			// watchlist
			$feedSelected = 'watchlist';
			$feedProxy = new WatchlistFeedAPIProxy();
			$feedRenderer = new WatchlistFeedRenderer();
		} else {
			// activity
			$feedSelected = 'activity';
			$feedProxy = new ActivityFeedAPIProxy();
			$feedRenderer = new ActivityFeedRenderer();
		}
		$feedProvider = new DataFeedProvider($feedProxy);
		
		// WikiActivity.js is MyHome.js modified for Oasis
		global $wgJsMimeType, $wgExtensionsPath, $wgStyleVersion;
		$wgOut->addScript("<script type=\"{$wgJsMimeType}\" src=\"{$wgExtensionsPath}/wikia/MyHome/WikiActivity.js?{$wgStyleVersion}\"></script>\n");
		
		$data = $feedProvider->get(50);  // this breaks when set to 60...
		
		global $wgEnableAchievementsInActivityFeed, $wgEnableAchievementsExt, $wgExtensionsPath, $wgStyleVersion;
		if((!empty($wgEnableAchievementsInActivityFeed)) && (!empty($wgEnableAchievementsExt))){
			$wgOut->addExtensionStyle("{$wgExtensionsPath}/wikia/AchievementsII/css/achievements_sidebar.css?{$wgStyleVersion}");
		}
		
		// use message from MyHome as special page title
		//$wgOut->setPageTitle(wfMsg('myhome-activity-feed'));
		$wgOut->setPageTitle(wfMsg('oasis-activity-header'));
		
		$template = new EasyTemplate(dirname(__FILE__).'/templates');
		$template->set('data', $data['results']);
		$template->set('tagid', 'myhome-activityfeed'); // FIXME: remove
		
		global $wgBlankImgUrl;
		$showMore = isset($data['query-continue']);
		if ($showMore) {
			$template->set('query_continue', $data['query-continue']);
		}
		if (empty($data['results'])) {
			$template->set('emptyMessage', wfMsgExt("myhome-activity-feed-empty", array( 'parse' )));
		}
		
		$template->set_vars(array(
					'assets' => array(
						'blank' => $wgBlankImgUrl,
						),
					'showMore' => $showMore,
					'type' => 'activity'
					));  // FIXME: remove
		
		$wgOut->addStyle(wfGetSassUrl("extensions/wikia/MyHome/oasis.scss"));
		
		$wgOut->addHTML($template->render('activityfeed.oasis'));
		
		wfProfileOut(__METHOD__);
	}
	
}

