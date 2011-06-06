<?php
class BlogListingModule extends Module {

	var $wgTitle;
	var $wgBlankImgUrl;
	var $wgStylePath;

	var $posts;
	var $blogListingClass;
	var $title;
	var $pager;
	var $seeMoreUrl;

	/**
	 * Modify results from Blogs
	 *
	 * Add likes count and render avatar
	 */
	static function getResults(&$results) {
		wfProfileIn(__METHOD__);

		global $wgLang;

		// get message for "read more" link
		wfLoadExtensionMessages('Blogs');
		$cutSign = wfMsg('blug-cut-sign');

		foreach($results as &$result) {
			$service = new PageStatsService($result['page']);

			$result['likes'] = false;
			$result['avatar'] = AvatarService::renderAvatar($result['username'], 48);
			$result['userpage'] = AvatarService::getUrl($result['username']);
			$result['date'] = $wgLang->date(wfTimestamp(TS_MW, $result['timestamp']));

			// "read more" handling
			if (strpos($result['text'], $cutSign) !== false) {
				$result['readmore'] = true;
			}
		}

		//print_pre($results);

		wfProfileOut(__METHOD__);
		return true;
	}

	/**
	 * Render blog listing
	 *
	 * Output HTML just for Oasis which will be hidden by default
	 */
	static function renderBlogListing(&$html, $posts, $aOptions, $sPager = null) {
		wfProfileIn(__METHOD__);
		global $wgTitle, $wgStylePath;

		// macbre: prevent PHP warnings and try to find the reason of them
		if (!is_array($posts)) {
			$url = wfGetCurrentUrl();
			Wikia::log(__METHOD__, false, "\$posts is not an array - {$url['url']}", true);

			wfProfileOut(__METHOD__);
			return true;
		}

		$additionalClass = '';
		if (!empty($aOptions['class'])) {
			$additionalClass = ' '.$aOptions['class'];
		}
		if ($aOptions['type'] == 'box') {
			$html .= wfRenderPartial('BlogListing', 'Index', array('posts' => $posts, 'blogListingClass' => "WikiaBlogListingBox module $additionalClass", 'wgTitle' => $wgTitle, 'wgStylePath' => $wgStylePath, 'title' => $aOptions['title'], 'seeMoreUrl' => $aOptions['seemore']));
		} else {
			$html .= wfRenderPartial('BlogListing', 'Index', array('posts' => $posts, 'blogListingClass' => "WikiaBlogListing$additionalClass", 'wgTitle' => $wgTitle, 'wgStylePath' => $wgStylePath, 'title' => $aOptions['title'], 'pager' => $sPager, 'seeMoreUrl' => $aOptions['seemore']));
		}

		wfProfileOut(__METHOD__);
		return true;
	}

	public function executeIndex() {
		//$this->posts = $data['posts'];
	}
}
