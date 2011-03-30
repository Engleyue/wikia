<?php

$wgExtensionCredits['specialpage'][] = array(
	'name' => 'CreateNewWiki',
	'descriptionmsg' => 'createnewwiki-desc',
	'author' => array('Hyun Lim')
);

$dir = dirname(__FILE__).'/';

// class autoloads mappings
$wgAutoloadClasses['CreateWikiLocalJob'] = $dir."../AutoCreateWiki/CreateWikiLocalJob.php";
$wgAutoloadClasses['CreateWiki'] = $dir."../AutoCreateWiki/CreateWiki.php";
$wgAutoloadClasses['CreateNewWikiModule'] = $dir . 'CreateNewWikiModule.class.php';
$wgAutoloadClasses['SpecialCreateNewWiki'] = $dir . 'SpecialCreateNewWiki.class.php';

// special page mapping
$wgSpecialPages['CreateNewWiki'] = 'SpecialCreateNewWiki';

// i18n mapping
$wgExtensionMessagesFiles['CreateNewWiki'] = $dir . 'CreateNewWiki.i18n.php';

// permissions
$wgAvailableRights[] = 'createnewwiki';
$wgGroupPermissions['*']['createnewwiki'] = true;
$wgGroupPermissions['staff']['createnewwiki'] = true;

$wgExtensionFunctions[] = 'CreateNewWikiModule::setupCreateNewWiki';