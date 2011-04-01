<?php

/**
 * ScavengerHunt
 *
 * A ScavengerHunt extension for MediaWiki
 * Alows to create a scavenger hunt game on a wiki
 *
 * @author Maciej Błaszkowski (Marooned) <marooned at wikia-inc.com>
 * @date 2011-01-31
 * @copyright Copyright (C) 2010 Maciej Błaszkowski, Wikia Inc.
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @package MediaWiki
 *
 * To activate this functionality, place this file in your extensions/
 * subdirectory, and add the following line to LocalSettings.php:
 *     include("$IP/extensions/wikia/ScavengerHunt/ScavengerHunt_setup.php");
 */

$wgExtensionCredits['special'][] = array(
	'name' => 'Scavenger hunt',
	'version' => '1.0',
	'author' => array(
		'[http://www.wikia.com/wiki/User:Marooned Maciej Błaszkowski (Marooned)]',
		'Władysław Bodzek' ),
	'description-msg' => 'scavengerhunt-desc'
);

$wgExtensionFunctions[] = 'ScavengerHuntSetup';

$dir = dirname(__FILE__);

// autoloaded classes
$wgAutoloadClasses['ScavengerHunt'] = "$dir/ScavengerHunt.class.php";
$wgAutoloadClasses['ScavengerHuntAjax'] = "$dir/ScavengerHuntAjax.class.php";
$wgAutoloadClasses['SpecialScavengerHunt'] = "$dir/SpecialScavengerHunt.php";

$wgAutoloadClasses['ScavengerHuntGame'] = "$dir/data/ScavengerHuntGame.class.php";
$wgAutoloadClasses['ScavengerHuntGames'] = "$dir/data/ScavengerHuntGames.class.php";
$wgAutoloadClasses['ScavengerHuntGameArticle'] = "$dir/data/ScavengerHuntGameArticle.class.php";
$wgAutoloadClasses['ScavengerHuntEntry'] = "$dir/data/ScavengerHuntEntry.class.php";
$wgAutoloadClasses['ScavengerHuntEntries'] = "$dir/data/ScavengerHuntEntries.class.php";

// i18n
$wgExtensionMessagesFiles['ScavengerHunt'] = "$dir/ScavengerHunt.i18n.php";

// special page
$wgSpecialPages['ScavengerHunt'] = 'SpecialScavengerHunt';

function ScavengerHuntSetup() {

	// WikiaApp
	$app = WF::build('App');

	// hooks
	$app->registerHook('MakeGlobalVariablesScript', 'ScavengerHunt', 'onMakeGlobalVariablesScript' );
	$app->registerHook('BeforePageDisplay', 'ScavengerHunt', 'onBeforePageDisplay' );

	// constuctors
	WF::addClassConstructor( 'ScavengerHuntGames', array( 'app' => $app ) );
	WF::addClassConstructor( 'ScavengerHuntEntries', array( 'app' => $app ) );
	WF::addClassConstructor( 'ScavengerHuntGame', array( 'app' => $app, 'id' => 0 ) );

	// XXX: standard MW constructors - needed to be moved to global place
	WF::addClassConstructor( 'Title', array(), 'newFromText' );
}

// Ajax dispatcher
$wgAjaxExportList[] = 'ScavengerHuntAjax';
function ScavengerHuntAjax() {
	global $wgUser, $wgRequest;
	$method = $wgRequest->getVal('method', false);

	if (method_exists('ScavengerHuntAjax', $method)) {
		wfProfileIn(__METHOD__);

		$data = ScavengerHuntAjax::$method();

		if (is_array($data)) {
			// send array as JSON
			$json = Wikia::json_encode($data);
			$response = new AjaxResponse($json);
			$response->setContentType('application/json; charset=utf-8');
		} else {
			// send text as text/html
			$response = new AjaxResponse($data);
			$response->setContentType('text/html; charset=utf-8');
		}

	} else {
		//TODO: add standard error message here
		$response = new AjaxResponse();
		$response->setContentType('text/html; charset=utf-8');
	}
	wfProfileOut(__METHOD__);
	return $response;
}
