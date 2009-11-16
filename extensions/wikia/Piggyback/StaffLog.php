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
	'name' => 'StaffLog',
	'author' => 'Tomasz Odrobny',
	'url' => '',
	'description' => 'Central logging for wikia staff ',
	'descriptionmsg' => 'myextension-desc',
	'version' => '0.0.0',
);

$dir = dirname(__FILE__) . '/';

$wgAutoloadClasses['StaffLog'] = $dir . 'StaffLog_body.php'; # Tell MediaWiki to load the extension body.
$wgAutoloadClasses['StaffLogger'] = $dir . 'StaffLog.events.php';
$wgExtensionMessagesFiles['StaffLog'] = $dir . 'StaffLog.i18n.php';
$wgExtensionAliasesFiles['StaffLog'] = $dir . 'StaffLog.alias.php';
$wgSpecialPages['StaffLog'] = 'StaffLog'; # Let MediaWiki know about your new special page.

$wgLogRestrictions['StaffLog'] = 'StaffLog';

$wgStaffLogType = array(1 => "Block");
$wgSpecialPageGroups['stafflog'] = 'changes';

require_once $dir."StaffLog.events.php";