<?php

/**
 * EditPageLayout
 *
 * Applies updated layout for edit pages (Oasis only)
 *
 * @author Maciej Brencz (Macbre) <macbre at wikia-inc.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @package MediaWiki
 *
 * To activate this functionality, place this file in your extensions/
 * subdirectory, and add the following line to LocalSettings.php:
 *     require_once("$IP/extensions/wikia/EditPageLayout/EditPageLayout_setup.php");
 */

$wgExtensionCredits['other'][] = array(
	'name' => 'EditPageLayout',
	'version' => '1.0',
	'author' => 'Maciej Brencz',
	'description' => 'Applies updated layout for edit pages',
);

$dir = dirname(__FILE__);

// WikiaApp
$app = WF::build('App');

// classes
$app->registerClass('EditPageLayout', $dir . '/EditPageLayout.class.php');
$app->registerClass('EditPageLayoutAjax', $dir . '/EditPageLayoutAjax.class.php');
$app->registerClass('EditPageLayoutHelper', $dir . '/EditPageLayoutHelper.class.php');
$app->registerClass('EditPageLayoutModule', $dir . '/EditPageLayoutModule.class.php');

// mocks classes
$app->registerClass('ObjectMocker', $dir . '/mocks/ObjectMocker.class.php');
$app->registerClass('ObjectTracer', $dir . '/mocks/ObjectTracer.class.php');
$app->registerClass('ObjectCallTrace', $dir . '/mocks/ObjectCallTrace.class.php');

// notices classes
$app->registerClass('EditPageNotice', $dir . '/notices/EditPageNotice.class.php');
$app->registerClass('EditPageNotices', $dir . '/notices/EditPageNotices.class.php');
$app->registerClass('EditPageOutputBridge', $dir . '/notices/EditPageOutputBridge.class.php');

// services
$app->registerClass('EditPageService', $dir . '/EditPageService.class.php');

// abstract special page class for custom edit pages
$app->registerClass('SpecialCustomEditPage', $dir . '/SpecialCustomEditPage.class.php');

// hooks
$app->registerHook('AlternateEditPageClass', 'EditPageLayoutHelper', 'onAlternateEditPageClass');
$app->registerHook('EditPageBeforeConflictDiff', 'EditPageLayoutHelper', 'onEditPageBeforeConflictDiff');
$app->registerHook('EditPageGetDiffText', 'EditPageLayoutHelper', 'onEditPageGetDiffText');
$app->registerHook('GetPreferences', 'EditPageLayoutHelper', 'onGetPreferences');

// messages
$app->registerExtensionMessageFile('EditPageLayout', $dir . '/EditPageLayout.i18n.php');

// register messages package for JS
$app->registerExtensionJSMessagePackage('EditPageLayout', array(
	'ok',
	'back',
	'preview',
	'savearticle',
	'editpagelayout-captcha-title',
	'editpagelayout-more',
	'editpagelayout-less',
	'editpagelayout-pageControls-changes',
	'editpagelayout-loadingStates-*',
	'editpagelayout-modules-*',
	'wikia-editor-*',
));

// Ajax dispatcher
$wgAjaxExportList[] = 'EditPageLayoutAjax';
function EditPageLayoutAjax() {
	global $wgRequest;
	wfProfileIn(__METHOD__);

	$ret = false;

	$method = $wgRequest->getVal('method', false);

	if ($method && method_exists('EditPageLayoutAjax', $method)) {
		$data = EditPageLayoutAjax::$method();

		if (is_array($data)) {
			$json = Wikia::json_encode($data);

			$response = new AjaxResponse($json);
			$response->setContentType('application/json; charset=utf-8');
			$ret = $response;
		}
		else {
			$ret = $data;
		}
	}

	wfProfileOut(__METHOD__);
	return $ret;
}


// TEMPORARY SOLUTION TO WORK-AROUND MESSAGE CACHE AND DUAL CODEBASE FOR RELATED EXTENSIONS
// TO BE REMOVED AFTER FINAL RELEASES OF EDIT PAGE RESKIN
$app->registerExtensionFunction('EditPageReskinMessageCacheFix');
function EditPageReskinMessageCacheFix() {
	global $wgMessageCache;

	$dir = dirname(dirname(__FILE__));
	$files = array(
		$dir . '/EditPageLayout/EditPageLayout.i18n.php',
		$dir . '/EditPageReskin/CreatePage/CreatePage.i18n.php',
		$dir . '/EditPageReskin/CategorySelect/CategorySelect.i18n.php',
		$dir . '/EditPageReskin/PageLayoutBuilder/PageLayoutBuilder.i18n.php',
		$dir . '/EditPageReskin/Blogs/Blogs.i18n.php',
		$dir . '/EditPageReskin/RTE/i18n/RTE.i18n.php',
		$dir . '/EditPageReskin/RTE/i18n/CK.core.i18n.php',
		$dir . '/EditPageReskin/RTE/i18n/CK.wikia.i18n.php',
	);

	foreach ($files as $file) {
		$messages = array();
		include $file;
		$wgMessageCache->addMessagesByLang($messages);
	}

	return true;
}