<?php
class PageHeaderModule extends Module {

	var $wgStylePath;

	var $content_actions;
	var $displaytitle;
	var $title;

	var $action;
	var $actionName;
	var $actionImage;
	var $categories;
	var $comments;
	var $dropdown;
	var $likes;
	var $pageExists;
	var $showSearchBox;
	var $subtitle;
	var $isMainPage;
	var $total;

	/**
	 * Use MW core variable to generate action button
	 */
	private function prepareActionButton() {
		global $wgTitle;
		wfProfileIn(__METHOD__);

		$namespace = $wgTitle->getNamespace();

		// remove "add section" action for Forum namespace pages
		if ($namespace == NS_FORUM && isset($this->content_actions['addsection'])) {
			unset($this->content_actions['addsection']);
		}

		// action button
		#print_pre($this->content_actions);

		// add section
		if (isset($this->content_actions['addsection'])) {
			$this->action = $this->content_actions['addsection'];
			$this->action['text'] = wfMsg('oasis-page-header-add-topic');

			$this->actionImage = MenuButtonModule::ADD_TOPIC_ICON;
			$this->actionName = 'addtopic';
		}
		// "Edit with form" (SMW)
		else if (isset($this->content_actions['form_edit'])) {
			$this->action = $this->content_actions['form_edit'];
			$this->actionImage = MenuButtonModule::EDIT_ICON;
			$this->actionName = 'form-edit';
		}
		// edit
		else if (isset($this->content_actions['edit'])) {
			$this->action = $this->content_actions['edit'];
			$this->actionImage = MenuButtonModule::EDIT_ICON;
			$this->actionName = 'edit';
		}
		// view source
		else if (isset($this->content_actions['viewsource'])) {
			$this->action = $this->content_actions['viewsource'];
			$this->actionImage = MenuButtonModule::LOCK_ICON;
			$this->actionName = 'source';
		}

		wfProfileOut(__METHOD__);
	}

	/**
	 * Get content actions for dropdown
	 */
	private function getDropdownActions() {
		wfProfileIn(__METHOD__);

		// var_dump($this->content_actions);
		$ret = array();

		// items to be added to "edit" dropdown
		$actions = array('move', 'protect', 'unprotect', 'delete', 'undelete');

		// add "edit" to dropdown if edit button says:
		//  * "Add topic" (__NEWSECTIONLINK__ magic word used on Forum namespace pages)
		//  * "Edit with form" (SMW pages)
		if (in_array($this->actionName, array('addtopic', 'form-edit'))) {
			array_unshift($actions, 'edit');
		}

		foreach($actions as $action) {
			if (isset($this->content_actions[$action])) {
				$ret[$action] = $this->content_actions[$action];
			}
		}

		wfProfileOut(__METHOD__);
		return $ret;
	}

	/**
	 * Get recent revisions of current article and format them
	 */
	private function getRecentRevisions() {
		wfProfileIn(__METHOD__);
		global $wgTitle;

		// use service to get data
		$service = new PageStatsService($wgTitle->getArticleId());

		// get info about current revision and list of authors of recent five edits
		$revisions = $service->getRecentRevisions();

		// format timestamps, render avatars and user links
		if (is_array($revisions)) {
			foreach($revisions as &$revision) {
				if (isset($revision['user'])) {
					$revision['avatarUrl'] = AvatarService::getAvatarUrl($revision['user']);
					$revision['link'] = AvatarService::renderLink($revision['user']);
				}
				$revision['timestamp'] = self::formatTimestamp($revision['timestamp']);
			}
		}

		wfProfileOut(__METHOD__);
		return $revisions;
	}

	public static function formatTimestamp($stamp) {
		wfProfileIn(__METHOD__);

		$diff = time() - strtotime($stamp);

		// show time difference if it's 14 or less days
		if ($diff < 15 * 86400) {
			$ret = wfTimeFormatAgo($stamp);
		}
		else {
			$ret = '';
		}

		wfProfileOut(__METHOD__);
		return $ret;
	}

