<?php

/**
 * @author Inez Korczyński <korczynski@gmail.com>
 *
 * @see https://internal.wikia-inc.com/wiki/AssetsManager
 */

if(!defined('MEDIAWIKI')) {
	exit(1);
}

$wgExtensionCredits['other'][] = array(
	'name' => 'AssetsManager',
	'author' => 'Inez Korczyński'
);

$wgAjaxExportList[] = 'AssetsManagerEntryPoint';
$wgHooks['MakeGlobalVariablesScript'][] = 'AssetsManager::onMakeGlobalVariablesScript';
$wgHooks['UserLoadFromSession'][] = 'AssetsManagerClearCookie';

function AssetsManagerClearCookie( $user, &$result ) {
	global $wgRequest;
	if ( $wgRequest->getVal('action') === 'ajax' && $wgRequest->getVal('rs') === 'AssetsManagerEntryPoint' ) {
		$result = new User();
	}
	return true;
}

function AssetsManagerEntryPoint() {
	global $wgRequest, $wgAutoloadClasses;

	$dir = dirname(__FILE__).'/';

	// Temporary logging
	// error_log("Temp log - #1 - AssetsManagerEntryPoint: " . $wgRequest->getFullRequestURL());

	$wgAutoloadClasses['AssetsManagerBaseBuilder'] = $dir.'builders/AssetsManagerBaseBuilder.class.php';
	$wgAutoloadClasses['AssetsManagerOneBuilder'] = $dir.'builders/AssetsManagerOneBuilder.class.php';
	$wgAutoloadClasses['AssetsManagerGroupBuilder'] = $dir.'builders/AssetsManagerGroupBuilder.class.php';
	$wgAutoloadClasses['AssetsManagerSassBuilder'] = $dir.'builders/AssetsManagerSassBuilder.class.php';
	$wgAutoloadClasses['AssetsManagerServer'] = $dir.'AssetsManagerServer.class.php';

	AssetsManagerServer::serve($wgRequest);

	exit();
}


