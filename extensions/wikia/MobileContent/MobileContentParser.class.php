<?php

class MobileContentParser {
	public static function onParserFirstCallInit( &$parser ) {
		$parser->setHook( 'mobile', 'MobileContentParser::displayContent' );
		$parser->setHook( 'nomobile', 'MobileContentParser::hideContent' );
		return true;
	}

	public static function displayContent( $contents, $attributes, $parser ) {
		$app = F::app();
		$skin = $app->wg->User->getSkin();

		if ( in_array( $skin->getSkinName(), $app->wg->MobileSkins ) ) {
			return $parser->recursiveTagParse( $contents );
		} else {
			return '';
		}
	}

	public static function hideContent( $contents, $attributes, $parser ) {
		$app = F::app();
		$skin = $app->wg->User->getSkin();

		if ( in_array( $skin->getSkinName(), $app->wg->MobileSkins ) ) {
			return '';
		} else {
			return $parser->recursiveTagParse( $contents );
		}
	}
}
