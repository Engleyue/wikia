<?php

/**
 * @package MediaWiki
 * @addtopackage maintenance
 */

ini_set( "include_path", dirname(__FILE__)."/../../../../maintenance/" );
require_once( "commandLine.inc" );

if ( wfReadOnly() || !empty( $wgAdSS_ReadOnly ) ) {
	echo "Read-only mode - exiting.";
	exit( 1 );
}

echo "Checking for users that owe us $".$wgAdSSBillingThreshold." or more OR haven't been billed in last month OR owe us anything but have no more ads in rotation\n";
$dbw = wfGetDB( DB_MASTER, array(), $wgAdSS_DBname );
$res = $dbw->select(
		array( 'billing' ),
		array( 'billing_user_id', 'sum(billing_amount) as billing_balance', 'max( if (billing_ppp_id>0, billing_timestamp, null ) ) as last_billed' ),
		array(),
		__METHOD__,
		array(
			'GROUP BY' => 'billing_user_id',
			'HAVING' => "billing_balance <= -$wgAdSSBillingThreshold OR ( billing_balance < 0 and last_billed < date_sub( now(), interval 1 month ) ) OR ( billing_balance < 0 and billing_user_id in ( select ad_user_id from ads group by ad_user_id having sum( if( ad_expires is not null and ad_closed is null, 1, 0 ) ) = 0 ) )",
		     )
	       );
foreach( $res as $row ) {
	$user = AdSS_User::newFromId( $row->billing_user_id );
	$amount = -$row->billing_balance;
	echo "{$user->toString()} | $amount | {$row->last_billed} | ";

	if( !$user->baid ) {
		echo "failed - no billing agreement!\n";
		continue;
	}

	echo "user: ". $user->id . " billed! (amount:" . $amount . ")\n";
}
$dbw->freeResult( $res );
