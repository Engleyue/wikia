<?php

/**
 * Nirvana Framework - Response class
 *
 * @ingroup nirvana
 *
 * @author Adrian 'ADi' Wieczorek <adi(at)wikia-inc.com>
 * @author Owen Davis <owen(at)wikia-inc.com>
 * @author Wojciech Szela <wojtek(at)wikia-inc.com>
 * @author Federico "Lox" Lucignano <wojtek(at)wikia-inc.com>
 */
class WikiaResponse {
	/**
	 * headers
	 */
	const ERROR_HEADER_NAME = 'X-Wikia-Error';

	/**
	 * Response codes
	 */
	const RESPONSE_CODE_OK = 200;
	const RESPONSE_CODE_ERROR = 501;
	const RESPONSE_CODE_FORBIDDEN = 403;

	/**
	 * Output formats
	 */
	const FORMAT_RAW = 'raw';
	const FORMAT_HTML = 'html';
	const FORMAT_JSON = 'json';
	const FORMAT_JSONP = 'jsonp';
	const FORMAT_INVALID = 'invalid';

	/**
	 * Cache targets
	 */
	const CACHE_TARGET_BROWSER = 0;
	const CACHE_TARGET_VARNISH = 1;

	/**
	 * View object
	 * @var WikiaView
	 */
	private $view = null;
	private $body = null;
	private $code = null;
	private $contentType = null;
	private $headers = array();
	private $format = null;
	private $skinName = null;
	private $controllerName = null;
	private $methodName = null;
	private $request = null;
	protected $data = array();
	protected $exception = null;

	/**
	 * constructor
	 * @param string $format
	 */
	public function __construct( $format, $request = null ) {
		$this->setFormat( $format );
		$this->setView( F::build( 'WikiaView', array( $this ) ) );
		$this->setRequest( $request );
	}

	public function setRequest( $request ) {
		$this->request = $request;
	}

	public function getRequest() {
		return $this->request;
	}

	/**
	 * set exception
	 * @param Exception $exception
	 */
	public function setException(Exception $exception) {
		$this->exception = $exception;
	}

	/**
	 * get view
	 * @return WikiaView
	 */
	public function getView() {
		return $this->view;
	}

	/**
	 * set view
	 * @param WikiaView $view
	 */
	public function setView( WikiaView $view ) {
		$this->view = $view;
		$this->view->setResponse( $this );
	}

	/**
	 * gets requested skin name
	 * @return String
	 */
	public function getSkinName() {
		return $this->skinName;
	}

	/**
	 * sets requested skin name
	 * @param String $name
	 */
	public function setSkinName( $name ) {
		$this->skinName = $name;
	}

	/**
	 * gets the controller name
	 * @return string
	 */
	public function getControllerName() {
		return $this->controllerName;
	}

	/**
	 * sets the controller name
	 * @param string $value
	 */
	public function setControllerName( $value ) {
		$this->controllerName = $value;
	}

	/**
	 * gets method name
	 * @return string
	 */
	public function getMethodName() {
		return $this->methodName;
	}

	/**
	 * sets method name
	 * @param string $value
	 */
	public function setMethodName( $value ) {
		$this->methodName = $value;
	}

	/**
	 * gets response data
	 * @return array
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * sets response data
	 * @param array $data
	 */
	public function setData( Array $data ) {
		$this->data = $data;
	}

	/**
	 * get response body
	 * @return string
	 */
	public function getBody() {
		return $this->body;
	}

	/**
	 * sets response body
	 * @param string $value
	 */
	public function setBody( $value ) {
		$this->body = $value;
	}

	/**
	 * reset all response data
	 */
	public function resetData() {
		$this->data = array();
	}

	/**
	 * append something to response body
	 * @param string $value
	 */
	public function appendBody( $value ) {
		$this->body .= $value;
	}

	/**
	 * get response code
	 * @return int
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * set response code
	 * @param int $value
	 */
	public function setCode( $value ) {
		$this->code = $value;
	}

	/**
	 * get content type
	 * @return string
	 */
	public function getContentType() {
		return $this->contentType;
	}

	/**
	 * set content type
	 * @param string $value
	 */
	public function setContentType( $value ) {
		$this->contentType = $value;
	}

