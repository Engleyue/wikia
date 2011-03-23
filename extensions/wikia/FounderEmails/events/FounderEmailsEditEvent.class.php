<?php

class FounderEmailsEditEvent extends FounderEmailsEvent {
	public function __construct( Array $data = array() ) {
		parent::__construct( 'edit' );
		$this->setData( $data );
	}

	public function process( Array $events ) {
		global $wgCityId, $wgEnableAnswers, $wgSitename;
		wfProfileIn( __METHOD__ );

		if ( $this->isThresholdMet( count( $events ) ) ) {
			// get just one event when we have more... for now, just randomly
			$eventData = $events[ rand( 0, count( $events ) -1 ) ];

			$founderEmails = FounderEmails::getInstance();
			$emailParams = array(
				'$FOUNDERNAME' => $founderEmails->getWikiFounder()->getName(),
				'$USERNAME' => $eventData['data']['editorName'],
				'$USERPAGEURL' => $eventData['data']['editorPageUrl'],
				'$USERTALKPAGEURL' => $eventData['data']['editorTalkPageUrl'],
				'$UNSUBSCRIBEURL' => $eventData['data']['unsubscribeUrl'],
				'$MYHOMEURL' => $eventData['data']['myHomeUrl'],
				'$WIKINAME' => $wgSitename,
			);

			$msgKeys = array();
			$today = date( 'Y-m-d' );
			$wikiType = !empty( $wgEnableAnswers ) ? '-answers' : '';

			$oFounder = $founderEmails->getWikiFounder();

			// BugID: 1961 Quit if the founder email is not confirmed
			if ( !$oFounder->isEmailConfirmed() ) {
				return true;
			}

			$aAllCounter = unserialize( $oFounder->getOption( 'founderemails-counter' ) );
			if ( empty( $aAllCounter ) ) {
				$aAllCounter = array();
			}

			$aWikiCounter = empty( $aAllCounter[$wgCityId] ) ? array() : $aAllCounter[$wgCityId];

			// quit if the Founder has recieved enough emails today			
			if ( !empty( $aWikiCounter[0] ) && $aWikiCounter[0] == $today && $aWikiCounter[1] === 'full' ) {
				return true;
			}

			// initialize or reset counter for today
			if ( empty( $aWikiCounter[0] ) || $aWikiCounter[0] !== $today ) {
				$aWikiCounter[0] = $today;
				$aWikiCounter[1] = 0;
			}
			
			$mailCategory = FounderEmailsEvent::CATEGORY_DEFAULT;
			// @FIXME magic number, move to config
			if ( $aWikiCounter[1] === 9 ) {
				$msgKeys['subject'] = 'founderemails-lot-happening-subject';
				$msgKeys['body'] = 'founderemails-lot-happening-body';
				$msgKeys['body-html'] = 'founderemails-lot-happening-body-HTML';
				$mailCategory = FounderEmailsEvent::CATEGORY_EDIT_HIGH_ACTIVITY;
			} elseif ( $eventData['data']['registeredUserFirstEdit'] ) {
				$msgKeys['subject'] = 'founderemails' . $wikiType . '-email-page-edited-reg-user-first-edit-subject';
				$msgKeys['body'] = 'founderemails' . $wikiType . '-email-page-edited-reg-user-first-edit-body';
				$msgKeys['body-html'] = 'founderemails' . $wikiType . '-email-page-edited-reg-user-first-edit-body-HTML';
				$mailCategory = FounderEmailsEvent::CATEGORY_FIRST_EDIT_USER;
			} elseif ( $eventData['data']['registeredUser'] ) {
				$msgKeys['subject'] = 'founderemails' . $wikiType . '-email-page-edited-reg-user-subject';
				$msgKeys['body'] = 'founderemails' . $wikiType . '-email-page-edited-reg-user-body';
				$msgKeys['body-html'] = 'founderemails' . $wikiType . '-email-page-edited-reg-user-body-HTML';
				$mailCategory = FounderEmailsEvent::CATEGORY_EDIT_USER;
			} else {
				$msgKeys['subject'] = 'founderemails' . $wikiType . '-email-page-edited-anon-subject';
				$msgKeys['body'] = 'founderemails' . $wikiType . '-email-page-edited-anon-body';
				$msgKeys['body-html'] = 'founderemails' . $wikiType . '-email-page-edited-anon-body-HTML';
				$mailCategory = FounderEmailsEvent::CATEGORY_EDIT_ANON;
			}

			$aWikiCounter[1] = ( $aWikiCounter[1] === 9 ) ? 'full' : $aWikiCounter[1] + 1;
			$aAllCounter[$wgCityId] = $aWikiCounter;

			$oFounder->setOption( 'founderemails-counter', serialize( $aAllCounter ) );
			$oFounder->saveSettings();

			$langCode = $oFounder->getOption( 'language' );
			$mailCategory .= (!empty($langCode) && $langCode == 'en' ? 'EN' : 'INT');

			$mailSubject = $this->getLocalizedMsgBody( $msgKeys['subject'], $langCode, array() );
			$mailBody = $this->getLocalizedMsgBody( $msgKeys['body'], $langCode, $emailParams );
			$mailBodyHTML = $this->getLocalizedMsgBody( $msgKeys['body-html'], $langCode, $emailParams );

			wfProfileOut( __METHOD__ );
			return $founderEmails->notifyFounder( $mailSubject, $mailBody, $mailBodyHTML, $mailCategory );
			
		}

		wfProfileOut( __METHOD__ );
		return false;
	}

