<?php
// Initialise common MW code
require ( dirname( __FILE__ ) . '/includes/WebStart.php' );

// This seems to be done by google translate
if ( $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	header ( "HTTP/1.1 200", true, 200);
	return;
}

if ( !empty( $wgEnableNirvanaAPI ) ){
	$app = F::app();
	
	// Ensure that we have a title stub, otherwise parser does not work BugId: 12901
	$app->wg->title = new Title();
	
	// initialize skin if requested
	$app->initSkin( (bool) $app->wg->Request->getVal( "skin", false ) );
	
	$response = $app->sendRequest( null, null, null, false );
	
	// commit any open transactions just in case the controller forgot to
	$app->commit();
	
	$response->sendHeaders();
	$response->render();
} else {
	header( "HTTP/1.1 503 Service Unavailable", true, 503 );
}