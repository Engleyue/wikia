<?php

/**
 * @author Inez Korczyński <korczynski@gmail.com>
 */

if(!defined('MEDIAWIKI')) {
	exit(1);
}

$wgExtensionCredits['other'][] = array(
	'name' => 'AssetsManager',
	'author' => 'Inez Korczyński'
);

$wgAjaxExportList[] = 'AssetsManagerEntryPoint';

function AssetsManagerEntryPoint() {
	global $wgRequest, $wgAutoloadClasses;

	$dir = dirname(__FILE__).'/';

	// Temporary log
	global $wgRequest;
	error_log("Temp log - #1 - AssetsManagerEntryPoint: " . $wgRequest->getFullRequestURL());

	$wgAutoloadClasses['AssetsManagerBaseBuilder'] = $dir.'builders/AssetsManagerBaseBuilder.class.php';
	$wgAutoloadClasses['AssetsManagerOneBuilder'] = $dir.'builders/AssetsManagerOneBuilder.class.php';
	$wgAutoloadClasses['AssetsManagerGroupBuilder'] = $dir.'builders/AssetsManagerGroupBuilder.class.php';
	$wgAutoloadClasses['AssetsManagerSassBuilder'] = $dir.'builders/AssetsManagerSassBuilder.class.php';	
	$wgAutoloadClasses['AssetsManagerServer'] = $dir.'AssetsManagerServer.class.php';
	
	AssetsManagerServer::serve($wgRequest);
	
	exit();
}


