<?php

class RelatedVideosService {

	const memcKeyPrefix = 'RelatedVideosService';
	const memcVersion = 10;
	const width = 160;
	const howLongVideoIsNew = 3;

	/**
	 * Get data for displaying and playing a Related Video
	 * @param int $articleId if provided, look up article by ID. Supercedes $title. Note: this forces a read from master DB, not slave.
	 * @param type $title if provided, look up article by text.
	 * @param string $source if video is on an external wiki, DB name of that wiki. Empty value indicates video is stored locally.
	 * @param int $videoWidth Width of resulting video player, in pixels
	 * @return Array 
	 */
	public function getRelatedVideoData( $params, $videoWidth = VideoPage::DEFAULT_OASIS_VIDEO_WIDTH, $cityShort='life', $useMaster=0, $videoHeight='', $useJWPlayer=true, $autoplay=true ){

		$title = isset( $params['title'] ) ? $params['title'] : '';
		$articleId = isset( $params['articleId'] ) ? $params['articleId'] : 0;
		$source = isset( $params['source'] ) ? $params['source'] : '';

		wfProfileIn( __METHOD__ );
		$title = urldecode( $title );
		$result = $this->getFromCache( $title, $source, $videoWidth, $cityShort );
		if ( empty( $result ) ){
			Wikia::log( __METHOD__, 'RelatedVideos', 'Not from cache' );
			if ( !empty( $source ) ){
				$url = F::app()->wg->wikiaVideoRepoPath;
				if ( !empty( $url ) ){
					$url.='wikia.php?controller=RelatedVideos&method=getVideoData&width='.self::width.'&videoWidth='.$videoWidth.'&title='.urlencode($title).'&articleId='.$articleId.'&cityShort='.$cityShort.'&videoHeight='.$videoHeight.'&useJWPlayer='.$useJWPlayer.'&autoplay='.$autoplay.'&format=json';
				}
				$httpResponse = Http::post( $url );
				$result = json_decode( $httpResponse, true );
				$result['data']['external'] = 1;
			} else {
				$result = F::app()->sendRequest(
					'RelatedVideos',
					'getVideoData',
					array(
						'width'		=> self::width,
					        'videoWidth'	=> $videoWidth,
						'title'		=> $title,
						'articleId'	=> $articleId,
						'cityShort'	=> $cityShort,
						'useMaster'	=> $useMaster,
						'videoHeight'	=> $videoHeight,
					        'useJWPlayer'	=> $useJWPlayer,
					        'autoplay'	=> $autoplay
					)
				)->getData();
				$result['data']['external'] = 0;
			}

			if ( isset( $result['data']['error']) ){
				return array();
			}
			// just to be sure and to be able to work cross devbox.
			if ( !isset( $result['data']['arrayId'] ) ){
				if ( !isset( $result['data']['id'] ) ) return array();
				$result['data']['arrayId'] = $result['data']['external'].'|'.$result['data']['id'];
			}

			$this->saveToCache( $title, $source, $videoWidth, $cityShort, $result );
		} else {
			Wikia::log( __METHOD__, 'RelatedVideos', 'From cache' );
		}

		// add local data
		$result['data'] = $this->extendVideoByLocalParams( $result['data'], $params );
		wfProfileOut( __METHOD__ );
		return $result['data'];
	}

	public function getRelatedVideoDataFromMaster( $params, $videoWidth=VideoPage::DEFAULT_OASIS_VIDEO_WIDTH, $cityShort='life', $videoHeight='' ){

		return $this->getRelatedVideoData( $params, $videoWidth, $cityShort, 1, $videoHeight );
	}

	public function getRelatedVideoDataFromTitle( $params, $videoWidth=VideoPage::DEFAULT_OASIS_VIDEO_WIDTH, $cityShort='life', $videoHeight='' ){

		$params['articleId'] = 0;
		return $this->getRelatedVideoData( $params, $videoWidth, $cityShort, 0, $videoHeight );
	}

