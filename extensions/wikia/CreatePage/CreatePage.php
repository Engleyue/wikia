<?php
/**
 * A special page to create a new article, using wysiwig editor
 *
 */

if(!defined('MEDIAWIKI')) {
	die();
}

$wgExtensionCredits['specialpage'][] = array(
	'name' => 'Create Page',
	'author' => 'Bartek Lapinski, Adrian Wieczorek',
	'url' => 'http://www.wikia.com' ,
	'description' => 'Allows to create a new page using wikia\'s Wysiwyg editor'
);

/**
 * messages file
 */
$wgExtensionMessagesFiles['CreatePage'] = dirname(__FILE__) . '/CreatePage.i18n.php';

/**
 * Special pages
 */
extAddSpecialPage(dirname(__FILE__) . '/SpecialCreatePage.php', 'CreatePage', 'CreatePage');

/**
 * setup functions
 */
$wgExtensionFunctions[] = 'wfCreatePageInit';
$wgHooks['MakeGlobalVariablesScript'][] = 'wfCreatePageSetupVars';

function wfCreatePageSetupVars( $vars ) {
	global $wgWikiaEnableNewCreatepageExt, $wgContentNamespaces, $wgContLang;

	$contentNamespaces = array();
	foreach($wgContentNamespaces as $contentNs) {
		$contentNamespaces[] = $wgContLang->getNsText($contentNs);
	}

	$vars['WikiaEnableNewCreatepage'] = $wgWikiaEnableNewCreatepageExt;
	$vars['ContentNamespacesText'] = $contentNamespaces;

	return true;
}

// initialize create page extension
function wfCreatePageInit() {
	global $wgUser, $wgHooks, $wgAjaxExportList, $wgOut, $wgScriptPath, $wgStyleVersion, $wgExtensionsPath, $wgWikiaEnableNewCreatepageExt;

	// load messages from file
	wfLoadExtensionMessages('CreatePage');

	if(empty($wgWikiaEnableNewCreatepageExt)) {
		// disable all new features and preserve old Special:CreatePage behavior
		return true;
	}

	if(get_class($wgUser->getSkin()) != 'SkinMonaco') {
		return true;
	}

	/**
	 * hooks
	 */
	$wgHooks['EditPage::showEditForm:initial'][] = 'wfCreatePageLoadPreformattedContent';
	$wgHooks['UserToggles'][] = 'wfCreatePageToggleUserPreference';
	$wgHooks['getEditingPreferencesTab'][] = 'wfCreatePageToggleUserPreference';

	$wgAjaxExportList[] = 'wfCreatePageAjaxGetDialog';
	$wgAjaxExportList[] = 'wfCreatePageAjaxCheckTitle';
}

function wfCreatePageToggleUserPreference($toggles, $default_array = false) {
	if(is_array($default_array)) {
		$default_array[] = 'createpagedefaultblank';
	}
	else {
		$toggles[] = 'createpagedefaultblank';
	}
	return true;
}

function wfCreatePageAjaxGetDialog() {
	global $wgWikiaCreatePageUseFormatOnly, $wgUser;

	$template = new EasyTemplate( dirname( __FILE__ )."/templates/" );

	$defaultLayout = $wgUser->getOption('createpagedefaultblank', false) ?  'blank' : 'format';

	$template->set_vars( array(
			'useFormatOnly' => !empty($wgWikiaCreatePageUseFormatOnly) ? true : false,
			'defaultPageLayout' => $defaultLayout
		)
	);

	$body = $template->execute( 'dialog' );
	$response = new AjaxResponse( $body );
	$response->setCacheDuration( 0 ); // no caching

	$response->setContentType('text/plain; charset=utf-8');

	return $response;
}

function wfCreatePageAjaxCheckTitle() {
	global $wgRequest, $wgUser;

	$result = array( 'result' => 'ok' );
	$sTitle = $wgRequest->getVal ('title') ;

	// perform title validation
	if(empty($sTitle)) {
		$result['result'] = 'error';
		$result['msg'] = wfMsg( 'createpage-error-empty-title' );
	}
	else {
		$oTitle = Title::newFromText($sTitle);

		if(!($oTitle instanceof Title)) {
			$result['result'] = 'error';
			$result['msg'] = wfMsg( 'createpage-error-invalid-title' );
		}
		else {
			if($oTitle->exists()) {
				$result['result'] = 'error';
				$result['msg'] = wfMsg( 'createpage-error-article-exists', array( $oTitle->getFullUrl(), $oTitle->getText() ) );
			}
			else { // title not exists
				// compressed spam filter - other have no sense since it's only title here at this point
				if( !wfSpamBlacklistTitleGenericTitleCheck( $oTitle ) ) {
					$result['result'] = 'error';
					$result['msg'] = wfMsg( 'createpage-error-article-spam' );
				}
				if ( $oTitle->getNamespace() == -1 ) {
					$result['result'] = 'error';
					$result['msg'] = wfMsg( 'createpage-error-invalid-title' );
				}
				if ( $wgUser->isBlockedFrom( $oTitle, false ) ) {
					$result['result'] = 'error';
					$result['msg'] = wfMsg( 'createpage-error-article-blocked' );
				}
			}
		}
	}

	$json = Wikia::json_encode($result);
	$response = new AjaxResponse( $json );
	$response->setCacheDuration( 3600 );

	$response->setContentType('text/plain; charset=utf-8');

	return $response;
}

function wfCreatePageLoadPreformattedContent( $editpage ) {
	global $wgRequest;
	if ($wgRequest->getCheck('useFormat')) {
		$editpage->textbox1 = wfMsgForContent( 'createpage-newpagelayout' );
	}
	return true ;
}

include( dirname( __FILE__ ) . "/SpecialEditPage.php");
include( dirname( __FILE__ ) . "/SpecialCreatePage.php");
