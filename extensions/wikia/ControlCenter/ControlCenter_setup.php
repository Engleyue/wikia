<?php
/**
 * ControlCenter
 *
 * @author Hyun Lim
 *
 */

$dir = dirname(__FILE__) . '/';
$app = F::app();
//classes
$app->registerClass('ControlCenterSpecialPageController', $dir . 'ControlCenterSpecialPageController.class.php');
$app->registerClass('ControlCenterModule', $dir . 'ControlCenterModule.class.php');
$app->registerClass('ControlCenterLogic', $dir . 'ControlCenterLogic.class.php');

// i18n mapping
$wgExtensionMessagesFiles['ControlCenter'] = $dir . 'ControlCenter.i18n.php';

// special pages
$app->registerSpecialPage('ControlCenter', 'ControlCenterSpecialPageController');