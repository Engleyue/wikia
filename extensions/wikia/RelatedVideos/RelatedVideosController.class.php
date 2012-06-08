<?php

class RelatedVideosController extends WikiaController {

	const SURVEY_URL = 'http://www.surveymonkey.com/s/RelatedVideosExperience';
	public function __construct( WikiaApp $app ) {
		global $wgRelatedVideosOnRail;
		$this->app = $app;
		if( !empty( $wgRelatedVideosOnRail ) ) {
			RelatedVideosService::$width = 150;
			RelatedVideosService::$height = 90;
		}
	}

	public function getCaruselRL(){
		// just use different template, logic stays the same
		return $this->getCarusel();
	}

	public function getCaruselElementRL(){
		// just use different template, logic stays the same
		return $this->getCaruselElement();
	}

	public function getCarusel(){

		if( Wikia::isMainPage() || ( !$this->app->wg->title instanceof Title ) || !$this->app->wg->title->exists() ) {
			return false;
		}
		$rvs = new RelatedVideosService();
		$videos = $rvs->getRVforArticleId( $this->app->wg->title->getArticleId() );
		
		$this->setVal( 'videos', $videos );
	}

	public function getVideo(){
		/* this is only used pre-migration (before Video Refactoring)
		   afterwards videos are played using regular Lightbox
		*/

		$title = urldecode($this->getVal( 'video_title' ));
		$external = $this->getVal( 'external', '' );
		$external = empty( $external ) ? null : $this->app->wg->wikiaVideoRepoDBName;
		$cityShort = $this->getVal('cityShort');
		$videoHeight = $this->getVal('videoHeight');
		$controlerName = str_replace('Controller', '', $this->getVal('controlerName', 'RelatedVideos'));
		$wikiLink = $this->getVal('wikiLink', '');

		$oRelatedVideosService = F::build('RelatedVideosService');
		$result = $oRelatedVideosService->getRelatedVideoDataFromTitle( array( 'title' => $title, 'source' => $external ), RelatedVideosData::DEFAULT_OASIS_VIDEO_WIDTH, $cityShort, $videoHeight );
		if ( isset( $result['error'] ) ){
			$this->setVal( 'error', $result['error'] );
		} else {
			$this->setVal( 'width', intval( $result['thumbnailData']['width'] ) );
			$this->setVal( 'height', intval( $result['thumbnailData']['height'] ) );
			$this->setVal( 'json', $result['embedJSON'] );
			if ( !empty( $result['embedJSON'] ) && isset( $result['embedJSON']['id'] ) ){
				$videoHtml = '<div id="'.$result['embedJSON']['id'].'"></div>';
			} else {
				$videoHtml = $result['embedCode'];
			}
			$this->setVal( 'html',
				 $this->app->renderView(
					$controlerName,
					'getVideoHtml',
					array(
						'videoHtml' => $videoHtml,
						'embedUrl' => $result['fullUrl'],
						'wikiLink' => $wikiLink,
					)
				)
			);
			$this->setVal( 'title', $result['title'] );
			if ( !empty( $result['external'] ) ){
				$this->setVal( 'embedUrl', $result['fullUrl'] );
			}
		}
	}

	public function getVideoHtml(){

		$videoHtml = $this->getVal( 'videoHtml' );
		$embedUrl = $this->getVal( 'embedUrl' );

		$this->setVal( 'videoHtml', $videoHtml );
		$this->setVal( 'embedUrl', $embedUrl );
	}
	
	/*
	 * get data for an article stored in NS_RELATED_VIDEOS 
	 */
	public function getLists() {

		$titleStr = $this->request->getVal( 'title', null );
		$title = Title::newFromText( $titleStr, NS_RELATED_VIDEOS );
		$relatedVideosNSData = RelatedVideosNamespaceData::newFromTitle($title);
		$this->setVal( 'data', $relatedVideosNSData->getData() );
	}

