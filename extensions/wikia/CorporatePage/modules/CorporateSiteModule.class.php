<?php
/**
 * Description of CorporateModule
 *
 * @author owen
 */
class CorporateSiteModule extends Module {

	var $slider;
	var $slider_class;
	var $data;

	var $is_manager;  // fix this typo

	// fix these
	var $hidehotspots;
	var $hidetopwikis;
	var $hidetopblogs;
	var $hidetopeditors;

// These are just templates

// FIXME: refactor the common functionality out of these
	public function executeTopHubWikis() {
		global $wgUser, $wgTitle;

		$isManager = $wgUser->isAllowed( 'corporatepagemanager' );
		$datafeeds = new WikiaStatsAutoHubsConsumerDB(DB_SLAVE);

		$lang = AutoHubsPagesHelper::getLangForHub($wgTitle);
		$tag_id = AutoHubsPagesHelper::getHubIdFromTitle($wgTitle);
		$tag_name = AutoHubsPagesHelper::getHubNameFromTitle($wgTitle);

		if ($isManager) {
			$temp = $datafeeds->getTopWikis($tag_id, $lang, 30, true, true);
			$temp['value'] = array_slice($temp['value'], 0, 30);
		} else {
			$temp = $datafeeds->getTopWikis($tag_id, $lang, 10, false);
			$temp['value'] = array_slice($temp['value'], 0, 10);
		}
		$this->hidetopwikis = false;
		$this->data['title'] = $wgTitle;
		$this->data['topWikis1'] = $temp['value'];
		$this->data['tag_id'] = $tag_id;
		$this->data['is_manager'] = $isManager;
	}

	public function executeTopHubUsers() {
		global $wgUser, $wgTitle;

		$isManager = $wgUser->isAllowed( 'corporatepagemanager' );
		$datafeeds = new WikiaStatsAutoHubsConsumerDB(DB_SLAVE);

		$lang = AutoHubsPagesHelper::getLangForHub($wgTitle);
		$tag_id = AutoHubsPagesHelper::getHubIdFromTitle($wgTitle);

		$temp = $datafeeds->getTopUsers($tag_id, $lang, 5);
		foreach ($temp['value'] as &$value) {
			$value['avatar'] = AvatarService::renderAvatar($value['username'], 20);
		}
		$this->data['title'] = $wgTitle;
		$this->data['topEditors'] = $temp['value'];
		$this->data['is_manager'] = $isManager;
	}

	public function executePopularHubPosts () {
		global $wgUser, $wgTitle, $wgStylePath, $wgEnableBlog;
		$isManager = $wgUser->isAllowed( 'corporatepagemanager' );
		$datafeeds = new WikiaStatsAutoHubsConsumerDB(DB_SLAVE);

		$lang = AutoHubsPagesHelper::getLangForHub($wgTitle);
		$tag_id = AutoHubsPagesHelper::getHubIdFromTitle($wgTitle);
		$tag_name = AutoHubsPagesHelper::getHubNameFromTitle($wgTitle);
		$this->hidetopblogs=false;
		if ($isManager) {
			$temp = $datafeeds->getTopBlogs($tag_id, $lang, 9, 3, true, true);
		} else {
			$temp = $datafeeds->getTopBlogs($tag_id, $lang, 3, 1);
		}
		$this->data['title'] = $wgTitle;
//		$this->data['topBlogs'] = $temp['value'];
		$this->data['is_manager'] = $isManager;

		$this->wgStylePath = $wgStylePath;
		$this->wgTitle = $wgTitle;

		// Swizzle data into format used by BlogListing template
		$posts = array();
		foreach ($temp['value'] as $value) {
			$post = array();
			$post['title'] = $value['title'];        // FIXME: this is wrong for the corp site
			$post['namespace'] = $value['namespace'];
			$post['timestamp'] = $value['timestamp'];
			$post['date'] = $value['date'];
			$post['avatar'] = AvatarService::renderAvatar($value['author'], 48);
			$post['userpage'] = $value['page_url'];
			$post['username'] = $value['author'];
			$post['readmore'] = null;
			$post['text'] = $value['description'];
			$post['comments'] = $value['all_count'];
			$post['likes'] = null;
			$posts[] = $post;
		}

		$this->posts = $posts;
	}

	public function executeSalesSupport () {

		global $wgUser;
		wfProfileIn(__METHOD__);

		// add CSS for this module
		$this->isAdmin = $wgUser->isAllowed('editinterface');

		wfProfileOut(__METHOD__);
	}

