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
	'author' => 'Adrian \'ADi\' Wieczorek, Garth Webb',
	'url' => 'http://www.wikia.com' ,
	'description' => 'provides a user page that is fun and easy to update',
	'descriptionmsg' => 'userprofilepage-desc'
);

/**
 * setup functions
 */
$wgExtensionFunctions[] = 'wfUserProfilePageInit';

function wfUserProfilePageInit() {
	global $wgHooks, $wgExtensionMessagesFiles, $wgAutoloadClasses;

	$dir = dirname(__FILE__) . '/';

	/**
	 * hooks
	 */
	$wgHooks['SkinTemplateOutputPageBeforeExec'][] = 'UserProfilePage::outputPageHook';
	//$wgHooks['RecentChange_save'][] = 'RecentChangeDetail::register';
	//$wgHooks['LinksUpdateComplete'][] = 'RecentChangeDetail::registerMediaInsertEvent';



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
	$wgAutoloadClasses['RecentChangeDetail'] = $dir . 'RecentChangeDetail.class.php';

	// extension css - not an ideal solution putting it here, just for now..
	global $wgOut, $wgExtensionsPath, $wgStyleVersion;
	$wgOut->addScript( "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$wgExtensionsPath}/wikia/UserProfilePage/css/UserProfilePage.css?{$wgStyleVersion}\" />" );
}
