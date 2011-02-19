<?php

class WikiaPollModule extends Module {

	var $data;
	var $embedded;
	var $poll;
	var $wgBlankImgUrl;
	var $wgLang;

	/**
	 * Render HTML Poll namespace pages
	 */
	public function executeIndex($params) {
		if (!empty($params['poll'])) {
			$this->poll = $params['poll'];
			$this->data = $this->poll->getData();

			$this->formatResults();
		}

		$this->embedded = !empty($params['embedded']);
	}

	public function executeSpecialPage() {

	}

	public function executeSpecialPageEdit($params) {
		$title = Title::newFromText ($params['title'], NS_WIKIA_POLL) ;

		if (is_object($title) && $title->exists()) {
			$this->poll = WikiaPoll::NewFromTitle($title);
			$this->data = $this->poll->getData();
		}
	}

	/**
	 * Calculate percantage for votes and scale bars
	 */
	private function formatResults() {		
		// format results
		foreach($this->data['answers'] as &$answer) {
			if ($this->data['votes'] > 0) {
				$answer['percentage'] = round($answer['votes'] / $this->data['votes'] * 100);
			}
			else {
				$answer['percentage'] = 0;
			}
		}
	}
		
}
