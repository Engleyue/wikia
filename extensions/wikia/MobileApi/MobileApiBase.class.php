<?php
/*
 * Base module, all the Mobile API modules must extend this class
 * @author Federico "Lox" Lucignano
 */
class MobileApiBase {
	const DEFAULT_STATUS_CODE = '200 OK';
	const DEFAULT_CONTENT_TYPE = 'text/html; charset=utf8';
	
	private $mResponseContent = null;
	private $mResponseContentType = 'text/html; charset=utf8';
	private $mResponseStatusCode = '200 OK';
	private $mRequest = null;
	
	function __construct( WebRequest $request ){
		$this->mRequest = $request;
	}
	
	public function getResponseContent(){
		return $this->mResponseContent;
	}
	
	protected function setResponseContent( $text = null ){
		$this->mResponseContent = $text;
	}
	
	public function getResponseContentType(){
		return $this->mResponseContentType;
	}
	
	protected function setResponseContentType( $type = self::DEFAULT_CONTENT_TYPE ){
		$this->mResponseContentType = $type;
	}
	
	public function getResponseStatusCode(){
		return $this->mResponseStatusCode;
	}
	
	protected function setResponseStatusCode( $code = self::DEFAULT_STATUS_CODE ){
		$this->mResponseStatusCode = $code;
	}
	
	protected function getRequest(){
		return $this->mRequest;
	}
}