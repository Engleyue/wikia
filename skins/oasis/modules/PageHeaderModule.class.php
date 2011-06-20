<?php
/**
 * Renders page header (title, subtitle, comments chicklet button, history dropdown, top categories)
 *
 * @author Maciej Brencz
 */

class PageHeaderModule extends Module {

	var $wgEnableWikiAnswers;
	var $wgStylePath;

	var $content_actions;
	var $displaytitle; // if true - don't encode HTML
	var $title;
	var $subtitle;
	var $undelete;

	// RT #72366 - line with page type, redirect info, link to subject page and old revision data
	var $pageType;
	var $pageTalkSubject;
	var $pageSubject;
	var $pageRedirect;

	var $pageSubtitle;

	var $action;
	var $actionName;
	var $actionImage;
	var $categories;
	var $comments;
	var $dropdown;
	var $likes;
	var $pageExists;
	var $showSearchBox;
	var $isMainPage;
	var $total;
	var $isNewFiles;

	var $wgUser;
	var $wgABTests;
	var $wgEnableUploads;

	/**
	 * Use MW core variable to generate action button
	 */
	private function prepareActionButton() {
		global $wgTitle, $wgRequest;

		$namespace = $wgTitle->getNamespace();
		$isDiff = !is_null($wgRequest->getVal('diff'));

		// "Add topic" action
		if (isset($this->content_actions['addsection'])) {
			// remove on Forum namespace pages / diff pages (RT #72666)
			if ($namespace == NS_FORUM || $isDiff) {
				unset($this->content_actions['addsection']);
			}
		}

		// action button
		#print_pre($this->content_actions);

		// PvX's rate (RT #76386)
		if (isset($this->content_actions['rate'])) {
			$this->action = $this->content_actions['rate'];
			$this->actionName = 'rate';
		}
		// "Add topic"
		else if (isset($this->content_actions['addsection'])) {
			$this->action = $this->content_actions['addsection'];
			$this->action['text'] = wfMsg('oasis-page-header-add-topic');

			$this->actionImage = MenuButtonModule::ADD_ICON;
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
	}

	/**
	 * Get content actions for dropdown
	 */
	private function getDropdownActions() {
		$ret = array();

		// items to be added to "edit" dropdown
		$actions = array('history', 'move', 'protect', 'unprotect', 'delete', 'undelete');

		// add "edit" to dropdown (if action button is not an edit)
		if (!in_array($this->actionName, array('edit', 'source'))) {
			array_unshift($actions, 'edit');
		}

		foreach($actions as $action) {
			if (isset($this->content_actions[$action])) {
				$ret[$action] = $this->content_actions[$action];
			}
		}

		return $ret;
	}

	/**
	 * Get recent revisions of current article and format them
	 */
	private function getRecentRevisions() {
		global $wgTitle, $wgMemc, $wgABTests;

		$revisions = array();

		// use service to get data
		$service = new PageStatsService($wgTitle->getArticleId());

		// get info about current revision and list of authors of recent five edits
		// This key is refreshed by the onArticleSaveComplete() hook
		$mKey = wfMemcKey('mOasisRecentRevisions2', $wgTitle->getArticleId());
		$revisions = $wgMemc->get($mKey);

		if (empty($revisions)) {
			$revisions = $service->getCurrentRevision();

			// format timestamps, render avatars and user links
			if (is_array($revisions)) {
				foreach($revisions as &$revision) {
					if (isset($revision['user'])) {
						$revision['avatarUrl'] = AvatarService::getAvatarUrl($revision['user']);
						$revision['link'] = AvatarService::renderLink($revision['user']);
					}
				}
			}
			$wgMemc->set($mKey, $revisions);
		}

		return $revisions;
	}

	public static function formatTimestamp($stamp) {

		$diff = time() - strtotime($stamp);

		// show time difference if it's 14 or less days
		if ($diff < 15 * 86400) {
			$ret = wfTimeFormatAgo($stamp);
		}
		else {
			$ret = '';
		}
		return $ret;
	}

	/**
	 * Render default page header (with edit dropdown, history dropdown, ...)
	 *
	 * @param: array $params
	 *    key: showSearchBox (default: false)
	 */
	public function executeIndex($params) {
		global $wgTitle, $wgArticle, $wgOut, $wgUser, $wgContLang, $wgSupressPageTitle, $wgSupressPageSubtitle, $wgSuppressNamespacePrefix, $wgCityId, $wgABTests;
		wfProfileIn(__METHOD__);

		// page namespace
		$ns = $wgTitle->getNamespace();

		// currently used skin
		$skin = $wgUser->getSkin();

		// action button (edit / view soruce) and dropdown for it
		$this->prepareActionButton();

		// dropdown actions
		$this->dropdown = $this->getDropdownActions();

		// for not existing pages page header is a bit different
		$this->pageExists = !empty($wgTitle) && $wgTitle->exists();

		// default title "settings" (RT #145371), don't touch special pages
		if ($ns != NS_SPECIAL) {
			$this->displaytitle = true;
			$this->title = $wgOut->getPageTitle();
		}
		else {
			// on special pages titles are already properly encoded (BugId:5983)
			$this->displaytitle = true;
		}

		// use service to get data
		$service = PageStatsService::newFromTitle( $wgTitle );

		// comments - moved here to display comments even on deleted/non-existant pages
		$this->comments = $service->getCommentsCount();

		if ($this->pageExists) {

			// show likes
			$this->likes = true;

			// get two popular categories this article is in
			$categories = array();

			// FIXME: Might want to make a WikiFactory variable for controlling this feature if we aren't
			// comfortable with its performance.
			// NOTE: Skip getMostLinkedCategories() on Lyrics and Marvel because we're not sure yet that it's fast enough.
			$LYRICS_CITY_ID = "43339";
			$MARVEL_CITY_ID = "2233";
			if(($wgCityId != $LYRICS_CITY_ID)  && ($wgCityId != $MARVEL_CITY_ID)){
				$categories = $service->getMostLinkedCategories();
			}

			// render links to most linked category page
			$this->categories = array();
			foreach($categories as $category => $cnt) {
				$title = Title::newFromText($category, NS_CATEGORY);
				if($title) {
					$this->categories[] = Wikia::link($title, $title->getText());
				}
			}

			// get info about current revision and list of authors of recent five edits
			$this->revisions = $this->getRecentRevisions();

			// mainpage?
			if (ArticleAdLogic::isMainPage()) {
				$this->isMainPage = true;

				// number of pages on this wiki
				$this->total = SiteStats::articles();
			}
		}

		// remove namespaces prefix from title
		$namespaces = array(NS_MEDIAWIKI, NS_TEMPLATE, NS_CATEGORY, NS_FILE);
		if (defined('NS_VIDEO')) {
			$namespaces[] = NS_VIDEO;
		}
		if ( in_array($ns, array_merge( $namespaces, $wgSuppressNamespacePrefix ) ) ) {
			$this->title = $wgTitle->getText();
			$this->displaytitle = false;
		}

		// talk pages
		if ($wgTitle->isTalkPage()) {
			// remove comments & FB like button
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

			$this->pageTalkSubject = Wikia::link($wgTitle->getSubjectPage(), wfMsg($msgKey), array('accesskey' => 'c'));
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
			$this->displaytitle = false;
		}

		// mainpage
		if (ArticleAdLogic::isMainPage()) {
			// change page title to just "Home"
			$this->title = wfMsg('oasis-home');
			// hide revisions / categories bar
			$this->categories = false;
			$this->revisions = false;
		}

		// render page type info
		switch($ns) {
			case NS_MEDIAWIKI:
				$this->pageType = wfMsg('oasis-page-header-subtitle-mediawiki');
				break;

			case NS_TEMPLATE:
				$this->pageType = wfMsg('oasis-page-header-subtitle-template');
				break;

			case NS_SPECIAL:
				$this->pageType = wfMsg('oasis-page-header-subtitle-special');

				// remove comments button (fix FB#3404 - Marooned)
				$this->comments = false;

				// FIXME: use PageHeaderIndexAfterExecute hook or $wgSupressPageSubtitle instead
				if($wgTitle->isSpecial('PageLayoutBuilderForm') || $wgTitle->isSpecial('PageLayoutBuilder') ) {
					$this->displaytitle = true;
					$this->pageType = "";
				}
				if($wgTitle->isSpecial('Newimages')) {
					$this->isNewFiles = true;
				}
				break;

			case NS_CATEGORY:
				$this->pageType = wfMsg('oasis-page-header-subtitle-category');
				break;

			case NS_FORUM:
				$this->pageType = wfMsg('oasis-page-header-subtitle-forum');
				break;
		}

		// render subpage info
		$this->pageSubject = $skin->subPageSubtitle();

		// render MW subtitle (contains old revision data)
		$this->subtitle = $wgOut->getSubtitle();

		// render redirect info (redirected from)
		if (!empty($wgArticle->mRedirectedFrom)) {
			$this->pageRedirect = trim($this->subtitle, '()');
			$this->subtitle = '';
		}

		// render redirect page (redirect to)
		if ($wgTitle->isRedirect()) {
			$this->pageType = $this->subtitle;
			$this->subtitle = '';
		}

		// if page is rendered using one column layout, show search box as a part of page header
		$this->showSearchBox = isset($params['showSearchBox']) ? $params['showSearchBox'] : false ;

		if (!empty($wgSupressPageTitle)) {
			$this->title = '';
			$this->subtitle = '';
		}

		if (!empty($wgSupressPageSubtitle)) {
			$this->subtitle = '';
		}
		else {
			// render pageType, pageSubject and pageSubtitle as one message
			$subtitle = array_filter(array(
				$this->pageType,
				$this->pageTalkSubject,
				$this->pageSubject,
				$this->pageRedirect,
			));

			/*
			 * support for language variants
			 * this adds links which automatically convert the content to that variant
			 *
			 * @author tor@wikia-inc.com
			 */
			if ( $wgContLang->hasVariants() ) {
				foreach ( $wgContLang->getVariants() as $variant ) {
					if ( $variant != $wgContLang->getCode() ) {
						$subtitle[] = Xml::element(
							'a',
							array(
								'href' => $wgTitle->getLocalUrl( array( 'variant' => $variant ) ),
								'rel' => 'nofollow'
							),
							$wgContLang->getVariantname( $variant )
						);
					}
				}
			}

			$pipe = wfMsg('pipe-separator');
			$this->pageSubtitle = implode(" {$pipe} ", $subtitle);
		}

		wfProfileOut(__METHOD__);
	}

	/**
	 * Render header for edit page
	 */
	public function executeEditPage() {
		global $wgTitle, $wgRequest, $wgSuppressToolbar, $wgShowMyToolsOnly;

		// special handling for special pages (CreateBlogPost, CreatePage)
		if ($wgTitle->getNamespace() == NS_SPECIAL) {
			wfProfileOut(__METHOD__);
			return;
		}

		// detect section edit
		$isSectionEdit = is_numeric($wgRequest->getVal('section'));

		// show proper message in the header
		$action = $wgRequest->getVal('action', 'view');

		$isPreview = $wgRequest->getCheck( 'wpPreview' ) || $wgRequest->getCheck( 'wpLivePreview' );
		$isShowChanges = $wgRequest->getCheck( 'wpDiff' );
		$isDiff = !is_null($wgRequest->getVal('diff')); // RT #69931
		$isEdit = in_array($action, array('edit', 'submit'));
		$isHistory = $action == 'history';

		// add editor's right rail when not editing main page
		if (!Wikia::isMainPage()) {
			OasisModule::addBodyClass('editor-rail');
		}

		// hide floating toolbar when on edit page / in preview mode / show changes
		if ($isEdit || $isPreview) {
			$wgSuppressToolbar = true;
		}

		// choose header message
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
		else if ($isHistory) {
			$titleMsg = 'oasis-page-header-history';
		}
		else {
			$titleMsg = 'oasis-page-header-editing';
		}

		$this->displaytitle = true;
		$this->title = wfMsg($titleMsg, htmlspecialchars($wgTitle->getPrefixedText()));

		// back to article link
		if (!$isPreview && !$isShowChanges) {
			$this->subtitle = Wikia::link($wgTitle, wfMsg('oasis-page-header-back-to-article'), array('accesskey' => 'c'), array(), 'known');
		}

		// add edit button
		if ($isDiff || $isHistory) {
			$this->prepareActionButton();

			// show only "My Tools" dropdown on toolbar
			$wgShowMyToolsOnly = true;
		}

		// render edit dropdown / commments chicklet on history pages
		if ($isHistory) {
			// dropdown actions
			$this->dropdown = $this->getDropdownActions();

			// use service to get data
			$service = new PageStatsService($wgTitle->getArticleId());

			// comments
			$this->comments = $service->getCommentsCount();
		}
	}

	/**
	 * Render edit box header when doing preview / showing changes
	 */
	public function executeEditBox() {
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
		$this->subtitle = Wikia::link($wgTitle, wfMsg('oasis-page-header-back-to-article'), array('accesskey' => 'c'), array(), 'known');
	}

	/**
	 * Called instead of executeIndex when the CorporatePage extension is enabled.
	 */
	public function executeCorporate() {
		global $wgTitle, $wgOut, $wgSuppressNamespacePrefix;
		wfProfileIn( __METHOD__ );
		
		// page namespace
		$ns = $wgTitle->getNamespace();
                
		// default title "settings" (RT #145371), don't touch special pages
		if ($ns == NS_FORUM) {
			$this->title = $wgTitle->getText();
			$this->displaytitle = false;
                // we don't want htmlspecialchars for SpecialPages (BugId:6012)
                } else if ($ns == NS_SPECIAL) {
                    $this->displaytitle = true;
		} else if ($ns != NS_SPECIAL) {
			$this->displaytitle = true;
			$this->title = $wgOut->getPageTitle();
		}

		// remove namespaces prefix from title
		$namespaces = array(NS_MEDIAWIKI, NS_TEMPLATE, NS_CATEGORY, NS_FILE);
		if (defined('NS_VIDEO')) {
			$namespaces[] = NS_VIDEO;
		}
		if ( in_array($ns, array_merge( $namespaces, $wgSuppressNamespacePrefix ) ) ) {
			$this->title = $wgTitle->getText();
			$this->displaytitle = false;
		}

		if (ArticleAdLogic::isMainPage()) {
			$this->title = '';
			$this->subtitle = '';
		}
		else if (BodyModule::isHubPage()) {
			$this->title = wfMsg('hub-header', $wgTitle);
			global $wgOut;
			$wgOut->addScriptFile('../oasis/js/CorporateHub.js');
		}
		
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Modify edit page: add preview notice bar and render edit box header
	 * (to be removed after EditPageReskin fully released)
	 */
	public static function modifyEditPage(&$editPage) {
		wfProfileIn(__METHOD__);
		global $wgUser;

		// get skin name
		$skinName = get_class($wgUser->getSkin());

		if ($skinName == 'SkinOasis') {
			// load CSS for editpage
			global $wgOut;
			$wgOut->addStyle(AssetsManager::getInstance()->getSassCommonURL('skins/oasis/css/core/_EditPage.scss'));

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

	static function onArticleSaveComplete(&$article, &$user, $text, $summary,
		$minoredit, $watchthis, $sectionanchor, &$flags, $revision, &$status, $baseRevId) {
		global $wgMemc;
		$wgMemc->delete(wfMemcKey('mOasisRecentRevisions2', $article->getTitle()->getArticleId()));
		return true;
	}
}
