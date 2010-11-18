<?php
/**
 * User Profile Page Extension - provides a user page that is fun and easy to update
 *
 * @author Adrian 'ADi' Wieczorek <adi(at)wikia-inc.com>
 * @author Garth Webb <garth(at)wikia-inc.com>
 */

if(!defined('MEDIAWIKI')) {
	die();
}

$wgExtensionCredits['specialpage'][] = array(
	'name' => 'User Profile Page',
	'author' => 'Adrian \'ADi\' Wieczorek, Garth Webb, Federico "Lox" Lucignano',
	'url' => 'http://www.wikia.com' ,
	'description' => 'provides a user page that is fun and easy to update',
	'descriptionmsg' => 'userprofilepage-desc'
);

/**
 * setup functions
 */
$wgExtensionFunctions[] = 'wfUserProfilePageInit';

function wfUserProfilePageInit() {
	global $wgHooks, $wgExtensionMessagesFiles, $wgAutoloadClasses, $wgAjaxExportList;

	$dir = dirname(__FILE__) . '/';

	/**
	 * hooks
	 */
	$wgHooks['SkinTemplateOutputPageBeforeExec'][] = 'UserProfilePageHelper::onSkinTemplateOutputPageBeforeExec';
	$wgHooks[ 'AlternateEdit' ][] = 'UserProfilePageHelper::onAlternateEdit';

	/**
	 * messages file
	 */
	$wgExtensionMessagesFiles['UserProfilePage'] = $dir . 'UserProfilePage.i18n.php';
	$wgExtensionMessagesFiles['MyHome'] = dirname($dir) . '/MyHome/MyHome.i18n.php';

	// we have to load extension messages here in order to Special:CreateFromTemplate work properly
	wfLoadExtensionMessages('UserProfilePage');

	/**
	 * classes
	 */
	$wgAutoloadClasses['UserProfilePage'] = $dir . 'UserProfilePage.class.php';
	$wgAutoloadClasses['UserProfilePageHelper'] = $dir . 'UserProfilePageHelper.class.php';
	$wgAutoloadClasses['RecentChangeDetail'] = $dir . 'RecentChangeDetail.class.php';
	$wgAutoloadClasses['UserProfilePageModule'] = $dir . 'UserProfilePageModule.class.php';
	$wgAutoloadClasses['UserProfileRailModule'] = $dir . 'UserProfileRailModule.class.php';

	/**
	 * ajax
	 */
	$wgAjaxExportList[] = 'UserProfilePageHelper::doAction';
}
