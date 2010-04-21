<?php
/*
 * Author: Tomek Odrobny
 * Data model
 */


class FollowModel {
	
	/**
	 * getWatchList -- get data for followe pages include see all 
	 *
	 * @static
	 * @access public
	 *
	 *
	 * @return bool
	 */
	
	static function getWatchList($user_id, $limit = 15, $namespace_head = null) {
		global $wgServer, $wgScript, $wgContentNamespaces;
		wfProfileIn( __METHOD__ );
		$db = wfGetDB( DB_SLAVE );

		$namespaces = array(
			NS_CATEGORY => 'wikiafollowedpages-special-heading-categories',	
			NS_BLOG_ARTICLE => 'wikiafollowedpages-special-heading-blogs',
			NS_BLOG_LISTING => 'wikiafollowedpages-special-heading-blogs',
			NS_PROJECT => 'wikiafollowedpages-special-heading-project' ,
			NS_TEMPLATE => 'wikiafollowedpages-special-heading-templates',
			NS_USER => 'wikiafollowedpages-special-heading-user',
			NS_MEDIAWIKI => 'wikiafollowedpages-special-heading-mediawiki',
			110 => 'wikiafollowedpages-special-heading-forum', //NS_FORUM
			NS_FILE => 'wikiafollowedpages-special-heading-media',
			NS_VIDEO => 'wikiafollowedpages-special-heading-media',
		);

		foreach ($wgContentNamespaces as $value) {
			$namespaces[$value] = 'wikiafollowedpages-special-heading-main'; 
		}
		
		if ($namespace_head != null) {
			foreach ($namespaces as $key => $value) {
				if ( $value != $namespace_head ) {
					unset($namespaces[$key]);
				}
			}
		}
		$namespaces_keys = array_keys($namespaces);
		
		$queryArray = array();
		foreach ($namespaces_keys as $value) {
			$queryArray[] = "(select wl_namespace, wl_title from watchlist where wl_user = ".intval($user_id)." and wl_namespace = ".intval($value)." ORDER BY wl_created desc limit ".intval($limit).")";
		}

		$res = $db->query( implode(" union ",$queryArray) );
		$out_data = array();
		while ($row =  $db->fetchRow( $res ) ) {
			$title = Title::makeTitle( $row['wl_namespace'], $row['wl_title'] );
			$row['url'] = $title->getFullURL();
			$row['hideurl'] = $wgServer.$wgScript."?action=ajax&rs=wfAjaxWatch&rsargs[]=".$title->getFullText()."&rsargs[]=u";
			$row['wl_title'] = str_replace("_"," ",$row['wl_title'] );
			if ($row['wl_namespace'] == NS_BLOG_ARTICLE) {
				$explode = explode("/", $row['wl_title']);
				if ( count($explode) > 1) {
					$row['wl_title'] = $explode[1];
					$row['by_user'] =  $explode[0];	
				}
			}
			
			if ( in_array($row['wl_namespace'], $wgContentNamespaces) && (NS_MAIN != $row['wl_namespace']) ) {
				$ttile = Title::makeTitle($row['wl_namespace'], "none");
				$row['other_namespace'] = $ttile->getNsText();
			}
			
			$out_data[$namespaces[ $row['wl_namespace'] ]][] = $row; 	
		}
		$query = "select wl_namespace, count(wl_title) as cnt  from watchlist where wl_user = ".intval($user_id)." and wl_namespace in (".implode(',', $namespaces_keys).") group by wl_namespace";
		
		$res =$db->query( $query );
		$out_count = array();
		
		while ($row =  $db->fetchRow( $res ) ) {
			$ns = $namespaces[$row['wl_namespace']];
			if ( !empty($out[$ns]) ) {
				$out[$ns]['count'] += $row['cnt'];
			} else {
				$out[$ns] = array('count' => $row['cnt'], 'data' => $out_data[$ns]); 	
			}
			
			$out[$ns]['show_more'] = 0;
			if ( $out[$ns]['count']  > $limit ) {
				$out[$ns]['show_more'] = 1;
			}
		}

		wfProfileOut( __METHOD__ );
		return $out;
	}

	/**
	 * getUserPageWatchList -- getdata for box on user page 
	 *
	 * @static
	 * @access public
	 *
	 *
	 * @return bool
	 */
		
	static function getUserPageWatchList($user_id) {
		global $wgMemc, $wgContentNamespaces;
		
		$NS = array(
			NS_BLOG_ARTICLE
		);
		
		$NS = array_merge($NS,$wgContentNamespaces);
		
		wfProfileIn( __METHOD__ );
		$db = wfGetDB( DB_SLAVE );
		$con = 'wl_user = '.intval($user_id).' and wl_namespace not in ('.implode(',', $NS).')';
		$res = $db->select(
				array( 'watchlist' ),
				array( 'wl_namespace',
					   'wl_title'),
				$con,
				__METHOD__,
				array(
					'ORDER BY' 	=> 'wl_created desc',
					'LIMIT'		=> 10,
					'GROUP BY' => 'wl_title'
				)
		);	

		while ($row = $db->fetchRow( $res ) ) {
			$title = Title::makeTitle( $row['wl_namespace'], $row['wl_title'] );
			$row['url'] = $title->getFullURL();
			$row['wl_title'] = str_replace("_"," ",$row['wl_title'] );
			if ($row['wl_namespace'] == NS_BLOG_ARTICLE || $row['wl_namespace'] == NS_BLOG_LISTING) {
				$explode = explode("/", $row['wl_title']);
				if ( count($explode) > 1) {
					$row['wl_title'] = $explode[1];
					$row['by_user'] =  $explode[0];	
				}
			}
			
			$watchlist[] = $row; 
		}
		wfProfileOut( __METHOD__ );
		return $watchlist;
	}
}
