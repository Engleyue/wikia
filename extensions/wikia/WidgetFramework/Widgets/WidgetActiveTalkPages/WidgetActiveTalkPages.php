<?php
/**
 * @author Maciej Brencz
 * */
if(!defined('MEDIAWIKI')) {
	die(1);
}

global $wgWidgets;
$wgWidgets['WidgetActiveTalkPages'] = array(
	'callback' => 'WidgetActiveTalkPages',
	'title' => 'widget-title-activetalkpages',
	'desc' => 'widget-desc-activetalkpages',
	'params' => array(
		'limit' => array(
			'type' => 'text',
			'default' => 10
		),
	),
    'closeable' => true,
    'editable' => true,
);

function WidgetActiveTalkPages($id, $params) {

	global $wgLang;

	wfProfileIn(__METHOD__);

	// get last edits from API
	$results = WidgetFrameworkCallAPI(array
	(
		'action'	=> 'query',
		'list'		=> 'recentchanges',
		'rcnamespace'   => NS_TALK,
		'rclimit'	=> 100
	));

	$list = array();

	if ( !empty($results['query']['recentchanges']) ) {
	    // prevent showing the same page more then once
	    foreach($results['query']['recentchanges'] as $edit)
	    {
		$timestamp = strtotime($edit['timestamp']);
		$date = $wgLang->sprintfDate('j M Y (H:i)', date('YmdHis', $timestamp));

		$title = Title::newFromText( $edit['title'], $edit['ns'] );
		if (!isset($list[$edit['title']])) {
    		    $list[$edit['title']] = array
		    (
			'href'  => $title->getLocalURL('diff='.$edit['revid']), 
			'title' => $date.' (rev #'.$edit['revid'].')',
			'name'  => $title->getText(),
		    );
		}
	    }
	}
	
	$limit = intval($params['limit']);
	$limit = ($limit <=0 || $limit > 50) ? 15 : $limit;

	// limit results list
	$list = array_slice($list, 0, $limit);

	// 'more' link...
	$more = Title::newFromText('Recentchanges', NS_SPECIAL)->getLocalURL('namespace=1');

	return WidgetFrameworkWrapLinks($list) . WidgetFrameworkMoreLink($more);
}
