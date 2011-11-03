<?php

class FounderEmails {
	static private $instance = null;
	private $mLastEventType = null;

	private function __construct() { }
	private function __clone() { }

	/**
	 * @return FounderEmails
	 */
	static public function getInstance() {
		if ( self::$instance == null ) {
			self::$instance = new FounderEmails;
		}
		return self::$instance;
	}

	/**
	 * get wiki founder
	 * @return User
	 */
	public function getWikiFounder( $wikiId = 0 ) {
		global $wgCityId, $wgFounderEmailsDebugUserId;

		$wikiId = !empty( $wikiId ) ? $wikiId : $wgCityId;

		if ( empty( $wgFounderEmailsDebugUserId ) ) {
			$wikiFounder = User::newFromId( WikiFactory::getWikiById( $wikiId )->city_founding_user );
		}
		else {
			$wikiFounder = User::newFromId( $wgFounderEmailsDebugUserId );
		}

		return $wikiFounder;
	}

	/**
	 * get list of wiki founder/admin/bureaucrat id
	 * Note: also called from maintenance script.
	 * @return array of user_id
	 */
	public function getWikiAdminIds($wikiId = 0, $use_master = false) {
		global $wgCityId, $wgFounderEmailsDebugUserId, $wgEnableAnswers, $wgMemc;
		wfProfileIn( __METHOD__ );

		$user_ids = array();
		if (empty($wgFounderEmailsDebugUserId)) {
			// get founder
			$wikiId = !empty( $wikiId ) ? $wikiId : $wgCityId;
			$wiki = WikiFactory::getWikiById($wikiId);
			if (!empty($wiki) && $wiki->city_public == 1) {
				$user_ids[] = $wiki->city_founding_user;
			
				// get admin and bureaucrat
				if (empty($wgEnableAnswers)) {
					$memKey = self::getMemKeyAdminIds($wikiId);
					$admin_ids = $wgMemc->get($memKey);
					if (is_null($admin_ids)) {
						$dbname = $wiki->city_dbname;
						$db_type = ($use_master) ? DB_MASTER : DB_SLAVE;
						$dbr = wfGetDB($db_type, array(), $dbname);
						$result = $dbr->select(
							'user_groups',
							'distinct ug_user',
							array ("ug_group in ('sysop','bureaucrat')"),
							__METHOD__
						);
					
						$admin_ids = array();
						while ($row = $dbr->fetchObject($result)) {
							$admin_ids[] = $row->ug_user;
						}
						$dbr->freeResult($result);
						$wgMemc->set($memKey, $admin_ids, 60*60*24);
					}
					$user_ids = array_unique(array_merge($user_ids, $admin_ids));
				}
			}
		} else {
			$user_ids[] = $wgFounderEmailsDebugUserId;
		}
		
		wfProfileOut( __METHOD__ );
		return $user_ids;
	}
	
	/**
	 * Get memcache key for list of admin_ids
	 * @param integer $wikiId
	 * @return string memcache key
	 */
	protected static function getMemKeyAdminIds($wikiId) {
		return wfSharedMemcKey('founderemail_admin_ids',$wikiId);
	}
	
	/**
	 * Get list of wikis with a particular local preference setting
	 * Since the expected default is 0, we need to look for users with up_property value set to 1
	 * 
	 * @param $prefPrefix String Which preference setting to search for, MUST be either:
	 *							 founderemails-complete-digest OR founderemails-views-digest
	 */
	
	public function getFoundersWithPreference( $prefPrefix ) {
		wfProfileIn( __METHOD__ );

		$prefixLength = strlen($prefPrefix) + 2;
		$db = wfGetDB(DB_SLAVE, array(), 'wikicities');
		$cityList = array();
		$oRes = $db->select (
			array ('wikicities.user_properties'), 
			array ("distinct substring(up_property, $prefixLength) city_id"), 
			array ( 
					"up_property like '$prefPrefix-%'", 
					'up_value' => 1) 
		);
		while ( $oRow = $db->fetchObject ( $oRes )) {
				$cityList[] = $oRow->city_id; 		
		}
		wfProfileOut( __METHOD__ );		
		return $cityList;
	}	
	

