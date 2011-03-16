<?php
/**
 * Renders header for user pages (profile / talk pages, Following, Contributions, blogs) with avatar and user's data
 *
 * @author Maciej Brencz
 */

class UserPagesHeaderModule extends Module {
	
	var $wgBlankImgUrl;
	var $wgStylePath;
	
	var $content_actions;
	var $displaytitle;
	var $subtitle;
	var $title;
	
	var $actionButton;
	var $actionImage;
	var $actionMenu;
	var $actionName;
	var $avatar;
	var $avatarMenu;
	var $comments;
	var $editTimestamp;
	var $likes;
	var $stats;
	var $tabs;
	var $userName;
	var $userPage;
	var $isUserProfilePageExt = false;
	
	
	var $fbAccessRequestURL;
	var $fbUser;
	var $fbData;
	
	/**
	 * Checks whether given user name is the current user
	 */
	public static function isItMe($userName) {
		global $wgUser;
		return $wgUser->isLoggedIn() && ($userName == $wgUser->getName());
	}
	
	/**
	 * Get name of the user this page referrs to
	 */
	public static function getUserName(Title $title, $namespaces, $fallbackToGlobal = true) {
		wfProfileIn(__METHOD__);
		global $wgUser, $wgRequest;
		
		$userName = null;
		
		if (in_array($title->getNamespace(), $namespaces)) {
			// get "owner" of this user / user talk / blog page
			$parts = explode('/', $title->getText());
		}
		else if ($title->getNamespace() == NS_SPECIAL) {
				if ($title->isSpecial( 'Following' ) || $title->isSpecial( 'Contributions' )) {
					$target = $wgRequest->getText('target');
					if ($target != '') {
						// /wiki/Special:Contributions?target=FooBar (RT #68323)
						$parts = array($target);
					}
					else {
						// get user this special page referrs to
						$parts = explode('/', $wgRequest->getText('title', false));
						
						// remove special page name
						array_shift($parts);
					}
				}
			}
		
		if (isset($parts[0]) && $parts[0] != '') {
			//this line was usign urldecode($parts[0]) before, see RT #107278, user profile pages with '+' symbols get 'non-existing' message
			$userName = str_replace('_', ' ', $parts[0] );
		}
		elseif ( $fallbackToGlobal ) {
			// fallback value
			$userName = $wgUser->getName();
		}
		
		wfProfileOut(__METHOD__);
		return $userName;
	}
	
	/**
	 * Get list of links for given username to be shown as tabs
	 */
	private function getTabs($userName) {
		wfProfileIn(__METHOD__);
		global $wgTitle, $wgUser, $wgEnableWikiaFollowedPages;
		
		$tabs = array();
		$namespace = $wgTitle->getNamespace();
		
		// profile
		$tabs[] = array(
				'link' => View::link(Title::newFromText($userName, NS_USER), wfMsg('profile')),
				'selected' => ($namespace == NS_USER),
				);
		
		// talk
		$tabs[] = array(
				'link' => View::link(Title::newFromText($userName, NS_USER_TALK), wfMsg('talkpage')),
				'selected' => ($namespace == NS_USER_TALK),
				);
		
		// blog
		if (defined('NS_BLOG_ARTICLE') && !User::isIP($this->userName)) {
			$tabs[] = array(
					'link' => View::link(Title::newFromText($userName, NS_BLOG_ARTICLE), wfMsg('blog-page'), array(), array(), 'known'),
					'selected' => ($namespace == NS_BLOG_ARTICLE),
					);
		}
		
		// contribs
		$tabs[] = array(
				'link' => View::link(SpecialPage::getTitleFor("Contributions/{$userName}"), wfMsg('contris_s')),
				'selected' => ($wgTitle->isSpecial( 'Contributions' )),
				);
		
		if (self::isItMe($userName)) {
			// following (only render when user is viewing his own user pages)
			if (!empty($wgEnableWikiaFollowedPages)) {
				$tabs[] = array(
						'link' => View::link(SpecialPage::getTitleFor('Following'), wfMsg('wikiafollowedpages-following')),
						'selected' => ($wgTitle->isSpecial( 'Following' )),
						);
			}
			
			// avatar dropdown menu
			$this->avatarMenu = array(
					View::link(SpecialPage::getTitleFor('Preferences'), wfMsg('oasis-user-page-change-avatar'))
					);
		}
		
		wfProfileOut(__METHOD__);
		return $tabs;
	}
	
