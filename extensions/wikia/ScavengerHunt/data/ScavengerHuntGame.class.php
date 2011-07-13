<?php
class ScavengerHuntGame {

	/**
	 * @var WikiaApp
	 */
	protected $app = null;
	/**
	 * @var ScavengerHuntGames
	 */
	protected $games = null;

	protected $id = 0;
	protected $hash = 0;
	protected $wikiId = 0;
	protected $isEnabled = false;
	protected $name = '';
	protected $landingTitle = ''; //:nosetter
	protected $landingArticleName = ''; //:nosetter
	protected $landingArticleWikiId = 0; //:nosetter
	protected $landingButtonText = '';
	protected $landingButtonX = '';
	protected $landingButtonY = '';
	protected $startingClueTitle = '';
	protected $startingClueText = '';
	protected $startingClueImage = '';
//	protected $startingClueImageTopOffset = 0;
//	protected $startingClueImageLeftOffset = 0;
	protected $startingClueButtonText = '';
	protected $startingClueButtonTarget = '';
	protected $articles = array();
	protected $entryFormTitle = '';
	protected $entryFormEmail = '';
	protected $entryFormUsername = '';
	protected $entryFormButtonText = '';
	protected $entryFormText = '';
	protected $entryFormImage = '';
//	protected $entryFormImageTopOffset = 0;
//	protected $entryFormImageLeftOffset = 0;
	protected $entryFormQuestion = '';
	protected $goodbyeTitle = '';
	protected $goodbyeText = '';
	protected $goodbyeImage = '';
//	protected $goodbyeImageTopOffset = 0;
//	protected $goodbyeImageLeftOffset = 0;
	protected $spriteImg = '';
	protected $clueColor = '#FFF';
	protected $clueSize = '13px';
	protected $clueFont = 'bold';

	protected $startPopupSprite = array();
	protected $finishPopupSprite = array();
	protected $progressBarBackgroundSprite = array();
	protected $progressBarExitSprite = array();
	protected $progressBarHintLabel = array();

	
	public function __construct( WikiaApp $app, $id = 0 ) {
		$this->startPopupSprite =
		$this->finishPopupSprite =
		$this->progressBarBackgroundSprite =
		$this->progressBarExitSprite =
		$this->progressBarHintLabel = ScavengerHuntGameArticle::getSpriteTemplate();

		$this->app = $app;
		$this->id = $id;
	}

	/** GETTER FOR GAMES OBJECT **/
	public function setGames( $games ) {
		$this->games = $games;
	}

	/** GETTERS AND SETTERS **/
	public function setLandingTitle( $landingTitle ) {
		$this->landingTitle = $landingTitle;
		$this->resetLandingParams( $landingTitle );
	}

	public function resetLandingParams( $landingTitle ) {
		$aExplodedURL = F::build( 'GlobalTitle', array( $landingTitle ), 'explodeURL' );
		$this->landingArticleWikiaId = $aExplodedURL['wikiId'];
		$this->landingArticleName = $aExplodedURL['articleName'];
	}

//	public function getStartingClueImageOffset() {
//		return array(
//			'top' => $this->startingClueImageTopOffset,
//			'left' => $this->startingClueImageLeftOffset,
//		);
//	}
//
//	public function getEntryFormImageOffset() {
//		return array(
//			'top' => $this->entryFormImageTopOffset,
//			'left' => $this->entryFormImageLeftOffset,
//		);
//	}
//
	public function getGoodbyeImageOffset() {
		return array(
			'top' => $this->goodbyeImageTopOffset,
			'left' => $this->goodbyeImageLeftOffset,
		);
	}

