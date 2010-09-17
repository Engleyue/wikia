<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 * @author Jakub Kurcek
 *
 * Returns WidgetBox formatted RSS / Atom Feeds
 */

class WidgetBoxRSS extends SpecialPage {
	var $mName, $mPassword, $mRetype, $mReturnto, $mCookieCheck, $mPosted;
	var $mAction, $mCreateaccount, $mCreateaccountMail, $mMailmypassword;
	var $mLoginattempt, $mRemember, $mEmail, $mBrowser;
	var $err;

	function  __construct() {
		parent::__construct( "WidgetBoxRSS" , '' /*restriction*/);
		wfLoadExtensionMessages("WidgetBoxRSS");
	}

	function execute() {
		global $wgLang, $wgAllowRealName, $wgRequest, $wgOut, $wgHubsPages;

		$this->mName = $wgRequest->getText( 'wpName' );
		$this->mRealName = $wgRequest->getText( 'wpContactRealName' );
		$this->mWhichWiki = $wgRequest->getText( 'wpContactWikiName' );
		$this->mProblem = $wgRequest->getText( 'wpContactProblem' );
		$this->mProblemDesc = $wgRequest->getText( 'wpContactProblemDesc' );
		$this->mPosted = $wgRequest->wasPosted();
		$this->mAction = $wgRequest->getVal( 'action' );
		$this->mEmail = $wgRequest->getText( 'wpEmail' );
		$this->mBrowser = $wgRequest->getText( 'wpBrowser' );
		$this->mCCme = $wgRequest->getCheck( 'wgCC' );

		$feed = $wgRequest->getText( "feed", false );
		$feedType = $wgRequest->getText ( "type", false );

		if (	$feed
			&& $feedType
			&& in_array( $feed, array( "rss", "atom" ) )
		) {
			switch( $feedType ){
				case 'AchivementsLeaderboard':
					$this->FeedAchivementsLeaderboard( $feed );
				break;
				case 'RecentImages':
					$this->FeedRecentImages( $feed );
				break;
				case 'RecentBadges':
					$this->FeedRecentBadges( $feed );
				break;
				case 'HotContent':
					$this->FeedHotContent( $feed );
				break;
				case 'RecentBlogPosts':
					$this->FeedRecentBlogPosts ( $feed );
				break;
				default :
					$this->showFeed( $feed );
				break;
			}
		} else {
			$oTmpl = new EasyTemplate( dirname( __FILE__ ) . "/templates/" );
			$wgOut->addHTML( $oTmpl->execute( "main-page" ) );
		}
		return;
		
	}
/**
 * @author Jakub Kurcek
 * @param format string 'rss' or 'atom'
 *
 */
	private function FeedRecentBlogPosts ( $format ){
		
		global $wgParser, $wgUser, $wgServer, $wgOut, $wgExtensionsPath, $wgRequest;

		// local settings
		$maxNumberOfBlogPosts = 10;

		// If blog listing does not exit treats parameter as empty;
		$sListing = $wgRequest->getVal( 'listing' );
		if ( !empty( $sListing ) && !Title::newFromText( $sListing, 502 )->exists() ){
			unset($sListing);
		};

		$oBlogListing = new CreateBlogListingPage;
		$oBlogListing->setFormData('listingAuthors', '');
		$oBlogListing->setFormData('tagContent', '');
		if ( !empty( $sListing ) ){
			$oBlogListing->parseTag( urldecode( $sListing ) );
			$subTitleName = wfMsg('blog-posts-from-listening', $sListing);
		} else {
			$oBlogListing->setFormData('listingCategories', '');
			$subTitleName = wfMsg('all-blog-posts');
		}

		$input = $oBlogListing->buildTagContent();

		$db = wfGetDB( DB_SLAVE, 'dpl' );

		$params = array (
			"summary" => true,
			"timestamp" => true,
			"count" => $maxNumberOfBlogPosts,
		);

		$result = BlogTemplateClass::parseTag( $input, $params, $wgParser, true );
		$feedArray = array();
		foreach( $result as $val ){
			$aValue = explode('/' , $val['title']);

			$feedArray[] = array(
				'title' =>  str_replace( '&nbsp;', ' ', strip_tags( $aValue[1] ) ),
				'description' => str_replace( '&nbsp;', ' ', strip_tags( $val['text'] ) ),
				'url' => $wgServer.$val['userpage'],
				'date' => $val['date'],
				'author' => $val['username'],
				'otherTags' => array(
					'image' => preg_replace('/<img.*src="(.*?)".*\/?>/', '$1', $val['avatar']),
				)
			);
		}
		$this->showFeed( $format , wfMsg('feed-title-blogposts').' - '.$subTitleName,  $feedArray);
	}

/**
 * @author Jakub Kurcek
 * @param format string 'rss' or 'atom'
 */
	private function FeedRecentBadges ( $format ){

		global $wgUser, $wgOut, $wgExtensionsPath, $wgServer;
		wfLoadExtensionMessages( 'AchievementsII' );

		// local settings
		$howOld = 30;
		$maxBadgesToDisplay = 6;
		$badgeImageSize = 40;
		
		$rankingService = new AchRankingService();

		// ignore welcome badges
		$blackList = array(BADGE_WELCOME);

		$awardedBadges = $rankingService->getRecentAwardedBadges( null, $maxBadgesToDisplay, $howOld, $blackList );

		$recents = array();
		$count = 1;

		$feedArray = array();
		
		// getRecentAwardedBadges can sometimes return more than $max items
		foreach ( $awardedBadges as $badgeData ) {
			$recents[] = $badgeData;
			$descriptionText = wfMsg('achievements-recent-info',
				$badgeData['user']->getUserPage()->getLocalURL(),
				$badgeData['user']->getName(),
				$badgeData['badge']->getName(),
				$badgeData['badge']->getGiveFor(),
				wfTimeFormatAgo($badgeData['date'])
			);
			$descriptionText = preg_replace('/<br\s*\/*>/i',"$1 $2",$descriptionText);
			$descriptionText = strip_tags($descriptionText);
			$feedArray[] = array (
				'title' => $badgeData['user']->mName ,
				'description' => $descriptionText,
				'url' => $badgeData['user']->getUserPage()->getFullURL(),
				'date' => $badgeData['date'],
				'author' => '',
				'otherTags' => array(
				    'image' => $wgServer.$badgeData['badge']->getPictureUrl($badgeImageSize),
				)
			);

			if ( $count++ >= $maxBadgesToDisplay ){
				break;
			}
		}

		$this->showFeed( $format , wfMsg('feed-title-recent-badges'),  $feedArray);

	}

/**
 * @author Jakub Kurcek
 * @param format string 'rss' or 'atom'
 */
	private function FeedRecentImages ( $format ){
		
		global $wgTitle, $wgLang, $wgRequest;

		// local settings
		$maxImagesNumber = 20;
		$defaultWidth = 150;
		$defaultHeight = 75;

		$imageServing = new imageServing( array(), $defaultWidth,array("w" => 2, "h" => 1) );
		$dbw = wfGetDB( DB_SLAVE );

		$res = $dbw->select( 'image',
				array( "img_name", "img_user_text", "img_size", "img_width", "img_height" ),
				array(
					"img_media_type != 'VIDEO'",
					"img_width > 32",
					"img_height > 32"
				),
				false,
				array(
					"ORDER BY" => "img_timestamp DESC",
					"LIMIT" => $maxImagesNumber
				)
		);

		$thumbSize = $wgRequest->getText ( "size", false );
		
		if ( $defaultWidth ){
			$thumbSize = ( integer )$thumbSize;
		} else {
			$thumbSize = $defaultThumbSize;
		}

		$feedArray = array();
		
		while ( $row = $dbw->fetchObject( $res ) ) {
			
			$tmpTitle = Title::newFromText( $row->img_name, NS_FILE );
			$image = wfFindFile( $tmpTitle );

			if ( !$image ) continue;

			$testImage = wfReplaceImageServer(
				$image->getThumbUrl(
					$imageServing->getCut($row->img_width, $row->img_height)."-".$image->getName()
				)
			);

			$feedArray[] = array (
				'title' => '',
				'description' => '',
				'url' => '',
				'date' => $image->getTimestamp(),
				'author' => $row->img_user_text,
				'otherTags' => array(
						'image' => $testImage
					)
			);
		}

		$this->showFeed( $format , wfMsg('feed-title-recent-images'),  $feedArray);
	}

/**
 * @author Jakub Kurcek
 * @param format string 'rss' or 'atom'
 */
	private function FeedHotContent ( $format ) {

		global $wgRequest;

		$defaultHubTitle ='tv';

		$hubTitle = $wgRequest->getVal( 'hub' );
		$allowedHubs = $this->allowedHubs();
		
		if ( isset( $allowedHubs[ $hubTitle ] ) && !is_array( $allowedHubs[ $hubTitle ] ) ){
			$oTitle = Title::newFromText( $hubTitle, 150 );
		} else {
			$oTitle = Title::newFromText( $defaultHubTitle, 150 );
		}
		$hubId = AutoHubsPagesHelper::getHubIdFromTitle( $oTitle );
		$feedArray = $this->PrepareHotContentFeed( $hubId );
		$this->showFeed( $format, 'Achivements leaderboard - '. $oTitle->getText(),  $feedArray );
	}
	
/**
 * @author Jakub Kurcek
 * @param hubId integer
 * @param forceRefresh boolean - if true clears the cache and creates new one.
 *
 * Returns data for feed creation. If no cache - creates one.
 */
	private function PrepareHotContentFeed ( $hubId, $forceRefresh = false ){

		global $wgMemc;

		// local settings
		$lang = "en";
		$thumbSize = 75;
		$resultsNumber = 10;
		$isDevBox = false; // switch to false after tests
		$stopCache = false;  // switch to false after tests

		if ( $forceRefresh ) $this->clearCache( $hubId );
		$memcFeedArray = $this->getFromCache( $hubId );
		if ( $memcFeedArray == null  || $stopCache ){
		
			$datafeeds = new WikiaStatsAutoHubsConsumerDB( DB_SLAVE );
			$out = $datafeeds->getTopArticles($hubId, $lang, $resultsNumber);
			$feedArray = array();
			foreach( $out['value'] as $key => $val ){

				if ( $isDevBox ){ // fake DevBox data
					$fakePageId = array( 119949, 119950, 32, 49, 83, 54 );
					$httpResult = Http::get( 'http://muppets.jakub.wikia-dev.com/api.php?action=imagecrop&imgId='.$fakePageId[rand(0,5)].'&imgSize='.$thumbSize.'&format=json&timestamp='.rand( 0,time() ) );
				}else{
					$httpResult = Http::get( $val['wikiurl'].'/api.php?action=imagecrop&imgId='.$val['page_id'].'&imgSize=75&format=json' );
				}
				$httpResultArr = json_decode( $httpResult );
				$feedArray[] = array(
					'title' =>  $val['page_name'],
					'description' => $val['all_count'],
					'url' => $val['page_url'],
					'date' => time(),
					'author' => 'Wikia',
					'otherTags' => array(
						'image' => ( isset($httpResultArr->image->imagecrop ) ) ? $httpResultArr->image->imagecrop : ''
					)
				);
			}
			$this->saveToCache( $hubId, $feedArray );

		} else {
			$feedArray = $memcFeedArray;
		}
		return $feedArray;
		
	}
/**
 * @author Jakub Kurcek
 * @param hubId integer
 *
 * Public controller for forced caching of specified hub results. Used for maintance script.
 */

