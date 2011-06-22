<?php

/**
 * @file
 * @ingroup SMW
 * @author Krzysztof Krzyżaniak (eloy) <eloy@wikia-inc.com>
 *
 * wikia settings which differ from default.
 */

if( empty( $_GET[ "smwsql"] ) ) {
	$smwgDefaultStore = "SMWSparqlStore";

	switch( $wgCityId ) {

		// familypedia2/allegrograph
		case 232959:
			$smwgSparqlQueryEndpoint = 'http://smw:smw@localhost:10035/repositories/smw';
			$smwgSparqlUpdateEndpoint = 'http://smw:smw@localhost:10035/repositories/smw';
			$smwgSparqlDataEndpoint = ''; // can be empty
			break;

		default:
			$smwgSparqlDatabase = 'SMWSparqlDatabase4Store';
			$smwgSparqlQueryEndpoint = 'http://localhost:9000/sparql/';
			$smwgSparqlUpdateEndpoint = 'http://localhost:9000/update/';
			$smwgSparqlDataEndpoint = 'http://localhost:9000/data/';
	}
}
