<?php

abstract class WikiaSearchClient {

	protected $results;

	abstract public function search( $query, $start, $length );

};
