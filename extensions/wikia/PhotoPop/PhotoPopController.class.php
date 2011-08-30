<?php
/**
 * @author Sean Colombo
 *
 * Displays the HTML5 + Canvas mobile game which will be inside of a Titanium app. Currently,
 * we're not sure how stand-alone the canvas part will be and how much it will communicate with the
 * Titanium code.
 *
 * @file
 * @ingroup SpecialPage
 */

if ( !defined( 'MEDIAWIKI' ) ) die();

/**
 * The entry point for the HTML/Canvas page containing the game.
 *
 * @ingroup SpecialPage
 */
class PhotoPopController extends WikiaController {
	var $DEFAULT_WIDTH = 480;
	var $DEFAULT_HEIGHT = 320;

	/**
	 * Like all WikiaController subclasses, this is called before any time that other endpoints are triggered.
	 */
	public function init(){
		wfLoadExtensionMessages( 'PhotoPop' );
	} // end init()

	/**
	 * Endpoint for the first screen that the player will see.  Essentially serves as a menu.
	 */
	public function homeScreen(){
		wfProfileIn( __METHOD__ );
		$this->boardWidth = $this->getVal('width', $this->DEFAULT_WIDTH);
		$this->boardHeight = $this->getVal('height', $this->DEFAULT_HEIGHT);

		// TODO: IMPLEMENT
		
		// TODO: IMPLEMENT

	} // end homeScreen()

	/**
	 * Shows a screen which lets the player choose from a number of configured games.  Each game
	 * will be served from the wiki on which the game is played regardless of where this selector screen is shown.
	 */
	public function selectorScreen(){
		$this->boardWidth = $this->getVal('width', $this->DEFAULT_WIDTH);
		$this->boardHeight = $this->getVal('height', $this->DEFAULT_HEIGHT);
		// TODO: IMPLEMENT
		// TODO: IMPLEMENT

	} // end selectorScreen()

	/**
	 * Since the canvas approach was very slow on mobile devices, this method was
	 * created to return a quick experimental version which uses just HTML and CSS
	 * instead of canvas.
	 */
	 public function playGame(){
		global $wgOut, $wgExtensionsPath, $wgStyleVersion, $wgRequest, $wgScriptPath;
		wfProfileIn( __METHOD__ );

		$this->boardWidth = $this->getVal('width', $this->DEFAULT_WIDTH);
		$this->boardHeight = $this->getVal('height', $this->DEFAULT_HEIGHT);
		$this->numRows = 4;
		$this->numCols = 6;
		$this->tileWidth = ceil($this->boardWidth / $this->numCols); // the tiles at the very bottom and right of the board may be slightly too large. their extra pixels will just fall outside of the visible board and not be seen.
		$this->tileHeight = ceil($this->boardHeight / $this->numRows);

		$this->photosPerGame = 10;

// TODO: FIGURE OUT HOW TO DO THIS BETTER.  Use URL params? Have it in a predictable image name on each wiki?
		$this->frontImageSrc = $wgExtensionsPath.'/wikia/PhotoPop/watermark_glee.png'; // this shows up immediately

		$this->backImageSrc = ''; // this is the one that's obscured... will be figured out in JS using the API.
		$this->answerButtonSrc_toOpen = $wgExtensionsPath.'/wikia/PhotoPop/answer-button-to-open.png';
		$this->answerButtonSrc_toClose = $wgExtensionsPath.'/wikia/PhotoPop/answer-button-to-close.png';
		$this->answerButtonWidth = 40;
		$this->continueButtonWidth = 48;
		$this->answerDrawerWidth = 150;
		$this->answerDrawerHeight = 250;
		$this->continueButtonSrc = $wgExtensionsPath.'/wikia/PhotoPop/continue-button.png';

		// Settings for the end-game screen.
		$this->endGame_overlayWidth = 300;
		$this->endGame_overlayHeight = 150; // this is the height of just the green portion, not counting the high-score flag on top
		$this->endGame_highScoreHeight = 24;
		$this->endGameButtonSize = 54;
		$this->endGame_playAgainSrc = $wgExtensionsPath.'/wikia/PhotoPop/end_replay.png';
		$this->endGame_goHomeSrc = $wgExtensionsPath.'/wikia/PhotoPop/end_home.png';
		$this->endGame_goToHighScoresSrc = $wgExtensionsPath.'/wikia/PhotoPop/end_scores.png';
		$this->url_goHome = "$wgScriptPath/wikia.php?controller=PhotoPop&method=homeScreen";

		$this->mwJsApiUrl = $wgExtensionsPath."/wikia/JavascriptAPI/Mediawiki.js?$wgStyleVersion";
		$this->gameJs_FlipBoard = $wgExtensionsPath."/wikia/PhotoPop/js/PhotoPop_FlipBoard.js?$wgStyleVersion";
		$this->gameJs = $wgExtensionsPath."/wikia/PhotoPop/js/PhotoPop.js?$wgStyleVersion";
		$this->jsMessagesUrl = $wgExtensionsPath."/wikia/JSMessages/js/JSMessages.js?$wgStyleVersion";
		$this->category = $wgRequest->getVal('category', '');

		// For <title> tag.  Site name and name of category (without the prefix).
		$catTitle = Title::newFromText($this->category);
		if(is_object($catTitle)){
			$this->categoryReadable = $catTitle->getText();
		} else {
			$this->categoryReadable = $this->category;
		}

		$vars = array();
		F::build('JSMessages')->enqueuePackage('PhotoPop', JSMessages::INLINE);
		F::build('JSMessages')->onMakeGlobalVariablesScript(&$vars);
 		$this->globalVariablesScript = Skin::makeVariablesScript( $vars );

		wfProfileOut( __METHOD__ );
	} // end playGame()
	
	/**
	 * Endpoint for the Canvas demo-version of the game.
	 *
	 * @WARNING: This demo showed that the game was just way too slow on actual mobile
	 * devices, so it was rewritten in normal HTML 5 without canvas.  This version should
	 * not be used for anything except the tech-demo that it is.
	 */
	public function getCanvas() {
		global $wgOut, $wgExtensionsPath, $wgStyleVersion;
		wfProfileIn( __METHOD__ );

		$this->canvasWidth = $this->getVal('width', 480);
		$this->canvasHeight = $this->getVal('height', 320);

		$this->gameJs = $wgExtensionsPath."/wikia/PhotoPop/js/PhotoPop_canvasDemo.js?$wgStyleVersion";

		wfProfileOut( __METHOD__ );
	} // end getCanvas()

} // end class SpecialPhotoPop