	/**
	 * Render default page header (with edit dropdown, history dropdown, ...)
	 *
	 * @param: array $params
	 *    key: showSearchBox (default: false)
	 */
	public function executeIndex($params) {
		wfProfileIn(__METHOD__);

		global $wgTitle, $wgContLang, $wgLang;

		// page namespace
		$ns = $wgTitle->getNamespace();

		// action button (edit / view soruce) and dropdown for it
		$this->prepareActionButton();

		// dropdown actions
		$this->dropdown = $this->getDropdownActions();

		// for not existing pages page header is a bit different
		$this->pageExists = !empty($wgTitle) && $wgTitle->exists();

		if ($this->pageExists) {
			// use service to get data
			$service = new PageStatsService($wgTitle->getArticleId());

			// comments
			$this->comments = $service->getCommentsCount();

			// likes
			$this->likes = $service->getLikesCount();

			// get two popular categories this article is in
			$categories = $service->getMostLinkedCategories();

			// render links to most linked category page
			$this->categories = array();

			foreach($categories as $category => $cnt) {
				$title = Title::newFromText($category, NS_CATEGORY);
				$this->categories[] = View::link($title, $title->getText());
			}

			// get info about current revision and list of authors of recent five edits
			$this->revisions = $this->getRecentRevisions();

			// mainpage?
			if (ArticleAdLogic::isMainPage()) {
				$this->isMainPage = true;

				// number of pages on this wiki
				$this->total = $wgLang->formatNum(SiteStats::articles());
			}
		}

		// remove namespaces prefix from title
		$namespaces = array(NS_MEDIAWIKI, NS_TEMPLATE, NS_CATEGORY, NS_FILE);
		if (defined('NS_VIDEO')) {
			$namespaces[] = NS_VIDEO;
		}

		if (in_array($ns, $namespaces)) {
			$this->title = $wgTitle->getText();
		}

		// talk pages
		if ($wgTitle->isTalkPage()) {
			// remove comments button
			$this->comments = false;

			// Talk: <page name without namespace prefix>
			$this->displaytitle = true;
			$this->title = Xml::element('strong', array(), $wgContLang->getNsText(NS_TALK) . ':');
			$this->title .= htmlspecialchars($wgTitle->getText());

			// back to subject article link
			switch($ns) {
				case NS_TEMPLATE_TALK:
					$msgKey = 'oasis-page-header-back-to-template';
					break;

				case NS_MEDIAWIKI_TALK:
					$msgKey = 'oasis-page-header-back-to-mediawiki';
					break;

				case NS_CATEGORY_TALK:
					$msgKey = 'oasis-page-header-back-to-category';
					break;

				case NS_FILE_TALK:
					$msgKey = 'oasis-page-header-back-to-file';
					break;

				default:
					$msgKey = 'oasis-page-header-back-to-article';
			}

			// special case for NS_VIDEO_TALK
			if (defined('NS_VIDEO') && ($ns == MWNamespace::getTalk(NS_VIDEO))) {
				$msgKey = 'oasis-page-header-back-to-video';
			}

			$this->subtitle = View::link($wgTitle->getSubjectPage(), wfMsg($msgKey), array('accesskey' => 'c'));
		}

		// category pages
		if ($ns == NS_CATEGORY) {
				// hide revisions / categories bar
				$this->categories = false;
				$this->revisions = false;
		}

		// forum namespace
		if ($ns == NS_FORUM) {
			// remove comments button
			$this->comments = false;

			// remove namespace prefix
			$this->title = $wgTitle->getText();
		}

		// mainpage
		if (ArticleAdLogic::isMainPage()) {
			// change page title to just "Home"
			$this->title = wfMsg('oasis-home');
			// hide revisions / categories bar
			$this->categories = false;
			$this->revisions = false;
		}

		// render proper message below page title (Mediawiki page, Template page, ...)
		switch($ns) {
			case NS_MEDIAWIKI:
				$this->subtitle = wfMsg('oasis-page-header-subtitle-mediawiki');
				break;

			case NS_TEMPLATE:
				$this->subtitle = wfMsg('oasis-page-header-subtitle-template');
				break;

			case NS_SPECIAL:
				$this->subtitle = wfMsg('oasis-page-header-subtitle-special');
				// special case for wiki activity page
				if ($wgTitle->isSpecial('WikiActivity')) {
					$this->subtitle = View::specialPageLink('RecentChanges', 'oasis-page-header-subtitle-special-wikiactivity');
				}
				break;

			case NS_CATEGORY:
				$this->subtitle = wfMsg('oasis-page-header-subtitle-category');
				break;

			case NS_FORUM:
				$this->subtitle = wfMsg('oasis-page-header-subtitle-forum');
				break;
		}

		// if page is rendered using one column layout, show search box as a part of page header
		$this->showSearchBox = isset($params['showSearchBox']) ? $params['showSearchBox'] : false ;
		// don't render likes right now
		$this->likes = false;

		// This is a reminder that this feature should probably work. --O
		global $wgSupressPageTitle;
		if ($wgSupressPageTitle === true) {
			$this->title = '';
			$this->subtitle = '';
		}
		wfProfileOut(__METHOD__);
	}