	/**
	 * send notification email to wiki founder
	 */
	public function notifyFounder( $user, $event, $mailSubject, $mailBody, $mailBodyHTML, $wikiId = 0, $category = 'FounderEmails' ) {
		global $wgPasswordSender, $wgNoReplyAddress;
		$from = new MailAddress( $wgPasswordSender, 'Wikia' );
		$replyTo = new MailAddress ( $wgNoReplyAddress );
		if ( $event->enabled( $wikiId, $user ) ) {
			return $user->sendMail( $mailSubject, $mailBody, $from, $replyTo, $category, $mailBodyHTML );
		}
	}

	/**
	 * register new event on wiki
	 * @param FounderEmailsEvent $event
	 * @param bool $doProcess perform event processing when done
	 */
	public function registerEvent( FounderEmailsEvent $event, $doProcess = true ) {
		global $wgCityId;
		wfProfileIn( __METHOD__ );
		// Each event has a different named option now
		if ( $event->enabled_wiki( $wgCityId ) ) {
			$event->create();
			if ( $doProcess ) {
				$this->processEvents( $event->getType(), true, $wgCityId );
			}
			$this->mLastEventType = $event->getType();
		}

		wfProfileOut( __METHOD__ );
	}

	/**
	 * process all registered events of given type
	 * @param string $eventType event type
	 * @param bool $useMasterDb master db flag
	 */
	public function processEvents( $eventType, $useMasterDb = false, $wikiId = null ) {
		global $wgWikicitiesReadOnly, $wgExternalSharedDB;

		wfProfileIn( __METHOD__ );
		$aEventsData = array();
		
		// Digest event types do not have records in the event table so just process them.
		if ($eventType == 'viewsDigest' || $eventType == "completeDigest") {
			$oEvent = FounderEmailsEvent::newFromType( $eventType );
			$result = $oEvent->process( $aEventsData );
		} else {		
			$dbs = wfGetDB( ( $useMasterDb ? DB_MASTER : DB_SLAVE ), array(), $wgExternalSharedDB );
			$whereClause = array( 'feev_type' => $eventType );
			if ( $wikiId != null ) {
				$whereClause['feev_wiki_id'] = $wikiId;
			}
			$res = $dbs->select( 'founder_emails_event', array( '*' ), $whereClause, __METHOD__, array( 'ORDER BY' => 'feev_timestamp' ) );

			while ( $row = $dbs->fetchObject( $res ) ) {
				$aEventsData[] = array( 'id' => $row->feev_id, 'wikiId' => $row->feev_wiki_id, 'timestamp' => $row->feev_timestamp, 'data' => unserialize( $row->feev_data ) );
			}
			if ( count( $aEventsData ) ) {
				$oEvent = FounderEmailsEvent::newFromType( $eventType );
				$result = $oEvent->process( $aEventsData );
				if ( $result && !$wgWikicitiesReadOnly && ( $wikiId != null ) ) {
					// remove processed events for this wiki
					$dbw = wfGetDB( DB_MASTER, array(), $wgExternalSharedDB );
					foreach ( $aEventsData as $event ) {
						$dbw->delete( 'founder_emails_event', array( 'feev_id' => $event['id'] ) );
					}
				}
			}
		}		
		wfProfileOut( __METHOD__ );
	}

	public function getLastEventType() {
		return $this->mLastEventType;
	}

