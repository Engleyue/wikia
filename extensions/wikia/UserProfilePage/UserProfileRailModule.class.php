<?php
class UserProfileRailModule extends Module {
	var $hiddenTopWikis;
	var $topWikis;
	var $userIsOwner;
	var $userPageUrl;
	var $activityFeed;
	var $specialContribsLink;
	var $topPages;
	var $hiddenTopPages;
	var $topPageImages;
	var $specialRandomLink;
	var $maxEdits;
	var $userRegistrationDate;
	private $maxTopPages = 6;
	private $maxTopWikis = 5;
	private $commentToEditRatio = 0.3;

	public function executeTopWikis() {
		wfProfileIn( __METHOD__ );

		$user = UserProfilePage::getInstance()->getUser();
		$this->topWikis = $this->getTopWikis();
		$this->hiddenTopWikis = $this->getHiddenTopWikis();
		$this->userIsOwner = UserProfilePage::getInstance()->userIsOwner();
		$this->userName =  $user->getName();
		$thos->userPageUrl = $user->getUserPage()->getLocalUrl();
		$this->maxEdits = 0;

		foreach ( $this->topWikis as $wikiId => $wikiData ) {
			if( in_array( $wikiId, $this->hiddenTopWikis ) ) {
				unset( $this->topWikis[ $wikiId ] );
			} elseif ( $wikiData[ 'editCount' ] > $this->maxEdits ) {
				$this->maxEdits = $wikiData[ 'editCount' ];
			}
		}

		wfProfileOut( __METHOD__ );
	}

	public function executeTopPages() {
		wfProfileIn( __METHOD__ );
		global $wgCityId;

		$this->topPages = $this->getTopPages();
		$this->hiddenTopPages = $this->getHiddenTopPages();
		$this->userName =  UserProfilePage::getInstance()->getUser()->getName();
		$this->userIsOwner = UserProfilePage::getInstance()->userIsOwner();
		$this->wikiName = WikiFactory::getVarValueByName( 'wgSitename', $wgCityId );

		$specialPageTitle = Title::newFromText( 'Random', NS_SPECIAL );
		$this->specialRandomLink = $specialPageTitle->getFullUrl();

		foreach ( $this->topPages as $pageId => $pageData ) {
			if( in_array( $pageData[ 'title' ], $this->hiddenTopPages ) ) {
				unset( $this->topPages[ $pageId ] );
			}
		}

		if( class_exists('imageServing') ) {
			// ImageServing extension enabled, get images
			$imageServing = new imageServing( array_keys( $this->topPages ), 70, array( 'w' => 2, 'h' => 3 ) );//80px x 120px
			$this->topPageImages = $imageServing->getImages(1); // get just one image per article
		}

		wfProfileOut( __METHOD__ );
	}

	public function getTopWikis() {
		wfProfileIn( __METHOD__ );
		global $wgStatsDB, $wgDevelEnvironment;

		$dbs = wfGetDB(DB_SLAVE, array(), $wgStatsDB);
		$res = $dbs->select(
			array( 'specials.events_local_users' ),
			array( 'wiki_id', 'edits' ),
			array( 'user_id' => UserProfilePage::getInstance()->getUser()->getId() ),
			__METHOD__,
			array(
				'ORDER BY' => 'edits DESC',
				'LIMIT' => $this->maxTopWikis
			)
		);

		$wikis = array();

		if( $wgDevelEnvironment ) {//DevBox test
			$wikis = array(
				4832 => 72,
				831 => 60,
				4036 => 35,
				177 => 12,
				1890 => 5
			); // test data

			foreach($wikis as $wikiId => $editCount) {
				$wikiName = WikiFactory::getVarValueByName( 'wgSitename', $wikiId );
				$wikiUrl = WikiFactory::getVarValueByName( 'wgServer', $wikiId );
				$wikiLogo = WikiFactory::getVarValueByName( "wgLogo", $wikiId );
				$themeSettings = WikiFactory::getVarValueByName( 'wgOasisThemeSettings', $wikiId);

				if( isset($themeSettings['wordmark-image-url']) ) {
					$wikiLogo = $themeSettings['wordmark-image-url'];
				}
				elseif( isset($themeSettings['wordmark-text']) ) {
					$wikiLogo = '';
					$wordmarkText = '<span style="color: ' . $themeSettings['color-header'] . '">' .$themeSettings['wordmark-text'] . '</span>';
				}
				else {
					$wordmarkText = '';
				}

				$wikis[$wikiId] = array( 'wikiName' => $wikiName, 'wikiUrl' => $wikiUrl, 'wikiLogo' => $wikiLogo, 'wikiWordmarkText' => $wordmarkText, 'editCount' => $editCount );
			}

		} else {
			while ( $row = $dbs->fetchObject( $res ) ) {
				$wikiId = $row->wiki_id;
				$editCount = $row->edits;
				$wikiName = WikiFactory::getVarValueByName( 'wgSitename', $wikiId );
				$wikiUrl = WikiFactory::getVarValueByName( 'wgServer', $wikiId );
				$wikiLogo = WikiFactory::getVarValueByName( "wgLogo", $wikiId );
				$themeSettings = WikiFactory::getVarValueByName( 'wgOasisThemeSettings', $wikiId);

				if( isset($themeSettings['wordmark-image-url']) ) {
					$wikiLogo = $themeSettings['wordmark-image-url'];
				}
				elseif( isset($themeSettings['wordmark-text']) ) {
					$wikiLogo = '';
					$wordmarkText = '<span style="color: ' . $themeSettings['color-header'] . '">' .$themeSettings['wordmark-text'] . '</span>';
				}
				else {
					$wordmarkText = '';
				}

				$wikis[$wikiId] = array( 'wikiName' => $wikiName, 'wikiUrl' => $wikiUrl, 'wikiLogo' => $wikiLogo, 'wikiWordmarkText' => $wordmarkText, 'editCount' => $editCount );
			}
		}

		wfProfileOut( __METHOD__ );
		return $wikis;
	}