	/**
	 * Get and format stats for given user
	 */
	private function getStats($userName) {
		wfProfileIn(__METHOD__);
		global $wgLang;
		
		$user = User::newFromName($userName);
		$stats = array();
		
		if (!empty($user) && $user->isLoggedIn()) {
			$userStatsService = new UserStatsService($user->getId());
			$stats = $userStatsService->getStats();
			
			if (!empty($stats)) {
				// date and points formatting
				$stats['date'] = $wgLang->date(wfTimestamp(TS_MW, $stats['date']));
				$stats['edits'] = $wgLang->formatNum($stats['edits']);
			}
		}
		
		wfProfileOut(__METHOD__);
		return $stats;
	}
	
	public function executeIndex() {
		wfProfileIn(__METHOD__);
		global $wgTitle, $wgEnableUserProfilePagesExt, $wgRequest, $wgUser, $wgOut;
		
		$namespace = $wgTitle->getNamespace();
		
		// get user name to display in header
		$this->userName = self::getUserName($wgTitle, BodyModule::getUserPagesNamespaces());
		$this->isUserProfilePageExt = ( !empty( $wgEnableUserProfilePagesExt ) && UserProfilePage::isAllowed() );
		
		// render avatar (100x100)
		$this->avatar = AvatarService::renderAvatar($this->userName, 100);
		$this->lastActionData = array();
		
		// show "Unregistered contributor" + IP for anon accounts
		if (User::isIP($this->userName)) {
			$this->displaytitle = true;
			$this->title = wfMsg('oasis-anon-header', $this->userName);
		}
		// show full title of subpages in user (talk) namespace
		else if (in_array($namespace, array(NS_USER, NS_USER_TALK))) {
				$this->title = $wgTitle->getText();
			}
			else {
				$this->title = $this->userName;
			}
		
		// link to user page
		$this->userPage = AvatarService::getUrl($this->userName);
		
		// render tabbed links
		$this->tabs = $this->getTabs($this->userName);
		
		// user stats (edit points, account creation date)
		$this->stats = $this->getStats($this->userName);
		
		// no "user" likes
		$this->likes = false;
		
		$this->actionMenu = array(
				'action' => array(),
				'dropdown' => array(),
				);

		
		// page type specific stuff
		if ($namespace == NS_USER) {
			if ( !$this->isUserProfilePageExt ) {
				// edit button
				if (isset($this->content_actions['edit'])) {
					$this->actionMenu['action'] = array(
							'href' => $this->content_actions['edit']['href'],
							'text' => wfMsg('oasis-page-header-edit-profile'),
							);
					
					$this->actionImage = MenuButtonModule::EDIT_ICON;
					$this->actionName = 'editprofile';
				}
			}
			else {
				// UserProfilePage extension stuff
				if( !self::isItMe( $this->userName ) ) {

					$title = Title::newFromText( $this->userName, NS_USER_TALK );
					$this->actionMenu['action'] = array(
						'href' => $title->getLocalUrl( 'action=edit&section=new' ),
						'text' => wfMsg('userprofilepage-leave-message'),
					);

					$this->actionImage = MenuButtonModule::MESSAGE_ICON;
					$this->actionName = 'leavemessage';
				}
				else {
					$this->actionMenu['action'] = array(
							'href' => $this->content_actions['edit']['href'],
							'text' => wfMsg('oasis-page-header-edit-profile'),
							);
					
					$this->actionImage = MenuButtonModule::EDIT_ICON;
					$this->actionName = 'editprofile';
				}
			}
		}
		else if ($namespace == NS_USER_TALK) {
			// "Leave a message" button
			if (isset($this->content_actions['addsection']['href'])) {
				$this->actionMenu['action'] = array(
						'href' => $this->content_actions['addsection']['href'],
						'text' => wfMsg('add_comment'),
						);
				
				$this->actionImage = MenuButtonModule::MESSAGE_ICON;
				$this->actionName = 'leavemessage';
				
				// different handling for "My talk page"
				if (self::isItMe($this->userName)) {
					$this->actionMenu['action']['text'] = wfMsg('edit');
					$this->actionMenu['action']['href'] = $this->content_actions['edit']['href'];
					
					$this->actionImage = MenuButtonModule::EDIT_ICON;
					$this->actionName = 'edit';
				}
			}
		}
		else if (defined('NS_BLOG_ARTICLE') && $namespace == NS_BLOG_ARTICLE) {
				// "Create a blog post" button
				if (self::isItMe($this->userName)) {
					wfLoadExtensionMessages('Blogs');
					
					$this->actionButton = array(
							'href' => SpecialPage::getTitleFor('CreateBlogPage')->getLocalUrl(),
							'text' => wfMsg('blog-create-post-label'),
							);
					
					$this->actionImage = MenuButtonModule::BLOG_ICON;
					$this->actionName = 'createblogpost';
				}
			}
		
		// dropdown actions for "Profile" and "Talk page" tabs
		if (in_array($namespace, array(NS_USER, NS_USER_TALK))) {
			$actions = array('move', 'protect', 'unprotect', 'delete', 'undelete');
			
			// add "edit" item to "Leave a message" button
			if ($this->actionName == 'leavemessage') {
				array_unshift($actions, 'edit');
			}
			
			foreach($actions as $action) {
				if (isset($this->content_actions[$action])) {
					$this->actionMenu['dropdown'][$action] = $this->content_actions[$action];
				}
			}
		}
		
		// don't show stats for user pages with too long titles (RT #68818)
		if (mb_strlen($this->title) > 35) {
			$this->stats = false;
		}
		
		// extra logic for subpages (RT #74091)
		if (!empty($this->subtitle)) {
			switch($namespace) {
				// for user subpages add link to theirs talk pages
				case NS_USER:
					$talkPage = $wgTitle->getTalkPage();
					
					// get number of revisions for talk page
					$service = new PageStatsService($wgTitle->getArticleId());
					$comments = $service->getCommentsCount();
					
					// render comments bubble
					$bubble = wfRenderModule('CommentsLikes', 'Index', array('comments' => $comments, 'bubble' => true));
					
					$this->subtitle .= ' | ';
					$this->subtitle .= $bubble;
					$this->subtitle .= View::link($talkPage);
					break;
				
				case NS_USER_TALK:
					$subjectPage = $wgTitle->getSubjectPage();
					
					$this->subtitle .= ' | ';
					$this->subtitle .= View::link($subjectPage);
					break;
			}
		}
		
		global $wgEnableFacebookSync;
		// Facebook profile Sync
		// only show on user profile homepage
		// only when the feature is enabled, the user is logged in, the user owns this profile page and the edit button (=user profile homepage) is enabled
		if ($wgEnableFacebookSync == true && $wgUser->isLoggedIn() && self::isItMe( $this->userName ) && isset($this->content_actions['edit']) && $this->isUserProfilePageExt && $namespace == NS_USER) {
			global $wgOut, $wgFacebookSyncAppID, $wgFacebookSyncAppSecret;
						
			$wgOut->addStyle(wfGetSassUrl("skins/oasis/css/modules/ProfileSync.scss"));
			$wgOut->addScriptFile('/skins/oasis/js/ProfileSync.js');
			
			// Facebook sync info
			define('FACEBOOK_REDIRECT_URL', $wgTitle->getFullURL() .'?fbrequest=sent&action=purge');
			
			// requested permissions
			$facebookScope = array('user_birthday','user_interests', 'user_relationships', 'user_work_history' ,'user_education_history','user_hometown','user_photos','user_religion_politics', 'user_website', 'user_likes');
			$facebookScope = implode(",", $facebookScope);
			
			// sync button
			$fbAccessRequestURL = "https://graph.facebook.com/oauth/authorize?client_id=".$wgFacebookSyncAppID."&scope=".$facebookScope."&redirect_uri=".FACEBOOK_REDIRECT_URL;
			$this->fbAccessRequestURL = $fbAccessRequestURL;
			
			if ($wgRequest->getVal( 'fbrequest' ) == 'sent') {
				if (!$wgRequest->getVal( 'error_reason' ) == 'user_denied') {
					$html = wfRenderModule('UserPagesHeader', 'FacebookConnect', array('fbAccess' => true));
					$this->fbData = $html;
				}
				else {
					// no access
					$html = wfRenderModule('UserPagesHeader', 'FacebookConnect', array('fbAccess' => false));
					$this->fbData = $html;
				}
			}
			else if ($wgRequest->getVal( 'fbrequest' ) == 'save') {
					// moved to extensions/Wikia/Oasis/Oasis_setup.php as hook ArticleViewHeader
			}
		}
		
		wfProfileOut(__METHOD__);
	}
	
	
	
