<?php

class RelatedVideosData {

	protected static $theInstance = null;

	function __construct() {
		
	}
	
	public function getVideoData( $title, $thumbnailWidth, $videoWidth = VideoPage::DEFAULT_OASIS_VIDEO_WIDTH, $autoplay = true, $useMaster = false, $cityShort='life', $videoHeight='', $useJWPlayer=true, $inAjaxResponse=false ) {

		wfProfileIn( __METHOD__ );

		$data = array();
		if ( empty( $title ) || !is_object( $title ) || !( $title instanceof Title ) || !$title->exists() ){
			$data['error'] = wfMsg( 'related-videos-error-no-video-title' );
		} else {

			if( WikiaVideoService::isVideoStoredAsFile() ) {
				$file = wfFindFile($title);

				if( empty($file) || !is_object( $file) || !( $file instanceof WikiaLocalFile ) ) {
					$data['error'] = wfMsg( 'related-videos-error-no-video-title' );
				} else {

					$meta = unserialize($file->getMetadata());
					$trans = $file->transform(array('width'=>$thumbnailWidth, 'height'=>-1));

					$thumb = array(
						'width' => $trans->width,
						'height' => $trans->height,
						'thumb' => $trans->url
					);

					$data['external']		= 0; // false means it is not set. Meaningful values: 0 and 1.
					$data['id']				= $title->getArticleID();
					$data['fullUrl']		= $title->getFullURL();
					$data['prefixedUrl']	= $title->getPrefixedURL();
					$data['description']	= $file->getDescription();
					$data['duration']		= $meta['duration'];
					$data['embedCode']		= null;
					$data['embedJSON']		= null;
					$data['provider']		= $file->minor_mime;
					$data['thumbnailData']	= $thumb;
					$data['title']			= $file->getTitle()->getText();
					$data['timestamp']		= $file->getTimestamp();

				}

			} else {

				$videoPage = F::build( 'VideoPage', array( $title ) );
				$videoPage->load($useMaster);
				$data['external']		= false; // false means it is not set. Meaningful values: 0 and 1.
				$data['id']				= $title->getArticleID();
				$data['fullUrl']		= $title->getFullURL();
				$data['prefixedUrl']	= $title->getPrefixedURL();
				$data['description']	= $videoPage->getDescription();
				$data['duration']		= $videoPage->getDuration();
				$data['embedCode']		= $videoPage->getEmbedCode( $videoWidth, $autoplay, $useJWPlayer, false, $cityShort, $videoHeight, $inAjaxResponse );
				$data['embedJSON']		= $videoPage->getJWPlayerJSON( $videoWidth, $autoplay, $cityShort, $videoHeight );
				$data['provider']		= $videoPage->getProvider();
				$data['thumbnailData']	= $videoPage->getThumbnailParams( $thumbnailWidth );
				$data['title']			= $videoPage->getTitle()->getText();
				$data['timestamp']		= $videoPage->getTimestamp();	//@todo for premium video, eventually use date published given by provider

			}

			$owner = '';
			$ownerUrl = '';
			$oArticle = F::build( 'Article', array( $title ) );
			
			if( !empty( $oArticle ) ){
				$owner = $oArticle->getUserText();
				if (!empty($owner)) {
					$oOwner = F::build( 'User', array( $owner ), 'newFromName' );
					if (is_object($oOwner)) {
						$ownerUrl = $oOwner->getUserPage()->getFullURL();
					}
				}
			}
			$data['owner'] = $owner;
			$data['ownerUrl'] = $ownerUrl;
			$data['arrayId'] = isset($data['error']) ? '' : $data['external'].'|'.$data['id'];
		}

		wfProfileOut( __METHOD__ );
		return $data;
	}
	
	public function addVideo( $articleId, $url ) {

		wfProfileIn( __METHOD__ );
		if ( empty( $articleId ) ){
			wfProfileOut( __METHOD__ );
			return wfMsg('related-videos-error-no-article-id');
		}

		if ( empty( $url ) ){
			wfProfileOut( __METHOD__ );
			return wfMsg('related-videos-error-no-video-url');
		}

		$targetTitle = F::build('Title', array($articleId), 'newFromId');
		if (!$targetTitle->exists()) {
			wfProfileOut( __METHOD__ );
			return wfMsg('related-videos-error-unknown', 876462);
		}

		// check permission
		$permErrors = $targetTitle->getUserPermissionsErrors( 'edit', F::app()->wg->user );
		if ($permErrors) {
			wfProfileOut( __METHOD__ );
			return wfMsg('related-videos-error-permission-article');
		}

		try {
			list($videoTitle, $videoPageId, $videoProvider) = $this->addVideoVideoPage( $articleId, $url );
		}
		catch( Exception $e ) {
			wfProfileOut( __METHOD__ );
			return $e->getMessage();
		}

		// add to article's whitelist
		$rvn = F::build( 'RelatedVideosNamespaceData', array( $targetTitle ), 'newFromTargetTitle' );
		$entry = $rvn->createEntry( $videoTitle->getText(), $videoProvider == VideoPage::V_WIKIAVIDEO );
		$retval = $rvn->addToList( RelatedVideosNamespaceData::WHITELIST_MARKER, array( $entry ), $articleId );
		if ( is_array( $rvn->entries ) ){
			$entry = end( $rvn->entries );
		}
		if ( is_object( $retval ) ) {
			if ( $retval->ok ) {
				$data = $entry;
				$data['articleId'] = $videoPageId;
				$data['title'] = $videoTitle->getText();
				$data['external'] = $videoProvider == VideoPage::V_WIKIAVIDEO;
				wfProfileOut( __METHOD__ );
				return $data;
			}
			else {

			}
		}
		wfProfileOut( __METHOD__ );

		return $retval;
	}

