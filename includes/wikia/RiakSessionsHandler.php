<?php


/**
 * This file gets included if $wgSessionsInRiak is set in the config.
 * It redirects session handling functions to store their data in riak
 * instead of the local filesystem.
 *
 * @file
 * @ingroup Cache
 * @author Krzysztof Krzyżaniak (eloy)
 */

class RiakSessionHandler {

	static public $mBucket = "session";

	/**
	 * return proper key for session
	 *
	 * if $wgWikiaCentralAuthDatabase is set it means that we use WikiaCentralAuth
	 * and we want to set prefix for $wgWikiaCentralAuthDatabase
	 *
	 * if $wgSharedDB is set it means that we use global user table on 1st cluster
	 *
	 * if nothing from above is set we have local user table (for example
	 * uncyclopedia)
	 */
	static public function key( $id ) {
		global $wgSharedDB, $wgDBname, $wgWikiaCentralAuthDatabase;

		/**
		 * default key
		 */
		$key = sprintf( "%s:session:%s", $wgDBname, $id );

		if( !empty( $wgWikiaCentralAuthDatabase ) ) {
			$key = sprintf( "%s:session:%s", $wgWikiaCentralAuthDatabase, $id );
		}
		elseif( !empty( $wgSharedDB ) ) {
			$key = sprintf( "%s:session:%s", $wgSharedDB, $id );
		}

		return $key;
	}

	static public function open() {
		#
		# NOP, riak is connectless protocol (HTTP)
		#
		return true;
	}

	static public function close() {
		#
		# NOP, riak is connectless protocol (HTTP)
		#
		return true;
	}

	static public function read( $id ) {
		#$riak = ;
		$bucket = wfGetRiakClient()->bucket( self::$mBucket );
		$object = $bucket->get( self::key( $id ) );
		$data   = $object->getData();
		return $data;
	}

	static public function write( $id, $data ) {
		$bucket = wfGetRiakClient()->bucket( self::$mBucket );
		$object = $bucket->newObject( self::key( $id ), $data );
		$object->store();
	}

	static public function destroy() {
		$bucket = wfGetRiakClient()->bucket( self::$mBucket );
		$object = $bucket->get( self::key( $id ) );
		$object->delete();
		$object->reload();
	}

	static public function gc( $maxlifetime ) {
		$riak = wfGetRiakClient();
	}

}

session_set_save_handler(
	array( "RiakSessionHandler", "open" ),
	array( "RiakSessionHandler", "close" ),
	array( "RiakSessionHandler", "read" ),
	array( "RiakSessionHandler", "write" ),
	array( "RiakSessionHandler", "destroy" ),
	array( "RiakSessionHandler", "gc" )
);
