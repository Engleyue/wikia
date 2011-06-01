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
	 * Get list of wikis with a particular local preference setting
	 * Since the expected default is 1, we need to look for users with no up_property value set
	 * 
	 * @param $prefPrefix String Which preference setting to search for, MUST be either:
	 *							 founderemails-complete-digest OR founderemails-views-digest
	 */
	
	public function getFoundersWithPreference( $prefPrefix ) {
		wfProfileIn( __METHOD__ );

		$db = wfGetDB(DB_SLAVE, array(), 'wikicities');
		$cityList = array();
		// Since the preference default is true, we need to find all users with NO row for that preference
		$oRes = $db->select (
			"city_list LEFT OUTER JOIN wikicities.user_properties ON city_founding_user = up_user AND (up_property like '$prefPrefix-%')",
			array ('city_id', 'up_property'),
			array ('up_property' => null)
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
	public function notifyFounder( $event, $mailSubject, $mailBody, $mailBodyHTML, $wikiId = 0, $category = 'FounderEmails' ) {
		global $wgPasswordSender;
		$from = new MailAddress( $wgPasswordSender, 'Wikia' );
		if ( $event->enabled( $wikiId ) ) {
			return $this->getWikiFounder( $wikiId )->sendMail( $mailSubject, $mailBody, $from, null, $category, $mailBodyHTML );
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
		if ( $event->enabled( $wgCityId ) ) {
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
		global $wgUser, $wgCityId, $wgSitename;
		wfProfileIn( __METHOD__ );

		if ( FounderEmails::getInstance()->getWikiFounder()->getId() == $wgUser->getId() ) {
			
			// If we are in digest mode, grey out the individual email options
			$disableEmailPrefs = FounderEmails::getInstance()->getWikiFounder()->getOption("founderemails-complete-digest-$wgCityId");
			
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
				'help' => wfMsg('wikiadoption-pref-label', $wgSitename),
				'section' => 'personal/wikiemail',
			);
			$defaultPreferences["founderemails-joins-$wgCityId"] = array(
				'type' => 'toggle',
				'label-message' => array('founderemails-pref-joins', $wgSitename),
				'section' => 'personal/wikiemail',
				'disabled' => $disableEmailPrefs,
			);
			$defaultPreferences["founderemails-edits-$wgCityId"] = array(
				'type' => 'toggle',
				'label-message' => array('founderemails-pref-edits', $wgSitename),
				'section' => 'personal/wikiemail',
				'disabled' => $disableEmailPrefs,
			);
			$defaultPreferences["founderemails-views-digest-$wgCityId"] = array(
				'type' => 'toggle',
				'label-message' => array('founderemails-pref-views-digest', $wgSitename),
				'section' => 'personal/wikiemail',
				'disabled' => $disableEmailPrefs,
			);
			$defaultPreferences["founderemails-complete-digest-$wgCityId"] = array(
				'type' => 'toggle',
				'label-message' => array('founderemails-pref-complete-digest', $wgSitename),
				'section' => 'personal/wikiemail',
			);
		}

		wfProfileOut( __METHOD__ );
		return true;
	}

	/* stats methods */
	
	public function getPageViews ( $cityID ) {
		global $wgStatsDB;
		wfProfileIn( __METHOD__ );
		
		$today = date( 'Ymd' );
		
		$db = wfGetDB(DB_SLAVE, array(), $wgStatsDB);

		$oRow = $db->selectRow( 
			array( 'page_views_wikia' ), 
			array( 'pv_views as cnt' ),
			array( 'pv_city_id' => $cityID, 'pv_use_date' => $today),
			__METHOD__
		);
		// Only returns one row, this is just for convenience
		$views = ( isset( $oRow->cnt ) ) ? $oRow->cnt : 0;
		
		wfProfileOut( __METHOD__ );		
		return $views;
	}

	public function getDailyEdits ($cityID, /*Y-m-d*/ $day = null) {
		global $wgStatsDB;
		wfProfileIn( __METHOD__ );
		
		$today = ( empty( $day ) ) ? date( 'Y-m-d' ) : $day;
		
		$db = wfGetDB(DB_SLAVE, array(), $wgStatsDB);

		$oRow = $db->selectRow( 
			array( 'events' ), 
			array( 'count(0) as cnt' ),
			array(  " rev_timestamp between '$today 00:00:00' and '$today 23:59:59' ", 'wiki_id' => $cityID ),
			__METHOD__
		);

		$edits = isset( $oRow->cnt ) ? $oRow->cnt : 0;
		
		wfProfileOut( __METHOD__ );		
		return $edits;
	}
	
	public function getUserEdits ($cityID, $day = null) {

		wfProfileIn( __METHOD__ );
		
		$userEdits = array();
		$today = ( empty( $day ) ) ? date( 'Ymd' ) : str_replace( "-", "", $day );
		
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
		global $wgStatsDB;
		
		wfProfileIn( __METHOD__ );
		
		$userJoined = array();
		$today = ( empty( $day ) ) ? date( 'Y-m-d' ) : $day;
		
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
}
