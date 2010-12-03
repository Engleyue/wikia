<?php

/**
 * Main Category Gallery class
 */
class CategoryDataService extends Service {

	private static function tableFromResult( $res, $mNamespace = NS_MAIN ){

		$articles = array();
		while ($row = $res->fetchObject($res)) {
			$articles[intval($row->page_id)] = array(
				'page_id'		=> $row->page_id
			);
		}
		return $articles;
	}

	public static function getAlphabetical( $sCategoryDBKey, $mNamespace ){

		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select(
			array( 'page', 'categorylinks' ),
			array( 'page_id', 'page_title' ),
			array(
				'cl_to' => $sCategoryDBKey,
				'page_namespace IN(' . $mNamespace . ')'
			),
			__METHOD__,
			array(	'ORDER BY' => 'page_title' ),
			array(	'categorylinks'  => array( 'INNER JOIN', 'cl_from = page_id' ))
		);
		return self::tableFromResult( $res, $mNamespace );
	}

	public static function getRecentlyEdited( $sCategoryDBKey, $mNamespace ){
		
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select(
			array( 'page', 'revision', 'categorylinks' ),
			array( 'page_id', 'page_title' ),
			array(
				'cl_to' => $sCategoryDBKey,
				'page_namespace IN(' . $mNamespace . ')'
			),
			__METHOD__,
			array(	'ORDER BY' => 'rev_timestamp DESC, page_title' ),
			array(	'revision'  => array( 'LEFT JOIN', 'rev_page = page_id' ),
				'categorylinks'  => array( 'INNER JOIN', 'cl_from = page_id' ))
		);
		return self::tableFromResult( $res, $mNamespace );
	}
	
	public function getMostVisited( $sCategoryDBKey, $mNamespace, $limit = false ){

		global $wgStatsDB, $wgCityId, $wgDevelEnvironment;

		if ( empty( $wgDevelEnvironment ) ) {
			// production mode

			$dbr = wfGetDB( DB_SLAVE );
			$res = $dbr->select(
				array( 'page', 'categorylinks' ),
				array( 'page_id', 'cl_to' ),
				array(	'cl_to' => $sCategoryDBKey,
					'page_namespace IN(' . $mNamespace . ')'
				),
				__METHOD__,
				array( 'ORDER BY' => 'page_title' ),
				array( 'categorylinks'  => array( 'INNER JOIN', 'cl_from = page_id' ) )
			);
			
			if ( $dbr->numRows( $res ) > 0 ) {
				Wikia::log(__METHOD__, ' Found some data in categories. Proceeding ');
				$aCategoryArticles = self::tableFromResult( $res );
				
				Wikia::log(__METHOD__, ' Searching for prepared data');

				$optionsArray = array();
				$optionsArray['ORDER BY'] = 'pv_views DESC';
				if ( !empty( $limit ) ) {
					$optionsArray['LIMIT'] = $limit;
				}
				
				$dbr = wfGetDB( DB_SLAVE, null, $wgStatsDB );
				$res = $dbr->select(
					array( 'specials.page_views_summary_articles' ),
					array( 'page_id' ),
					array(
						'city_id' => $wgCityId,
						'page_ns IN(' . $mNamespace . ')'
					),
					__METHOD__,
					$optionsArray
				);
				if ( ( $dbr->numRows( $res ) == 0 ) ) {
	
					Wikia::log(__METHOD__, ' No data. Try to gather some by myself');

					$optionsArray = array();
					$optionsArray['GROUP BY'] = 'pv_page_id';
					$optionsArray['ORDER BY'] = 'sum(pv_views) DESC';
					if ( !empty( $limit ) ) {
						$optionsArray['LIMIT'] = $limit;
					}

					$lastMonth = strftime( "%Y%m%d", time() - 30 * 24 * 60 * 60 );
					$res = $dbr->select(
						array( 'page_views_articles' ),
						array( 'pv_page_id as page_id' ),
						array(
							'pv_city_id' => $wgCityId,
							'pv_namespace IN(' . $mNamespace . ')'
						),
						__METHOD__,
						array(
							'GROUP BY' => 'pv_page_id',
							'ORDER BY' => 'sum(pv_views) DESC'
						)
					);
				}
				if ( $dbr->numRows( $res ) > 0 ) {

					Wikia::log(__METHOD__, 'Found some data. Lets find a commmon part with categories');
					$aSortedArticles = self::tableFromResult( $res );
					$aResult = array();
					foreach( $aSortedArticles as $key => $val ){
						if ( isset($aCategoryArticles[$key]) ){
							unset( $aCategoryArticles[$key] );
							$aResult[$key] = $val;
							if ( !empty( $limit ) && count($aResult) >= $limit ){
								return $aResult;
							}
						}
					}

					return $aResult + $aCategoryArticles;
				} else {
					
					Wikia::log(__METHOD__, 'No data at all. Quitting.');
					return array();
				}
			} else {
				Wikia::log(__METHOD__, ' No articles in category found - quitting');
				return array();
			}
			
		} else {
			// devbox version

			$optionsArray = array();
			$optionsArray['ORDER BY'] = 'count DESC, page_title';
			if ( $limit ) {
				$optionsArray['LIMIT'] = $limit;
			}

			$dbr = wfGetDB( DB_SLAVE );
			$res = $dbr->select(
				array( 'page', 'page_visited', 'categorylinks' ),
				array( 'page_id', 'page_title' ),
				array(	'cl_to' => $sCategoryDBKey,
					'page_namespace IN(' . $mNamespace . ')'
				),
				__METHOD__,
				$optionsArray,
				array(	'page_visited'  => array( 'LEFT JOIN', 'article_id = page_id' ),
					'categorylinks'  => array( 'INNER JOIN', 'cl_from = page_id' ))
			);
			
			return self::tableFromResult( $res, $mNamespace );
		}
	}
}