	public function ReloadHotContentFeed ( $hubId ){

		$this->PrepareHotContentFeed( (integer) $hubId , true);
		
	}

/**
 * @author Jakub Kurcek
 * @param hubId integer
 * @param content array
 *
 * Caching functions.
 */

	private function getKey( $hubId ) {
		
		return wfSharedMemcKey( 'widgetbox_hub_hotcontent', $hubId );
	}

	private function saveToCache( $hubId, $content ) {

		global $wgMemc;
		$memcData = $this->getFromCache( $hubId );
		if ( $memcData == null ){
			$wgMemc->set( $this->getKey( $hubId ), $content );
			return false;
		}
		return true;
	}

	private function getFromCache ( $hubId ){

		global $wgMemc;
		return $wgMemc->get( $this->getKey( $hubId ) );
	}

	private function clearCache ( $hubId ){

		global $wgMemc;
		return $wgMemc->delete( $this->getKey( $hubId ) );
	}

/**
 * @author Jakub Kurcek
 *
 * Returns array of accepted hubs. 
 */

	public function allowedHubs (){

		global $wgHubsPages;
		return $wgHubsPages['en'];
	}
	
/**
 * @author Jakub Kurcek
 * @param format string 'rss' or 'atom'
 */
	private function FeedAchivementsLeaderboard ( $format ) {

		global $wgLang, $wgServer, $wgOut, $wgExtensionsPath, $wgStyleVersion, $wgSupressPageTitle, $wgUser, $wgWikiaBotLikeUsers, $wgJsMimeType;
		wfLoadExtensionMessages('AchievementsII');

		// local settings
		$maxEntries = 20;
		$howOld = 30;
		
		$rankingService = new AchRankingService();
		$ranking = $rankingService->getUsersRanking(20, true);

		// recent
		$levels = array(BADGE_LEVEL_PLATINUM, BADGE_LEVEL_GOLD, BADGE_LEVEL_SILVER, BADGE_LEVEL_BRONZE);
		$recents = array();
		
		foreach($levels as $level) {
			$limit = 3;
			$blackList = null;
			if($level == BADGE_LEVEL_BRONZE) {
				if($maxEntries <= 0) break;

				$limit = $maxEntries;
				$blackList = array(BADGE_WELCOME);
			}

			$awardedBadges = $rankingService->getRecentAwardedBadges($level, $limit, 3, $blackList);

			if ( $total = count ( $awardedBadges ) ) {
				$recents[$level] = $awardedBadges;
				$maxEntries -= $total;
			}
		}
		$feedArray = array();
		foreach($ranking as $rank => $rankedUser){
			++$rank;
			$name = htmlspecialchars( $rankedUser->getName() );
			$feedArray[] = array(
				'title' =>  $rank,
				'description' => $name,
				'url' => $wgServer.$rankedUser->getUserPageUrl(),
				'date' => time(),
				'author' => 'Wikia',
				'',
				'otherTags' => array(
					'image' => $rankedUser->getAvatarUrl(),
					'score' => $wgLang->formatNum( $rankedUser->getScore() )
				)
			);			
  		}

		$this->showFeed( $format , wfMsg('feed-title-leaderboard'),  $feedArray);
	}

/**
 * @author Jakub Kurcek
 * @param format string 'rss' or 'atom'
 * @param subtitle string
 * @param feedData array
 *
 * returns RSS/Atom feed
 */
	private function showFeed( $format, $subtitle, $feedData ) {
		
		global $wgOut, $wgRequest, $wgParser, $wgMemc, $wgTitle;
		global $wgSitename;
		wfProfileIn( __METHOD__ );
		$sFeedName = self::getFeedClass( $format );
		$feed = new $sFeedName( wfMsg('feed-main-title'),  $subtitle, $wgTitle->getFullUrl() );
		$feed->outHeader();
		foreach ( $feedData as $val ) {
			$item = new ExtendedFeedItem(
				$val['title'],
				$val['description'],
				$val['url'],
				$val['date'],
				$val['author'],
				'',
				$val['otherTags']
				
			);
			$feed->outItem( $item );
		}
		$feed->outFooter();
		wfProfileOut( __METHOD__ );
	}

	private static function getFeedClass ( $format ){
		
		if ( $format == 'atom' ){
			return 'WidgetBoxAtomFeed';
		} else {
			return 'WidgetBoxRSSFeed';
		}
	}
}

