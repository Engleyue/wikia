<?php

$wgExtensionCredits['specialpage'][] = array(
	'name' => 'My Home',
	'descriptionmsg' => 'myhome-desc',
	'author' => array('Inez Korczyński', 'Maciej Brencz', '[http://www.wikia.com/wiki/User:Marooned Maciej Błaszkowski (Marooned)]')
);

$dir = dirname(__FILE__) . '/';

// Special:MyHome
$wgAutoloadClasses['SpecialMyHome'] = $dir.'SpecialMyHome.class.php';
$wgSpecialPages['MyHome'] = 'SpecialMyHome';
//$wgSpecialPageGroups['MyHome'] = 'users';
$wgExtensionAliasesFiles['MyHome'] = $dir . 'SpecialMyHome.alias.php';

// Special:ActivityFeed
$wgAutoloadClasses['SpecialActivityFeed'] = $dir.'SpecialActivityFeed.class.php';
$wgSpecialPages['ActivityFeed'] = 'SpecialActivityFeed';
//$wgSpecialPageGroups['ActivityFeed'] = 'changes';

// Special:WikiActivity
$wgAutoloadClasses['SpecialWikiActivity'] = $dir.'SpecialWikiActivity.class.php';
$wgSpecialPages['WikiActivity'] = 'SpecialWikiActivity';
$wgSpecialPageGroups['WikiActivity'] = 'changes';

// hooks
$wgHooks['CustomUserData'][] = 'MyHome::addToUserMenu';
$wgHooks['InitialQueriesMainPage'][] = 'MyHome::getInitialMainPage';
$wgHooks['GetPreferences'][] = 'MyHome::onGetPreferences';
$wgHooks['AddNewAccount2'][] = 'MyHome::addNewAccount';