	public function hasContentType() {
		return (bool) $this->contentType;
	}

	public function getFormat() {
		return $this->format;
	}

	public function setFormat( $value ) {
		if ( $value == self::FORMAT_HTML || $value == self::FORMAT_JSON || $value == self::FORMAT_RAW || $value == self::FORMAT_JSONP ) {
			$this->format = $value;
		} else {
			$this->format = self::FORMAT_INVALID;
		}
	}

	public function getHeaders() {
		return $this->headers;
	}

	public function setHeader( $name, $value, $replace = true ) {
		if( $replace ) {
			$this->removeHeader( $name );
		}

		$this->headers[] = array(
		 'name' => $name,
		 'value' => $value,
		 'replace' => $replace
		);
	}

	/**
	 * Sets correct cache headers for the browser, Varnish or both
	 *
	 * @param integer $expiryTime validity for the Expires header in seconds
	 * @param integer $maxAge validity for the Cache-Control max-age header in seconds
	 * @param array $targets an array with the targets to be affected by the headers, one (or a combination) of
	 * WikiaResponse::CACHE_TARGET_BROWSER and WikiaResponse::CACHE_TARGET_VARNISH
	 */
	public function setCacheValidity( $expiryTime = null, $maxAge = null, Array $targets = array() ){
		$targetBrowser = ( in_array( self::CACHE_TARGET_BROWSER, $targets ) );
		$targetVarnish = ( in_array( self::CACHE_TARGET_VARNISH, $targets ) );

		if ( !is_null( $expiryTime ) ){
			$expiryTime = (int) $expiryTime;

			if ( $targetBrowser ) {
				$this->setHeader( 'Expires', gmdate( 'D, d M Y H:i:s', time() + $expiryTime ) . ' GMT', true);
			}

			if ( $targetVarnish) {
				$this->setHeader( 'X-Pass-Expires', gmdate( 'D, d M Y H:i:s', time() + $expiryTime ) . ' GMT', true );
			}
		}

		if( !is_null( $maxAge ) ) {
			$maxAge = (int) $maxAge;

			if ( $targetBrowser ) {
				$this->setHeader( 'Cache-Control', "max-age={$maxAge}", true );
			}

			if ( $targetVarnish) {
				$this->setHeader( 'X-Pass-Cache-Control', "max-age={$maxAge}", true );
			}
		}
	}

	public function getHeader( $name ) {
		$result = array();

		foreach( $this->headers as $key => $header ) {
			if( $header['name'] == $name ) {
				$result[] = $header;
			}
		}

		return ( count( $result ) ? $result : null );
	}

	public function removeHeader( $name ) {
		foreach( $this->headers as $key => $header ) {
			if( $header['name'] == $name ) {
				unset( $this->headers[ $key ] );
			}
		}
	}

	public function setVal( $key, $value ) {
		$this->data[$key] = $value;
	}

	public function getVal( $key, $default = null ) {
		if( isset( $this->data[$key] ) ) {
			return $this->data[$key];
		}

		return $default;
	}

	public function hasException() {
		return ( $this->exception == null ) ? false : true;
	}

	public function getException() {
		return $this->exception;
	}

	/**
	 * alias to be compatibile with MW AjaxDispatcher
	 * @return string
	 */
	public function printText() {
		print $this->toString();
	}

	public function render() {
		print $this->toString();
	}

	public function toString() {
		if( $this->body === null ) {
			$this->body = $this->view->render();
		}
		return $this->body;
	}

