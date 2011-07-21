<?php
/**
 * LinkSuggest
 *
 * This extension provides the users with article title suggestions as he types
 * a link in wikitext.
 *
 * @file
 * @ingroup Extensions
 * @author Inez Korczyński <inez@wikia-inc.com>
 * @author Bartek Łapiński <bartek@wikia-inc.com>
 * @author Lucas Garczewski (TOR) <tor@wikia-inc.com>
 * @author Sean Colombo <sean@wikia.com>
 * @copyright Copyright (c) 2008-2009, Wikia Inc.
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

if(!defined('MEDIAWIKI')) {
	die(1);
}

$wgExtensionCredits['other'][] = array(
    'name' => 'LinkSuggest',
    'author' => 'Inez Korczyński, Bartek Łapiński, Ciencia al Poder, Lucas Garczewski, Sean Colombo',
    'version' => '1.53',
);

$wgExtensionMessagesFiles['LinkSuggest'] = dirname(__FILE__).'/'.'LinkSuggest.i18n.php';

$wgHooks['GetPreferences'][] = 'wfLinkSuggestGetPreferences' ;
$wgHooks['MakeGlobalVariablesScript'][] = 'wfLinkSuggestSetupVars';


function wfLinkSuggestSetupVars( $vars ) {
	global $wgContLang;
	$vars['ls_template_ns'] = $wgContLang->getFormattedNsText( NS_TEMPLATE );
	$vars['ls_file_ns'] = $wgContLang->getFormattedNsText( NS_FILE );
	return true;
}

function wfLinkSuggestGetPreferences($user, &$preferences) {
	$preferences['disablelinksuggest'] = array(
		'type' => 'toggle',
		'section' => 'editing/editing-experience',
		'label-message' => 'tog-disablelinksuggest',
	);
	return true;
}

$wgHooks['EditForm::MultiEdit:Form'][] = 'AddLinkSuggest';
function AddLinkSuggest($a, $b, $c, $d) {
	global $wgOut, $wgExtensionsPath, $wgStyleVersion, $wgUser;

	if($wgUser->getOption('disablelinksuggest') != true) {
		$wgOut->addHTML('<div id="LS_imagePreview" style="visibility: hidden; position: absolute; z-index: 1001; width: 180px;" class="yui-ac-content"></div>');
		$wgOut->addHTML('<div id="wpTextbox1_container" class="link-suggest-container"></div>');

		$js = "{$wgExtensionsPath}/wikia/LinkSuggest/LinkSuggest.js?{$wgStyleVersion}";

		// load YUI for Oasis - TODO: FIXME: why do we load YUI? It doesn't appear to be needed by this extension (if calling code needs it, that should load it).
		if (Wikia::isOasis()) {
			$wgOut->addHTML('<script type="text/javascript">$(function() {$.loadYUI(function() {$.getScript('.Xml::encodeJsVar($js).')})})</script>');
		}
		else {
			$wgOut->addScript('<script type="text/javascript" src="'.$js.'"></script>');
		}
	}
	return true;
}

global $wgAjaxExportList;
$wgAjaxExportList[] = 'getLinkSuggest';
$wgAjaxExportList[] = 'getLinkSuggestImage';

function getLinkSuggestImage() {
	global $wgRequest;
	$imageName = $wgRequest->getText('imageName');

	$out = 'N/A';
	try {
		$img = wfFindFile($imageName);
		if($img) {
			$out = $img->createThumb(180);
		}
	} catch (Exception $e) {
		$out = 'N/A';
	}

	$ar = new AjaxResponse($out);
	$ar->setCacheDuration(60 * 60);
	return $ar;
}

function wfLinkSuggestGetTextUpperBound( $text ) {
	$len = mb_strlen($text);
	if ($len == 0)
		return false;
	$lastChar = Wikia::ord(mb_substr($text,-1));
	if ($lastChar >= 0x7FFFFFFF)
		return wfLinkSuggestGetTextUpperBound( mb_substr($text,0,$len-1) );
	// this should check for invalid utf8 code points, but don't care about it (super-rare case)
	return mb_substr($text,0,$len-1) . Wikia::chr($lastChar + 1);
}

function getLinkSuggest() {
	global $wgRequest, $wgContLang, $wgCityId, $wgExternalDatawareDB, $wgContentNamespaces;

	// trim passed query and replace spaces by underscores
	// - this is how MediaWiki store article titles in database
	$query = urldecode( trim( $wgRequest->getText('query') ) );
	$query = str_replace(' ', '_', $query);

	// Allow the calling-code to specify a namespace to search in (which at the moment, could be overridden by having prefixed text in the input field).
	// NOTE: This extension does parse titles to try to find things in other namespaces, but that actually doesn't work in practice because jQuery
	// Autocomplete will stop making requests after it finds 0 results.  So if you start to type "Category" and there is no page beginning
	// with "Cate", it will not even make the call to LinkSuggest.
	$namespace = $wgRequest->getVal('ns');

	// explode passed query by ':' to get namespace and article title
	$queryParts = explode(':', $query, 2);

	if(count($queryParts) == 2) {
		$query = $queryParts[1];

		$namespaceName = $queryParts[0];

		// try to get the index by canonical name first
		$namespace = MWNamespace::getCanonicalIndex(strtolower($namespaceName));
		if ( $namespace == null ) {
			// if we failed, try looking through localized namespace names
			$namespace = array_search(ucfirst($namespaceName), $wgContLang->getNamespaces());
			if (empty($namespace)) {
				// getting here means our "namespace" is not real and can only be part of the title
				$query = $namespaceName . ':' . $query;
			}
		}
	}

	// which namespaces to search in?
	if (empty($namespace)) {
		// search only within content namespaces (BugId:4625) - default behaviour
		$namespaces = $wgContentNamespaces;
	}
	else {
		// search only within a given namespace
		$namespaces = array($namespace);
	}

	$results = array();

	$query = mb_strtolower($query);
	$queryUpper = wfLinkSuggestGetTextUpperBound($query);
	$query = addslashes($query);
	$queryUpper = addslashes($queryUpper);

	$db = wfGetDB(DB_SLAVE, 'search');

	$res = $db->select(
		array( "querycache", "page" ),
		array( "qc_namespace", "qc_title" ),
		array(
			" qc_title = page_title ",
			" qc_namespace = page_namespace ",
			" page_is_redirect = 0 ",
			" qc_type = 'Mostlinked' ",
			// faster replacement for: " LOWER(qc_title) LIKE LOWER('{$query}%') ",
			" LOWER(qc_title) >= '{$query}' ",
			" LOWER(qc_title) < '{$queryUpper}' ",
			" qc_namespace IN (" . implode(',', $namespaces) . ")"
		),
		__METHOD__,
		array("ORDER BY" => "qc_value DESC", "LIMIT" => 10)
	);
	while($row = $db->fetchObject($res)) {
		$results[] = wfLinkSuggestFormatTitle($row->qc_namespace, $row->qc_title);
	}
	$db->freeResult( $res );

	$dbs = wfGetDB( DB_SLAVE, array(), $wgExternalDatawareDB );
	$res = $dbs->select(
		array( "pages" ),
		array( "page_namespace", "page_title" ),
		array(
			" page_wikia_id " => $wgCityId,
			// faster replacement for: " page_title_lower LIKE '{$query}%' ",
			" page_title_lower >= '{$query}' ",
			" page_title_lower < '{$queryUpper}' ",
			" page_namespace IN (" . implode(',', $namespaces) . ")",
			" page_status = 0 "
		),
		__METHOD__,
		array(
			"ORDER BY" => "page_title_lower ASC",
			"LIMIT" => (15 - count($results))
		)
	);
	while($row = $dbs->fetchObject($res)) {
		$results[] = wfLinkSuggestFormatTitle($row->page_namespace, $row->page_title);
	}
	$dbs->freeResult( $res );

	$results = array_unique($results);
	$format = $wgRequest->getText('format');

	if($format == 'json') {
		$out = Wikia::json_encode(array('query' => $wgRequest->getText('query'), 'suggestions' => array_values($results)));
	} else {
		$out = implode("\n", $results);
	}

	$ar = new AjaxResponse($out);
	$ar->setCacheDuration(60 * 60); // cache results for one hour

	// set proper content type to ease development
	if ($format == 'json') {
		$ar->setContentType('application/json; charset=utf-8');
	}
	else {
		$ar->setContentType('text/plain; charset=utf-8');
	}

	return $ar;
}

/**
 * Returns formatted title based on given namespace and title
 *
 * @param $namespace integer page namespace ID
 * @param $title string page title
 * @return string formatted title (prefixed with namespace)
 */
function wfLinkSuggestFormatTitle($namespace, $title) {
	if ($namespace > 0) {
		$title = MWNamespace::getCanonicalName($namespace) . ':' . $title;
	}

	return str_replace('_', ' ', $title);
}
