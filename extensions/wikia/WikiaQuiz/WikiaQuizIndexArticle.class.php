<?php

/**
 * This class is used to render Quiz namespace page
 */

class WikiaQuizIndexArticle extends Article {

	private $mQuiz;

	function __construct($title) {
		parent::__construct($title);

		$this->mQuiz = WikiaQuiz::newFromArticle($this);
	}

	/**
	 * Render Quiz namespace page
	 */
	public function view() {
		global $wgOut, $wgUser, $wgTitle, $wgJsMimeType, $wgExtensionsPath;
		wfProfileIn(__METHOD__);
		
		wfLoadExtensionMessages('WikiaQuiz');

		// let MW handle basic stuff
		parent::view();

		// quiz doesn't exist
		if (!$wgTitle->exists() || empty($this->mQuiz)) {
			wfProfileOut(__METHOD__);
			return;
		}

		// set page title
		$title = $this->mQuiz->getTitle();
		$wgOut->setPageTitle($title);

		// add CSS/JS
		$wgOut->addStyle(AssetsManager::getInstance()->getSassCommonURL('extensions/wikia/WikiaQuiz/css/WikiaQuizBuilder.scss'));

		// render quiz page
		$wgOut->clearHTML();
		$wgOut->addHTML($this->mQuiz->render());

		wfProfileOut(__METHOD__);
	}

	/**
	 * Purge quiz (and articles embedding it) when quiz's page is purged
	 */
	public function doPurge() {
		parent::doPurge();

		wfDebug(__METHOD__ . "\n");

		// purge quiz's data
		if (!empty($this->mQuiz)) {
			$this->mQuiz->purge();
		}
		
		// purge QuizPlay article
		$quizPlayTitle = F::build('Title', array($this->getTitle()->getText(), NS_WIKIA_PLAYQUIZ), 'newFromText');
		$quizPlayArticle = F::build('Article', array($quizPlayTitle));
		$quizPlayArticle->doPurge();
	}
}
