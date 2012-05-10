<?php

$wgExtensionCredits['specialpage'][] = array(
	'name' => 'Theme designer',
	'author' => array( 'Christian Williams', 'Inez Korczyński', 'Maciej Brencz' ),
	'descriptionmsg' => 'themedesigner-desc',
);

$dir = dirname( __FILE__ );

// autoloads
$wgAutoloadClasses[ 'UploadBackgroundFromFile' ] = "{$dir}/UploadBackgroundFromFile.class.php";
$wgAutoloadClasses[ 'UploadFaviconFromFile' ] = "{$dir}/UploadFaviconFromFile.class.php";
$wgAutoloadClasses[ 'UploadWordmarkFromFile' ] = "{$dir}/UploadWordmarkFromFile.class.php";
$wgAutoloadClasses['ThemeDesignerController'] = "$dir/ThemeDesignerController.class.php";
$wgAutoloadClasses['SpecialThemeDesigner'] = "$dir/SpecialThemeDesigner.class.php";
$wgAutoloadClasses['SpecialThemeDesignerPreview'] = "$dir/SpecialThemeDesignerPreview.class.php";

// special pages
$wgSpecialPages['ThemeDesigner'] = 'SpecialThemeDesigner';
$wgSpecialPages['ThemeDesignerPreview'] = 'SpecialThemeDesignerPreview';
// @todo FIXME: add aliases file for localised special page names.

// i18n
$wgExtensionMessagesFiles['ThemeDesigner'] = "$dir/ThemeDesigner.i18n.php";

// Ability to access ThemeDesigner.
$wgAvailableRights[] = 'themedesigner';
$wgGroupPermissions['*']['themedesigner'] = false;
$wgGroupPermissions['sysop']['themedesigner'] = true;
$wgGroupPermissions['helper']['themedesigner'] = true;
$wgGroupPermissions['staff']['themedesigner'] = true;
