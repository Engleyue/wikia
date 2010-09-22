<?php

ini_set( "include_path", dirname(__FILE__)."/../" );
require( "commandLine.inc" );

if (isset($options['help'])) {
	die( "fix blog comments" );
}

$userName = isset( $options['user'] ) ? $options['user'] : 'Maintenance script';

$help = isset($options['help']);
$wikia = isset($options['wikia']) ? $options['wikia'] : "";
$dry = isset($options['dry']) ? $options['dry'] : "";

$method = 'fixBlogComments';

function fixAllBlogComments( $dry ) {
	global $method, $wgExternalDatawareDB, $wgCityId, $wgDBname;

	$dbw = wfGetDB( DB_SLAVE );
	$res = $dbw->select(
		array( 'recentchanges' ),
		array( 'rc_id, rc_title as page_title, rc_namespace as page_namespace'),
		array(
			'rc_namespace' => NS_BLOG_ARTICLE_TALK,
			"( rc_title not rlike '.+[0-9]+$' or rc_title not like '%@comment%' )",
			'rc_timestamp between \'20100915000000\` and \'20100917000000\''
		),
		$method
	);

	$pages = array();
	while ($row = $dbw->fetchRow($res)) {
		$pages[] = $row;
	}
	$dbw->freeResult( $res );

	print sprintf("Found %s: %0d pages \n", $wgDBname, count($pages));

	if( !empty($pages) ) {
		foreach ( $pages as $row ) {
			print "parse " . $row['page_title'] . "\n";

			$parts = ArticleComment::explode($row['page_title']);
			if ( $parts['blog'] == 1 && count($parts['partsOriginal']) > 0 ) {
				$parts['parsed'] = array();
				foreach ( $parts['partsOriginal'] as $id => $title ) {
#					list ($user, $date) = explode( '-', $title );
					$parts['parsed'][$id] = sprintf('%s-%s', '@comment', $title );
				}

				$newTitle = sprintf('%s/%s', $parts['title'], implode("/", $parts['parsed']) );

				if ( $dry ) {
					 print "update page set page_title = '$newTitle' where page_title = '{$row['page_title']}' and page_namespace = '".NS_BLOG_ARTICLE_TALK."' \n";
				} else {
					$dbw->update(
						'page',
						array( 'page_title' => $newTitle ),
						array( 'page_title' => $row['page_title'], 'page_namespace' => NS_BLOG_ARTICLE_TALK ),
						$method
					);
				}
				# update job
				if ( $dry ) {
					 print "update job set job_title = '$newTitle' where job_title = '{$row['page_title']}' and job_namespace = '".NS_BLOG_ARTICLE_TALK."' \n";
				} else {
					$dbw->update(
						'job',
						array( 'job_title' => $newTitle ),
						array( 'job_title' => $row['page_title'], 'job_namespace' => NS_BLOG_ARTICLE_TALK ),
						$method
					);
				}

				if( $dry ) {
					printf(
						"update recentchanges set rc_title = '%s' where rc_id = %d and rc_namespace = %d\n",
						$newTitle,
						$row['rc_id'],
						NS_BLOG_ARTICLE_TALK
					);
				}
				else {
					$dbw->update(
						'recentchanges',
						array( 'rc_title' => $newTitle ),
						array( 'rc_id' => $row['rc_id'], 'rc_namespace' => NS_BLOG_ARTICLE_TALK ),
						$method
					);
				}
				
				# update watchlist
				if ( $dry ) {
					 print "update watchlist set wl_title = '$newTitle' where wl_title = '{$row['page_title']}' and wl_namespace = '".NS_BLOG_ARTICLE_TALK."' \n";
				} else {
					$dbw->update(
						'watchlist',
						array( 'wl_title' => $newTitle ),
						array( 'wl_title' => $row['page_title'], 'wl_namespace' => NS_BLOG_ARTICLE_TALK ),
						$method
					);
				}

				# update dataware
				if ( $dry ) {
					 printf( "update pages set page_title = '%s' where page_id = %d and page_wikia_id = %d\n",
						$newTitle,
						$row['page_id'],
						$wgCityId
					);
				}
				else {
					$dbext = wfGetDB( DB_MASTER, array(), $wgExternalDatawareDB );
					$dbext->update(
						'pages',
						array( 'page_title' => $newTitle ),
						array( 'page_id' => $row['page_id'], "page_wikia_id" => $wgCityId ),
						$method
					);
				}
				
				if( $dry ) {
					printf(
						"update recentchanges set rc_title = '%s' where rc_id = %d and rc_namespace = %d\n",
						$newTitle,
						$row['rc_id'],
						NS_BLOG_ARTICLE_TALK
					);
				}
				else {
					$dbw->update(
						'recentchanges',
						array( 'rc_title' => $newTitle ),
						array( 'rc_id' => $row['rc_id'], 'rc_namespace' => NS_BLOG_ARTICLE_TALK ),
						$method
					);
				}				
			}
		}
	}
}


if ( $help ) {
	echo <<<TEXT
Usage:
    php fixblogcomments.php --help
    php fixblogcomments.php --parse=CITY_ID --dry

    --help         : This help message
    --dry		   : generate SQL commands (do not update in database)
TEXT;
	exit(0);
}
else {
     global $wgCityId;
	# set wgDisableBlogComments
	WikiFactory::setVarByName( "wgDisableBlogComments", $wgCityId, true ) ;
	WikiFactory::clearCache( $wgCityId );

	fixAllBlogComments( $dry );

	WikiFactory::removeVarByName( "wgDisableBlogComments", $wgCityId );
	WikiFactory::setVarByName( "wgUseNewBlogComments", $wgCityId, true ) ;
	WikiFactory::clearCache( $wgCityId );
}
