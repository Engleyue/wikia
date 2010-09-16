<?php
// initialize skin only once

global $wgTitle, $wgUser, $wgForceSkin, $wgOut;

$wgForceSkin = 'oasis';
$wgTitle = Title::newMainPage();
$wgUser = User::newFromName('WikiaBot');

wfSuppressWarnings();
ob_start();

$wgOut->setCategoryLinks(array('foo' => 1, 'bar' => 2));

$skin = $wgUser->getSkin();
$skin->outputPage(new OutputPage());

wfClearOutputBuffers();
wfRestoreWarnings();

class ModuleDataTest extends PHPUnit_Framework_TestCase {

	// TODO: use it when we will update phpunit to v3.4+
	public static function setUpBeforeClass() {}

	function testLatestActivityModule() {
		global $wgSitename;

		$moduleData = Module::get('LatestActivity')->getData();

		$this->assertEquals(
			3,
			count($moduleData)
		);
	}

	function testSearchModule() {
		global $wgSitename;

		$moduleData = Module::get('Search')->getData();

		$this->assertEquals(
			wfMsg('Tooltip-search', $wgSitename),
			$moduleData['placeholder']
		);
	}

	function testRailModule() {
		global $wgTitle;
		$wgTitle = Title::newMainPage();

		$moduleData = Module::get('Rail')->getData();

		$this->assertType("array",
			$moduleData
		);

	}

	function testRailSubmoduleExists() {
		global $wgTitle;
		$wgTitle = Title::newFromText('FooBar');

		$moduleData = Module::get('Body')->getData();

		// search module lives at index 1500
		$this->assertType("array",
			$moduleData['railModuleList'][1500]
		);

	}

	function testAdModule() {
		global $wgTitle;
		$this->markTestSkipped();
		$wgTitle = Title::newMainPage();

		$moduleData = Module::get('Ad', 'Index', array ('slot' => 'TOP_BOXAD'))->getData();

		// boxad is 300 wide
		$this->assertEquals(
			'300',
			$moduleData['imgWidth']
		);
	}

