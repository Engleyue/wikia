<?php

/**
 * This class represents RelatedVideos data for an article that is stored in the namspace NS_RELATED_VIDEOS.
 */

class RelatedVideosEmbededData extends RelatedVideosNamespaceData {

	const CACHE_TTL = 86400;
	const CACHE_VER = 9;
	const VIDEOWIKI_MARKER = 'VW:';
	const BLACKLIST_MARKER = 'BLACKLIST';
	const WHITELIST_MARKER = 'WHITELIST';
	const VIDEO_MARKER = '* ';
	const GLOBAL_RV_LIST = 'RelatedVideosGlobalList';
	
	protected function __construct( $id, Title $title = null ) {
		$this->mId = $id;
		$this->mTitle = ( $title ? $title : null );
		$this->mData = null;
		$this->mExists = ( $id > 0);
		$this->mMemcacheKey = wfMemcKey( 'relatedVideosEmbededData', 'data', F::app()->wg->wikiaVideoRepoDBName, $id, self::CACHE_VER );

		wfDebug(__METHOD__ . ": relatedVideosNS article ID #{$id}\n");
	}

	/**
	 * Return instance of this class for given title from RelatedVideos namespace
	 */
	static public function newFromTitle( Title $title ) {
		$id = $title->getArticleId();
		return new self( $id, $title );
	}

	public function getData() {
		if ( is_null( $this->mData ) ) {
			$this->load();
		}

		return $this->mData;
	}

	/**
	 * Load RelatedVideos NS article data (try to use cache layer)
	 */
	protected function load( $master = false ) {
		global $wgMemc;
		
		wfProfileIn(__METHOD__);

		if ( !$master ) {
			$this->mData = $wgMemc->get( $this->mData );
		}
		
		if ( empty( $this->mData ) ) {
			$article = Article::newFromID( $this->mId );

			// check existence
			if ( empty( $article ) ) {
				wfDebug(__METHOD__ . ": RelatedVideos NS article doesn't exist\n");
				wfProfileOut(__METHOD__);
				return;
			}

			$lists = array();
			$lists[ self::BLACKLIST_MARKER ] = array();
			$lists[ self::WHITELIST_MARKER ] = array();

			$dbr = wfGetDB( DB_SLAVE );

			$res = $dbr->select(
				array( 'imagelinks' ),
				array( 'il_to' ),
				' il_from = ' . $this->mId,
				__METHOD__
			);

			if ( empty( $res ) ){
				return;
			}
			while ( $row = $res->fetchObject( $res ) ) {
				$sTitle = substr( $row->il_to, 1 );
				$oTitle = Title::newFromText( $sTitle, NS_VIDEO );
				if ( is_object( $oTitle ) && $oTitle->exists() ){
					$lists[ self::WHITELIST_MARKER ][] = $this->createEntry( $sTitle );
				}
			}

			$this->mData = array(
				'lists' => $lists,
			);

			wfDebug(__METHOD__ . ": loaded from scratch\n");
			
			// store it in memcache
			F::app()->wg->memc->set($this->mMemcacheKey, $this->mData, self::CACHE_TTL);
		}
		else {
			wfDebug(__METHOD__ . ": loaded from memcache\n");
		}

		$this->mExists = true;

		wfProfileOut(__METHOD__);
		return;
	}

	/**
	 * Add entries to specified list, and remove them from the other list
	 * @param string $list BLACKLIST_MARKER or WHITELIST_MARKER
	 * @param array $entries
	 * @param int $mainNsArticleId ID of associated article in NS_MAIN
	 * @return type 
	 */
	
	public function addToList( $list, Array $entries, $mainNsArticleId ) {

		// do nothing
		return false;
	}
}