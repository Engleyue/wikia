<?php

/**
 * Parameter manipulation converting the value to a float.
 * 
 * @since 0.4
 * 
 * @file ParamManipulationFloat.php
 * @ingroup Validator
 * @ingroup ParameterManipulations
 * 
 * @author Jeroen De Dauw
 */
class ParamManipulationFloat extends ItemParameterManipulation {
	
	/**
	 * Constructor.
	 * 
	 * @since 0.4
	 */
	public function __construct() {
		parent::__construct();
	}	
	
	/**
	 * @see ItemParameterManipulation::doManipulation
	 * 
	 * @since 0.4
	 */	
	public function doManipulation( &$value, Parameter $parameter, array &$parameters ) {
		$value = (float)$value;
	}
	
}