	/**
	 * Sets up Facebook Connect request URLS and does the requests and stores the data
	 *
	 * @param bool $arg Users has granted access (true or false)*
	 */
	public function executeFacebookConnect($arg) {
		global $wgRequest, $wgTitle, $wgUser, $wgFacebookSyncAppID, $wgFacebookSyncAppSecret;
		
		if ($arg['fbAccess'] == true) {
			include('extensions/FBConnect/facebook-sdk/facebook.php');
			$facebook = new FacebookAPI(array('appId' =>$wgFacebookSyncAppID,'secret'=>$wgFacebookSyncAppSecret,	'cookie' =>true, ));
			
			$token_url = 'https://graph.facebook.com/oauth/access_token?client_id=' .$wgFacebookSyncAppID .'&redirect_uri=' .FACEBOOK_REDIRECT_URL .'&client_secret=' .$wgFacebookSyncAppSecret .'&code=' .$wgRequest->getVal( 'code' );
			$access_token = Http::get($token_url);
			$graph_url = "https://graph.facebook.com/me?" . $access_token;
			$user = json_decode(Http::get($graph_url));
			
			$likes_url = "https://graph.facebook.com/me/likes?" . $access_token;
			$likes = json_decode(Http::get($likes_url));
			
			$interests_url = "https://graph.facebook.com/me/likes?" . $access_token;
			$interests = json_decode(Http::get($interests_url));	
			
			$this->fbSelectFormURL = $wgRequest->appendQueryValue('title', $this->getUserURL());
			$this->fbSelectFormURL = $wgRequest->appendQueryValue('fbrequest', 'save');
			
			$this->fbUser = $user;
			$this->fbUserLikes = $likes;
			$this->fbUserInterests = $interests;
			$this->fbAccess = true;
		}
		else {
			// error message - no access granted
			$this->fbAccess = false;
		}
	}
	
	
	