	/*
	 * for getting videos localy and cross wiki
	 */
	public function getVideoData() {

		$videoArticleId = $this->getVal( 'articleId', 0 );
		$videoName = urldecode($this->getVal( 'title', '' ));
		$width = $this->getVal( 'width', 0 );
		$useMaster = $this->getVal( 'useMaster', 0 );
		$videoWidth = $this->getVal( 'videoWidth', RelatedVideosData::DEFAULT_OASIS_VIDEO_WIDTH );
		$videoHeight = $this->getVal( 'videoHeight', '' );
		$cityShort = $this->getVal( 'cityShort', 'life');
		$useJWPlayer = $this->getVal( 'useJWPlayer', true );
		$autoplay = $this->getVal( 'autoplay', true );
		$inAjaxReponse = $this->getVal('inAjaxResponse');

		if ( $videoArticleId ) {
			$videoTitle = Title::newFromID( $videoArticleId, GAID_FOR_UPDATE );
			$useMaster = true;
		} else {
			$videoTitle = Title::newFromText( $videoName, NS_VIDEO );
			// var_dump( $videoTitle );
			$useMaster = ( false || !empty( $useMaster ) );
		}

		$rvd = F::build('RelatedVideosData'); /* @var $rvd RelatedVideosData */
		$videoData = $rvd->getVideoData( $videoName, $width, $videoWidth, $autoplay, $useMaster, $cityShort, $videoHeight, $useJWPlayer, $inAjaxReponse );
		$this->setVal( 'data', $videoData );
	}

	public function getCaruselElement(){

		global $wgVideoHandlersVideosMigrated;

		$video = $this->getVal( 'video' );
		$preloaded = $this->getVal( 'preloaded' );

		$videoFile = wfFindFile($video['id']);

		$videoThumbObj = $videoFile->transform( array('width'=>$video['thumbnailData']['width'],
		                                              'height'=>$video['thumbnailData']['height']) );

		$videoThumb = $videoThumbObj->toHtml( array('custom-url-link' => $video['fullUrl'],
			                                    'linkAttribs' => array(
			                                                        'class' => 'video-thumbnail lightbox',
			                                                        'data-video-name' => $video['title'],
			                                                        'data-external' => $video['external'],
			                                                        'data-ref' => $video['prefixedUrl']
			                                                       ),
							    'duration' => true,
							    'src' => $preloaded ? false : wfBlankImgUrl(),
							    'constHeight' => RelatedVideosService::$height,
							    'usePreloading' => true
						      )
					        );

		$this->setVal( 'videoThumb', $videoThumb );
		$this->setVal( 'video', $video );
		$this->setVal( 'preloaded', $preloaded );
		$this->setVal( 'videoPlay',empty($wgVideoHandlersVideosMigrated) ? 'video-play' : 'lightbox');
	}

	public function getAddVideoModal(){

		$this->setVal( 'html', $this->app->renderView( 'RelatedVideos', 'addVideoModalText' ) );
		$this->setVal( 'title',	wfMsg('related-videos-add-video-to-this-page') );
	}

	public function addVideoModalText(){
		
	}
	
	public function addVideo() {
		global $wgRelatedVideosOnRail;

		$url = urldecode( $this->getVal( 'url', '' ) );
		$articleId = $this->getVal( 'articleId', '' );
		$rvd = F::build( 'RelatedVideosData' );
		$retval = $rvd->addVideo( $articleId, $url );
		if ( is_array( $retval ) ) {
			$rvs = F::build( 'RelatedVideosService' );
			$data = $rvs->getRelatedVideoDataFromMaster( $retval );
			if ( empty($wgRelatedVideosOnRail) ) {
				$this->setVal( 'html', $this->app->renderView( 'RelatedVideos', 'getCaruselElement', array( 'video' => $data, 'preloaded' => 1 ) ));
			} else {
				$this->setVal( 'html', $this->app->renderView( 'RelatedVideos', 'getCaruselElementRL', array( 'video' => $data, 'preloaded' => 1 ) ));
			}
			$this->setVal( 'error', isset( $data['error'] ) ? $data['error'] : null);
		} else {
			$this->setVal( 'data', null );
			$this->setVal( 'error', $retval );
		}
	}
	
	public function removeVideo() {
		
		$articleId = $this->getVal( 'articleId', '' );
		$title = urldecode( $this->getVal( 'title', '' ) );
		$external = $this->getVal( 'external', 0 );
		$rvd = F::build( 'RelatedVideosData' );
		$retval = $rvd->removeVideo( $articleId, $title, $external );
		if ( is_string( $retval ) ) {
			$this->setVal( 'error', $retval );
		}
		else {
			$this->setVal( 'error', null );
		}
	}
}
