<?php
$config = array();

// Rich Text Editor JavaScript (before reskin)
$config['oldrte'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'#function_AssetsConfig::getRTEAssets'
	)
);
// Reskined rich text editor
$config['rte'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'#function_AssetsConfig::getRTEAssetsEPL'
	)
);
// Generic edit page JavaScript
$config['epl'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'#function_AssetsConfig::getEPLAssets',
	)
);
// Generic edit page JavaScript + reskined rich text editor
$config['eplrte'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'#group_epl',
		'#group_rte'
	)
);

// Site specific CSS
$config['site_anon_css'] = array(
	'type' => AssetsManager::TYPE_CSS,
	'assets' => array(
		'#function_AssetsConfig::getSiteCSS'
	)
);

$config['site_user_css'] = array(
	'type' => AssetsManager::TYPE_CSS,
	'assets' => array(
		'#group_site_anon_css',
	)
);

// jQuery
$config['oasis_jquery'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//skins/common/jquery/jquery-1.5.2.js',
		'//skins/common/jquery/jquery.json-1.3.js',
		'//skins/common/jquery/jquery.getcss.js',
		'//skins/common/jquery/jquery.cookies.2.1.0.js',
		'//skins/common/jquery/jquery.timeago.js',
		'//skins/common/jquery/jquery.store.js',
		'//skins/common/jquery/jquery.wikia.js',
		'//skins/oasis/js/tables.js',
		'//skins/oasis/js/common.js'
	)
);

// Oasis shared JS
$config['oasis_shared_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'#group_oasis_jquery',
		'//skins/common/wikibits.js',
		'//skins/common/mwsuggest.js',
		'//skins/oasis/js/tracker.js',
		'//skins/common/jquery/jquery.wikia.modal.js',
		'//skins/common/jquery/jquery.wikia.tracker.js',
		'//skins/oasis/js/hoverMenu.js',
		'//skins/oasis/js/PageHeader.js',
		'//skins/oasis/js/Search.js',
		'//skins/oasis/js/WikiaFooter.js',
		'//skins/oasis/js/buttons.js',
		'//skins/oasis/js/WikiHeader.js',
		'//skins/oasis/js/LatestPhotos.js',
		'//skins/oasis/js/Interlang.js',
		'//extensions/wikia/ShareFeature/js/ShareFeature.js',
		'//extensions/wikia/ArticleComments/js/ArticleComments.js',
		'//extensions/wikia/RelatedPages/js/RelatedPages.js',
		'//skins/oasis/js/WikiaNotifications.js',
		'//skins/oasis/js/Spotlights.js',
		'//skins/common/ajax.js',
		'//extensions/wikia/CreatePage/js/CreatePage.js',
		'//extensions/wikia/ImageLightbox/ImageLightbox.js',
		'//extensions/wikia/AjaxLogin/AjaxLoginBindings.js',
		'//extensions/FBConnect/fbconnect.js',
		'//extensions/wikia/AdEngine/AdConfig.js',
		'//extensions/wikia/AdEngine/AdEngine.js',
		'//extensions/wikia/AdEngine/AdProviderOpenX.js',
		'//extensions/wikia/AdEngine/LazyLoadAds.js',
		'//extensions/wikia/AdEngine/ghost/gw-2010.10.11/lib/gw.js',
		'//extensions/wikia/Geo/geo.js',
		'//extensions/wikia/QuantcastSegments/qcs.js',
		'//extensions/wikia/ApertureAudience/Aperture.js',
		'//extensions/wikia/AdEngine/liftium/Liftium.js',
		'//extensions/wikia/AdEngine/liftium/Wikia.js',
		'//extensions/wikia/AdEngine/liftium/AdsInContent.js',
		'//extensions/wikia/AdEngine/AdDriver.js',
		'//extensions/wikia/AdSS/adss.js',
		'//extensions/wikia/PageLayoutBuilder/js/view.js', // TODO: load it on demand
		'//extensions/wikia/JSMessages/js/JSMessages.js', // TODO: maybe move to jquery.wikia.js
		'//skins/oasis/js/GlobalModal.js',
		'//skins/oasis/js/FirefoxFindFix.js',
		'//extensions/wikia/GlobalNotification/GlobalNotification.js'
	)
);

// Oasis anon JS
$config['oasis_anon_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//skins/oasis/js/LatestActivity.js',
		'//extensions/wikia/Interstitial/Exitstitial.js',
		'#function_AssetsConfig::getSiteJS'
	)
);

// Oasis user JS
$config['oasis_user_js'] = array(
	'type' => AssetsManager::TYPE_JS,
	'assets' => array(
		'//skins/common/ajaxwatch.js',
		'//extensions/wikia/ArticleAjaxLoading/ArticleAjaxLoading.js',
		'#function_AssetsConfig::getSiteJS'
	)
);
