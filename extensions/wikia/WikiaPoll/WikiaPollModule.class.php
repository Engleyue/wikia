<?php

class WikiaPollModule extends Module {

	var $data;
	var $embedded;
	var $poll;
	var $wgBlankImgUrl;

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

	public function executeSpecialPage() {}

	/**
	 * Calculate percantage for votes and scale bars
	 */
	private function formatResults() {
		// minimum and maximum width of bar (in %)
		$minWidth = 10;
		$maxWidth = 100;

		// find most and least favorite answer
		$maxVotes = 0;
		$minVotes = $this->data['votes'];

		foreach($this->data['answers'] as &$answer) {
			$maxVotes = max($maxVotes, $answer['votes']);
			$minVotes = min($minVotes, $answer['votes']);
		}

		// format results
		foreach($this->data['answers'] as &$answer) {
			if ($this->data['votes'] > 0) {
				// scale results bar (minWidth% = minVotes / maxVotes = maxWidth%)
				$answer['bar-width'] = $minWidth + round( (($answer['votes'] - $minVotes) / ($maxVotes - $minVotes)) * ($maxWidth - $minWidth));
				$answer['percentage'] = round($answer['votes'] / $this->data['votes'] * 100);
			}
			else {
				$answer['bar-width'] = $minWidth;
				$answer['percentage'] = 0;
			}
		}
	}
		
}
