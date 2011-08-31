<?php
/**
 * Special site showing informations from FogBugz in Diagrams
 * @author Pawe� Rych�y
 * @author Piotr Paw�owski ( Pepe )
 * @brief Special Page based on project writen by wojtek@wikia-inc.com
 */
class FogBugzDiagramsSpecialController extends WikiaSpecialPageController {

	public function __construct() {
	    // standard SpecialPage constructor call
		parent::__construct( 'FogBugzDiagrams', '', false );
	}


	
	
	/**
	 * The index page of FogBugzDiagrams Extension
	 */
	public function index() {
		$this->wf->profileIn( __METHOD__ );    	
		$this->response->addAsset( "./skins/common/jquery/jquery.flot.js" );
		$this->response->addAsset( "./skins/common/jquery/jquery.flot.threshold.js" );
		$this->response->addAsset( "./skins/common/jquery/jquery.flot.stack.js" );
	 	$this->response->addAsset( "./extensions/wikia/FogBugzDiagrams/js/scripts.js" );
		$this->response->addAsset( "./extensions/wikia/FogBugzDiagrams/css/cssFile.css" );
		
		$report= new FogBugzReport();
		$data_send = $report->getPreparedData();
		$this->response->setData( $data_send );
		
		$this->wg->Out->setPageTitle( $this->wf->msg( 'fog-bugz-diagrams-special-page' ) );
		$this->wf->profileOut( __METHOD__ );
	} 

} 
    	
    	
