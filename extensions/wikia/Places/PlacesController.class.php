<?php

class PlacesController extends WikiaController {

	// avoid having interactive maps with the same ID
	private static $mapId = 1;

	public function __construct( WikiaApp $app ) {
		$this->app = $app;
	}

	/**
	 * Render static map from given set of attributes
	 *
	 * Used to render <place> parser hook
	 */
	public function placeFromAttributes(){
		$attributes = $this->getVal('attributes', array());
		$oPlaceModel = F::build( 'PlaceModel', array( $attributes ), 'newFromAttributes' );

		$this->request->setVal('model', $oPlaceModel);
		$this->forward( 'Places', 'placeFromModel');
	}

	/**
	 * Render static map for given place model
	 */
	public function placeFromModel(){
		$oPlaceModel = $this->getVal('model', null);
		$rteData = $this->getVal('rteData', false);

		if ( empty( $oPlaceModel ) ){
			$oPlaceModel = F::build('PlaceModel');
		}
		$this->setVal( 'url', $oPlaceModel->getStaticMapUrl() );
		$this->setVal( 'align', $oPlaceModel->getAlign() );
		$this->setVal( 'width', $oPlaceModel->getWidth() );
		$this->setVal( 'height', $oPlaceModel->getHeight() );
		$this->setVal( 'lat', $oPlaceModel->getLat() );
		$this->setVal( 'lon', $oPlaceModel->getLon() );
		$this->setVal( 'zoom', $oPlaceModel->getZoom() );
		$this->setVal( 'categories', $oPlaceModel->getCategoriesAsText() );
		$this->setVal( 'caption', $oPlaceModel->getCaption() );
		$this->setVal( 'rteData', $rteData );
	}

	/**
	 * Render interactive map for given set of points
	 *
	 * Map center can be specified
	 */
	public function renderMarkers() {
		$this->setVal('markers', $this->prepareMarkers($this->getVal('markers')));
		$this->setVal('center', $this->getVal('center'));
		$this->setVal('mapId', 'places-map-' . self::$mapId++);
		$this->setVal('height', $this->getVal('height', 500));
		$this->setVal('options', $this->getVal('options', array()));
	}

	/**
	 * Get markers from articles "related" to a given article
	 *
	 * Returns data to be rendered on the client-side
	 */
	public function getMarkersRelatedToCurrentTitle(){
		$sTitle = $this->getVal('title', '');
		$sCategoriesText = $this->getVal('category', '');

		$oTitle = F::build( 'Title', array( $sTitle ), 'newFromText' );
		if ( $oTitle instanceof Title ){
			$oPlacesModel = F::build('PlacesModel');
			$oMarker = F::build( 'PlaceStorage', array( $oTitle ), 'newFromTitle' )->getModel();
			$oMarker->setCategories( $sCategoriesText );

			if( !empty( $sCategoriesText ) ){
				$aMarkers = $oPlacesModel->getFromCategories( $oMarker->getCategories() );
			} else {
				$aMarkers = $oPlacesModel->getFromCategoriesByTitle( $oTitle );
			}
			$oMarker = F::build( 'PlaceStorage', array( $oTitle ), 'newFromTitle' )->getModel();

			$this->setVal('center', $oMarker->getForMap());
			$this->setVal('markers', $this->prepareMarkers($aMarkers));

			// generate modal caption
			$this->setVal('caption', $this->wf->msgExt('places-modal-go-to-special', array('parseinline', 'parsemag'), count($this->markers)));
		}
	}

	/**
	 * Internal method used to render tooltip for each marker
	 */
	protected function prepareMarkers( Array $aMarkers ) {
		$markers = array();

		foreach( $aMarkers as $oMarker ){
			$aMapParams = $oMarker->getForMap();
			if ( !empty( $aMapParams ) ){
				$tmpArray = $oMarker->getForMap();
				$tmpArray['tooltip'] = $this->sendRequest(
					'Places',
					'getMapSnippet',
					array(
					    'data' => $tmpArray
					)
				)->toString();
				$markers[] = $tmpArray;
			}
		}

		return $markers;
	}

	/**
	 * Render marker tooltip
	 */
	public function getMapSnippet() {
		$data = $this->getVal( 'data' );
		$this->setVal( 'imgUrl', isset( $data['imageUrl'] ) ? $data['imageUrl'] : '' );
		$this->setVal( 'title', isset( $data['label'] ) ? $data['label'] : '' );
		$this->setVal( 'url', isset( $data['articleUrl'] ) ? $data['articleUrl'] : '' );
		$this->setVal( 'textSnippet', isset( $data['textSnippet'] ) ? $data['textSnippet'] : '' );
	}