	// TODO: maybe move to separate test class
	function testNotificationsModule() {
		global $wgUser, $wgRequest;

		// create set of fake objects
		$fakeTitleA = Title::newFromText('Foo');
		$fakeTitleB = Title::newFromText('Bar');
		$fakeArticle = new Article($fakeTitleA);

		/**
		 * Test notifications
		 */
		NotificationsModule::clearNotifications();

		$message = 'Notification about something very important';
		NotificationsModule::addNotification($message, array('data' => 'bar'));

		$moduleData = Module::get('Notifications')->getData();

		$notification = array(
			'message' => $message,
			'data' => array('data' => 'bar'),
			'type' => NotificationsModule::NOTIFICATION_GENERIC_MESSAGE,
		);

		$this->assertEquals(
			array($notification),
			$moduleData['notifications']
		);

		// badge notification
		if (class_exists('AchBadge')) {
			NotificationsModule::clearNotifications();

			// create fake badge
			$badge = new AchBadge(BADGE_WELCOME, 1, BADGE_LEVEL_BRONZE);
			$html = '';

			wfSuppressWarnings();
			$data = array(
				'name' => $badge->getName(),
				'picture' => $badge->getPictureUrl(90),
				'points' => wfMsg('achievements-points', AchConfig::getInstance()->getLevelScore($badge->getLevel())),
				'reason' => $badge->getPersonalGivenFor(),
				'userName' => $wgUser->getName(),
				'userPage' => $wgUser->getUserPage()->getLocalUrl(),
			);

			$message = wfMsg('oasis-badge-notification', $data['userName'], $data['name'], $data['reason']);

			NotificationsModule::addBadgeNotification($wgUser, $badge, $html);
			wfRestoreWarnings();

			$moduleData = Module::get('Notifications')->getData();

			$notification = array(
				'message' => $message,
				'data' => $data,
				'type' => NotificationsModule::NOTIFICATION_NEW_ACHIEVEMENTS_BADGE,
			);

			$this->assertEquals(
				array($notification),
				$moduleData['notifications']
			);
		}

		// edit similar
		NotificationsModule::clearNotifications();

		$message = 'Edit similar message';
		NotificationsModule::addEditSimilarNotification($message);

		$moduleData = Module::get('Notifications')->getData();

		$notification = array(
			'message' => $message,
			'data' => array(),
			'type' => NotificationsModule::NOTIFICATION_EDIT_SIMILAR,
		);

		$this->assertEquals(
			array($notification),
			$moduleData['notifications']
		);

		// community messages
		NotificationsModule::clearNotifications();

		$message = 'Edit similar message';
		NotificationsModule::addCommunityMessagesNotification($message);

		$moduleData = Module::get('Notifications')->getData();

		$notification = array(
			'message' => $message,
			'data' => array(),
			'type' => NotificationsModule::NOTIFICATION_COMMUNITY_MESSAGE,
		);

		$this->assertEquals(
			array($notification),
			$moduleData['notifications']
		);


		/**
		 * Test confirmations
		 */
		NotificationsModule::clearNotifications();
		NotificationsModule::addConfirmation('Confirmation of something done');

		$moduleData = Module::get('Notifications', 'Confirmation')->getData();

		$this->assertEquals(
			'Confirmation of something done',
			$moduleData['confirmation']
		);

		// preferences saved
		NotificationsModule::clearNotifications();

		$prefs = (object) array('mSuccess' => true);
		$status = 'success';
		NotificationsModule::addPreferencesConfirmation($prefs, $status, '');

		$moduleData = Module::get('Notifications', 'Confirmation')->getData();

		$this->assertEquals(
			wfMsg('savedprefs'),
			$moduleData['confirmation']
		);

		// page moved
		NotificationsModule::clearNotifications();

		$form = false;
		$oldUrl = $fakeTitleA->getFullUrl('redirect=no');
		$newUrl = $fakeTitleB->getFullUrl();
		$oldText = $fakeTitleA->getPrefixedText();
		$newText = $fakeTitleB->getPrefixedText();

		// don't render links
		$oldLink = $oldText;
		$newLink = $newText;

		$message = wfMsgExt('movepage-moved', array('parseinline'), $oldLink, $newLink, $oldText, $newText);
		NotificationsModule::addPageMovedConfirmation($form, $fakeTitleA, $fakeTitleB);

		$moduleData = Module::get('Notifications', 'Confirmation')->getData();

		$this->assertEquals(
			$message,
			$moduleData['confirmation']
		);

		// page removed
		NotificationsModule::clearNotifications();

		$reason = '';
		$message = wfMsgExt('oasis-confirmation-page-deleted', array('parseinline'), $fakeTitleA->getPrefixedText());
		NotificationsModule::addPageDeletedConfirmation($fakeArticle, $wgUser, $reason, $fakeArticle->getId());

		$moduleData = Module::get('Notifications', 'Confirmation')->getData();

		$this->assertEquals(
			$message,
			$moduleData['confirmation']
		);

		// page removed
		NotificationsModule::clearNotifications();

		$message = wfMsg('oasis-confirmation-page-undeleted');
		NotificationsModule::addPageUndeletedConfirmation($fakeTitleA, false);

		$moduleData = Module::get('Notifications', 'Confirmation')->getData();

		$this->assertEquals(
			$message,
			$moduleData['confirmation']
		);

		// log out
		NotificationsModule::clearNotifications();

		$html = '';
		$message = wfMsg('oasis-confirmation-user-logout');
		NotificationsModule::addLogOutConfirmation($wgUser, $html, false);

		$moduleData = Module::get('Notifications', 'Confirmation')->getData();

		$this->assertEquals(
			$message,
			$moduleData['confirmation']
		);

		// facebook connect
		NotificationsModule::clearNotifications();
		$wgRequest->setVal('fbconnected', 2);

		$html = '';
		$preferencesUrl = SpecialPage::getTitleFor('Preferences')->getFullUrl();
		$message = wfMsgExt('fbconnect-connect-error-msg', array('parseinline'), $preferencesUrl);
		NotificationsModule::addFacebookConnectConfirmation($html);

		$moduleData = Module::get('Notifications', 'Confirmation')->getData();

		$this->assertEquals($message, $moduleData['confirmation']);
		$this->assertEquals(' error', $moduleData['confirmationClass']);
	}

	function testRandomWikiModule() {
		global $wgEnableRandomWikiOasisButton;

		// let's enable the module
		$wgEnableRandomWikiOasisButton = true;

		$moduleData = Module::get('RandomWiki')->getData();

		$this->assertType('string', $moduleData['url']);

		// now let's disable the module
		$wgEnableRandomWikiOasisButton = false;

		$moduleData = Module::get('RandomWiki')->getData();

		$this->assertEquals(
			null,
			$moduleData['url']);
	}

	function testOasisModule() {

		// add custom CSS class to <body>
		OasisModule::addBodyClass('testCssClass');

		// turn of PHP warnings / don't emit skin's HTML
		wfSuppressWarnings();
		ob_start();

		// render the skin
		$moduleData = Module::get('Oasis')->getData();

		wfClearOutputBuffers();
		wfRestoreWarnings();

		// assertions
		$this->assertTrue(in_array('testCssClass', $moduleData['bodyClasses']));
		$this->assertRegExp('/^<link href=/', $moduleData['printableCss']);
		$this->assertType('string', $moduleData['body']);
		$this->assertType('string', $moduleData['headscripts']);
		$this->assertType('string', $moduleData['csslinks']);
		$this->assertType('string', $moduleData['headlinks']);
		$this->assertType('string', $moduleData['globalVariablesScript']);
	}