	public function getHiddenTopWikis() {
		wfProfileIn( __METHOD__ );
		global $wgExternalSharedDB, $wgDevelEnvironment;

		$dbs = wfGetDB( DB_SLAVE, array(), $wgExternalSharedDB);
		$wikis = UserProfilePage::getInstance()->getHiddenFromDb( $dbs );

		if( $wgDevelEnvironment ) {//DevBox test
			$wikis = array(
				933 => 72,
				2677 => 60,
				899 => 35,
				1057 => 12
			); // test data

			foreach($wikis as $wikiId => $editCount) {
				$wikiName = WikiFactory::getVarValueByName( 'wgSitename', $wikiId );
				$wikiUrl = WikiFactory::getVarValueByName( 'wgServer', $wikiId );
				$wikiLogo = WikiFactory::getVarValueByName( "wgLogo", $wikiId );
				$themeSettings = WikiFactory::getVarValueByName( 'wgOasisThemeSettings', $wikiId);

				if( isset($themeSettings['wordmark-image-url']) ) {
					$wikiLogo = $themeSettings['wordmark-image-url'];
				}
				elseif( isset($themeSettings['wordmark-text']) ) {
					$wikiLogo = '';
					$wordmarkText = '<span style="color: ' . $themeSettings['color-header'] . '">' .$themeSettings['wordmark-text'] . '</span>';
				}
				else {
					$wordmarkText = '';
				}

				$wikis[$wikiId] = array( 'wikiName' => $wikiName, 'wikiUrl' => $wikiUrl, 'wikiLogo' => $wikiLogo, 'wikiWordmarkText' => $wordmarkText, 'editCount' => $editCount );
			}

		}

		wfProfileOut( __METHOD__ );

		return $wikis;
	}

