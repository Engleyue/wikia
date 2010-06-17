<?php

/**
 * WikiaApiQueryEventsData
 *
 * @author Piotr Molski (moli) <moli@wikia.com>
 *
 */
class WikiaApiQueryEventsData extends ApiQueryBase {

	private 
		$mParams 		= false,
		$mPageId		= false,
		$mLogid			= false,
		$mRevId 		= false,
		$mTimestamp		= false, 
		$mSize 			= false,
		$mIsNew			= false,
		$mCityId		= 0;

	/**
	 * constructor
	 */
	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, "");
	}

	protected function getTokenFunctions() {
		wfProfileIn( __METHOD__ );
		
		if ( !isset($this->tokenFunctions) ) {
			/*// If we're in JSON callback mode, no tokens can be obtained
			if ( !is_null( $this->getMain()->getRequest()->getVal('callback') ) ) {
				return array();
			}*/
			$this->tokenFunctions = array();
			wfRunHooks('WikiaApiQueryEventsDataTokens', array( &$this->tokenFunctions ) );
		}
		
		wfProfileOut( __METHOD__ );
		return $this->tokenFunctions;
	}

	private function getRevisionCount() {
		wfProfileIn( __METHOD__ );
		$this->mRevId = 0;
		if ( isset($this->params['revid']) ) {
			$this->mRevId = intval($this->params['revid']);
		}
		$count = $this->mRevId > 0;
		wfProfileOut( __METHOD__ );
		return $count;
	}

	private function getPageCount() {
		wfProfileIn( __METHOD__ );
		$this->mPageId = 0;
		if ( isset($this->params['pageid']) ) {
			$this->mPageId = intval($this->params['pageid']);
		}
		$count = $this->mPageId > 0;
		wfProfileOut( __METHOD__ );
		return $count;
	}

	private function getLoggingCount() {
		wfProfileIn( __METHOD__ );
		$this->mLogid = 0;
		if ( isset($this->params['logid']) ) {
			$this->mLogid = intval($this->params['logid']);
		}
		$count = $this->mLogid > 0;
		wfProfileOut( __METHOD__ );
		return $count;
	}
	
	private function getArchivePage($oRC) {
		wfProfileIn( __METHOD__ );
		
		if ( empty($this->mPageId) ) {
			wfProfileOut( __METHOD__ );
			return false;
		}
		
		if ( !is_object($oRC) ) {
			wfProfileOut( __METHOD__ );
			return false;
		}

		$db = $this->getDB();

		$fields = array(
			'ar_namespace as page_namespace',
			'ar_title as page_title',
			'ar_comment as rev_comment',
			'ar_user as rev_user',
			'ar_user_text as rev_user_text',
			'ar_timestamp as rev_timestamp',
			'ar_minor_edit as rev_minor_edit',
			'ar_rev_id as rev_id',
			'ar_text_id as rev_text_id',
			'ar_len as rev_len',
			'ar_page_id as page_id'
		);

		$this->profileDBIn();
		$oRow = $db->selectRow( 
			'archive', 
			$fields, 
			array( 
				'ar_title'		=> $oRC->getAttribute('rc_title'),
				'ar_namespace'	=> $oRC->getAttribute('rc_namespace'),
				'ar_page_id'	=> $this->mPageId 
			),
			__METHOD__, 
			array( 
				'ORDER BY' => 'ar_timestamp desc' 
			)
		);
		$this->profileDBOut();

		$result = false;
		if ( is_object($oRow) && isset($oRow) && ( $oRow->page_id == $this->mPageId ) ) {
			$result = $oRow;
		} 
		
		wfProfileOut( __METHOD__ );
		return $result;
	}

	private function getRevisionFromLog() {
		wfProfileIn( __METHOD__ );

		$this->addTables   ( 'logging' );
		$this->addFields   ( 'recentchanges.*' );
		$this->addTables   ( 'recentchanges' );
		$this->addWhere    ( 'log_id = rc_logid' );
		$this->addWhere    ( 'log_title = rc_title' );
		$this->addWhere    ( 'log_namespace = rc_namespace' );
		$this->addWhereFld ( 'log_id', $this->mLogid );

		$res = $this->select(__METHOD__);

		$count = 0;
		$oRC = false;
		$db = $this->getDB();
		if ( $row = $db->fetchObject($res) ) {
			$oRC = RecentChange::newFromRow( $row );
		}
		$db->freeResult($res);
		
		error_log("oRC = " . print_r($oRC, true), 3, "/tmp/moli.log");

		$res = ( is_object($oRC) ) ? $this->getArchivePage($oRC) : false;
		error_log("res = " . print_r($res, true), 3, "/tmp/moli.log");
		
		wfProfileOut( __METHOD__ );
		return $res;
	}

	private function getRevisionFromPage() {
		wfProfileIn( __METHOD__ );
		$this->addTables	( 'revision' );
		$this->addFields	( 'page_id' );
		$this->addFields	( Revision::selectPageFields() );
		$this->addFields	( Revision::selectFields() );
		$this->addTables	( 'page' );
		$this->addWhere		( 'page_id = rev_page' );
		$this->addWhereFld	( 'rev_id', $this->mRevId );
		$this->addWhereFld	( 'page_id', $this->mPageId );

		$result = false;
		$res = $this->select(__METHOD__);
		$db = $this->getDB();
		if ( $oRow = $db->fetchObject($res) ) {
			if ( is_object($oRow) ) {
				$result = $oRow;
			}
		}
		$db->freeResult($res);
		
		wfProfileOut( __METHOD__ );
		return $result;
	}

	private function checkIsNew() {
		wfProfileIn( __METHOD__ );

		$db = $this->getDB();
		$this->profileDBIn();
		$oRow = $db->selectRow( 
			'revision', 
			'rev_id', 
			array( 
				'rev_id'		=> $this->mRevId,
				'rev_page'		=> $this->mPageId,
			),
			__METHOD__
		);
		$this->profileDBOut();
		$this->mIsNew = ( isset( $oRow->rev_id ) ) ? false : true; 
		
		wfProfileOut( __METHOD__ );
		return intval($this->mIsNew);
	}

	public function execute() {
		global $wgCityId;
		wfProfileIn( __METHOD__ );

		# extract request params
		$this->mCityId = $wgCityId;
		$this->params = $this->extractRequestParams(false);
		error_log ( "params1 = " .print_r($this->params, true) . " \n", 3, "/tmp/moli.log");

		# check "pageid" param
		$pageCount = $this->getPageCount();

		# check "revid" param
		$revCount = $this->getRevisionCount();

		# check "logid" param
		$logCount = $this->getLoggingCount();

		error_log ("params = " . print_r($this->params, true) . " \n", 3, "/tmp/moli.log");
		error_log ("revCount = $revCount \n", 3, "/tmp/moli.log");
		error_log ("pageCount = $pageCount \n", 3, "/tmp/moli.log");
		error_log ("logCount = $logCount \n", 3, "/tmp/moli.log");

		if ( $revCount === 0 && $pageCount === 0 && $logCount == 0 ) {
			wfProfileOut( __METHOD__ );
			return;
		}

		if ( $pageCount == 0 ) {
			wfProfileOut( __METHOD__ );
			$this->dieUsage('The pageid parameter can not be empty', 'pageid');
		}

		if ( $logCount > 0 && $revCount > 0 ) {
			wfProfileOut( __METHOD__ );
			$this->dieUsage('The logid parameter may not be used with the revid parameter', 'logid');
		}

		if ( $pageCount > 0 && ( $revCount == 0 && $logCount == 0 ) ) {
			wfProfileOut( __METHOD__ );
			$this->dieUsage('The pageid parameter may not be used without the revid= or logid= parameter', 'logid');
		}

		# if logids is set
		$deleted = 0;
		if ( $logCount > 0 ) {
			$oRow = $this->getRevisionFromLog();
			$deleted = 1;
			if ( $oRow === false ) {
				wfProfileOut( __METHOD__ );
				return false;
			}
		} else {
			$oRow = $this->getRevisionFromPage();
			if ( $oRow === false ) {
				wfProfileOut( __METHOD__ );
				return false;
			}
		}
		
		$vals = $this->extractRowInfo($oRow, $deleted);
		
		$pageInfo = array(
			'id' => $oRow->page_id,
			'title' => $oRow->page_title,
			'namespace' => $oRow->page_namespace,
		);
		
		$this->getResult()->setIndexedTagName($vals, 'events');
		$this->getResult()->addValue('query', 'page', $pageInfo);
		$this->getResult()->addValue('query', 'revision', $vals);

		wfProfileOut( __METHOD__ );
	}
	
	private function _get_user_ip($user_id) {
		wfProfileIn( __METHOD__ );
		$db = $this->getDB();

		$this->profileDBIn();
		$oRow = $db->selectRow( 
			'cu_changes', 
			'cuc_user, cuc_ip, cuc_timestamp', 
			array( 
				'cuc_user'	=> $user_id 
			),
			__METHOD__, 
			array( 
				'ORDER BY' => 'cuc_user desc, cuc_ip desc, cuc_timestamp desc' 
			)
		);
		$this->profileDBOut();

		$ip = '';
		if ( is_object($oRow) && isset($oRow->cuc_ip) ) {
			$ip = $oRow->cuc_ip;
		}
		
		wfProfileOut( __METHOD__ );
		return $ip;
	}

	private function _user_is_bot($user_text) {
		$user_is_bot = false;
		$oUser = User::newFromName($user_text);
		if ( $oUser instanceof User ) {
			$user_is_bot = $oUser->isBot();
		}
		return $user_is_bot;
	}
	
	private function _revision_is_redirect($content) {
		$titleObj = Title::newFromRedirect( $content );
		$rev_is_redirect = is_object($titleObj) ;
		return $rev_is_redirect;
	}

	private function _revision_is_content($oTitle) {
		global $wgEnableBlogArticles;
		$is_content_ns = 0;
		if ( $oTitle instanceof Title ) {
			$is_content_ns = $oTitle->isContentPage();
			if ( empty($is_content_ns) && $wgEnableBlogArticles ) { 
				$is_content_ns = (!in_array($oTitle->getNamespace(), array(NS_BLOG_ARTICLE, NS_BLOG_ARTICLE_TALK, NS_BLOG_LISTING, NS_BLOG_LISTING_TALK)));
			}
		}
		return (int) $is_content_ns;
	}
	
	private function _make_links($content) {
		$links = array(
			'image' => 0,
			'video' => 0			
		);
		$oArticle = Article::newFromId($this->mPageId);
		if ( $oArticle instanceof Article ) {
			$editInfo = $oArticle->prepareTextForEdit( $content, $this->mRevId );
			$images = $editInfo->output->getImages();
			if ( !empty($images) ) {
				foreach ($images as $iname => $dummy) {
					if ( substr($iname, 0, 1) == ':' ) {
						$links['video']++;							
					} else {
						$links['image']++;
					}
				}
			}
		}
		return $links;
	}

	private function extractRowInfo( $oRow, $deleted = 0 ) {
		wfProfileIn( __METHOD__ );

		$vals = array ();
		if ( $deleted == 0 ) {
			$oRevision = new Revision($oRow);
			$oTitle = $oRevision->getTitle();
			$content = $oRevision->revText();

			# revision id
			$vals['revid'] = intval($oRevision->getId());
			# username
			$vals['username'] = $oRevision->getUserText();
			# user id
			$vals['userid'] = $oRevision->getUser();
			# user ip
			$vals['user_ip'] = ( IP::isIPAddress($vals['username']) ) ? $vals['username'] : $this->_get_user_ip($vals['userid']);
			# user is bot
			$vals['userisbot'] = intval( $this->_user_is_bot( $vals['username'] ) );
			# is new
			$vals['isnew'] = $this->checkIsNew();
			# timestamp
			$vals['timestamp'] = wfTimestamp( TS_DB, $oRevision->getTimestamp() );
			# size
			$vals['size'] = intval($oRevision->getSize());
			#words
			$vals['words'] = str_word_count( $content );
			# revision is redirect
			$vals['isredirect'] = intval( $this->_revision_is_redirect( $content ) );
			# revision is content
			$vals['iscontent'] = intval( $this->_revision_is_content( $oTitle ) );
			# is deleted
			$vals['isdeleted'] = $deleted;
			# links
			$links = $this->_make_links( $content );
			$vals['imagelinks'] = $links['image'];
			$vals['video'] = $links['video'];
		} else {
			$oTitle = Title::makeTitle( $oRow->page_namespace, $oRow->page_title );
			# revision id
			$vals['revid'] = intval($oRow->rev_id);
			# username
			$vals['username'] = $oRow->rev_user_text;
			# user id
			$vals['userid'] = intval($oRow->rev_user);
			# user ip
			$vals['user_ip'] = ( IP::isIPAddress($vals['username']) ) ? $vals['username'] : $this->_get_user_ip($vals['userid']);
			# user is bot
			$vals['userisbot'] = intval( $this->_user_is_bot( $vals['username'] ) );
			# is new
			$vals['isnew'] = 0;
			# timestamp
			$vals['timestamp'] = wfTimestamp( TS_DB, $oRow->rev_timestamp );
			# size
			$vals['size'] = intval( $oRow->rev_len );
			# words
			$vals['words'] = 0;
			# revision is redirect
			$vals['isredirect'] = 0;
			# revision is content
			$vals['iscontent'] = intval( $this->_revision_is_content( $oTitle ) );
			# is deleted
			$vals['isdeleted'] = $deleted;
			# links
			$vals['imagelinks'] = 0;
			$vals['video'] = 0;
		}

		wfProfileOut( __METHOD__ );
		return $vals;
	}

	public function getAllowedParams() {
		return array (
			'pageid' => array (
				ApiBase :: PARAM_TYPE => 'integer',
				ApiBase :: PARAM_ISMULTI => false
			),
			'revid' => array (
				ApiBase :: PARAM_TYPE => 'integer',
				ApiBase :: PARAM_ISMULTI => false
			),
			'logid' => array (
				ApiBase :: PARAM_TYPE => 'integer',
				ApiBase :: PARAM_ISMULTI => false
			)
		);
	}

	public function getParamDescription() {
		return array (
			'pageid' 	=> 'Identifier of page',
			'revid' 	=> 'Identifier of revision',
			'logid' 	=> 'Identifier of logs (from logging)'
		);
	}

	public function getDescription() {
		return array (
			'Get informations needed to fill events table.'
		);
	}

	protected function getExamples() {
		return array (
			'Get first 5 revisions of the "Main Page" that were not made made by anonymous user "127.0.0.1"',
			'  api.php?action=query&prop=wkevinfo&titles=Main%20Page&rvlimit=5&rvprop=timestamp|user|comment&rvexcludeuser=127.0.0.1',
			'Get first 5 revisions of the "Main Page" that were made by the user "MediaWiki default"',
			'  api.php?action=query&prop=wkevinfo&titles=Main%20Page&rvlimit=5&rvprop=timestamp|user|comment&rvuser=MediaWiki%20default',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id: WikiaApiQueryEventsData.php 48642 2010-06-09 16:21:38Z moli $';
	}
};
