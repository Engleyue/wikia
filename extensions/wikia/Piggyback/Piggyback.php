<?php
# Alert the user that this is not a valid entry point to MediaWiki if they try to access the special pages file directly.
if (!defined('MEDIAWIKI')) {
	echo <<<EOT
To install my extension, put the following line in LocalSettings.php:
require_once( "\$IP/extensions/MyExtension/MyExtension.php" );
EOT;
	exit( 1 );
}



$wgExtensionCredits['specialpage'][] = array(
	'name' => 'Piggyback',
	'author' => 'Tomasz Odrobny',
	'url' => '',
	'description' => 'Logon other user account',
	'descriptionmsg' => 'myextension-desc',
	'version' => '0.0.0',
);

$dir = dirname(__FILE__) . '/';


$wgAutoloadClasses['Piggyback'] = $dir . 'Piggyback_body.php'; # Tell MediaWiki to load the extension body.
$wgAutoloadClasses['PBLoginForm']  = $dir . 'Piggyback_body.php'; # Tell MediaWiki to load the extension body.
$wgAutoloadClasses['PiggybackTemplate'] = $dir . 'Piggyback_form.php'; # Tell MediaWiki to load the extension body.
$wgExtensionMessagesFiles['Piggyback'] = $dir . 'Piggyback.i18n.php';
$wgExtensionAliasesFiles['Piggyback'] = $dir . 'Piggyback.alias.php';
$wgSpecialPages['Piggyback'] = 'Piggyback'; # Let MediaWiki know about your new special page.
$wgHooks['UserLogoutComplete'][] = 'PiggybackGoToParent';

/*
 * event for logout (back to parent user)
 */

function PiggybackGoToParent($wgUser,$injected_html,$oldName) {
	global $wgRequest;
	if( PBLoginForm::isPiggyback() ) {
		$loginForm = new PBLoginForm($wgRequest);
		$loginForm->goToParent( $oldName );
	}
	return true;
}