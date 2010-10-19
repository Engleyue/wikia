<?php

class UserProfilePage {
	/**
	 * @var User
	 */
	private $user;
	private $hiddenPages = null;
	private $hiddenWikis = null;
	private $templateEngine = null;

	public function __construct( User $user ) {
		global $wgUser, $wgSitename;

		$this->user = $user;
		$this->templateEngine = new EasyTemplate( dirname(__FILE__) . "/templates/" );

		// set "global" template variables
		$this->templateEngine->set( 'isOwner', ( $this->user->getId() == $wgUser->getId() ) ? true : false );
		$this->templateEngine->set( 'userPageUrl', $this->user->getUserPage()->getLocalUrl() );
		$this->templateEngine->set( 'wikiName', $wgSitename );
	}

	public function get( $pageBody ) {
		global $wgOut, $wgJsMimeType, $wgExtensionsPath, $wgStyleVersion;

		//$wgOut->addScript( "<script type=\"{$wgJsMimeType}\" src=\"{$wgExtensionsPath}/wikia/UserProfilePage/js/UserProfilePage.js?{$wgStyleVersion}\" ></script>\n" );

		$userContribsProvider = new UserContribsProviderService;

		$this->templateEngine->set_vars(
			array(
				'userName'         => $this->user->getName(),
				'activityFeedBody' => $this->renderUserActivityFeed( $userContribsProvider->get( 6, $this->user ) ),
				'topWikisBody'     => $this->renderTopSection( 'user-top-wikis', $this->getTopWikis(), $this->getHiddenTopWikis() ),
				'topPagesBody'     => $this->renderTopSection( 'user-top-pages', $this->getTopPages(), $this->getHiddenTopPages() ),
				'aboutSection'     => $this->populateAboutSectionVars(),
				'pageBody'         => $pageBody,
			));
		return $this->templateEngine->render( 'user-profile-page' );
	}

	/**
	 * render user's activity feed section
	 * @param array $data
	 * @return string
	 */
	private function renderUserActivityFeed( Array $data ) {
		global $wgBlankImgUrl;
		wfProfileIn(__METHOD__);

		$this->templateEngine->set_vars(
			array(
				'activityFeed' => $data,
				'assets' => array( 'blank' => $wgBlankImgUrl )
			)
		);

		wfProfileOut(__METHOD__);
		return $this->templateEngine->render( 'user-contributions' );
	}

	/**
	 * render user's top (pages or wikis) section
	 * @param string $sectionName
	 * @param array $topData
	 * @param array $topDataHidden
	 * @return string
	 */
	private function renderTopSection( $sectionName, Array $topData, Array $topDataHidden ) {
		wfProfileIn(__METHOD__);

		// create title objects for hidden pages, so we can get a valid urls
		$hidden = array();
		foreach( $topDataHidden as $pageTitleText ) {
			$title = Title::newFromText( $pageTitleText );
			if( $title instanceof Title ) {
				$hidden[] = array( 'title' => $title->getText(), 'url' => $title->getFullUrl() );
			}
		}

		$this->templateEngine->set_vars(
			array(
				'topData' => $topData,
				'topDataHidden' => $hidden
			)
		);

		wfProfileOut(__METHOD__);
		return $this->templateEngine->render( $sectionName );
	}


	private function populateAboutSectionVars() {
		global $wgOut;
		$sTitle = $this->user->getUserPage()->getText() . '/' . wfMsg( 'userprofilepage-about-article-title' );
		$oTitle = Title::newFromText( $sTitle, NS_USER );
		$oArticle = new Article($oTitle, 0);

		$oSpecialPageTitle = Title::newFromText( 'CreateFromTemplate', NS_SPECIAL );

		if( $oTitle->exists() ) {
			$sArticleBody = $wgOut->parse( $oArticle->getContent() );
			$sArticleEditUrl = $oTitle->getLocalURL( 'action=edit' );
		}
		else {
			$sArticleBody = wfMsg( 'userprofilepage-about-empty-section' );
			$sArticleEditUrl = $oSpecialPageTitle->getLocalURL( 'type=aboutuser&wpTitle=' . $oTitle->getPrefixedURL() . '&returnto=' . $this->user->getUserPage()->getFullUrl( 'action=purge' ) );
		}

		return array( 'body' => $sArticleBody, 'articleEditUrl' => $sArticleEditUrl );
	}

