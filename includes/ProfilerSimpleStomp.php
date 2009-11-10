<?php
/**
 * @file
 * @ingroup Profiler
 */

require_once( dirname( __FILE__ ) . '/ProfilerSimple.php' );

/**
 * @ingroup Profiler
 */
class ProfilerSimpleStomp extends ProfilerSimple {

	public $mFiltered = true;
	public $mProfiledMethods = array(
		'memcached::get',
		'Database::query',
		'Database::open',
		'Database::query-master',
		'Setup.php-extensions',
		'MediaWiki::performAction',
		'Output-skin',
		);

	function profileIn($functionname) {
		global $wgReportTimeViaStomp;
		if( !empty( $wgReportTimeViaStomp ) && 
			( !$this->mFiltered || in_array( $functionname, $this->mProfiledMethods ) ) ) {
			parent::profileIn($functionname);
		}
	}

	function profileOut($functionname) {
		global $wgReportTimeViaStomp;
		if( $functionname=='close' || 
			( !empty( $wgReportTimeViaStomp ) && 
			( !$this->mFiltered || in_array( $functionname, $this->mProfiledMethods ) ) ) ) {
			parent::profileOut($functionname);
		}
	}

	function getFunctionReport() {
		global $wgReportTimeViaStomp;

		if( empty( $wgReportTimeViaStomp ) ) {
			return;
		}

		global $wgStompServer, $wgStompUser, $wgStompPassword;
		global $wgTitle, $wgUseAjax, $wgRequest, $wgOut;

		$key = 'wikia.apache.service_time.'.wfHostname();
		$body = array(
				'key' => $key,
				'url' => strpos($_SERVER['REQUEST_URI'], 'http')===false ? 
						'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] :
						$_SERVER['REQUEST_URI'],
			     );
		foreach( $this->mCollated as $k=>$v ) {
			if( $k=='-total' || !$this->mFiltered || in_array( $k, $this->mProfiledMethods ) ) {
				$body['profiles'][$k] = $v;
			}
		}
		$action = $wgRequest->getVal( 'action', 'view' );
		if( $wgUseAjax && $action == 'ajax' )			$body['type'] = 'ajax';
		elseif( $action == 'raw' )				$body['type'] = 'raw';
		elseif( $action == 'render' )				$body['type'] = 'render';
		elseif( defined( 'MW_API' ) )				$body['type'] = 'api';
		elseif( $wgOut->getRedirect() != '' )			$body['type'] = 'redirect';
		else {
			if( $wgTitle != null && is_object( $wgTitle ) && $wgTitle instanceof Title ) {
				if( $wgTitle->getNamespace() == NS_SPECIAL )		$body['type'] = 'special';
				elseif( $wgTitle->isRedirect() )			$body['type'] = 'redirect';
				else							$body['type'] = 'article';
			} else {
				$body['type'] = '';
			}
		}
		try {
			$stomp = new Stomp( $wgStompServer );
			$stomp->connect( $wgStompUser, $wgStompPassword );
			$stomp->sync = false;
			$stomp->send(
					$key,
					Wikia::json_encode( $body ),
					array( 'exchange' => 'amq.topic', 'bytes_message' => 1 )
				    );
		}
		catch( Stomp_Exception $e ) {
			Wikia::log( __METHOD__, 'stomp_exception', $e->getMessage() );
		}
	}

}

