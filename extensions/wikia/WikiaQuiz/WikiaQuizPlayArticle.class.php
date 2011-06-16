<?php

/**
 * This class is used to render PlayQuiz namespace page
 */

class WikiaQuizPlayArticle extends Article {

	private $mQuiz;

	function __construct($title) {
		parent::__construct($title);

		// quiz object is linked to NS_WIKIA_QUIZ namespace
		$quizTitle = F::build('Title', array($title->getText(), NS_WIKIA_QUIZ), 'newFromText');
		$this->mQuiz = WikiaQuiz::newFromTitle($quizTitle);
		if (!empty($this->mQuiz)) {
			$this->mQuiz->getData();	// lazy load data
		}
	}

	/**
	 * Render PlayQuiz namespace page
	 */
	public function view() {
		global $wgOut, $wgUser, $wgTitle, $wgExtensionsPath, $wgSuppressRail;
		wfProfileIn(__METHOD__);
		
		wfLoadExtensionMessages('WikiaQuiz');

		// let MW handle basic stuff
		parent::view();

		// quiz doesn't exist
		if (empty($this->mQuiz)) {
			wfProfileOut(__METHOD__);
			return;
		}
		
		// suppress rail
		$wgSuppressRail = true;

		// set page title
		$title = $this->mQuiz->getTitle();
		$wgOut->setPageTitle($title);

		// render quiz page
		$wgOut->clearHtml();
		$wgOut->addHtml(wfRenderModule('WikiaQuiz', 'PlayQuiz', array('data'=>$this->mQuiz->getData())));
		$wgOut->addStyle(AssetsManager::getInstance()->getSassCommonURL('extensions/wikia/WikiaQuiz/css/WikiaPlayQuiz.scss'));
		$wgOut->addScript('<script src="'.AssetsManager::getInstance()->getOneCommonURL('skins/common/modernizr-1.7.min.js').'"></script>');
		$wgOut->addScript('<script src="'.$wgExtensionsPath.'/wikia/WikiaQuiz/js/WikiaPlayQuiz.js"></script>');
		
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Purge poll (and articles embedding it) when poll's page is purged
	 */
	public function doPurge() {
		parent::doPurge();

		wfDebug(__METHOD__ . "\n");

		// purge poll's data
		if (!empty($this->mQuiz)) {
			$this->mQuiz->purge();
		}
	}
}