	function testCommentsLikesModule() {
		global $wgTitle;
		$wgTitle = Title::newMainPage();

		$moduleData = Module::get('CommentsLikes', 'Index', array ('comments' => 123))->getData();

		$this->assertRegExp('/^123$/', $moduleData['comments']);
		$this->assertRegExp('/'.preg_quote($wgTitle->getDBkey()).'/', $moduleData['commentsLink']);
		$this->assertRegExp('/^$/', $moduleData['commentsTooltip']);
		$this->assertEquals(null, $moduleData['likes']);

		// not-existing page
		$title = Title::newFromText('NotExistingPage');

		$moduleData = Module::get('CommentsLikes', 'Index', array ('comments' => 0, 'likes' => 20, 'title' => $title))->getData();

		$this->assertEquals('0', $moduleData['comments']);
		$this->assertRegExp('/Talk:NotExistingPage/', $moduleData['commentsLink']);
		$this->assertTrue($moduleData['commentsTooltip'] != '');
		$this->assertEquals(20, $moduleData['likes']);
	}

	function testAchievementsModule() {
		global $wgTitle;
		$wgTitle = Title::newFromText('User:WikiaBot');

		$moduleData = Module::get('Achievements')->getData();

		$this->assertEquals ($moduleData['ownerName'], 'WikiaBot');
		$this->assertEquals ($moduleData['viewer_is_owner'], true);
		$this->assertEquals ($moduleData['max_challenges'], count($moduleData['challengesBadges']));
		$this->assertType ('array', $moduleData['challengesBadges'][0]);
		$this->assertType ('object', $moduleData['challengesBadges'][0]['badge']);

		if (count($moduleData['ownerBadges']) > 0) {
			// TODO: WikiaBot has no badges, but we could add some
		}
	}

	function testBodyModule() {
		global $wgTitle;

		//Special pages should have no modules
		$wgTitle = Title::newFromText('Special:SpecialPages');
		$moduleData = Module::get('Body')->getData();
		$railList = $moduleData['railModuleList'];
		$this->assertEquals (null, $railList);

		//Special search page should only have ad modules on it
		$wgTitle = Title::newFromText('Special:Search');
		$moduleData = Module::get('Body')->getData();
		$railList = $moduleData['railModuleList'];
		foreach ($railList as $module) {
			$this->assertEquals ('Ad', $module[0]);
		}

		// User page check
		$wgTitle = Title::newFromText('User:WikiaBot');
		$moduleData = Module::get('Body')->getData();
		$railList = $moduleData['railModuleList'];
		$this->assertEquals($railList[1200][0], 'FollowedPages');
		$this->assertEquals($railList[1350][0], 'Achievements');

		// Content page check
		$wgTitle = Title::newFromText('Foo');
		$moduleData = Module::get('Body')->getData();
		$railList = $moduleData['railModuleList'];
		$this->assertEquals($railList[1500][0], 'Search');
		$this->assertEquals($railList[1150][0], 'Spotlights');
	}

	function testAccountNavigationModule() {
		global $wgUser;
		$userName = $wgUser->getName();

		$moduleData = Module::get('AccountNavigation')->getData();

		// user urls
		$this->assertEquals(6, count($moduleData['personal_urls']));
		$this->assertRegExp("/User:{$userName}$/", $moduleData['personal_urls']['userpage']['href']);

		// dropdown
		$this->assertRegExp("/User_talk:{$userName}/", $moduleData['dropdown'][0]);

		// logout link
		$this->assertRegExp('/Log out<\/a>$/', $moduleData['links'][0]);

		// user data
		$this->assertFalse($moduleData['isAnon']);
		$this->assertEquals($userName, $moduleData['username']);
		$this->assertEquals($moduleData['profileAvatar'], AvatarService::renderAvatar($userName, 16));
	}

	function testArticleCategoriesModule() {
		$moduleData = Module::get('ArticleCategories')->getData();

		$this->assertRegExp('/^<div id=\'catlinks\'/', $moduleData['catlinks']);
		$this->assertRegExp('/Category:Foo/', $moduleData['catlinks']);
		$this->assertRegExp('/Category:Bar/', $moduleData['catlinks']);
	}