	public function executePopularStaffPosts () {
		global $wgUser, $wgTitle, $wgStylePath, $wgEnableBlog, $wgContLanguageCode;

		$isManager = $wgUser->isAllowed( 'corporatepagemanager' );
		$datafeeds = new WikiaStatsAutoHubsConsumerDB(DB_SLAVE);
//		$lang = AutoHubsPagesHelper::getLangForHub($wgTitle);
//		$tag_id = AutoHubsPagesHelper::getHubIdFromTitle($wgTitle);
//		$tag_name = AutoHubsPagesHelper::getHubNameFromTitle($wgTitle);

		$wikiurl = "http://community.wikia.com";
		$html_out = Http::get( $wikiurl."/api.php?action=query&list=categorymembers&cmtitle=Category:Staff_blogs&cmnamespace=500&cmsort=timestamp&cmdir=desc&format=json" );
		$data = json_decode($html_out, true);
		$page_ids = array();
		if (isset($data['query']) && isset($data['query']['categorymembers'])) {
			foreach ($data['query']['categorymembers'] as $r) {
				$page_ids[] = $r['pageid'];
			}
		}
		if ($isManager) {
			$temp = $datafeeds->getTopBlogs("staff", $wgContLanguageCode, 8, 4, true, true, $page_ids);
		} else {
			$temp = $datafeeds->getTopBlogs("staff", $wgContLanguageCode, 4, 4, false, false, $page_ids);
		}
		$posts = array();
		foreach ($temp['value'] as $value) {
			// get additional data for the blog
			$post = array();
			$post['title'] = $value['title'];
			$post['namespace'] = $value['namespace'];
			$post['timestamp'] = $value['timestamp'] ;
			$post['date'] = $value['date'];
			$post['avatar'] = AvatarService::renderAvatar($value['author'], 48);
			$post['userpage'] = $value['real_pagename'];  // FIXME
			$post['username'] = $value['author'];
			$post['readmore'] = null;
			$post['text'] = $value['description'];
			$post['comments'] = $value['all_count'];
			$post['likes'] = null;
			$posts[] = $post;
		}
		$this->posts = $posts;
		$this->data['title'] = 'Popular Staff Blogs';
		$this->wgStylePath = $wgStylePath;
		$this->wgTitle = $wgTitle;
	}

	public function executeHotSpots () {
		global $wgUser, $wgTitle;

		$isManager = $wgUser->isAllowed( 'corporatepagemanager' );
		$datafeeds = new WikiaStatsAutoHubsConsumerDB(DB_SLAVE);

		$lang = AutoHubsPagesHelper::getLangForHub($wgTitle);
		$tag_id = AutoHubsPagesHelper::getHubIdFromTitle($wgTitle);
		$tag_name = AutoHubsPagesHelper::getHubNameFromTitle($wgTitle);

		if ( $isManager ) {
			$temp = $datafeeds->getTopArticles( $tag_id, $lang, 15, 3, true, true, true );
		} else {
			$temp = $datafeeds->getTopArticles( $tag_id, $lang, 5, 1, false, false, true );
		}

		$this->data['title'] = $wgTitle;
		$this->data['hotSpots'] = $temp['value'];
		$this->data['tag_id'] = $tag_id;
		$this->data['is_manager'] = $isManager;
	}

	public function executeSlider() {
		global $wgOut, $wgTitle, $wgStylePath, $wgParser;
		wfLoadExtensionMessages( 'CorporatePage' );

		if (BodyModule::isHubPage()) {
			$this->slider_class = "small";
			$tag_name = AutoHubsPagesHelper::getHubNameFromTitle($wgTitle);
			$this->slider = CorporatePageHelper::parseMsgImg( 'hub-' . $tag_name . '-slider', true );

			// render slider's HTML using WikiaPhotoGallery (BugId:8478)
			$slider = new WikiaPhotoGallery();
			$slider->setParser($wgParser);
			$slider->parseParams(array(
				'type' => 'slider',
				'orientation'=> 'bottom',
			));

			// add images
			$sliderWikitext = '';

			foreach($this->slider as $image) {
				// ElmoControlRoom.jpg|Label|link=http://wikia.com|linktext=Link text
				$sliderWikitext .= "{$image['imagetitle']}|{$image['title']}|link={$image['href']}|linktext={$image['desc']}\n";
			}

			// set the content and parse it
			$slider->setText($sliderWikitext);
			$slider->parse();

			// render it
			$this->sliderHtml = $slider->toHTML();
		}
		if (ArticleAdLogic::isMainPage()) {
			$this->isMainPage = true;
			$this->slider_class = "big";
			$this->slider = CorporatePageHelper::parseMsgImg('corporatepage-slider',true);
		}
		else {
			$this->isMainPage = false;
		}
	}
}