	public static function onGetPreferences($user, &$defaultPreferences) {
		global $wgUser, $wgCityId, $wgSitename, $wgEnableUserPreferencesV2Ext;
		wfProfileIn( __METHOD__ );

		if ( !FounderEmailsEvent::isAnswersWiki() && in_array($wgUser->getId(), FounderEmails::getInstance()->getWikiAdminIds()) ) {
			
			if ( empty($wgEnableUserPreferencesV2Ext) ) {
				$section = 'personal/wikiemail';
				$prefVersion = '';
			} else {
				$section = 'emailv2/wikiemail';
				$prefVersion = '-v2';
			}
			
			// If we are in digest mode, grey out the individual email options
			$disableEmailPrefs = $wgUser->getOption("founderemails-complete-digest-$wgCityId");
			
			/*  This is the old preference, no longer used 
			 *  TODO: Write conversion script from old to new
			$defaultPreferences['founderemailsenabled'] = array(
				'type' => 'toggle',
				'label-message' => 'tog-founderemailsenabled',
				'section' => 'personal/email',
			);
			 */
			$defaultPreferences["adoptionmails-label-$wgCityId"] = array(
				'type' => 'info',
				'label' => '',
				'help' => wfMsg('wikiadoption-pref-label'.$prefVersion, $wgSitename),
				'section' => $section,
			);
			$defaultPreferences["founderemails-joins-$wgCityId"] = array(
				'type' => 'toggle',
				'label-message' => array('founderemails-pref-joins'.$prefVersion, $wgSitename),
				'section' => $section,
				'disabled' => $disableEmailPrefs,
			);
			$defaultPreferences["founderemails-edits-$wgCityId"] = array(
				'type' => 'toggle',
				'label-message' => array('founderemails-pref-edits'.$prefVersion, $wgSitename),
				'section' => $section,
				'disabled' => $disableEmailPrefs,
			);
			$defaultPreferences["founderemails-views-digest-$wgCityId"] = array(
				'type' => 'toggle',
				'label-message' => array('founderemails-pref-views-digest'.$prefVersion, $wgSitename),
				'section' => $section,
				'disabled' => $disableEmailPrefs,
			);
			$defaultPreferences["founderemails-complete-digest-$wgCityId"] = array(
				'type' => 'toggle',
				'label-message' => array('founderemails-pref-complete-digest'.$prefVersion, $wgSitename),
				'section' => $section,
			);
		}

		wfProfileOut( __METHOD__ );
		return true;
	}
	
	/**
	 * Hook - clear cache for list of admin_ids
	 * @param object $user
	 * @param array $addgroup
	 * @param array $removegroup
	 * @return true 
	 */
	public function onUserRightsChange($user, $addgroup, $removegroup) {
		global $wgCityId, $wgMemc;
		wfProfileIn( __METHOD__ );
		
		if (!empty($wgCityId)) {
			if (($addgroup && (in_array('sysop', $addgroup) || in_array('bureaucrat', $addgroup))) 
				|| ($removegroup && (in_array('sysop', $removegroup) || in_array('bureaucrat', $removegroup)))) {
				$memKey  = self::getMemKeyAdminIds($wgCityId);
				$wgMemc->delete($memKey);
				FounderEmails::getInstance()->getWikiAdminIds($wgCityId, true);
			}
		}
		
		wfProfileOut( __METHOD__ );
		return true;
	}

	/* stats methods */
	
	public function getPageViews ( $cityID ) {
		global $wgStatsDB, $wgStatsDBEnabled;
		wfProfileIn( __METHOD__ );
		
		$today = date( 'Ymd', strtotime('-1 day') );

		$views = 0;
		if ( !empty( $wgStatsDBEnabled ) ) {
			$db = wfGetDB(DB_SLAVE, array(), $wgStatsDB);

			$oRow = $db->selectRow( 
				array( 'page_views_wikia' ), 
				array( 'pv_views as cnt' ),
				array( 'pv_city_id' => $cityID, 'pv_use_date' => $today),
				__METHOD__
			);
			// Only returns one row, this is just for convenience
			$views = ( isset( $oRow->cnt ) ) ? $oRow->cnt : 0;
		}
		
		wfProfileOut( __METHOD__ );		
		return $views;
	}