	function testContentDisplayModule() {
		$moduleData = Module::get('ContentDisplay')->getData();

		// content display
		$this->assertType ('string', $moduleData['bodytext']);

		// picture attribution
		$html = 'TESTTESTTESTTEST';
		$file = wfFindFile('Wiki.png');
		$addedBy = $file->getUser();

		ContentDisplayModule::renderPictureAttribution(false, false, $file, false, false, $html);

		$this->assertRegExp('/^TEST<div class="picture-attribution"><img src/', $html);
		$this->assertRegExp('/User:' . $addedBy . '/', $html);
	}

	function testGlobalHeaderModule() {
		$moduleData = Module::get('GlobalHeader')->getData();

		$this->assertRegExp('/^http:\/\/www.wikia.com\/Special:CreateWiki/', $moduleData['createWikiUrl']);
		$this->assertRegExp('/wikia.com\//', $moduleData['centralUrl']);
		$this->assertType('array', $moduleData['menuNodes']);
		$this->assertType('array', $moduleData['menuNodes'][0]);
	}

	function testHistoryDropdownModule() {
		$revisions = array('foo', 'bar');
		$moduleData = Module::get('HistoryDropdown', 'Index', array('revisions' => $revisions))->getData();

		$this->assertType('array', $moduleData['content_actions']);
		$this->assertEquals($revisions, $moduleData['revisions']);
	}

	function testHotSpotsModule() {
		$moduleData = Module::get('HotSpots', 'Index')->getData();

		$this->assertType('array', $moduleData['data']['results']);
		$this->assertEquals(count($moduleData['data']['results']), 5);
		$this->assertTrue(array_key_exists('title', $moduleData['data']['results'][0]));
		$this->assertTrue(array_key_exists('url', $moduleData['data']['results'][0]));
		$this->assertTrue(array_key_exists('count', $moduleData['data']['results'][0]));
	}

	function testFollowedPagesModule () {
		global $wgTitle;

		// User page check
		$wgTitle = Title::newFromText('User:WikiaBot');
		$moduleData = Module::get('FollowedPages')->getData();
		#print_r($moduleData);
		$this->assertType('array', $moduleData['data']);
		$this->assertTrue(count($moduleData['data']) >= $moduleData['max_followed_pages']);
	}

	function testMenuButtonModule() {
		$data = array(
			'action' => 'url',
			'name' => 'edit',
			'image' => MenuButtonModule::EDIT_ICON,
			'dropdown' => array(
				'move' => array(),
				'protect' => array(),
				'delete' => array(),
				'foo' => array(),
			),
		);

		$moduleData = Module::get('MenuButton', 'Index', $data)->getData();

		$this->assertEquals($data['action'], $moduleData['action']);
		$this->assertEquals($data['name'], $moduleData['actionName']);
		$this->assertRegExp('/^<img /', $moduleData['icon']);
		$this->assertEquals(array_keys($data['dropdown']), array_keys($moduleData['dropdown']));
		$this->assertEquals('m', $moduleData['dropdown']['move']['accesskey']);
	}

	function testPageHeaderModule() {
		global $wgTitle, $wgSupressPageTitle;

		// main page
		$wgTitle = Title::newMainPage();
		$wgSupressPageTitle = true;
		$moduleData = Module::get('PageHeader')->getData();
		$this->assertTrue($moduleData['isMainPage']);
		$this->assertEquals('', $moduleData['title']);
		$this->assertEquals('', $moduleData['subtitle']);
		$wgSupressPageTitle = false;

		// talk page
		$wgTitle = Title::newFromText('Foo', NS_TALK);
		$moduleData = Module::get('PageHeader')->getData();
		$this->assertRegExp('/Talk:/', $moduleData['title']);
		$this->assertRegExp('/Foo" title="Foo"/', $moduleData['subtitle']);

		// edit page header
		$moduleData = Module::get('PageHeader', 'EditPage')->getData();
		$this->assertRegExp('/Editing:/', $moduleData['title']);
		$this->assertRegExp('/Talk:Foo" title="Talk:Foo"/', $moduleData['subtitle']);

		// edit box header
		$moduleData = Module::get('PageHeader', 'EditBox')->getData();
		$this->assertRegExp('/Editing:/', $moduleData['title']);
		$this->assertRegExp('/Talk:Foo" title="Talk:Foo"/', $moduleData['subtitle']);

		// add edit box header
		$editPage = (object) array(
			'preview' => true,
			'diff' => false,
			'editFormTextTop' => '',
		);

		PageHeaderModule::modifyEditPage($editPage);

		$this->assertRegExp('/<div id="WikiaEditBoxHeader"/', $editPage->editFormTextTop);
		$this->assertRegExp('/Editing:/', $editPage->editFormTextTop);
	}

}
