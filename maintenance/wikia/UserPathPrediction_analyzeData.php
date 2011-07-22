<?php
/**
 * This script downloads the data required for UserPathPrediction, parses and analyzes it; the resulting dat is stored in DB in a summary table
 *
 * @package MediaWiki
 * @subpackage Maintanance
 *
 * @author: Federico 'Lox' Lucignano <federico(at)wikia-inc.com>
 * @author: Jakub Olek <bukaj.kelo(at)gmail.com>
 *
 * @usage: SERVER_ID=177 php UserPathPrediction_analyzeData.php --conf /usr/wikia/conf/current/wiki.factory/LocalSettings.php --aconf /usr/wikia/conf/current/AdminSettings.php
 */

ini_set( 'include_path', dirname( __FILE__ ) . '/../' );

$options = array(
	'help',
	'conf',
	'aconf',
	'date',
	's3conf'
);

require_once( 'commandLine.inc' );

global $IP;

if ( isset( $options['help'] ) ) {
	echo(
		"Usage: SERVER_ID=177 php UserPathPrediction_initData.php " .
		"--conf /usr/wikia/conf/current/wiki.factory/LocalSettings.php " .
		"--aconf /usr/wikia/conf/current/AdminSettings.php " .
		"--date=20110623 " .
		"--s3conf=PATH_TO_S3_CONFIG\n\n"
	);
	exit( 0 );
}

//by default the script will download and process data for the last 24 hours
$date = ( !empty( $options['date'] ) ) ? $options['date'] : date( "Ymd", strtotime( "-1 day" ) );//"20110504"
$s3ConfigFile = ( !empty( $options['s3conf'] ) ) ? $options['s3conf'] : null;

require_once( "$IP/extensions/wikia/hacks/UserPathPrediction/UserPathPrediction.setup.php" );

$app = F::app();
$wikis;

$app->sendRequest( 'UserPathPredictionLogService', 'log', array( 'msg' => 'Start' ) );
echo( "Initializing data analysis for User Path Prediction.\n\n" );

try{
	echo( "Downloading and parsing data from {$date}, this could take a while...\n\n");
	$app->sendRequest( 'UserPathPredictionService', 'extractOneDotData', array( 'date' => $date, 'backendParams' => array( 's3ConfigFile' => $s3ConfigFile ) ) );
	echo( 'Done.');
} catch (WikiaException $e) {
	echo $e;
	exit( 1 );
}

echo( "Done.\n" );
?>