	/**
	 * Render header for edit page
	 */
	public function executeEditPage() {
		wfProfileIn(__METHOD__);
		global $wgTitle, $wgRequest;

		// special handling for special pages (CreateBlogPost, CreatePage)
		if ($wgTitle->getNamespace() == NS_SPECIAL) {
			wfProfileOut(__METHOD__);
			return;
		}

		// detect section edit
		$isSectionEdit = is_numeric($wgRequest->getVal('section'));

		// show proper message in the header
		$isPreview = $wgRequest->getCheck( 'wpPreview' ) || $wgRequest->getCheck( 'wpLivePreview' );
		$isShowChanges = $wgRequest->getCheck( 'wpDiff' );
		$isDiff = $wgRequest->getInt('diff');

		if ($isPreview) {
			$titleMsg = 'oasis-page-header-preview';
		}
		else if ($isShowChanges) {
			$titleMsg = 'oasis-page-header-changes';
		}
		else if ($isDiff) {
			$titleMsg = 'oasis-page-header-diff';
		}
		else if ($isSectionEdit) {
			$titleMsg = 'oasis-page-header-editing-section';
		}
		else {
			$titleMsg = 'oasis-page-header-editing';
		}

		$this->displaytitle = true;
		$this->title = wfMsg($titleMsg, htmlspecialchars($wgTitle->getPrefixedText()));

		// back to article link
		if (!$isPreview && !$isShowChanges) {
			$this->subtitle = View::link($wgTitle, wfMsg('oasis-page-header-back-to-article'), array('accesskey' => 'c'));
		}

		wfProfileOut(__METHOD__);
	}

	/**
	 * Render edit box header when doing preview / showing changes
	 */
	public function executeEditBox() {
		wfProfileIn(__METHOD__);
		global $wgTitle, $wgRequest;

		// detect section edit
		$isSectionEdit = is_numeric($wgRequest->getVal('wpSection'));

		if ($isSectionEdit) {
			$msg = 'oasis-page-header-editing-section';
		}
		else {
			$msg = 'oasis-page-header-editing';
		}

		// Editing: foo
		$this->displaytitle = true;
		$this->title = wfMsg($msg, htmlspecialchars($wgTitle->getPrefixedText()));

		// back to article link
		$this->subtitle = View::link($wgTitle, wfMsg('oasis-page-header-back-to-article'), array('accesskey' => 'c'));

		wfProfileOut(__METHOD__);
	}

	/**
	 * Modify edit page: add preview notice bar and render edit box header
	 */
	public static function modifyEditPage(&$editPage) {
		wfProfileIn(__METHOD__);
		global $wgUser;

		// get skin name
		$skinName = get_class($wgUser->getSkin());

		if ($skinName == 'SkinOasis') {
			// load CSS for editpage
			global $wgOut;
			$wgOut->addStyle(wfGetSassUrl('skins/oasis/css/core/_EditPage.scss'));

			// TODO: dirty hack to make CategorySelect works
			$wgOut->addScriptFile('jquery/jquery-ui-1.7.2.custom.js');
			$wgOut->addScriptFile('jquery/jquery.json-1.3.js');

			// render preview notice bar
			if ($editPage->preview) {
				// show preview confirmation bar below global nav
				NotificationsModule::addConfirmation(wfMsg('oasis-preview-confirmation'), NotificationsModule::CONFIRMATION_PREVIEW);
			}

			// render edit box header
			if ($editPage->preview || $editPage->diff) {
				$editPage->editFormTextTop .= wfRenderModule('PageHeader', 'EditBox');
			}
		}

		wfProfileOut(__METHOD__);
		return true;
	}
}