	public static function register( $oRecentChange ) {
		global $wgUser, $wgCityId;
		wfProfileIn( __METHOD__ );

		if ( FounderEmails::getInstance()->getLastEventType() == 'register' ) {
			// special case: creating userpage after user registration, ignore event
			wfProfileOut( __METHOD__ );
			return true;
		}

		$isRegisteredUser = false;
		$isRegisteredUserFirstEdit = false;
		$ctcUserpage = 'FE02';
		$ctcUnsubscribe = 'FE05';

		if ( $oRecentChange->getAttribute( 'rc_user' ) ) {
			$editor = ( $wgUser->getId() == $oRecentChange->getAttribute( 'rc_user' ) ) ? $wgUser : User::newFromID( $oRecentChange->getAttribute( 'rc_user' ) );
			$isRegisteredUser = true;

			if ( class_exists( 'Masthead' ) ) {
				$userStats = Masthead::getUserStatsData( $editor->getName(), true );
				if ( $userStats['editCount'] == 1 ) {
					$isRegisteredUserFirstEdit = true;
					$ctcUserpage = 'FE06';
					$ctcUnsubscribe = 'FE07';
				}
			}
		} else {
			// Anon user
			$editor = ( $wgUser->getName() == $oRecentChange->getAttribute( 'rc_user_text' ) ) ? $wgUser : User::newFromName( $oRecentChange->getAttribute( 'rc_user_text' ), false );
		}

		$config = FounderEmailsEvent::getConfig( 'edit' );
		if ( ( $editor->getId() == FounderEmails::getInstance()->getWikiFounder()->getId() ) || in_array( 'staff', $editor->getGroups() ) || in_array( $editor->getId(), $config['skipUsers'] ) ) {
			// page edited by founder, staff member or excluded user, skipping..
			wfProfileOut( __METHOD__ );
			return true;
		}

		if ( $editor->isAllowed( 'bot' ) ) {
			// skip bots
			wfProfileOut( __METHOD__ );
			return true;
		}

		// Build unsubscribe url
		$wikiFounder = FounderEmails::getInstance()->getWikiFounder();
		$hash_url = Wikia::buildUserSecretKey( $wikiFounder->getName(), 'sha256' );
		$unsubscribe_url = Title::newFromText('Unsubscribe', NS_SPECIAL)->getFullURL( array( 'key' => $hash_url, 'ctc' => $ctcUnsubscribe ) );

		$oTitle = Title::makeTitle( $oRecentChange->getAttribute( 'rc_namespace' ), $oRecentChange->getAttribute( 'rc_title' ) );
		$eventData = array(
			'titleText' => $oTitle->getText(),
			'titleUrl' => $oTitle->getFullUrl(),
			'editorName' => $editor->getName(),
			'editorPageUrl' => $editor->getUserPage()->getFullUrl( 'ctc=' . $ctcUserpage ),
			'editorTalkPageUrl' => $editor->getTalkPage()->getFullUrl( 'ctc=' . $ctcUserpage ),
			'registeredUser' => $isRegisteredUser,
			'registeredUserFirstEdit' => $isRegisteredUserFirstEdit,
			'unsubscribeUrl' => $unsubscribe_url,
			'myHomeUrl' => Title::newFromText( 'WikiActivity', NS_SPECIAL )->getFullUrl( 'ctc=FE20' )
		);

		FounderEmails::getInstance()->registerEvent( new FounderEmailsEditEvent( $eventData ) );

		wfProfileOut( __METHOD__ );
		return true;
	}
}
