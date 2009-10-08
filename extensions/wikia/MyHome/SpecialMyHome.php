<?php

class SpecialMyHome extends SpecialPage {

	function __construct() {
		parent::__construct('MyHome', '' /* no restriction */, true /* listed */);
		wfLoadExtensionMessages('MyHome');
	}

	function execute($par) {
		global $wgOut, $wgUser;

		$this->setHeaders();

		// not available for skins different then monaco
		if(get_class($wgUser->getSkin()) != 'SkinMonaco') {
			$wgOut->addWikiText(wfMsg('myhome-switch-to-monaco'));
			return;
		}

		// not available for anons
		if($wgUser->isAnon()) {
			$wgOut->addHTML('<div id="myhome-log-in">');
			$wgOut->addWikiText(wfMsg('myhome-log-in'));
			$wgOut->addHTML('</div>');

			// RT #23970
			$wgOut->addInlineScript(<<<JS
$(function() {
	$('#myhome-log-in').find('a').click(function(ev) {
		ev.preventDefault();
		openLogin(ev);
	});
});
JS
);
			return;
		}

		// load dependencies (CSS and JS)
		global $wgExtensionsPath, $wgStyleVersion, $wgJsMimeType;
		$wgOut->addExtensionStyle("{$wgExtensionsPath}/wikia/MyHome/MyHome.css?{$wgStyleVersion}");
		$wgOut->addScript("<script type=\"{$wgJsMimeType}\" src=\"{$wgExtensionsPath}/wikia/MyHome/MyHome.js?{$wgStyleVersion}\"></script>\n");

		######
		### Prepare HTML for feed (watchlist/activity) section
		######

		// choose default view
		if ($par == '') {
			$par = MyHome::getDefaultView();
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
		// render choosen feed
		$feedHTML = $feedRenderer->render($feedProvider->get(60));

		######
		### Prepare HTML for user contributions section
		######

		$contribsProvider = new UserContributionsProvider();
		$contribsData = $contribsProvider->get(5);
		$contribsRenderer = new UserContributionsRenderer();
		$contribsHtml = $contribsRenderer->render($contribsData);

		######
		### Prepare HTML for hot spots
		######

		$hotSpotsProvider = new HotSpotsProvider();
		$hotSpotsData = $hotSpotsProvider->get();
		$hotSpotsRenderer = new HotSpotsRenderer();
		$hotSpotsHtml = $hotSpotsRenderer->render($hotSpotsData);

		######
		### Prepare HTML for community corner
		######

		$isAdmin = $wgUser->isAllowed('editinterface');

		$communityCornerTemplate = new EasyTemplate(dirname(__FILE__).'/templates');
		$communityCornerTemplate->set_vars(array('isAdmin' => $isAdmin));
		$communityCornerHTML = $communityCornerTemplate->execute('communityCorner');

		######
		### Show HTML
		######

		$template = new EasyTemplate(dirname(__FILE__).'/templates');
		$template->set_vars(array(
			'feedSelected' => $feedSelected,
			'myhomeUrl' => Skin::makeSpecialUrl('MyHome'),

			'feedHTML' => $feedHTML,
			'contribsHTML' => $contribsHtml,
			'hotSpotsHTML' => $hotSpotsHtml,
			'communityCornerHTML' => $communityCornerHTML,
		));

		$wgOut->addHTML($template->execute('myhome'));
	}
}