	/**
	 * get list of user's top pages (most edited)
	 *
	 * @author ADi
	 * @return array
	 */
	public function getTopPages() {
		global $wgMemc, $wgStatsDB, $wgCityId, $wgContentNamespaces;
		wfProfileIn(__METHOD__);

		//select page_id, count(page_id) from stats.events where wiki_id = N and user_id = N and event_type in (1,2) group by 1 order by 2 desc limit 10;
		$dbs = wfGetDB( DB_SLAVE, array(), $wgStatsDB );
		$res = $dbs->select(
			array( 'stats.events' ),
			array( 'page_id', 'count(page_id) AS count' ),
			array(
				'wiki_id' => $wgCityId,
				'user_id' => $this->user->getId() ),
				'event_type IN (1,2)',
				'page_ns IN (' . join( ',', $wgContentNamespaces ) . ')',
			__METHOD__,
			array(
				'GROUP BY' => 'page_id',
				'ORDER BY' => 'count DESC',
				'LIMIT' => 6
			)
		);

		/* revision
		$dbs = wfGetDB( DB_SLAVE );
		$res = $dbs->select(
			array( 'revision' ),
			array( 'rev_page', 'count(*) AS count' ),
			array( 'rev_user' => $this->user->getId() ),
			__METHOD__,
			array(
				'GROUP BY' => 'rev_page',
				'ORDER BY' => 'count DESC',
				'LIMIT' => 6
			)
		);
		*/

		// TMP: dev-box only
		$pages = array( 4 => 289, 1883 => 164, 1122 => 140, 31374 => 112, 2335 => 83, 78622 => 82 ); // test data
		foreach($pages as $pageId => $editCount) {
			$title = Title::newFromID( $pageId );
			if( ( $title instanceof Title ) && ( $title->getArticleID() != 0 ) && ( !$this->isTopPageHidden( $title->getText() ) ) ) {
				$pages[ $pageId ] = array( 'id' => $pageId, 'url' => $title->getFullUrl(), 'title' => $title->getText(), 'imgUrl' => null, 'editCount' => $editCount );
			}
			else {
				unset( $pages[ $pageId ] );
			}
		}

		/*
		$pages = array();
		while($row = $dbs->fetchObject($res)) {
			$pageId = $row->page_id;
			$title = Title::newFromID( $pageId );
			if( ( $title instanceof Title ) && ( $title->getArticleID() != 0 ) && ( !$this->isTopPageHidden( $title->getText() ) ) ) {
				$pages[ $pageId ] = array( 'id' => $pageId, 'url' => $title->getFullUrl(), 'title' => $title->getText(), 'imgUrl' => null, 'editCount' => $row->count );
			}
			else {
				unset( $pages[ $pageId ] );
			}
		}
		*/


		if( class_exists('imageServing') ) {
			// ImageServing extension enabled, get images
			$imageServing = new imageServing( array_keys( $pages ), 100, array( 'w' => 1, 'h' => 1 ) );
			$images = $imageServing->getImages(1); // get just one image per article

			foreach( $pages as $pageId => $data ) {
				if( isset( $images[$pageId] ) ) {
					$image = $images[$pageId][0];
					$data['imgUrl'] = $image['url'];
				}
				$pages[ $pageId ] = $data;
			}
		}

		wfProfileOut(__METHOD__);
		return $pages;
	}

	public function getTopWikis() {
		global $wgExternalDatawareDB;

		// SELECT lu_wikia_id, lu_rev_cnt FROM city_local_users WHERE lu_user_id=$userId ORDER BY lu_rev_cnt DESC LIMIT $limit;
		$dbs = wfGetDB(DB_SLAVE, array(), $wgExternalDatawareDB);
		$res = $dbs->select(
			array( 'city_local_users' ),
			array( 'lu_wikia_id', 'lu_rev_cnt' ),
			array( 'lu_user_id' => $this->user->getId() ),
			__METHOD__,
			array(
				'ORDER BY' => 'lu_rev_cnt DESC',
				'LIMIT' => 4
			)
		);

		$wikis = array();
		while($row = $dbs->fetchObject($res)) {
			$wikiId = $row->lu_wikia_id;
			$editCount = $row->lu_rev_cnt;
			$wikiName = WikiFactory::getVarValueByName( 'wgSitename', $wikiId );
			$wikiUrl = WikiFactory::getVarValueByName( 'wgServer', $wikiId );
			$wikiLogo = WikiFactory::getVarValueByName( "wgLogo", $wikiId );
			if( !$this->isTopWikiHidden( $wikiUrl ) ) {
				$wikis[$wikiId] = array( 'wikiName' => $wikiName, 'wikiUrl' => $wikiUrl, 'wikiLogo' => $wikiLogo, 'editCount' => $editCount );
			}
		}

		// TMP: local only
		$wikis = array( 4832 => 72, 3613 => 60, 4036 => 35, 177 => 72 ); // test data
		foreach($wikis as $wikiId => $editCount) {
			$wikiName = WikiFactory::getVarValueByName( 'wgSitename', $wikiId );
			$wikiUrl = WikiFactory::getVarValueByName( 'wgServer', $wikiId );
			$wikiLogo = WikiFactory::getVarValueByName( "wgLogo", $wikiId );

			if( !$this->isTopWikiHidden( $wikiUrl ) ) {
				$wikis[$wikiId] = array( 'wikiName' => $wikiName, 'wikiUrl' => $wikiUrl, 'wikiLogo' => $wikiLogo, 'editCount' => $editCount );
			}
		}
		//

		return $wikis;
	}

