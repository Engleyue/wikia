<?php

/** @defgroup nirvana Nirvana
 *  The Nirvana Framework
 */

/**
 * Nirvana Framework - Application class
 *
 * @ingroup nirvana
 *
 * @author Adrian 'ADi' Wieczorek <adi(at)wikia-inc.com>
 * @author Owen Davis <owen(at)wikia-inc.com>
 * @author Wojciech Szela <wojtek(at)wikia-inc.com>
 */
class WikiaApp {

	/**
	 * localRegistry
	 * @var WikiaLocalRegistry
	 */
	private $localRegistry = null;
	/**
	 * hook dispatcher
	 * @var WikiaHookDispatcher
	 */
	private $hookDispatcher = null;
	/**
	 * dispatcher
	 * @var WikiaDispatcher
	 */
	private $dispatcher = null;
	/**
	 * global MW variables helper accessor
	 * @var WikiaGlobalRegistry
	 */
	public $wg = null;
	/**
	 * global MW functions helper accessor
	 * @var WikiaFunctionWrapper
	 */
	public $wf = null;

	/**
	 * constructor
	 * @param WikiaGlobalRegistry $globalRegistry
	 * @param WikiaLocalRegistry $localRegistry
	 * @param WikiaHookDispatcher $hookDispatcher
	 */
	public function __construct(WikiaGlobalRegistry $globalRegistry = null, WikiaLocalRegistry $localRegistry = null, WikiaHookDispatcher $hookDispatcher = null, WikiaFunctionWrapper $functionWrapper = null) {

		if(is_null($globalRegistry)) {
			$globalRegistry = F::build('WikiaGlobalRegistry');
		}
		if(is_null($localRegistry)) {
			F::setInstance('WikiaLocalRegistry', new WikiaLocalRegistry());
			$localRegistry = F::build('WikiaLocalRegistry');
		}
		if(is_null($hookDispatcher)) {
			F::setInstance('WikiaHookDispatcher', new WikiaHookDispatcher());
			$hookDispatcher = F::build( 'WikiaHookDispatcher' );
		}
		if(is_null($functionWrapper)) {
			$functionWrapper = F::build( 'WikiaFunctionWrapper' );
		}

		$this->localRegistry = $localRegistry;
		$this->hookDispatcher = $hookDispatcher;

		// set helper accessors
		$this->wg = $globalRegistry;
		$this->wf = $functionWrapper;

		// register ajax dispatcher
		$this->wg->append('wgAjaxExportList', 'WikiaApp::ajax');
	}
	
	/**
	 * checks if an reference or a string refer to a WikiaService instance
	 * 
	 * @param mixed $controllerName a string with the name of the class or a reference to an object
	 */
	public function isService( $controllerName ) {
		return ( is_object( $controllerName ) ) ? is_a( $controllerName, 'WikiaService' ) : ( ( strrpos( $controllerName, 'Service' ) === ( strlen( $controllerName ) - 7 ) ) );
	}
	
	/**
	 * checks if an reference or a string refer to a WikiaController instance
	 * 
	 * @param mixed $controllerName a string with the name of the class or a reference to an object
	 */
	public function isController( $controllerName ) {
		return ( is_object( $controllerName ) ) ? is_a( $controllerName, 'WikiaController' ) : ( ( strrpos( $controllerName, 'Controller' ) === ( strlen( $controllerName ) - 10 ) ) );
	}
	
	/**
	 * @deprecated
	 * 
	 * checks if an reference or a string refer to a Module instance, this method exists only for supporting legacy code
	 * 
	 * @param mixed $controllerName a string with the name of the class or a reference to an object
	 */
	public function isModule( $controllerName ) {
		return ( is_object( $controllerName ) ) ? is_a( $controllerName, 'Module' ) : ( ( strrpos( $controllerName, 'Module' ) === ( strlen( $controllerName ) - 6 ) ) );
	}
	
