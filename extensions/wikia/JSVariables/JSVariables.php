<?php
/*
 * Author: Inez Korczyński (inez@wikia.com)
 */

$wgHooks['MakeGlobalVariablesScript'][] = 'wfMakeGlobalVariablesScript';

function wfMakeGlobalVariablesScript($vars) {
	wfProfileIn(__METHOD__);

	global $wgMemc, $wgCityId, $wgEnableAjaxLogin, $wgUser, $wgDBname, $wgPrivateTracker, $wgWikiaAdvertiserCategory, $wgExtensionsPath, $wgTitle, $wgArticle, $wgStyleVersion, $wgSitename, $wgWikiFactoryTags, $wgDisableAnonymousEditing, $wgGroupPermissions, $wgBlankImgUrl, $wgDontRewriteSassUrl, $wgEnableGA;

	$cats = wfGetBreadCrumb();
	$idx = count($cats)-2;
	if(isset($cats[$idx])) {
	    $vars['wgCatId'] = $cats[$idx]['id'];
	    $vars['wgParentCatId'] = $cats[$idx]['parentId'];
	} else	{
	    $vars['wgCatId'] = 0;
	    $vars['wgParentCatId'] = 0;
	}

	$vars['wgCityId'] = $wgCityId;
	$vars['wgID'] = isset($wgCityId) ? (int) $wgCityId : -1; // this one or one above should be deleted
	$vars['wgEnableAjaxLogin'] = (is_array($wgEnableAjaxLogin)) ? in_array($vars['skin'], $wgEnableAjaxLogin) : false;
	$vars['wgDB'] = $wgDBname;
	$vars['wgBlankImgUrl'] = $wgBlankImgUrl;

	// NOTE: This doesn't appear to be used in the code anywhere. If that's true, we can probably remove it.  It is set by the AdEngine though, so perhaps Athena uses it?
	$vars['wgPrivateTracker'] = !empty($wgPrivateTracker) ? $wgPrivateTracker : false;

	if($vars['wgIsArticle'] == false && $vars['wgEnableAjaxLogin']) {
		$vars['ajaxLogin1'] = wfMsg('ajaxLogin1');
		$vars['ajaxLogin2'] = wfMsg('ajaxLogin2');
	}
	$vars['wgMainpage'] = wfMsgForContent( 'mainpage' );
	$vars['wgIsMainpage'] = ($wgTitle->getArticleId() == Title::newMainPage()->getArticleId() && $wgTitle->getArticleId() != 0);
	if(!$vars['wgIsMainpage']) {
		if(!empty($wgArticle->mRedirectedFrom)) {
			if($vars['wgMainpage'] == $wgArticle->mRedirectedFrom->getPrefixedText()) {
				$vars['wgIsMainpage'] = true;
			}
		}
	}

	$vars['wgStyleVersion'] = isset($wgStyleVersion) ? $wgStyleVersion : '' ;
	if(isset($wgUser->getSkin()->themename)) {
		$vars['themename'] = $wgUser->getSkin()->themename;
	}

	$vars['wgExtensionsPath'] = $wgExtensionsPath;
	$vars['wgSitename'] = $wgSitename;

	// Monaco specific?
	$vars['wgMenuMore'] = strtolower(wfMsg('moredotdotdot'));
	if($wgUser->isAllowed('editinterface')) {
		$vars['wgMenuEdit'] = wfMsg('monaco-edit-this-menu');
	}

	$vars['wgAfterContentAndJS'] = array();

	// Set the JavaScript variable which is used by AJAX request to make data caching possible - Inez
	$vars['wgMWrevId'] = $wgMemc->get(wfMemcKey('wgMWrevId'));

	// RT #21084: get URL to YUI StaticChute package
	$StaticChute = new StaticChute('js');
	$StaticChute->useLocalChuteUrl();

	$vars['wgYUIPackageURL'] = $StaticChute->getChuteUrlForPackage('yui');

	// macbre: get revision ID of current article
	if ( $wgTitle->isContentPage() && !is_null($wgArticle)) {
		$vars['wgRevisionId'] = !empty($wgArticle->mRevision) ? $wgArticle->mRevision->getId() : intval($wgArticle->mLatest);
	}

	if(isset($wgWikiFactoryTags) && is_array($wgWikiFactoryTags)) {
		$vars['wgWikiFactoryTagIds'] = array_keys( $wgWikiFactoryTags );
		$vars['wgWikiFactoryTagNames'] = array_values( $wgWikiFactoryTags );
	}

	// is anon editing disabled?
	if ( $wgDisableAnonymousEditing || $wgGroupPermissions['user']['edit'] === false ) {
		$vars['wgDisableAnonymousEditing'] = true;
	} else {
		$vars['wgDisableAnonymousEditing'] = false;
	}

	// make direct requests to sassServer?
	if (!empty($wgDontRewriteSassUrl)) {
		$vars['wgDontRewriteSassUrl'] = true;
	}
	
	$vars['wgEnableGA'] = $wgEnableGA;
	$vars['_gaq'] = array();

	wfProfileOut(__METHOD__);

	return true;
}
