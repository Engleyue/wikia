<?php
/**
 * WikiaHomePage
 *
 * @author Andrzej 'nAndy' Łukaszewski
 * @author Hyun Lim
 * @author Marcin Maciejewski
 * @author Saipetch Kongkatong
 * @author Sebastian Marzjan
 *
 */

$dir = dirname(__FILE__) . '/';
$app = F::app();

$wgExtensionCredits['other'][] = array(
		'name'			=> 'WikiaHomePage',
		'author'		=> 'Andrzej "nAndy" Łukaszewski, Hyun Lim, Marcin Maciejewski, Saipetch Kongkatong, Sebastian Marzjan',
		'description'	=> 'WikiaHomePage',
		'version'		=> 1.0
);

// helper hierarchy
// row assigners

$app->registerClass('WikiImageRowAssigner',$dir.'classes/WikiImageRowAssigner.class.php');
$app->registerClass('WikiImageRowHelper',$dir.'classes/WikiImageRowHelper.class.php');
$app->registerClass('WikiImageNameRowHelper',$dir.'classes/WikiImageNameRowHelper.class.php');
$app->registerClass('WikiImageReviewStatusRowHelper',$dir.'classes/WikiImageReviewStatusRowHelper.class.php');

// getdata helpers
$app->registerClass('WikiGetDataHelper',$dir.'classes/WikiGetDataHelper.class.php');
$app->registerClass('WikiGetDataForVisualizationHelper',$dir.'classes/WikiGetDataForVisualizationHelper.class.php');
$app->registerClass('WikiGetDataForPromoteHelper',$dir.'classes/WikiGetDataForPromoteHelper.class.php');


//classes
$app->registerClass('WikiaHomePageController', $dir.'WikiaHomePageController.class.php');
$app->registerClass('WikiaHomePageSpecialController', $dir.'WikiaHomePageSpecialController.class.php');
$app->registerClass('WikiaHomePageHelper', $dir.'WikiaHomePageHelper.class.php');
$app->registerClass('CityVisualization', $dir.'CityVisualization.class.php');





//special page
$app->registerSpecialPage('WikiaHomePage', 'WikiaHomePageSpecialController');

//i18n mapping
$app->registerExtensionMessageFile('WikiaHomePage', $dir.'WikiaHomePage.i18n.php');
F::build('JSMessages')->registerPackage('WikiaHomePage', array('wikia-home-page-*'));

$app->registerHook('GetHTMLAfterBody', 'WikiaHomePageController', 'onGetHTMLAfterBody');
$app->registerHook('OutputPageBeforeHTML', 'WikiaHomePageController', 'onOutputPageBeforeHTML');
$app->registerHook('WikiaMobileAssetsPackages', 'WikiaHomePageController', 'onWikiaMobileAssetsPackages');
$app->registerHook('ArticleCommentCheck', 'WikiaHomePageController', 'onArticleCommentCheck');
$app->registerHook('AfterGlobalHeader', 'WikiaHomePageController', 'onAfterGlobalHeader');

//add wikia staff tool rights to staff users
$wgGroupPermissions['*']['wikiahomepagestafftool'] = false;
$wgGroupPermissions['staff']['wikiahomepagestafftool'] = true;
$wgGroupPermissions['vstf']['wikiahomepagestafftool'] = false;
$wgGroupPermissions['helper']['wikiahomepagestafftool'] = false;
$wgGroupPermissions['sysop']['wikiahomepagestafftool'] = false;