	public function setId( $id ) { $this->id = $id; }
	public function setWikiId( $wikiId ) { $this->wikiId = $wikiId; }
	public function setEnabled( $isEnabled ) { $this->isEnabled = $isEnabled; }
	public function setName( $name ) { $this->name = $name; }
	public function setLandingButtonText( $landingButtonText ) { $this->landingButtonText = $landingButtonText; }
	public function setLandingButtonX( $landingButtonX ) { $this->landingButtonX = $landingButtonX; }
	public function setLandingButtonY( $landingButtonY ) { $this->landingButtonY = $landingButtonY; }
	public function setStartingClueTitle( $startingClueTitle ) { $this->startingClueTitle = $startingClueTitle; }
	public function setStartingClueText( $startingClueText ) { $this->startingClueText = $startingClueText; }
	public function setStartingClueImage( $startingClueImage ) { $this->startingClueImage = $startingClueImage; }
	public function setStartingClueImageTopOffset( $startingClueImageTopOffset ) { $this->startingClueImageTopOffset = $startingClueImageTopOffset; }
	public function setStartingClueImageLeftOffset( $startingClueImageLeftOffset ) { $this->startingClueImageLeftOffset = $startingClueImageLeftOffset; }
	public function setStartingClueButtonText( $startingClueButtonText ) { $this->startingClueButtonText = $startingClueButtonText; }
	public function setStartingClueButtonTarget( $startingClueButtonTarget ) { $this->startingClueButtonTarget = $startingClueButtonTarget; }
	public function setArticles( $articles ) { $this->articles = $articles; }
	public function setEntryFormTitle( $entryFormTitle ) { $this->entryFormTitle = $entryFormTitle; }
	public function setEntryFormText( $entryFormText ) { $this->entryFormText = $entryFormText; }
	public function setEntryFormButtonText( $entryFormButtonText ) { $this->entryFormButtonText = $entryFormButtonText; }
	public function setEntryFormImage( $entryFormImage ) { $this->entryFormImage = $entryFormImage; }
	public function setEntryFormImageTopOffset( $entryFormImageTopOffset ) { $this->entryFormImageTopOffset = $entryFormImageTopOffset; }
	public function setEntryFormImageLeftOffset( $entryFormImageLeftOffset ) { $this->entryFormImageLeftOffset = $entryFormImageLeftOffset; }
	public function setEntryFormQuestion( $entryFormQuestion ) { $this->entryFormQuestion = $entryFormQuestion; }
	public function setGoodbyeTitle( $goodbyeTitle ) { $this->goodbyeTitle = $goodbyeTitle; }
	public function setGoodbyeText( $goodbyeText ) { $this->goodbyeText = $goodbyeText; }
	public function setGoodbyeImage( $goodbyeImage ) { $this->goodbyeImage = $goodbyeImage; }
	public function setGoodbyeImageTopOffset( $goodbyeImageTopOffset ) { $this->goodbyeImageTopOffset = $goodbyeImageTopOffset; }
	public function setGoodbyeImageLeftOffset( $goodbyeImageLeftOffset ) { $this->goodbyeImageLeftOffset = $goodbyeImageLeftOffset; }
	public function setSpriteImg( $image ) { $this->spriteImg = $image; }
	
	public function getId() { return $this->id; }
	public function getWikiId() { return $this->wikiId; }
	public function isEnabled() { return $this->isEnabled; }
	public function getName() { return $this->name; }
	public function getLandingTitle() { return $this->landingTitle; }
	public function getLandingArticleName() { return $this->landingArticleName; }
	public function getLandingArticleWikiId() { return $this->landingArticleWikiId; }
	public function getLandingButtonText() { return $this->landingButtonText; }
	public function getLandingButtonX() { return $this->landingButtonX; }
	public function getLandingButtonY() { return $this->landingButtonY; }
	public function getStartingClueTitle() { return $this->startingClueTitle; }
	public function getStartingClueText() { return $this->startingClueText; }
	public function getStartingClueImage() { return $this->startingClueImage; }
	public function getStartingClueImageTopOffset() { return $this->startingClueImageTopOffset; }
	public function getStartingClueImageLeftOffset() { return $this->startingClueImageLeftOffset; }
	public function getStartingClueButtonText() { return $this->startingClueButtonText; }
	public function getStartingClueButtonTarget() { return $this->startingClueButtonTarget; }
	public function getArticles() { return $this->articles; }
	public function getEntryFormTitle() { return $this->entryFormTitle; }
	public function getEntryFormText() { return $this->entryFormText; }
	public function getEntryFormButtonText() { return $this->entryFormButtonText; }
	public function getEntryFormImage() { return $this->entryFormImage; }
	public function getEntryFormImageTopOffset() { return $this->entryFormImageTopOffset; }
	public function getEntryFormImageLeftOffset() { return $this->entryFormImageLeftOffset; }
	public function getEntryFormQuestion() { return $this->entryFormQuestion; }
	public function getEntryFormEmail() { return $this->entryFormEmail; }
	public function getEntryFormUsername() { return $this->entryFormUsername; }	public function getGoodbyeTitle() { return $this->goodbyeTitle; }
	public function getGoodbyeText() { return $this->goodbyeText; }
	public function getGoodbyeImage() { return $this->goodbyeImage; }
	public function getGoodbyeImageTopOffset() { return $this->goodbyeImageTopOffset; }
	public function getGoodbyeImageLeftOffset() { return $this->goodbyeImageLeftOffset; }
	public function getSpriteImg() { return $this->spriteImg; }
	public function getStartPopupSprite(){ return $this->startPopupSprite; }
	public function getClueColor(){ return $this->clueColor; }
	public function getClueSize(){ return $this->clueSize; }
	public function getClueFont(){ return $this->clueFont; }
	public function getFinishPopupSprite(){ return $this->finishPopupSprite; }

	protected function getDataProperties() {

		return array_merge( $this->getDataNonArrayProperties(), $this->getDataArrayProperties(), array('articles') );
	}

	public function getDataNonArrayProperties(){
		return array ( 'clueColor', 'clueFont', 'clueSize', 'name', 'hash', 'landingTitle', 'landingArticleName', 'landingArticleWikiId', 'landingButtonText', 'landingButtonX', 'landingButtonY', 'startingClueTitle',
			'startingClueText', 'startingClueButtonText', 'startingClueButtonTarget', 'entryFormTitle', 'entryFormText', 'entryFormButtonText',
			'entryFormQuestion', 'goodbyeTitle', 'goodbyeText', 'goodbyeImage', 'spriteImg', 'entryFormEmail', 'entryFormUsername' );
	}