	protected function addVideoVideoPage( $url ) {
		// create temp VideoPage to parse URL
		$tempname = 'Temp_video_' . F::app()->wg->user->getID() . '_'.rand(0, 1000); // FIXME: use normal empty title;
		$temptitle = F::build( 'Title', array( NS_VIDEO, $tempname ), 'makeTitle' );
		$video = F::build( 'VideoPage', array( $temptitle ) );

		if( !$video->parseUrl( $url ) ) {
			wfProfileOut( __METHOD__ );
			throw Exception( wfMsg( 'related-videos-add-video-error-bad-url' ) );
		}

		if( !$video->checkIfVideoExists() ) {
			wfProfileOut( __METHOD__ );
			throw Exception( wfMsg( 'related-videos-add-video-error-nonexisting' ) );
		}

		$videoName = $video->getVideoName();
		$videoProvider = $video->getProvider();
		$videoId = $video->getVideoId();
		$videoMetadata = $video->getData();

		// check if video already exists on this wiki. If not, create 
		// new VideoPage
		$videoTitle = $this->sanitizeTitle( $videoName );

		if( is_null( $videoTitle ) ) {
			wfProfileOut( __METHOD__ );
			throw Exception( wfMsg ( 'related-videos-add-video-error-bad-name' ) );
		}

		if( $videoTitle->exists() ) {
			$videoPageId = $videoTitle->getArticleID();
			// no need to create video page
		} else {
			// is the target protected?
			$permErrors = $videoTitle->getUserPermissionsErrors( 'edit', F::app()->wg->user );
			$permErrorsUpload = $videoTitle->getUserPermissionsErrors( 'upload', F::app()->wg->user );
			$permErrorsCreate = $videoTitle->getUserPermissionsErrors( 'create', F::app()->wg->user );

			if( $permErrors || $permErrorsUpload || $permErrorsCreate ) {
				throw Exception( wfMsg( 'related-videos-add-video-error-permission-video' ) );
			}

			$video = F::build( 'VideoPage', array( $videoTitle ) );
			$video->loadFromPars( $videoProvider, $videoId, $videoMetadata );
			$video->setName( $videoName );
			$video->save();
			$videoPageId = $video->getTitle()->getArticleID();
		}

		return array($videoTitle, $videoPageId, $videoProvider);

	}

	protected function sanitizeTitle($name) {

		wfProfileIn( __METHOD__ );
		// sanitize title
		$name = preg_replace(Title::getTitleInvalidRegex(), ' ', $name);
		// get rid of slashes. these are technically allowed in article
		// titles, but they refer to subpages, which videos don't have
		$name = str_replace('/', ' ', $name);
		$name = str_replace('  ', ' ', $name);

		$name = substr($name, 0, VideoPage::MAX_TITLE_LENGTH);	// DB column Image.img_name has size 255

		wfProfileOut( __METHOD__ );
		return Title::makeTitleSafe(NS_VIDEO, $name);		
	}
	
	public function removeVideo($articleId, $title, $isExternal) {

		wfProfileIn( __METHOD__ );
		// general validation
		if ( empty( $title ) ) {
			wfProfileOut( __METHOD__ );
			return wfMsg('related-videos-add-video-error-bad-url');
		}

		if ( empty( $articleId ) ) {
			wfProfileOut( __METHOD__ );
			return wfMsg('related-videos-error-unknown', 876463);
		}

		$targetTitle = F::build('Title', array($articleId), 'newFromId');
		if (!$targetTitle->exists()) {
			wfProfileOut( __METHOD__ );
			return wfMsg('related-videos-error-unknown', 876464);
		}
		
		// check permission
		$permErrors = $targetTitle->getUserPermissionsErrors( 'edit', F::app()->wg->user );
		if ($permErrors) {
			wfProfileOut( __METHOD__ );
			return wfMsg('related-videos-error-permission-article');
		}
		
		$rvn = F::build('RelatedVideosNamespaceData', array($targetTitle), 'newFromTargetTitle');

		// standardize format of title
		$titleObj = F::build('Title', array($title), 'newFromText');
		$title = $titleObj->getText();
		$entry = $rvn->createEntry($title, $isExternal);

		// check video exists
		$rvs = F::build('RelatedVideosService');
		
		$entry['articleId'] = 0;
		$data = $rvs->getRelatedVideoData( $entry );
		if ( empty( $data['title'] ) ) {
			wfProfileOut( __METHOD__ );
			return wfMsg( 'related-videos-remove-video-error-nonexisting' );
		}

		$retval = $rvn->addToList( RelatedVideosNamespaceData::BLACKLIST_MARKER, array($entry), $articleId );
		if (is_object($retval)) {
			if ($retval->ok) {
				wfProfileOut( __METHOD__ );
				return true;
			}
		}
		else {	// error message returned
			return $retval;
		}
		
		wfProfileOut( __METHOD__ );
		return wfMsg('related-videos-error-unknown', 543886);
	}
}
