<?php
/**
 * @author ADi
 */
abstract class SDObject {

	abstract public function getName();
	abstract public function getTypeName();
	abstract public function getRendererNames();

	public function render( $context = SD_CONTEXT_DEFAULT ) {
		$rendererFactory = F::build( 'SDElementRendererFactory' );
		$renderer = $rendererFactory->getRenderer( $this, $context );
		return ( !empty( $renderer ) ) ? $renderer->render() : false;
	}

}