	public function getDataArrayProperties(){
		return array( 'startPopupSprite', 'finishPopupSprite', 'progressBarBackgroundSprite', 'progressBarExitSprite' ,'progressBarHintLabel' );
		
	}

	public function getData() {
		$result = array();
		foreach ($this->getDataProperties() as $varName)
			$result[$varName] = $this->$varName;
		return $result;
	}

	public function getJSArray() {
		// 2DO
		return array();
	}

	public function getHash() {
		return $this->hash;
	}

	public function resetHash(){
		$this->hash = date('YmdGis');
	}

	public function setData( $data ) {
		foreach ($this->getDataProperties() as $varName)
			if (array_key_exists($varName, $data))
				$this->$varName = $data[$varName];
		// special cases
		if ( array_key_exists('landingTitle', $data) )
			$this->setLandingTitle($data['landingTitle']);
	}

	protected function getAllProperties() {
		return array( 'id', 'wikiId', 'isEnabled', 'name', 'hash',
			'clueColor', 'clueFont', 'clueSize', 'landingTitle', 'landingArticleName', 'landingButtonText', 'startingClueTitle',
			'startingClueText', // 'startingClueImage', 'startingClueImageTopOffset', 'startingClueImageLeftOffset',
			'startingClueButtonText', 'startingClueButtonTarget', 'articles', 'entryFormTitle', 'entryFormText', 'entryFormButtonText', 'landingButtonX', 'landingButtonY',
			'entryFormImage', 'entryFormQuestion', //'entryFormImageTopOffset', 'entryFormImageLeftOffset',
			'goodbyeTitle', 'goodbyeText', 'goodbyeImage', // 'goodbyeImageTopOffset', 'goodbyeImageLeftOffset',
			'spriteImg', 'startPopupSprite', 'finishPopupSprite', 'progressBarBackgroundSprite', 'progressBarExitSprite', 'progressBarHintLabel', 'entryFormEmail', 'entryFormUsername' );
	}

	public function getAll() {
		$result = array();
		foreach ($this->getAllProperties() as $varName)
			$result[$varName] = $this->$varName;
		return $result;
	}

	public function setAll( $data ) {
		foreach ($this->getAllProperties() as $varName)
			if (array_key_exists($varName, $data))
				$this->$varName = $data[$varName];
		// special cases

		if ( isset( $data['landingButtonX'] ) ){
			$this->landingButtonX = (int)$data['landingButtonX'];
		}

		if ( isset( $data['landingButtonY'] ) ){
			$this->landingButtonY = (int)$data['landingButtonY'];
		}

		if (
			array_key_exists('landingTitle', $data) && (
				!array_key_exists('landingArticleName', $data) ||
				!array_key_exists('landingArticleWikiId', $data)
			)
		){
			$this->setLandingTitle($data['landingTitle']);
		}

		$this->resetHash();
		
	}

	public function setAllFromRequest( $request ){
		
	}

	/** BASIC OPERATIONS **/
	public function save() {

		$this->resetHash();
		return $this->games->save($this);
	}

	public function load() {
		// already loaded - nothing to do
	}

	public function delete() {
		return $this->games->delete($this);
	}

	/** HELPER METHODS **/
	public function newGameArticle() {
		return $this->games->newGameArticle();
	}

	public function findArticleByTitle( Title $title ) {
		return $this->findArticleById($title->getArticleName());
	}

	public function findArticleByName( $articleName ) {
		if (!empty($articleName)) {
			foreach ($this->articles as $article) {
				if ($articleName === $article->getArticleName()) {
					return $article;
				}
			}
		}
		return false;
	}

	public function getArticleNames() {
		$articleNames = array();
		foreach ( $this->articles as $article ) {
			$articleName = $article->getArticleName();
				$articleNames[] = $articleName;
		}
		return $articleNames;
	}

	public function makeIdentifier( $wikiId, $articleName ){
		return ( $wikiId.'|'.urldecode( $articleName ) );
	}

	public function getArticleIdentifier() {
		$articleNames = array();
		foreach ( $this->articles as $article ) {
			$articleIdentifiers[] =
				$this->makeIdentifier(
					$article->getWikiId(),
					$article->getArticleName()
				);
		}
		return $articleIdentifiers;
	}

	protected function getEntries() {
		return $this->games->getEntries( $this );
	}

	/** ENTRIES MANAGEMENT **/
	public function addEntry( ScavengerHuntEntry $entry ) {
		if ($this->getId() == 0) {
			return false;
		}

		$entry->setGameId($this->getId());
		$entries = $this->getEntries();
		return $entries->save($entry);
	}

	public function listEntries( $startId = 0, $limit = -1 ) {
		if ($this->getId() == 0) {
			return array();
		}

		$entries = $this->getEntries();
		return $entries->findAllByGameId( $this->getId(), $startId, $limit );
	}
}