	/**
	 * perform action (hide/unhide page or wiki)
	 *
	 * @author ADi
	 * @param string $actionName
	 * @param string $type
	 * @param string $value
	 */
	public function doAction( $actionName, $type, $value) {
		wfProfileIn( __METHOD__ );
		$methodName = strtolower( $actionName ) . ucfirst( $type );

		if( method_exists( $this, $methodName ) ) {
			return call_user_func_array( array( $this, $methodName ), array( $value ) );
		}
		wfProfileOut( __METHOD__ );
	}

	private function hidePage( $pageTitleText ) {
		wfProfileIn( __METHOD__ );
		if( !$this->isTopPageHidden( $pageTitleText ) ) {
			$this->hiddenPages[] = $pageTitleText;
			$this->updateHiddenInDb( wfGetDB( DB_MASTER ), $this->hiddenPages );
		}
		return $this->renderTopSection( 'user-top-pages', $this->getTopPages(), $this->getHiddenTopPages() );
		wfProfileOut( __METHOD__ );
	}

	private function unhidePage( $pageTitleText ) {
		wfProfileIn( __METHOD__ );
		if( $this->isTopPageHidden( $pageTitleText ) ) {
			for( $i = 0; $i < count( $this->hiddenPages ); $i++ ) {
				if( $this->hiddenPages[ $i ] == $pageTitleText ) {
					unset( $this->hiddenPages[ $i ] );
					$this->hiddenPages = array_values( $this->hiddenPages );
				}
			}
			$this->updateHiddenInDb( wfGetDB( DB_MASTER ), $this->hiddenPages );
		}
		return $this->renderTopSection( 'user-top-pages', $this->getTopPages(), $this->getHiddenTopPages() );
		wfProfileOut( __METHOD__ );
	}

	/**
	 * auxiliary method for updating hidden pages in db
	 * @author ADi
	 */
	private function updateHiddenInDb( $dbHandler, $data ) {
		wfProfileIn( __METHOD__ );

		$dbHandler->replace(
			'page_wikia_props',
			null,
			array( 'page_id' => $this->user->getId(), 'propname' => 10, 'props' => serialize( $data ) ),
			__METHOD__
		);
		$dbHandler->commit();

		wfProfileOut( __METHOD__ );
	}

	public function isTopPageHidden( $pageTitleText ) {
		return ( in_array( $pageTitleText, $this->getHiddenTopPages() ) ? true : false );
	}

	public function isTopWikiHidden( $wikiUrl ) {
		return ( in_array( $wikiUrl, $this->getHiddenTopWikis() ) ? true : false );
	}

	private function hideWiki( $wikiUrl) {
		wfProfileIn( __METHOD__ );
		global $wgExternalSharedDB;

		if( !$this->isTopWikiHidden( $wikiUrl ) ) {
			$this->hiddenWikis[] = $wikiUrl;
			$this->updateHiddenInDb( wfGetDB( DB_MASTER, array(), $wgExternalSharedDB ), $this->hiddenWikis );
		}

		return $this->renderTopSection( 'user-top-wikis', $this->getTopWikis(), $this->getHiddenTopWikis() );
		wfProfileOut( __METHOD__ );
	}

	private function unhideWiki( $wikiUrl ) {
		wfProfileIn( __METHOD__ );
		global $wgExternalSharedDB;

		if( $this->isTopWikiHidden( $wikiUrl) ) {
			for( $i = 0; $i < count( $this->hiddenWikis ); $i++ ) {
				if( $this->hiddenWikis[ $i ] == $wikiUrl ) {
					unset( $this->hiddenWikis[ $i ] );
					$this->hiddenWikis = array_values( $this->hiddenWikis );
				}
			}
			$this->updateHiddenInDb( wfGetDB( DB_MASTER, array(), $wgExternalSharedDB ), $this->hiddenWikis );
		}

		return $this->renderTopSection( 'user-top-wikis', $this->getTopPages(), $this->getHiddenTopPages() );
		wfProfileOut( __METHOD__ );
	}

	private function getHiddenTopPages() {
		wfProfileIn( __METHOD__ );

		if( $this->hiddenPages == null ) {
			$dbs = wfGetDB( DB_SLAVE );
			$this->hiddenPages = $this->getHiddenFromDb( $dbs );
		}

		wfProfileOut( __METHOD__ );
		return $this->hiddenPages;
	}

	private function getHiddenTopWikis() {
		wfProfileIn( __METHOD__ );
		global $wgExternalSharedDB;

		if( $this->hiddenWikis == null ) {
			$dbs = wfGetDB( DB_SLAVE, array(), $wgExternalSharedDB);
			$this->hiddenWikis = $this->getHiddenFromDb( $dbs );
		}

		wfProfileOut( __METHOD__ );
		return $this->hiddenWikis;
	}

	/**
	 * auxiliary method for getting hidden pages/wikis from db
	 * @author ADi
	 */
	private function getHiddenFromDb( $dbHandler ) {
		$row = $dbHandler->selectRow(
			array( 'page_wikia_props' ),
			array( 'props' ),
			array( 'page_id' => $this->user->getId() , 'propname' => 10 ),
			__METHOD__,
			array()
		);
		return ( empty($row) ? array() : unserialize( $row->props ) );
	}

}