	public function getDailyEdits ($cityID, /*Y-m-d*/ $day = null) {
		global $wgStatsDB, $wgStatsDBEnabled;
		wfProfileIn( __METHOD__ );
		
		$edits = 0;
		if ( !empty( $wgStatsDBEnabled ) ) {
			$today = ( empty( $day ) ) ? date( 'Y-m-d', strtotime('-1 day') ) : $day;
			
			$db = wfGetDB(DB_SLAVE, array(), $wgStatsDB);

			$oRow = $db->selectRow( 
				array( 'events' ), 
				array( 'count(0) as cnt' ),
				array(  " rev_timestamp between '$today 00:00:00' and '$today 23:59:59' ", 'wiki_id' => $cityID ),
				__METHOD__
			);

			$edits = isset( $oRow->cnt ) ? $oRow->cnt : 0;
		}
		
		wfProfileOut( __METHOD__ );		
		return $edits;
	}
	
	public function getUserEdits ($cityID, $day = null) {

		wfProfileIn( __METHOD__ );
		
		$userEdits = array();
		$today = ( empty( $day ) ) ? date( 'Ymd', strtotime('-1 day') ) : str_replace( "-", "", $day );
		
		$dbname = WikiFactory::IDtoDB( $cityID );
		
		if ( empty($dbname) ) {
			wfProfileOut( __METHOD__ );
			return 0;
		}
		
		$db = wfGetDB(DB_SLAVE, 'vslow', $dbname);
		$oRes = $db->select(
			array( 'revision' ), 
			array( 'rev_user', 'min(rev_timestamp) as min_ts' ),
			array( 'rev_user > 0' ),
			__METHOD__,
			array( 'GROUP BY' => 'rev_user', 'HAVING' => "min(rev_timestamp) like '" . $db->escapeLike( $today ) . "%'" )
		);
		
		while ( $oRow = $db->fetchObject ( $oRes ) ) { 
			$userEdits[ $oRow->rev_user ] = $oRow->min_ts;
		} 
		$db->freeResult( $oRes );
		
		wfProfileOut( __METHOD__ );		
		return $userEdits;
	}
	
	public function getJoinedUsers ($cityID, $day = null) {
		global $wgStatsDB, $wgStatsDBEnabled;
		
		wfProfileIn( __METHOD__ );
		
		$userJoined = array();
		if ( !empty( $wgStatsDBEnabled ) ) {
			$today = ( empty( $day ) ) ? date( 'Y-m-d', strtotime('-1 day') ) : $day;
			
			$db = wfGetDB(DB_SLAVE, array(), $wgStatsDB);
			$oRes = $db->select(
				array( 'user_login_history' ), 
				array( 'user_id', 'min(ulh_timestamp) as min_ts' ),
				array( 
					'city_id' => $cityID,
					'user_id > 0' 
				),
				__METHOD__,
				array( 'GROUP BY' => 'user_id', 'HAVING' => "min(ulh_timestamp) like '" . $db->escapeLike( $today ) . "%'" )
			);

			while ( $oRow = $db->fetchObject ( $oRes ) ) { 
				$userJoined[ $oRow->user_id ] = $oRow->min_ts; 
			} 
			$db->freeResult( $oRes );
		}
		
		wfProfileOut( __METHOD__ );		
		return $userJoined;
	}
	
	public function getNewUsers( $cityId, $day = null ) {
		wfProfileIn( __METHOD__ );		
		$result = 0;
		
		$editUsers = $this->getUserEdits( $cityId, $day );
		$addUsers = $this->getJoinedUsers( $cityId, $day );
		
		if ( empty( $editUsers ) ) {
			$result = count( $addUsers );
		} elseif ( empty ( $addUsers ) ) {
			$result = count ( $editUsers );
		} else {
			$result = count( $editUsers + $addUsers );
		}
		
		wfProfileOut( __METHOD__ );	
		
		return $result;		
	}
	
	/**
	 * add link (<a> tag) to the param
	 * @param array $params
	 * @param array $links
	 * @return array 
	 */
	public static function addLink($params, $links, $color='#2C85D5') {
		if (is_array($params) && is_array($links)) {
			foreach($links as $key => $value) {
				if (array_key_exists($key, $params))
					$params[$key] = '<a href="'.$value.'" style="color:'.$color.';">'.$params[$key].'</a>';
			}
		}
		return $params;
	}
	
}
