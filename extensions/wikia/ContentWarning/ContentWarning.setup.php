<?php

/**
 * ContentWarning
 * @author Kyle Florence, Saipetch Kongkatong, Tomasz Odrobny
 */

$wgExtensionCredits['other'][] = array(
	'name' => 'ContentWarning',
	'author' => array( 'Kyle Florence', 'Saipetch Kongkatong', 'Tomasz Odrobny' )
);

$dir = dirname(__FILE__) . '/';
$app = F::app();

//classes
$app->registerClass('ContentWarningController', $dir.'ContentWarningController.class.php');
$app->registerClass('ContentWarningHooks', $dir . 'ContentWarningHooks.class.php');


// i18n mapping
$app->registerExtensionMessageFile('ContentWarning', $dir.'ContentWarning.i18n.php');

// special pages
$app->registerSpecialPage('ContentWarning', 'ContentWarningSpecialController');

// Hooks
$app->registerHook('MakeGlobalVariablesScript', 'ContentWarningHooks', 'onMakeGlobalVariablesScript');