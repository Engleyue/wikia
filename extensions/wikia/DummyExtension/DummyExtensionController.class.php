<?php

class DummyExtensionController extends WikiaController {

	public function helloWorld() {
		if($this->request->getVal('param') == 1 ) {
			$this->response->setTemplatePath( dirname( __FILE__ ) . '/templates/someTemplate.php' );
		}
		$this->response->setVal( 'header', 'Hello World!' );
	}


	public function errorTest() {
		throw new WikiaException( 'Test Exception' );
	}

}