	/**
	 * Create a new place based on geo data provided and store it in the database
	 */
	public function saveNewPlaceToArticle(){
		$oPlaceModel = F::build('PlaceModel');
		$oPlaceModel->setPageId( $this->getVal( 'articleId', 0 ) );

		if ( $oPlaceModel->getPageId() == 0 ){
			$this->setVal( 'error', wfMsg( 'places-error-no-article' ) );
			$this->setVal( 'success', false );
		} else {
			$oStorage = PlaceStorage::newFromId( $oPlaceModel->getPageId() );
			if ( $oStorage->getModel()->isEmpty() == false ){
				$this->setVal( 'error', wfMsg( 'places-error-place-already-exists' ) );
				$this->setVal( 'success', false );
			} else {
				$oPlaceModel->setAlign( $this->getVal( 'align', false ) );
				$oPlaceModel->setWidth( $this->getVal( 'width', false ) );
				$oPlaceModel->setHeight( $this->getVal( 'height', false ) );
				$oPlaceModel->setLat( $this->getVal( 'lat', false ) );
				$oPlaceModel->setLon( $this->getVal( 'lon', false ) );
				$oPlaceModel->setZoom( $this->getVal( 'zoom', false ) );

				$sText = $this->sendRequest(
					'PlacesController',
					'getPlaceWikiTextFromModel',
					array(
					    'model' => $oPlaceModel
					)
				)->toString();

				$oTitle = Title::newFromID( $oPlaceModel->getPageId() );

				if ( ($oTitle instanceof Title ) && $oTitle->exists() ) {
					$oArticle = F::build( 'Article', array( $oTitle ) );
					$sNewContent = $sText . $oArticle->getContent();
					$status =
						$oArticle->doEdit(
							$sNewContent,
							wfMsg( 'places-updated-geolocation' ),
							EDIT_UPDATE
						);
					$this->setVal( 'success', true );
				} else {
					$this->setVal( 'error', wfMsg( 'places-error-no-article' ) );
					$this->setVal( 'success', false );
				}
			}
		}
	}

	/**
	 * Render wikitext of <place> tag for given model
	 */
	public function getPlaceWikiTextFromModel(){
		$oPlaceModel = $this->getVal( 'model', null );

		if ( empty( $oPlaceModel ) || !( $oPlaceModel instanceof PlaceModel ) ) {
			$oPlaceModel = F::build('PlaceModel');
		}

		$this->setVal( 'oEmptyPlaceModel', F::build('PlaceModel') );
		$this->setVal( 'oPlaceModel', $oPlaceModel );
	}

	/**
	 * Renders the geolocation button for adding coordinates to a page
	 */
	public function getGeolocationButton(){

		if (	$this->app->wg->title->isContentPage() &&
			F::build(
				'PlaceStorage',
				array( $this->app->wg->title ),
				'newFromTitle'
			)->getModel()->isEmpty() &&
			F::build(
				'PlaceCategory',
				array( $this->app->wg->title->getFullText() ),
				'newFromTitle'
			)->isGeoTaggingEnabledForArticle( $this->app->wg->title )
		){

			$this->setVal(
				'geolocationParams',
				$this->getGeolocationButtonParams()
			);
			$this->response->setVal(
				'jsSnippet',
				PlacesParserHookHandler::getJSSnippet()
			);
			F::build( 'JSMessages' )
				->enqueuePackage(
					'PlacesGeoLocationModal',
					JSMessages::INLINE
				);
		} else {
			$this->skipRendering();
		}
	}

	/**
	 * Purge geolocationplaceholder cache
	 */
	public function purgeGeoLocationButton(){
		$this->getGeolocationButtonParams( true );
		$this->skipRendering();
	}

	/**
	 * Returns geolocation button params
	 */
	private function getGeolocationButtonParams( $refreshCache = false ){
		$sMemcKey = $this->app->wf->MemcKey(
			$this->app->wg->title->getText(),
			$this->app->wg->title->getNamespace(),
			'GeolocationButtonParams'
		);

		// use user default
		if ( empty( $iWidth ) ){
			$wopt = $this->app->wg->user->getOption( 'thumbsize' );
			if( !isset( $this->app->wg->thumbLimits[ $wopt ] ) ) {
				$wopt = User::getDefaultOption( 'thumbsize' );
			}
			$iWidth = $this->app->wg->thumbLimits[ $wopt ];
		}

		$aResult = array(
			'align' => 'right',
			'width' => $iWidth
		);

		$aMemcResult = $this->app->wg->memc->get( $sMemcKey );
		$refreshCache = true;
		if ( $refreshCache || empty( $aMemcResult ) ){
			$oArticle = F::build( 'Article', array( $this->app->wg->title ) );
			$sRawText = $oArticle->getRawText();
			$aMatches = array();
			$string = $this->app->wg->contLang->getNsText( NS_IMAGE ) . '|' . MWNamespace::getCanonicalName(NS_IMAGE);
			$iFound = preg_match (
				'#\[\[('.$string.'):[^\]]*|thumb[^\]]*\]\]#',
				$sRawText,
				$aMatches
			);
			if ( !empty( $iFound ) ){
				reset( $aMatches );
				$sMatch = current( $aMatches );
				$sMatch = str_replace( '[[', '', $sMatch );
				$sMatch = str_replace( ']]', '', $sMatch );
				$aMatch = explode( '|', $sMatch );
				foreach( $aMatch as $element ){
					if ( $element == 'left' ){ $aResult['align'] = $element; }
					if ( substr( $element, -2 ) == 'px' && (int)substr( $element, 0, -2 ) > 0 ){
						$aResult['width'] = (int)substr( $element, 0, -2 );
					}
				}
			}
			$iExpires = 60*60*24;
			$this->app->wg->memc->set(
				$sMemcKey,
				$aResult,
				$iExpires
			);
		} else {
			$aResult[ 'align' ] = $aMemcResult[ 'align' ];
			if ( !empty( $aMemcResult['width'] ) ){
				$aResult[ 'width' ] = $aMemcResult[ 'width' ];
			}
		}

		// get default image width
		return $aResult;
	}
}
