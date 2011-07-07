<?php
class ErrorModule extends Module {

	var $wgBlankImgUrl;
	var $headline;
	var $errors;
	
	public function executeIndex($errors) {
		global $wgBlankImgUrl;
		if (isset($errors['controller'])) unset ($errors['controller']);
		if (isset($errors['method'])) unset ($errors['method']);
		$this->headline = wfMsg('oasis-modal-error-headline');
		$this->errors = $errors;
	}
	
}