<?php
/**
 * Wikia Search Extension - cross-Wikia search engine using Solr backend (based on MWSearch)
 *
 * @author Adrian 'ADi' Wieczorek <adi(at)wikia.com>
 * @author Garth Webb <garth@wikia-inc.com>
 *
 */

$wgSearchType = 'SolrSearch';

if( empty($wgEnableCrossWikiaSearch) ) {
	$wgEnableCrossWikiaSearch = false;
}
else {
	// cross-wikia search specific options
	$wgAFSEnabled = false; // disable AFS
	$wgUseWikiaSearchUI = true;
}

if( empty($wgCrossWikiaSearchExcludedWikis) ) {
	$wgCrossWikiaSearchExcludedWikis = array();
}

$wgExtensionCredits['other'][] = array(
	'name'        => 'Wikia Search',
	'version'     => '0.1.1',
	'author'      => '[http://www.wikia.com/wiki/User:Adi3ek Adrian \'ADi\' Wieczorek]',
	'descriptionmsg' => 'search-desc',
);

$dir = dirname(__FILE__) . '/';

require_once( $dir . 'SolrPhpClient/Apache/Solr/Service.php' );

// messages
$wgExtensionMessagesFiles['WikiaSearch'] = $dir . 'Search.i18n.php';

// hooks
$wgHooks['SearchShowHit'][] = 'SolrResult::showHit';
$wgHooks['SpecialSearchBoxExtraRefinements'][] = 'SolrSearch::renderExtraRefinements';
$wgHooks['SpecialSearchPagerParams'][] = 'SolrSearch::addPagerParams';
$wgHooks['SpecialSearchShortDialog'][] = 'SolrSearch::onSpecialSearchShortDialog';
$wgHooks['SpecialSearchIsgomatch'][] = 'SolrSearch::onSpecialSearchIsgomatch';

if( !empty($wgEnableWikiaImageOneBoxInSearch) ) {
	$wgHooks['SpecialSearchResults'][] = 'ImageOneBox::examineSearchResults';
	$wgHooks['SpecialSearchShowHit'][] = 'ImageOneBox::showImageOneBox';
}

// Search A/B testing config
if( empty( $wgWikiaSearchABTestEnabled ) ) {
	$wgWikiaSearchABTestEnabled = false;
}
$wgWikiaSearchABTestModes = array( 'AB_VANILLA', 'AB_INDEXTANK' );

// classes
$wgAutoloadClasses['SolrSearch'] = $dir . 'Search_body.php';
$wgAutoloadClasses['SolrResult'] = $dir . 'Search_body.php';
$wgAutoloadClasses['SolrSearchSet'] = $dir . 'Search_body.php';
$wgAutoloadClasses['SpecialWikiaSearch'] = $dir . 'SpecialWikiaSearch.php';

if( !empty($wgEnableWikiaImageOneBoxInSearch) ) {
	$wgAutoloadClasses['ImageOneBox'] = $dir . 'ImageOneBox.class.php';
}
