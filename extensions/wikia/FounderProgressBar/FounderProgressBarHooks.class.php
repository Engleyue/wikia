<?php

/**
 * This class is a container for all the hooks we will use to capture actions/events for the Founder Progress Bar
 *
 */

class FounderProgressBarHooks {

	/**
	 * @desc Counts actions involve adding or editing articles
	 */
	function onArticleSaveComplete (&$article, &$user, $text, $summary, $minoredit, $watchthis, $sectionanchor, &$flags, $revision, &$status, $baseRevId) {

		// Quick exit if we are already done with Founder Progress Bar (memcache key set for 30 days)
		if (self::allTasksComplete()) {
			return true;
		}
		
		$app = F::app();
		$title = $article->getTitle();
		
		if ($flags & EDIT_NEW) {
			// Tasks related to adding new pages X (do not count auto generated user pages or categories or files or ...)
			if ($title->getNamespace() == NS_MAIN) {
				$app->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_PAGE_ADD_10));			
				$app->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_PAGE_ADD_20));			
				if (self::bonusTaskEnabled(FT_BONUS_PAGE_ADD_5)) {
					$app->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_BONUS_PAGE_ADD_5));			
				}
			}
			// if blogpost
			if ($app->wg->EnableBlogArticles && $title->getNamespace() == NS_BLOG_ARTICLE) {
				$app->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_BLOGPOST_ADD));				
			}			
			// if topten list
			if ($app->wg->EnableTopListsExt && $title->getNamespace() == NS_TOPLIST) {
				$app->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_TOPTENLIST_ADD));				
			}				
			// Bonus task: add page layout builder
			if (defined( NS_PLB_LAYOUT ) && $title->getNamespace() == NS_PLB_LAYOUT && self::bonusTaskEnabled(FT_BONUS_PAGELAYOUT_ADD) ) {
				$app->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_BONUS_PAGELAYOUT_ADD));
			} 	

		}
		
		// Tasks related to updating existing pages
		if ($flags & EDIT_UPDATE) {

			// Tasks related to editing any article content X
			$app->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_TOTAL_EDIT_75));
			$app->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_TOTAL_EDIT_300));		
			if (self::bonusTaskEnabled(FT_BONUS_EDIT_50)) {
				$app->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_BONUS_EDIT_50));		
			}
			
			// if main page
			if ($title->getArticleId() == Title::newMainPage()->getArticleId()) {
				$app->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_MAINPAGE_EDIT));				
				
				// Is there a better way to detect if there's a slider on the main page?
				if (stripos($text, "slider") > 0) {
					$app->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_MAINPAGE_ADDSLIDER));				
				}
			}
			
			// Add a page to a category: this var is set by the Parser
			$categoryInserts = Wikia::getVar('categoryInserts');
			if (!empty($categoryInserts)) {
				$app->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_CATEGORY_ADD_3));
				$app->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_CATEGORY_ADD_5));				
			}
			
			// edit category page X
			if ($title->getNamespace() == NS_CATEGORY) {
				$app->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_CATEGORY_EDIT));
			}		

			// edit TOP_NAV Wiki-navigation X
			if ($title->getNamespace() == NS_MEDIAWIKI && $title->getText() == "Wiki-navigation") {
				$app->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_TOPNAV_EDIT));
			}

			// if commcorner X
			if ($title->getNamespace() == NS_MEDIAWIKI && $title->getText() == "Community-corner") {
				$app->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_COMMCORNER_EDIT));				
			}
			
			// edit profile page X
			if ($title->getNamespace() == NS_USER && $title->getText() == $app->wg->User->getName()) {
				$app->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_PROFILE_EDIT));				
			}
			// if page contains gallery tag
			if (stripos ($text, "<gallery") > 0) {
				$app->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_GALLERY_ADD));			
			}
			
			// if page contains video tag
			if (stripos ($text, "<video") > 0) {
				$app->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_VIDEO_ADD));			
			}
			
		}						
		return true;
	}
	
	/**
	 * @desc Counts the following actions
	 * 
	 * Adding a photo or uploading a new wordmark X
	 * 
	 */
	function onUploadComplete (&$image) {

		// Quick exit if tasks are all completed
		if (self::allTasksComplete()) {
			return true;
		}
		
		$app = F::app();		
		// Any image counts for these
		$app->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_PHOTO_ADD_10));
		$app->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_PHOTO_ADD_20));
		// Only workmark counts for this one
		if ($image && $image->getTitle()->getText() == "Wiki-wordmark.png") {
			$app->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_WORDMARK_EDIT));
		}
		if (self::bonusTaskEnabled(FT_BONUS_PHOTO_ADD_10)) {
			$app->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_BONUS_PHOTO_ADD_10));		
		}		
				
		return true;
	}
	
	function onAddNewAccount ($user) {

		// Quick exit if tasks are all completed
		if (FounderProgressBarHooks::allTasksComplete()) {
			return true;
		}
		
		F::app()->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_USER_ADD_5));
		return true;
	}
	
	// Initialize schema for a new wiki
	function onWikiCreation ( $params ) {

		// Always initialize for new wikis
		if (isset($params['city_id'])) {
			FounderProgressBarHooks::initRecords($params['city_id']);
		}

		return true;
	}
	
	// When a bonus task is enabled it is added to the full task list
	public static function bonusTaskEnabled($task_id) {
		$data = F::app()->sendRequest('FounderProgressBar', 'getLongTaskList', array())->getData();
		if (isset($data['list'][$task_id])) return true;
		return false;
	}
	
	// Initialize a scratch record for each of the initial available tasks
	public static function initRecords($wiki_id) {
		// Records go into global wikicites table
		$app = F::app();
		$dbw = $app->wf->GetDB(DB_MASTER, array(), $app->wg->ExternalSharedDB);

		// FIXME: this has to be updated when new tasks are added
		for($task_id = 10; $task_id <= 300; $task_id+=10) {
			$sql = "INSERT IGNORE INTO founder_progress_bar_tasks SET wiki_id=$wiki_id, task_id=$task_id";
			$dbw->query ($sql);
		}		
		$dbw->commit();
		$memKey = $app->wf->MemcKey('FounderLongTaskList');
		$app->wg->Memc->delete($memKey);
		
	}
	/**
	 * This helper function checks to see if all tasks are completed.
	 * Skipped tasks do NOT count against this total, but bonus tasks do
	 * If all tasks are complete, award that event (if not awarded already) and set a memcache flag
	 */
	public static function allTasksComplete() {
		$app = F::app();
		$memKey = $app->wf->MemcKey('FounderTasksCompleted');
		$task_complete = $app->wg->Memc->get($memKey);
		if (empty($task_complete)) {
			$response = $app->sendRequest('FounderProgressBar',"isTaskComplete", array("task_id" => "1000"));
			$completed = $response->getVal('task_completed', 0);
			// Completion task set, and once set it can never be undone
			if ($completed) {
				$app->wg->Memc->set($memKey, true, 86400*30 );
				return true;				
			}
			// Tasks are not complete, so we need to count how many we have completed to see if we are done
			$response = $app->sendRequest('FounderProgressBar',"getLongTaskList", array("use_master" => true));			
			$data = $response->getVal('data');
			// Completion task NOT set but all other tasks are complete, so set it.  
			// TODO: display some kind of YAY YOU DID IT! message here
			if ($data['tasks_completed'] >= $data['total_tasks']) {
				$app->sendRequest('FounderProgressBar', 'doTask', array('task_id' => FT_COMPLETION));
				$app->wg->Memc->set($memKey, true, 86400*30 );
				return true;
			}
			return false;
		}
		return $task_complete;
	}
	
}