	/**
	 * get list of user's top pages (most edited)
	 *
	 * @author ADi
	 * @return array
	 */
	public function getTopPages() {
		global $wgMemc, $wgStatsDB, $wgCityId, $wgContentNamespaces, $wgDevelEnvironment;
		wfProfileIn(__METHOD__);

		//select page_id, count(page_id) from stats.events where wiki_id = N and user_id = N and event_type in (1,2) group by 1 order by 2 desc limit 10;
		$dbs = wfGetDB( DB_SLAVE, array(), $wgStatsDB );
		$res = $dbs->select(
			array( $wgStatsDB . '.events' ),
			array( 'page_id', 'count(page_id) AS count' ),
			array(
				'wiki_id' => $wgCityId,
				'user_id' => UserProfilePage::getInstance()->getUser()->getId(),
				'event_type IN (1,2)',
				'page_ns IN (' . join( ',', $wgContentNamespaces ) . ')'
			),
			__METHOD__,
			array(
				'GROUP BY' => 'page_id',
				'ORDER BY' => 'count DESC',
				'LIMIT' => $this->maxTopPages
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

		$pages = array();
		if( $wgDevelEnvironment ) { //DevBox test
			$pages = array( 4 => 28, 1883 => 16, 1122 => 14, 31374 => 11, 2335 => 8, 78622 => 3 ); // test data
		} else {
			while( $row = $dbs->fetchObject($res) ) {
				$pages[ $row->page_id ] = $row->count;
			}
		}

		// get top commented pages and merge
		foreach( $this->getTopCommentedPages() as $pageId => $commentCount ) {
			$commentPoints = round( $commentCount * $this->commentToEditRatio );
			if( isset( $pages[ $pageId ] ) ) {
				$pages[ $pageId ] += $commentPoints;
			}
			else {
				$pages[ $pageId ] = $commentPoints;
			}
		}

		arsort( $pages );

		$articleService = new ArticleService();
		foreach($pages as $pageId => $editCount) {
			$title = Title::newFromID( $pageId );
			if( ( $title instanceof Title ) && ( $title->getArticleID() != 0 ) ) {
				$articleService->setArticleById( $title->getArticleID() );
				$pages[ $pageId ] = array( 'id' => $pageId, 'url' => $title->getFullUrl(), 'title' => $title->getText(), 'imgUrl' => null, 'editCount' => $editCount, 'textSnippet' => $articleService->getTextSnippet( 100 ) );
			}
			else {
				unset( $pages[ $pageId ] );
			}
		}

		wfProfileOut(__METHOD__);
		return array_slice( $pages, 0, $this->maxTopPages );
	}

	public function getTopCommentedPages() {
		global $wgMemc, $wgArticleCommentsNamespaces, $wgEnableArticleCommentsExt;
		wfProfileIn(__METHOD__);

		$talkNamespaces = array();

		if( is_array($wgArticleCommentsNamespaces) ) {
			foreach( $wgArticleCommentsNamespaces as $ns ) {
				$talkNamespaces[] = MWNamespace::getTalk( $ns );
			}
		}

		if( count($talkNamespaces) == 0 || empty($wgEnableArticleCommentsExt) ) {
			wfProfileOut(__METHOD__);
			return array();
		}

		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select(
			array( 'page', 'revision' ),
			array( 'page_title', 'page_namespace' ),
			array(
				'page_id=rev_page',
				'rev_user' => UserProfilePage::getInstance()->getUser()->getId(),
				'page_namespace IN (' . join( ',', $talkNamespaces ) . ')',
			),
			__METHOD__,
			array()
		);

		$pages = array();
		while($row = $dbr->fetchObject($res)) {
			if( strpos( $row->page_title, '@comment') !== false ) {
				$commentData = ArticleComment::explode( $row->page_title );
				if( !empty( $commentData ) ) {
					$title = Title::newFromText( $commentData['title'], MWNamespace::getSubject( $row->page_namespace )   );
					if( isset( $pages[$title->getArticleId()] ) ) {
						$pages[$title->getArticleId()]++;
					}
					else {
						$pages[$title->getArticleId()] = 1;
					}
				}
			}
		}
		arsort(  $pages );

		wfProfileOut(__METHOD__);
		return $pages;
	}

	public function getHiddenTopPages() {
		global $wgDevelEnvironment;

		wfProfileIn( __METHOD__ );

		$dbs = wfGetDB( DB_SLAVE );
		$pages = UserProfilePage::getInstance()->getHiddenFromDb( $dbs );

		if( $wgDevelEnvironment ) {//DevBox test
			$pages = array( 8 => 289, 456 => 164, 2345 => 140, 12322 => 112, 66 => 83, 6767 => 82 ); // test data
			foreach($pages as $pageId => $editCount) {
				$title = Title::newFromID( $pageId );
				if( ( $title instanceof Title ) && ( $title->getArticleID() != 0 ) ) {
					$pages[ $pageId ] = array( 'id' => $pageId, 'url' => $title->getFullUrl(), 'title' => $title->getText(), 'imgUrl' => null, 'editCount' => $editCount );
				}
				else {
					unset( $pages[ $pageId ] );
				}
			}

		}

		wfProfileOut( __METHOD__ );
		return $pages;
	}

	/**
	 * adds the hook for own JavaScript variables in the document
	 */
	/*public function __construct() {
		global $wgHooks;
		$wgHooks['MakeGlobalVariablesScript'][] = 'UserProfileTopWikisModule::addAchievementsJSVariables';
	}*/


	/**
	 * adds JavaScript variables inside the page source, cl
	 *
	 * @param mixed $vars the main vars for the JavaScript printout
	 *
	 */
	/*static function addAchievementsJSVariables (&$vars) {
		$lang_view_all = wfMsg('achievements-viewall-oasis');
		$lang_view_less = wfMsg('achievements-viewless');
		$vars['wgAchievementsMoreButton'] = array($lang_view_all, $lang_view_less);
		return true;
	}*/
}