<?php


/**
 * Nirvana Framework - Dispatcher class, this is where all magic happens
 *
 * @ingroup nirvana
 *
 * @author Adrian 'ADi' Wieczorek <adi(at)wikia-inc.com>
 * @author Owen Davis <owen(at)wikia-inc.com>
 * @author Wojciech Szela <wojtek(at)wikia-inc.com>
 */
class WikiaDispatcher {

	const DEFAULT_METHOD_NAME = 'index';

	protected function getMethodName(WikiaRequest $request) {
		return $request->getVal( 'method', self::DEFAULT_METHOD_NAME );
	}

	protected function getControllerName(WikiaRequest $request) {
		return $request->getVal( 'controller' );
	}

	protected function getControllerClassName( $controllerName ) {
		return !empty( $controllerName ) ? ( $controllerName . 'Controller' ) : null;
	}

	/**
	 * dispatch the request
	 *
	 * @param WikiaApp $app
	 * @param WikiaRequest $request
	 * @return WikiaResponse
	 */
	public function dispatch(WikiaApp $app, WikiaRequest $request = null) {
		if (null === $request) {
			$request = F::build( 'WikiaRequest', array( 'params' => ( $_POST + $_GET ) ) );
		}

		$format = $request->getVal('format', 'html');
		$response = F::build( 'WikiaResponse', array( 'format' => $format ) );

		do {
			$request->setDispatched(true);
			try {
				$method = $this->getMethodName( $request );
				$controllerName = $this->getControllerName( $request );
				$controllerClassName = $this->getControllerClassName( $controllerName );
				if( empty($controllerClassName) ) {
					throw new WikiaException( sprintf('Invalid controller name: %s', $controllerName) );
				}

				// Work around for module dispatching until modules are renamed
				if (!class_exists($controllerClassName)) {
					$controllerClassName = $controllerName . "Module";
					$method = ucfirst($method);
					$wgAutoloadClasses = $app->getGlobal( 'wgAutoloadClasses' );
					if( isset( $wgAutoloadClasses[$controllerClassName] ) ) {
						$moduleTemplatePath = dirname($wgAutoloadClasses[$controllerClassName]).'/templates/'.$controllerName.'_'.$method.'.php';
						$response->getView()->setTemplatePath($moduleTemplatePath);
					}
					$method = "execute" . $method;
					$params = $request->getParams();
				}
				$app->runFunction( 'wfProfileIn', ( __METHOD__ . " (" . $controllerName.'_'.$method .")" ) );

				$response->setControllerName($controllerName);
				$response->setMethodName($method);

				if (!class_exists($controllerClassName)) {
					throw new WikiaException( sprintf('Controller does not exists: %s', $controllerClassName) );
				}

				$controller = F::build( $controllerClassName );

				if ( !method_exists($controller, $method) || !is_callable( array($controller, $method) ) ) {
					throw new WikiaException( sprintf('Could not dispatch %s::%s', $controllerClassName, $method) );
				}

				$controller->setRequest($request);
				$controller->setResponse($response);
				$controller->setApp($app);
				$controller->init();

				// BugId:5125 - keep old hooks naming convention
				$originalMethod = ucfirst($this->getMethodName($request));

				if($app->runHook( ( $controllerName . $originalMethod . 'BeforeExecute' ), array( &$controller, &$params ) )) {
					$result = $controller->$method($params);
					if($result === false) {
						// skip template rendering when false returned
						$controller->skipRendering();
					}
				}
				$app->runHook( ( $controllerName . $originalMethod . 'AfterExecute' ), array( &$controller, &$params ) );

			} catch (Exception $e) {
				$app->runFunction( 'wfProfileOut', ( __METHOD__ . " (" . $controllerName.'_'.$method .")" ) );
				// Work around for errors thrown inside modules -- remove when modules go away
				if ($response instanceof Module) {
					$response = F::build( 'WikiaResponse', array( 'format' => $format ) );
				}

				$response->setException($e);

				if ($controllerClassName != 'WikiaErrorController' && $method != 'error') {
					// Work around for module dispatching until modules are renamed
					$response->getView()->setTemplatePath(null);

					$request->setVal('controller', 'WikiaError');
					$request->setVal('method', 'error');
					$request->setDispatched(false);
				}
			}
		} while (!$request->isDispatched());

		if ($request->isInternal() && $response->hasException()) {
			throw $response->getException();
		}

		$app->runFunction( 'wfProfileOut', ( __METHOD__ . " (" . $controllerName.'_'.$method .")" ) );
		return $response;
	}
}