	/**
	 * @deprecated
	 * 
	 * returns a partial name for a WikiaController or a Module subclass name to use in dispatching requests and loading templates,
	 * this method exists only for supporting legacy code
	 * 
	 * @param mixed $controllerName a string with the name of the class or a reference to an object
	 */
	public function getControllerLegacyName( $controllerName ) {
		if ( is_object( $controllerName ) ) {
			$controllerName = get_class( $controllerName );
		}
		
		if ( $this->isController( $controllerName ) ) {
			return substr( $controllerName, 0, strlen( $controllerName ) - 10 );
		} elseif ( $this->isModule( $controllerName ) ) {
			return substr( $controllerName, 0, strlen( $controllerName ) - 6 );
		} else {
			return $controllerName;
		}
	}

	/**
	 * get hook dispatcher
	 * @return WikiaHookDispatcher
	 */
	public function getHookDispatcher() {
		return $this->hookDispatcher;
	}

	/**
	 * set MediaWiki registry (global)
	 * @param WikiaGlobalRegistry $globalRegistry
	 */
	public function setGlobalRegistry(WikiaGlobalRegistry $globalRegistry) {
		$this->wg = $globalRegistry;
	}

	/**
	 * get MediaWiki registry (global)
	 * @return WikiaGlobalRegistry
	 */
	public function getGlobalRegistry() {
		return $this->wg;
	}

	/**
	 * set Wikia registry (local)
	 * @param WikiaLocalRegistry $localRegistry
	 */
	public function setLocalRegistry(WikiaLocalRegistry $localRegistry) {
		$this->localRegistry = $localRegistry;
	}

	/**
	 * get Wikia registry (local)
	 * @return WikiaLocalRegistry
	 */
	public function getLocalRegistry() {
		return $this->localRegistry;
	}

	/**
	 * get global function wrapper
	 * @return WikiaFunctionWrapper
	 */
	public function getFunctionWrapper() {
		return $this->wf;
	}

	/**
	 * set global function wrapper
	 * @param WikiaFunctionWrapper $functionWrapeper
	 */
	public function setFunctionWrapper(WikiaFunctionWrapper $functionWrapper) {
		$this->wf = $functionWrapper;
	}

	/**
	 * get dispatcher object
	 * @return WikiaDispatcher
	 */
	public function getDispatcher() {
		if( $this->dispatcher == null ) {
			$this->dispatcher = F::build( 'WikiaDispatcher' );
		}
		return $this->dispatcher;
	}

	/**
	 * set dispatcher object
	 * @param WikiaDispatcher $dispatcher
	 */
	public function setDispatcher(WikiaDispatcher $dispatcher) {
		$this->dispatcher = $dispatcher;
	}

	/**
	 * register hook (alias: WikiaHookDispatcher::registerHook)
	 * @param string $hookName
	 * @param string $className
	 * @param string $methodName
	 * @param array $options
	 * @param bool $alwaysRebuild
	 */
	public function registerHook( $hookName, $className, $methodName, Array $options = array(), $alwaysRebuild = false, $object = null ) {
		$this->wg->append( 'wgHooks', $this->hookDispatcher->registerHook( $className, $methodName, $options, $alwaysRebuild, $object ), $hookName );
	}

	/**
	 * register class
	 * @param mixed $className the name of the class or a list of classes contained in the same file passed as an array
	 * @param string $filePath
	 */
	public function registerClass($className, $filePath) {
		//checking if $className is an array should be faster than creating a 1 element array and then use the same foreach loop
		if ( is_array( $className ) ) {
			foreach ( $className  as $cls ) {
				$this->wg->set( 'wgAutoloadClasses', $filePath, $cls );
			}
		} else {
			$this->wg->set( 'wgAutoloadClasses', $filePath, $className );
		}
	}

	/**
	 * register extension init function
	 * @param string $functionName
	 */
	public function registerExtensionFunction( $functionName ) {
		$this->wg->append( 'wgExtensionFunctions', $functionName );
	}

	/**
	 * register extension message file
	 * @param string $name
	 * @param string $filePath
	 */
	public function registerExtensionMessageFile( $name, $filePath ) {
		$this->wg->set( 'wgExtensionMessagesFiles', $filePath, $name );
	}

	/**
	 * register extension alias file
	 * @param string $name
	 * @param string $filePath
	 */
	public function registerExtensionAliasFile( $name, $filePath ) {
		$this->wg->set( 'wgExtensionAliasesFiles', $filePath, $name );
	}

