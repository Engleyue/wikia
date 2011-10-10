<?php

/**
 * Wiki Features Special Page
 * @author Hyun
 * @author Owen
 * @author Saipetch
 */
class WikiFeaturesSpecialController extends WikiaSpecialPageController {

	public function __construct() {
		wfLoadExtensionMessages('WikiFeatures');
		parent::__construct('WikiFeatures', '', false);
	}
	
	public function init() {
		
	}
	
	public function index() {
		$this->wg->Out->setPageTitle(wfMsg('wikifeatures-title'));
		if (!$this->wg->User->isLoggedIn()) {	// show this feature to logged in users only regardless of their rights
			$this->displayRestrictionError();
			return false;  // skip rendering
		}
		
		$skinName = $this->wg->User->getSkin()->getSkinName();
		$this->isOasis = ($skinName === 'oasis');
		if($skinName !== 'oasis') {
			$this->forward('WikiFeaturesSpecial', 'notOasis');
			return;
		}
		
		$this->response->addAsset('extensions/wikia/WikiFeatures/css/WikiFeatures.scss');
		$this->response->addAsset('extensions/wikia/WikiFeatures/js/modernizr.transform.js');
		$this->response->addAsset('extensions/wikia/WikiFeatures/js/WikiFeatures.js');
		
		if($this->getVal('simulateNewLabs', false)) { // debug code
			WikiFeaturesHelper::$release_date = array (
				'wgEnableChat' => '2032-09-01'
			);
		}
		
		$this->features = WikiFeaturesHelper::getInstance()->getFeatureNormal();
		$this->labsFeatures = WikiFeaturesHelper::getInstance()->getFeatureLabs();
		
		$this->editable = ($this->wg->User->isAllowed('wikifeatures')) ? true : false ;	// only those with rights can make edits
		
		if($this->getVal('simulateEmptyLabs', false)) {	// debug code
			$this->labsFeatures = array();
		}
	}
	
	public function notOasis() {
		// empty method
	}

	/**
	 * @desc enable/disable feature
	 * @requestParam string enabled [true/false]
	 * @requestParam string feature	(extension variable)
	 * @responseParam string result [OK/error]
	 * @responseParam string error (error message)
	 */
	public function toggleFeature() {
		$enabled = $this->getVal('enabled', null);
		$feature = $this->getVal('feature', null);
		
		// check user permission
		if(!$this->wg->User->isAllowed( 'wikifeatures' )) {
			$this->setVal('result', 'error');
			$this->setVal('error', $this->wf->Msg('wikifeatures-error-permission'));
			return;
		}

		// check if feature given is actually something we allow setting
		if ( !in_array( $feature, $this->wg->WikiFeatures['normal'] ) && !in_array( $feature, $this->wg->WikiFeatures['labs'] ) ) {
			$this->setVal('result', 'error');
			$this->setVal('error', $this->wf->Msg('wikifeatures-error-invalid-parameter'));
			return;
		}

		// validate feature
		$wg_value = WikiFactory::getVarByName($feature, $this->wg->CityId);
		if (($enabled != 'true' && $enabled != 'false') || empty($feature) || empty($wg_value)) {
			$this->setVal('result', 'error');
			$this->setVal('error', $this->wf->Msg('wikifeatures-error-invalid-parameter'));
			return;
		}
		
		$enabled = ($enabled=='true');
		
		$logMsg = "set extension option: $feature = ".var_export($enabled, TRUE);
		$log = WF::build( 'LogPage', array( 'wikifeatures' ) );
		$log->addEntry( 'wikifeatures', SpecialPage::getTitleFor('WikiFeatures'), $logMsg, array() );
		WikiFactory::setVarByName($feature, $this->wg->CityId, $enabled, "WikiFeatures");
		
		if ($feature == 'wgShowTopListsInCreatePage')
			WikiFactory::setVarByName('wgEnableTopListsExt', $this->wg->CityId, $enabled, "WikiFeatures");
		
		// clear cache for active wikis
        WikiFactory::clearCache( $this->wg->CityId );		
		$this->wg->Memc->delete(WikiFeaturesHelper::getInstance()->getMemcKeyNumActiveWikis($feature));
			
		$this->setVal('result', 'ok');
	}

/**
 * Save a fogbugz ticket
 * @requestParam type $category
 * @requestParam type $message
 * @responseParam string result [OK/error]
 * @responseParam string error (error message)
 */
	
	public function saveFeedback() {
		
		$user = $this->wg->User;
		$feature = $this->getVal('feature');
		$category = $this->getVal('category');
		$message = $this->getVal('message');
	
		if( !$user->isLoggedIn() ) {
			$this->result = 'error';
			$this->error = $this->wf->Msg('wikifeatures-error-permission');
		}
		
		// TODO: validate feature_id
		if ( !array_key_exists($feature, WikiFeaturesHelper::$feedbackAreaIDs) ) {
			$this->result = 'error';
			$this->error = $this->wf->Msg('wikifeatures-error-invalid-parameter', 'feature');
		} else if ( !array_key_exists($category, WikiFeaturesHelper::$feedbackCategories) || $category == 0) {
			$this->result = 'error';
			$this->error = $this->wf->Msg('wikifeatures-error-invalid-category');
		} else if ( !$message || strlen($message) < 10 || strlen($message) > 1000 ) {
			$this->result = 'error';
			$this->error = $this->wf->Msg('wikifeatures-error-message');
		} else if( WikiFeaturesHelper::getInstance()->isSpam($user->getName(), $feature) ) {
			$this->result = 'error';
			$this->error = $this->wf->Msg('wikifeatures-error-spam-attempt');
		}

		// Passed validations, actually do something useful
		if( is_null($this->error) ) {
			$this->result = 'ok';
			$bugzdata = WikiFeaturesHelper::getInstance()->saveFeedbackInFogbugz( $feature, $user->getEmail(), $user->getName(), $message, $category );
			$this->caseId = $bugzdata['caseId'];
			$this->msg = wfMsg('wikifeatures-feedback-success');
		}
	}
	
}
