<?php

class SpecialWikiBuilder extends UnlistedSpecialPage {

	public function __construct() {
		wfLoadExtensionMessages('WikiBuilder');
		parent::__construct('WikiBuilder', 'wikibuilder');
	}

	public function execute() {
		global $wgOut, $wgExtensionsPath, $wgUser;
		wfProfileIn( __METHOD__ );

		// TODO: check user rights
		if ( !$wgUser->isAllowed( 'wikibuilder' ) ) {
			$this->displayRestrictionError();
			wfProfileOut( __METHOD__ );
			return;
		}

		$wgOut->addHtml(wfRenderModule('WikiBuilder'));
		$wgOut->addStyle(wfGetSassUrl('extensions/wikia/WikiBuilder/css/WikiBuilder.scss'));
		$wgOut->addScript('<script src="'.$wgExtensionsPath.'/wikia/JavascriptAPI/Mediawiki.js"></script>');
		$wgOut->addScript('<script src="'.$wgExtensionsPath.'/wikia/ThemeDesigner/js/ThemeDesigner.js"></script>');
		$wgOut->addScript('<script src="'.$wgExtensionsPath.'/wikia/WikiBuilder/js/WikiBuilder.js"></script>');

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Checks if WikiPayment is enabled and handles fetching PayPal token - if disabled, displays error message
	 *
	 * @author Maciej Błaszkowski <marooned at wikia-inc.com>
	 */
	static function upgradeToPlus() {
		wfProfileIn( __METHOD__ );

		if (method_exists('SpecialWikiPayment', 'fetchPaypalToken')) {
			wfProfileIn(__METHOD__);

			$data = SpecialWikiPayment::fetchPaypalToken();
			if (empty($data['url'])) {
				$result = array(
					'status' => 'error',
					'caption' => wfMsg('owb-step4-error-caption'),
					'content' => wfMsg('owb-step4-error-token-content')
				);
			} else {
				$result = array(
					'status' => 'ok',
					'data' => $data
				);
			}
		} else {
			wfLoadExtensionMessages('WikiBuilder');
			$result = array(
				'status' => 'error',
				'caption' => wfMsg('owb-step4-error-caption'),
				'content' => wfMsg('owb-step4-error-upgrade-content')
			);
		}

		$json = Wikia::json_encode( $result );
		$response = new AjaxResponse( $json );
		$response->setContentType( 'application/json; charset=utf-8' );

		wfProfileOut( __METHOD__ );
		return $response;
	}
}