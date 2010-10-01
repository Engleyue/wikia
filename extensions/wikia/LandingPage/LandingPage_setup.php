<?php

$wgExtensionCredits['specialpage'][] = array(
	'name' => 'Landing Page',
	'description' => 'Landing Page',
	'author' => 'Maciej Brencz',
);

$dir = dirname(__FILE__) . '/';

// register special page
$wgAutoloadClasses['SpecialLandingPage'] = $dir.'SpecialLandingPage.class.php';
$wgSpecialPages['LandingPage'] = 'SpecialLandingPage';

// for the change
#$wgAutoloadClasses['SpecialLandingPageChange'] = $dir.'SpecialLandingPageChange.class.php';
#$wgSpecialPages['LandingPageChange'] = 'SpecialLandingPageChange';

// i18n
$wgExtensionMessagesFiles['LandingPage'] = $dir . 'LandingPage.i18n.php';
$wgExtensionMessagesFiles['LandingPageChange'] = $dir . 'LandingPage.i18n.php';


