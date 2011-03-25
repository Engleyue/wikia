<?php

/**
 * This class is used to render Poll namespace page
 */

class WikiaPollArticle extends Article {

	private $mPoll;

	function __construct($title) {
		parent::__construct($title);

		$this->mPoll = WikiaPoll::newFromArticle($this);
	}

	/**
	 * Render Poll namespace page
	 */
	public function view() {
		global $wgOut, $wgTitle, $wgJsMimeType, $wgExtensionsPath;
		wfProfileIn(__METHOD__);

		wfLoadExtensionMessages('WikiaPoll');

		// let MW handle basic stuff
		parent::view();

		// poll doesn't exist
		if (!$wgTitle->exists() || empty($this->mPoll)) {
			wfProfileOut(__METHOD__);
			return;
		}

		// set page title
		$question = wfMsg('wikiapoll-question', $this->mPoll->getTitle());
		$wgOut->setPageTitle($question);

		// add CSS/JS
		$wgOut->addStyle(F::app()->getAssetsManager()->getSassCommonURL('extensions/wikia/WikiaPoll/css/WikiaPoll.scss'));
		$wgOut->addScript("<script type=\"$wgJsMimeType\" src=\"$wgExtensionsPath/wikia/WikiaPoll/js/WikiaPoll.js\"></script>");

		// render poll page
		$wgOut->clearHTML();
		$wgOut->addHTML($this->mPoll->render());

		wfProfileOut(__METHOD__);
	}

	/**
	 * Purge poll (and articles embedding it) when poll's page is purged
	 */
	public function doPurge() {
		parent::doPurge();

		wfDebug(__METHOD__ . "\n");

		// purge poll's data
		if (!empty($this->mPoll)) {
			$this->mPoll->purge();
		}
	}
}
