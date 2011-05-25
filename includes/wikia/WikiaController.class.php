<?php

/**
 * Nirvana Framework - Controller class
 *
 * @ingroup nirvana
 *
 * @author Adrian 'ADi' Wieczorek <adi(at)wikia-inc.com>
 * @author Owen Davis <owen(at)wikia-inc.com>
 * @author Wojciech Szela <wojtek(at)wikia-inc.com>
 */
abstract class WikiaController {

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
	 * application object
	 * @var WikiaApp
	 */
	protected $app = null;
	/**
	 * global registry object
	 * @var WikiaGlobalRegistry
	 */
	protected $wg = null;
	/**
	 * function wrapper object
	 * @var WikiaFunctionWrapper
	 */
	protected $wf = null;

	//protected $allowedRequests = array( 'help' => array('html', 'json') );

	public function canDispatch( $method, $format ) {
		/*
		if ( !is_array( $this->allowedRequests )
		  || !isset( $this->allowedRequests[$method] )
		  || !is_array( $this->allowedRequests[$method] )
		  || !in_array( $format, $this->allowedRequests[$method] ) ) {
			return false;
		}
		*/

		return true;
	}

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

	/**
	 * get application
	 * @return WikiaApp
	 */
	public function getApp() {
		return $this->app;
	}

	/**
	 * set application
	 * @param WikiaApp $app
	 */
	public function setApp( WikiaApp $app ) {
		$this->app = $app;

		// setting helpers
		$this->wg = $app->wg;
		$this->wf = $app->wf;
	}

	/**
	 * redirects flow to another controller/method
	 *
	 * @param string $controllerName
	 * @param string $methodName
	 * @param bool $resetResponse
	 */
	public function redirect( $controllerName, $methodName, $resetResponse = true ) {
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
	public function sendRequest( $controllerName, $methodName, $params = array() ) {
		return $this->app->sendRequest( $controllerName, $methodName, $params );
	}

	/**
	 * Convenience method for sending requests to the same controller
	 *
	 * @param string $methodName
	 * @param array $params
	 * @return WikiaResponse
	 */
	public function sendSelfRequest( $methodName, $params = array() ) {
		return $this->sendRequest( $this->response->getControllerName(), $methodName, $params );
	}

	/**
	 * Convenience method for getting a value from the request object
	 * @param string $key
	 * @param string $value
	 */

	public function getVal($key, $default = null) {
		return $this->request->getVal($key, $default);
	}

	/**
	 * Convenience method for setting a value on the response object
	 * @param string $key
	 * @param string $value
	 */
	public function setVal($key, $value) {
		$this->response->setVal($key, $value);
	}

	/**
	 * Prints documentation for current controller
	 * @todo implement request/responseParams tags
	 */
	public function help() {
		$reflection = new ReflectionClass($this);
		$methods    = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
		$help       = array();

		foreach ($methods as $index => $method) {
			//if (!isset($this->allowedRequests[$method->name])) {
			//	unset($methods[$index]);
			//} else {
				$comment = $method->getDocComment();
				if ($comment) {
					$comment = substr($comment, 3, -2);
					$comment = preg_replace('~^\s*\*\s*~m', '', $comment);
				}
				$data = array(
					'method' => $method->name,
					'formats' => array( 'html', 'json' ),
					//'formats' => $this->allowedRequests[$method->name],
					'description' => $comment
				);
				$help[] = $data;
			//}
		}

		$this->getResponse()->setVal('class', substr($reflection->name, 0, -10));
		$this->getResponse()->setVal('methods', $help);
		$this->getResponse()->getView()->setTemplatePath( dirname( __FILE__ ) .'/templates/Wikia_help.php' );
	}

	/**
	 * force framework to skip rendering the template
	 */
	public function skipRendering() {
		$this->response->setBody('');
	}

	/**
	 * init function for controller, called just before method
	 */
	public function init() {}
}
