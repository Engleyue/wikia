<?php
/**
 * Controller for InWikiGame extension
 *
 * @author Andrzej 'nAndy' Łukaszewski
 * @author Marcin Maciejewski
 * @author Sebastian Marzjan
 */
class InWikiGameController extends WikiaController {

	public function executeIndex() {
		$this->gameId = $this->getVal('inWikiGameId', 1);
		$this->jsSnippet = F::build('JSSnippets')->addToStack(
			array('/extensions/wikia/InWikiGame/js/InWikiGame.js', '/extensions/wikia/InWikiGame/css/InWikiGame.scss'),
			null,
			'InWikiGame.init',
			array(
				'id' => 'InWikiGameWrapper-' . $this->gameId
			)
		);
	}
}