	public function sendHeaders() {
		if( ( $this->getFormat() == WikiaResponse::FORMAT_JSON ) && $this->hasException() ) {
			// set error header for JSON response (as requested for mobile apps)
			$this->setHeader( self::ERROR_HEADER_NAME, $this->getException()->getMessage() );
		}

		if( ( $this->getFormat() == WikiaResponse::FORMAT_JSON ) && !$this->hasContentType() ) {
			$this->setContentType( 'application/json; charset=utf-8' );
		} else if ( $this->getFormat() == WikiaResponse::FORMAT_JSONP ) {
			$this->setContentType( 'text/javascript; charset=utf-8' );
		} else if ( $this->getFormat() == WikiaResponse::FORMAT_HTML ) {
			$this->setContentType( 'text/html; charset=utf-8' );
		}

		foreach ( $this->getHeaders() as $header ) {
			$this->sendHeader( ( $header['name'] . ': ' . $header['value'] ), $header['replace']);
		}

		if ( !empty( $this->code ) ) {
			$msg = '';

			//standard HTTP response codes get automatically described by PHP and those descriptions shouldn't be overridden, ever
			//use a custom error code if you need a custom code description
			if( !$this->isStandardHTTPCode( $this->code ) ) {
				if ( $this->hasException() ) {
					$msg = ' ' . $this->getException()->getMessage();
				}

				if(empty($msg))
					$msg = ' Unknown';
			}

			$this->sendHeader( "HTTP/1.1 {$this->code}{$msg}", false );
		}

		if ( !empty( $this->contentType ) ) {
			$this->sendHeader( "Content-Type: " . $this->contentType, true );
		}
	}

	/**
	 * @brief redirects to another URL
	 *
	 * @param string $url the URL to redirect to
	 */
	public function redirect( $url ){
		$this->sendHeader( "Location: " . $url, true );
	}

	public function addAsset( $assetName ){
		if ( $this->format == 'html' ) {
			//check if is a configured package
			$app = F::app();
			$assetsManager = F::build( 'AssetsManager', array(), 'getInstance' );
			$type = $app->getAssetsConfig()->getGroupType( $assetName );
			$isGroup = true;

			if ( empty( $type ) ) {
				//single asset
				$isGroup = false;

				//get the resource type from the file extension
				$tokens = explode( '.', $assetName );
				$tokensCount = count( $tokens );

				if ( $tokensCount > 1 ) {
					$extension = strtolower( $tokens[$tokensCount - 1] );

					if( in_array( $extension, $assetsManager->getAllowedAssetExtensions() ) ){
						switch ( $extension ) {
							case 'js':
								$type = AssetsManager::TYPE_JS;
								break;
							case 'css':
								$type = AssetsManager::TYPE_CSS;
								break;
							case 'scss':
								$type = AssetsManager::TYPE_SCSS;
								break;
						}
					}
				}
			}

			//asset type not recognized
			if ( empty( $type ) ) {
				throw new WikiaException( 'Unknown asset type' );
			}

			$sources = array();

			if ( $isGroup ) {
				// Allinone == 0 ? returns array of URLS : returns url string
				$sources =  $assetsManager->getGroupCommonURL( $assetName );
			} else {
				if ( $type == AssetsManager::TYPE_SCSS ) {
					$sources[] =  $assetsManager->getSassCommonURL( $assetName );
				} else {
					$sources[] =  $assetsManager->getOneCommonURL( $assetName );
				}
			}

			foreach($sources as $src){
				switch ( $type ) {
					case AssetsManager::TYPE_CSS:
					case AssetsManager::TYPE_SCSS:
						$app->wg->Out->addStyle( $src );
						break;
					case AssetsManager::TYPE_JS:
						$app->wg->Out->addScript( "<script type=\"{$app->wg->JsMimeType}\" src=\"{$src}\"></script>" );
						break;
				}
			}
		}
	}

	private function isStandardHTTPCode($code){
		return in_array( $code, array(
			100, 101,
			200, 201, 202, 203, 204, 205, 206,
			300, 301, 302, 303, 304, 305, 306, 307,
			401, 402, 403, 404, 405, 406, 407, 408, 409, 410, 411, 412, 413, 414, 415, 416, 417,
			500, 501, 502, 503, 504, 505
		) );
	}

	// @codeCoverageIgnoreStart
	protected function sendHeader( $header, $replace ) {
		header( $header, $replace );
	}
	// @codeCoverageIgnoreEnd

	public function __toString() {
		try {
			return $this->toString();
		} catch( Exception $e ) {
			// php doesn't allow exceptions to be thrown inside __toString() so we need an extra try/catch block here
			$app = F::app();
			$this->setException( $e );
			return $app->getView( 'WikiaError', 'error', array( 'response' => $this, 'devel' => $app->wg->DevelEnvironment ) )->render();
		}
	}

}
