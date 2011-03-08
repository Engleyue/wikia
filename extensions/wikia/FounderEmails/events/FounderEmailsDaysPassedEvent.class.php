<?php

class FounderEmailsDaysPassedEvent extends FounderEmailsEvent {
	public function __construct( Array $data = array() ) {
		parent::__construct( 'daysPassed' );
		$this->setData( $data );
	}

	public function process( Array $events ) {
		global $wgExternalSharedDB, $wgEnableAnswers;
		wfProfileIn( __METHOD__ );

		$founderEmails = FounderEmails::getInstance();
		foreach ( $events as $event ) {
			$wikiId = $event['wikiId'];
			$activateTime = $event['data']['activateTime'];
			$activateDays = $event['data']['activateDays'];

			if ( time() >= $activateTime ) {

				$emailParams = array(
					'$FOUNDERNAME' => $event['data']['founderUsername'],
					'$FOUNDERPAGEEDITURL' => $event['data']['founderUserpageEditUrl'],
					'$WIKINAME' => $event['data']['wikiName'],
					'$WIKIURL' => $event['data']['wikiUrl'],
					'$WIKIMAINPAGEURL' => $event['data']['wikiMainpageUrl'],
					'$UNSUBSCRIBEURL' => $event['data']['unsubscribeUrl']
				);

				$wikiType = !empty( $wgEnableAnswers ) ? '-answers' : '';
				$langCode = $founderEmails->getWikiFounder( $wikiId )->getOption( 'language' );
				// force loading messages for given languege, to make maintenance script works properly
				wfLoadExtensionMessages( 'FounderEmails', $langCode );

				$mailSubject = $this->getLocalizedMsgBody( 'founderemails' . $wikiType . '-email-' . $activateDays . '-days-passed-subject', $langCode, array() );
				$mailBody = $this->getLocalizedMsgBody( 'founderemails' . $wikiType . '-email-' . $activateDays . '-days-passed-body', $langCode, $emailParams );
				$mailBodyHTML = $this->getLocalizedMsgBody( 'founderemails' . $wikiType . '-email-' . $activateDays . '-days-passed-body-HTML', $langCode, $emailParams );

				$founderEmails->notifyFounder( $mailSubject, $mailBody, $mailBodyHTML, $wikiId );

				$dbw = wfGetDB( DB_MASTER, array(), $wgExternalSharedDB );
				$dbw->delete( 'founder_emails_event', array( 'feev_id' => $event['id'] ) );
			}
		}

		// always return false to prevent deleting from FounderEmails::processEvent
		wfProfileOut( __METHOD__ );
		return false;
	}

	public static function register( $wikiParams, $debugMode = false ) {
		global $wgFounderEmailsExtensionConfig, $wgCityId;
		wfProfileIn( __METHOD__ );

		$founderEmails = FounderEmails::getInstance();
		$wikiFounder = $founderEmails->getWikiFounder();
		$mainpageTitle = Title::newFromText( wfMsgForContent( 'Mainpage' ) );

		// set FounderEmails notifications enabled by default for wiki founder
		$wikiFounder->setOption( 'founderemailsenabled', true );
		$wikiFounder->saveSettings();

		foreach ( $wgFounderEmailsExtensionConfig['events']['daysPassed']['days'] as $daysToActivate ) {
			switch( $daysToActivate ) {
				case 0:
					$ctcUnsubscribe = 'FE03';
					break;
				case 3:
					$ctcUnsubscribe = 'FE08';
					break;
				case 10:
					$ctcUnsubscribe = 'FE09';
					break;
				default:
					$ctcUnsubscribe = 'FE03';
			}
			// Build unsubscribe url
			$hash_url = Wikia::buildUserSecretKey( $wikiFounder->getName(), 'sha256' );
			$unsubscribe_url = Title::newFromText('Unsubscribe', NS_SPECIAL)->getFullURL( array( 'key' => $hash_url, 'ctc' => $ctcUnsubscribe ) );

			$eventData = array(
				'activateDays' => $daysToActivate,
				'activateTime' => time() + ( 86400 * $daysToActivate ),
				'wikiName' => $wikiParams['title'],
				'wikiUrl' => $wikiParams['url'],
				'wikiMainpageUrl' => $mainpageTitle->getFullUrl(),
				'founderUsername' => $wikiFounder->getName(),
				'founderUserpageEditUrl' => $wikiFounder->getUserPage()->getFullUrl( 'action=edit' ),
				'unsubscribeUrl' => $unsubscribe_url
			);

			if ( $debugMode ) {
				$eventData['activateTime'] = 0;
			}

			$founderEmails->registerEvent( new FounderEmailsDaysPassedEvent( $eventData ), false );
		}

		wfProfileOut( __METHOD__ );
		return true;
	}
}
