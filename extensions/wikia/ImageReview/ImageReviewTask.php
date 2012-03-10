<?php

/**
 * TaskManager task to go through a list of images and delete them.
 *
 * Mostly copied from MultiWikiDeleteTask
 *
 * @author tor
 * @date 2012-03-09
 */

class ImageReviewTask extends BatchTask {
        var $mType, $mVisible, $mArguments, $mMode, $mAdmin;
        var $records, $title, $namespace;
        var $mUser, $mUsername;

	const REASON_MSG = 'imagereview-reason';

        /* constructor */
        function __construct( $params = array() ) {
                $this->mType = 'imagereview';
                $this->mVisible = false; // do not show form for this task
                $this->mParams = $params;
                $this->mTTL = 86400; // 24 hours
                $this->records = 1000; // TODO: needed?
                parent::__construct();
        }

	function execute( $params = null ) {
		global $IP, $wgWikiaLocalSettingsPath;
		/*      go with each supplied wiki and delete the supplied article
			load all configs for particular wikis before doing so
			(from wikifactory, not by _obsolete_ maintenance scripts
			and from LocalSettings as worked on fps)
		 */

		$this->mTaskID = $params->task_id;
		$oUser = User::newFromId( $params->task_user_id );

		if ( $oUser instanceof User ) {
			$oUser->load();
			$this->mUser = $oUser->getName();
		} else {
			$this->log("Invalid user - id: " . $params->task_user_id );
			return true;
		}

		$data = unserialize($params->task_arguments);	

		foreach ( $data as $wikiId => $imageId ) {
			$retval = "";

			$dbname = WikiFactory::getWikiByID( $wikiId );
			if ( !$dbname ) continue;

			$title = GlobalTitle::newFromId( $imageId );

			$city_url = WikiFactory::getVarValueByName( "wgServer", $wikiId );
			if ( empty($city_url) ) continue;

			$city_path = WikiFactory::getVarValueByName( "wgScript", $wikiId );

			$city_lang = WikiFactory::getVarValueByName( "wgLanguageCode", $wikiId );
			$reason = wfMsgExt( self::REASON_MSG, array( 'language' => $city_lang ) );

			$sCommand  = "SERVER_ID={$wikiId} php $IP/maintenance/wikia/deleteOn.php ";
			$sCommand .= "-u " . escapeshellarg( $this->mUser ) . " ";
			$sCommand .= "-t " . escapeshellarg( $title->getPrefixedText() ) . " ";
			if ( $reason ) {
				$sCommand .= "-r " . escapeshellarg( $reason ) . " ";
			}
			$sCommand .= "--conf {$wgWikiaLocalSettingsPath}";

			$actual_title = wfShellExec($sCommand, $retval);

			if ($retval) {
				$this->addLog('Article deleting error! (' . $city_url . '). Error code returned: ' .  $retval . ' Error was: ' . $actual_title);
			} else {
				$this->addLog('Removed: <a href="' . $city_url . $city_path . '?title=' . wfEscapeWikiText($actual_title)  . '">' . $city_url . $city_path . '?title=' . $actual_title . '</a>');
			}
		}
	}
}