	private function extendVideoByLocalParams( $videoData, $localParams ){

		if ( isset( $localParams['isNewDate'] ) && !empty( $localParams['isNewDate'] ) ){
			$newDate = date( 'YmdHis', mktime( 0, 0, 0, date( 'm' ), date( 'd' ) - self::howLongVideoIsNew, date( 'Y' ) ) );
			$videoData['isNew'] = ( (int)$localParams['isNewDate'] > $newDate ) ? 1 : 0;
		} else {
			$videoData['isNew'] = 0;
		}

		$videoData['date'] = isset( $localParams['date'] ) ? $localParams['date'] : $videoData['timestamp'];

		if ( isset( $localParams['userName'] ) && !empty( $localParams['userName'] ) ){
			$oUser = F::build( 'User', array( $localParams['userName'] ), 'newFromName' );
			$oUser->load();
			if ( is_object( $oUser ) && ( $oUser->getID() > 0 ) ) {
				$videoData['externalByUser'] = 1;
				$videoData['owner'] = $oUser->getName();
				$videoData['ownerUrl'] = $oUser->getUserPage()->getFullURL();
			}
		}
		return $videoData;
	}

	public function getMemcKey( $title, $source, $videoWidth, $cityShort ) {

		if( empty( $source ) ){
			$video = Title::newFromText( $title, NS_VIDEO );
			if ($video instanceof Title && $video->exists() ) {
				return F::app()->wf->memcKey( $video->getArticleID(), F::app()->wg->wikiaVideoRepoDBName, $videoWidth, $cityShort, self::memcKeyPrefix, self::memcVersion );
			}
			return '';
		} else {
			return F::app()->wf->sharedMemcKey( md5( $title ), F::app()->wg->wikiaVideoRepoDBName, $videoWidth, $cityShort, self::memcKeyPrefix, self::memcVersion );
		}
	}

	public function saveToCache( $title, $source, $videoWidth, $cityShort, $data ) {

		$oMemc = F::app()->wg->memc;
		$weekInSeconds = 604800;
		$oMemc->set(
			$this->getMemcKey( $title, $source, $videoWidth, $cityShort ),
			$data, 
			$weekInSeconds
		);
	}

	public function getFromCache( $title, $source, $videoWidth, $cityShort ) {

		$oMemc = F::app()->wg->memc;
		return $oMemc->get( $this->getMemcKey( $title, $source, $videoWidth, $cityShort ) );
	}

	public function isTitleRelatedVideos($title) {

		if (!($title instanceof Title)) {
			return false;
		}
		if (defined('NS_RELATED_VIDEOS') && $title->getNamespace() == NS_RELATED_VIDEOS ) {
			return true;
		}
		return false;
	}

	public function editWikiActivityParams($title, $res, $item){

		if ( $this->isTitleRelatedVideos( $title ) ){
			$oTitle =  Title::newFromText( $title->getText(), NS_MAIN );
			$item['title'] = $oTitle->getText();
			$item['url'] = $oTitle->getLocalUrl();
			$item['relatedVideos'] = true;
			$item['relatedVideosDescription'] = isset( $res['comment'] ) ? $res['comment'] : '';
		}
		return $item;
		
	}

	public function createWikiActivityParams($title, $res, $item){

		if ( $this->isTitleRelatedVideos( $title ) ){
			$oTitle =  Title::newFromText( $title->getText(), NS_MAIN );
			$item['title'] = $oTitle->getText();
			$item['url'] = $oTitle->getLocalUrl();
			$item['relatedVideos'] = true;
			$item['relatedVideosDescription'] = isset( $res['comment'] ) ? $res['comment'] : '';
		}
		return $item;	
	}

	private function parseSummary( $text ){

		$app = F::app();
		// empty title is requred for parsing, otherwise it will not work.
		// cannot use wfMsgExt due to FogBugzId:12901
		return $app->wg->parser->parse(
			$text,
			$app->wg->title,
			F::build('ParserOptions'),
			false
		)->getText();
	}

	public function formatRelatedVideosRow( $text ){

		$html = Xml::openElement('tr');
		$html .= Xml::openElement('td');
		$html .= $this->parseSummary( $text );
		$html .= Xml::closeElement('td');
		$html .= Xml::closeElement('tr');
		return $html;
	}
}