	/**
	 * register special page
	 * @param string $name special page name
	 * @param string $className class name
	 * @param string $group special page group
	 */
	public function registerSpecialPage( $name, $className, $group = null ) {
		$this->wg->set( 'wgSpecialPages', $className, $name );
		
		if( !empty( $group ) ) {
			$this->wg->set( 'wgSpecialPageGroups', $group, $name );
		}
	}
	
	/**
	 * get global variable (alias: WikiaGlobalRegistry::get(var,'mediawiki'))
	 * @param string $globalVarName
	 */
	public function getGlobal( $globalVarName ) {
		return $this->wg->get( $globalVarName );
	}

	/**
	 * set global variable (alias: WikiaGlobalRegistry::set(var, value, key))
	 * @param string $globalVarName variable name
	 * @param mixed $value value
	 * @param string $key key (optional)
	 */
	public function setGlobal( $globalVarName, $value, $key = null ) {
		return $this->wg->set( $globalVarName, $value, $key );
	}

	/**
	 * get array of globals
	 *
	 * how to use:
	 *  list( $wgTitle, $wgUser ) = $app->getGlobals( 'wgTitle', 'wgUser' );
	 *
	 * @param list of global's names, comma separated
	 * @return array
	 */
	public function getGlobals() {
		$globals = array();
		$funcArgs = func_get_args();

		foreach( $funcArgs as $globalName ) {
			$globals[] = $this->getGlobal( $globalName );
		}

		return $globals;
	}

	/**
	 * Prepares and sends a request to a Controller
	 *
	 * @param $controllerName string the name of the controller, without the 'Controller' or 'Model' suffix
	 * @param $methodName string the name of the Controller method to call
	 * @param $params array an array with the parameters to pass to the specified method
	 * @param $internal boolean wheter it's an internal (PHP to PHP) or external request
	 *
	 * @return WikiaResponse a response object with the data produced by the method call
	 */
	public function sendRequest( $controllerName = null, $methodName = null, $params = array(), $internal = true ) {
		$values = array();
		
		if( !empty( $controllerName ) ) {
			$values['controller'] = $controllerName;
		}
		
		if( !empty( $methodName ) ) {
			$values['method'] = $methodName;
		}
		
		$params = array_merge( (array) $params, $values );
		
		if ( empty( $methodName ) || empty( $controllerName ) ) {
			$params = array_merge( $params, $_POST, $_GET );
		}
		
		$request = new WikiaRequest( $params );

		$request->setInternal( $internal );

		return $this->getDispatcher()->dispatch( $this, $request );
	}

	/**
	 * simple global function wrapper (most likely it won't work for references)
	 *
	 * @param string $funcName global function name
	 * @param mixed $arg1 - $argN function arguments
	 * @experimental
	 */
	public function runFunction() {
		$funcArgs = func_get_args();
		$funcName = array_shift( $funcArgs );
		return $this->wf->run( $funcName, $funcArgs );
	}

	/**
	 * simple wfRunHooks wrapper
	 *
	 * @param string $hookName The name of the hook to run
	 * @param array $params An array of the params to pass in the hook call
	 */
	public function runHook( $hookName, $parameters ) {
		return wfRunHooks( $hookName, $parameters );
	}

	/**
	 * get view Object for given controller and method (previously wfRenderPartial)
	 * @param string $controllerName
	 * @param string $method
	 * @param array $data
	 */
	public function getView( $controllerName, $method, Array $data = array() ) {
		return F::build( 'WikiaView', array( $controllerName, $method, $data ), 'newFromControllerAndMethodName' );
	}

	/**
	 * Helper function to get output as HTML for controller and method (previously wfRenderModule)
	 * @param string $name
	 * @param string $action
	 * @param array $params
	 * @return string
	 */
	public function renderView( $name, $action, Array $params = null ) {
		$response = $this->sendRequest( $name, $action, $params, false );
		return $response->toString();
	}

	/**
	 * @todo: take a look here, consider removing
	 */
	public static function ajax() {
		return F::app()->sendRequest( null, null, null, false );
	}
}