	/**
	 * form processor for Facebook Connect data
	 *
	 */
	public function executeFacebookConnectArticle() {
		global $wgRequest;
		
		$formElements = array('fb-name','fb-birthday', 'fb-relationshipstatus', 'fb-languages', 'fb-hometown','fb-location','fb-education','fb-gender', 'fb-work', 'fb-religion','fb-political','fb-website','fb-interests');
		
		$this->fbSaveData = array();
		
		foreach ($formElements as $formElement) {
			$this->fbSaveData[$formElement] = $wgRequest->getVal($formElement);
		}
	}
	
	/**
	 * dirty way to get the user url
	 * returns the full path of the users
	 **/
	public static function getUserURL() {
		global $wgUser;
		
		$user_name = $wgUser->mName;
		$userURL = explode('/', AvatarService::getUrl($user_name));
		$userURL = $userURL[count($userURL) -1];
		return $userURL;	
	}
	
	
	
	/**
	 * hook function  - save Facebook profile data
	 *
	 * @param string $article the article
	 * @param string $outputDone the output is done
	 * @param string $userParserCache enable or disable cache
	 * @return bool need to return true
	 *
	 */
	public static function saveFacebookConnectProfile($article, $outputDone, $userParserCache ) { //$fbContent
		global $wgArticle, $wgUser, $wgTitle, $wgOut, $wgRequest;
		
		if ($wgRequest->getVal( 'fbrequest' ) != 'save') {
			return true;
		}		
		
		$fbContent = wfRenderModule('UserPagesHeader', 'FacebookConnectArticle');
		
		if ($fbContent) {
			// getting users page url, not the clean way?
			$userURL = self::getUserURL();
			
			$articleTitle = Title::newFromText($userURL);
			$wgArticle = new Article($articleTitle);
			$userProfileContent = $wgArticle->getContent(); // reading content
			
			// remove already existing sync
			$regex = '#<table class="fbconnect-synced-profile[^>]+>[\w\W]*?</table>#i';
			$userProfileContent = preg_replace($regex, '', $userProfileContent);
			
			$newUserProfileContent = $fbContent .$userProfileContent;
			
			// save updated profile
			$summary = "Synced profile with Facebook.";
			NotificationsModule::addConfirmation(wfMsg('fb-sync-success-message'), NotificationsModule::CONFIRMATION_PREVIEW);
			
			$status = $wgArticle->doEdit($newUserProfileContent, $summary, 
					( 0 ) |
					( 0 ) | 
					( 0 ) |
					( 0 ) );
			
			$wgOut->redirect($wgTitle->getFullUrl());
		}
		
		return true;
	}
	
	
	/**
	 * Render header for blog post
	 */
	public function executeBlogPost() {
		wfProfileIn(__METHOD__);
		global $wgTitle, $wgLang;
		
		// remove User_blog:xxx from title
		$titleParts = explode('/', $wgTitle->getText());
		array_shift($titleParts);
		$this->title = implode('/', $titleParts);
		
		// get user name to display in header
		$this->userName = self::getUserName($wgTitle, BodyModule::getUserPagesNamespaces());
		
		// render avatar (48x48)
		$this->avatar = AvatarService::renderAvatar($this->userName, 48);
		
		// link to user page
		$this->userPage = AvatarService::getUrl($this->userName);
		
		// user stats (edit points, account creation date)
		$this->stats = $this->getStats($this->userName);
		
		// commments / likes / date of first edit
		if (!empty($wgTitle) && $wgTitle->exists()) {
			$service = new PageStatsService($wgTitle->getArticleId());
			
			$this->editTimestamp = $wgLang->date($service->getFirstRevisionTimestamp());
			$this->comments = $service->getCommentsCount();
			$this->likes = true;
		}
		
		// edit button / dropdown
		if (isset($this->content_actions['edit'])) {
			$this->actionMenu['action'] = $this->content_actions['edit'];
		}
		
		// dropdown actions
		$actions = array('move', 'protect', 'unprotect', 'delete', 'undelete');
		foreach($actions as $action) {
			if (isset($this->content_actions[$action])) {
				$this->actionMenu['dropdown'][$action] = $this->content_actions[$action];
			}
		}
		
		wfProfileOut(__METHOD__);
	}
	
	/**
	 * Render header for blog listing
	 */
	public function executeBlogListing() {
		wfProfileIn(__METHOD__);
		
		wfLoadExtensionMessages('Blogs');
		// "Create blog post" button
		$this->actionButton = array(
				'href' => SpecialPage::getTitleFor('CreateBlogPage')->getLocalUrl(),
				'text' => wfMsg('blog-create-post-label'),
				);
		$this->title = wfMsg('create-blog-post-category');
		
		wfProfileOut(__METHOD__);
	}
}