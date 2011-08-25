<?php

/**
 * Nirvana Framework - Dispatchable Object class
 * This class adds Request / Response vars and a sendRequest method to WikiaObject
 *
 * @ingroup nirvana
 *
 * @author Federico "Lox" Lucignano <federico(at)wikia-inc.com>
 * @author Owen Davis <owen(at)wikia-inc.com>
 */
abstract class WikiaDispatchableObject extends WikiaObject {
	/**
	 * request object
	 * @var WikiaRequest
	 */
	protected $request = null;
	
	/**
	 * response object
	 * @var WikiaResponse
	 */
	protected $response = null;
	
	/**
	 * wether the class accepts external requests
	 * @return boolean
	 */
	abstract public function allowsExternalRequests();

	/**
	 * Forwards application flow to another controller/method
	 *
	 * @param string $controllerName
	 * @param string $methodName
	 * @param bool $resetResponse
	 */
	protected function forward( $controllerName, $methodName, $resetResponse = true ) {
		if( $resetResponse ) {
			$this->response->resetData();
		}

		$this->request->setVal( 'controller', $controllerName );
		$this->request->setVal( 'method', $methodName );
		$this->request->setDispatched(false);
	}

	/**
	 * send request to another controller/method
	 *
	 * @param string $controllerName
	 * @param string $methodName
	 * @param array $params
	 * @return WikiaResponse
	 */
	protected function sendRequest( $controllerName, $methodName, $params = array() ) {
		return $this->app->sendRequest( $controllerName, $methodName, $params );
	}

	/**
	 * Convenience method for sending requests to the same controller
	 *
	 * @param string $methodName
	 * @param array $params
	 * @return WikiaResponse
	 */
	protected function sendSelfRequest( $methodName, $params = array() ) {
		return $this->sendRequest( $this->response->getControllerName(), $methodName, $params );
	}

	/**
	 * Convenience method for getting a value from the request object
	 * @param string $key
	 * @param string $value
	 */

	protected function getVal($key, $default = null) {
		return $this->request->getVal($key, $default);
	}

	/**
	 * Convenience method for setting a value on the response object
	 * @param string $key
	 * @param string $value
	 */
	protected function setVal($key, $value) {
		$this->response->setVal($key, $value);
	}

	/**
	 * force framework to skip rendering the template
	 */
	public function skipRendering() {
		$this->response->setBody('');
	}

	/**
	 * init function for controller, called just before sendRequest method dispatching
	 */
	public function init() {}

	/**
	 * set request
	 * @param WikiaRequest $request
	 */
	public function setRequest(WikiaRequest $request) {
		$this->request = $request;
	}

	/**
	 * get request
	 * @return WikiaRequest
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * set response
	 * @param WikiaResponse $response
	 */
	public function setResponse(WikiaResponse $response) {
		$this->response = $response;
	}

	/**
	 * get response
	 * @return WikiaResponse
	 */
	public function getResponse() {
		return $this->response;
	}
	
	// Magic setting of template variables so we don't have to do $this->response->setVal
	// NOTE: This is the opposite behavior of the Oasis Module
	// In a module, a public member variable goes to the template
	// In a controller, a public member variable does NOT go to the template, it's a local var
	
	public function __set($propertyName, $value) {
		if (property_exists($this, $propertyName)) {
			$this->$propertyName = $value;
		} else {
			$this->response->setVal( $propertyName, $value );
		}
	}
	
	public function __get($propertyName) {
		if (property_exists($this, $propertyName)) {
			return $this->$propertyName;
		} else {
			return $this->response->getVal( $propertyName );
		}
	}
}