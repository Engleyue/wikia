<?php
/**
 * CommunityMessages
 *
 * A CommunityMessages extension for MediaWiki
 * Helper extension for Community Messages
 *
 * @author Maciej Błaszkowski (Marooned) <marooned at wikia-inc.com>
 * @date 2010-07-30
 * @copyright Copyright (C) 2010 Maciej Błaszkowski, Wikia Inc.
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @package MediaWiki
 *
 * To activate this functionality, place this file in your extensions/
 * subdirectory, and add the following line to LocalSettings.php:
 *     require_once("$IP/extensions/wikia/CommunityMessages/CommunityMessages_setup.php");
 */

class CommunityMessages {
	/**
	 * hook handler
	 * check conditions and display message
	 *
	 * @author Maciej Błaszkowski <marooned at wikia-inc.com>
	 */
	static function onSkinTemplatePageBeforeUserMsg(&$msg) {
		global $wgUser, $wgMemc, $wgCityId, $wgCookiePrefix;

		//get timestamp of message
		$communityMessagesTimestamp = $wgMemc->get(wfMemcKey('CommunityMessagesTimestamp'));

		if (!$communityMessagesTimestamp) {
//TODO: rename 'community-corner' to 'community-messages' here AND in /extensions/wikia/MyHome/templates/communityCorner.tmpl.php
			$msgTitle = Title::newFromText('community-corner', NS_MEDIAWIKI);
			if ($msgTitle) {
				$msgRev = Revision::newFromTitle($msgTitle);
				if ($msgRev) {
					$communityMessagesTimestamp = wfTimestamp(TS_UNIX, $msgRev->getTimestamp());
					$wgMemc->set(wfMemcKey('CommunityMessagesTimestamp'), $communityMessagesTimestamp, 86400 /*24h*/);
				}
			}
		}

		if (!$communityMessagesTimestamp) {
			//no message?
			return true;
		}

		if ($communityMessagesTimestamp < (time() - 86400 /*24h*/)) {
			//message older than 24h - do not inform user about it
			return true;
		}

		if ($wgUser->isLoggedIn()) {
			$userTimestamp = self::getUserTimestamp($wgUser);

			if ($userTimestamp !== false && $userTimestamp >= $communityMessagesTimestamp) {
				//old, seen message
				return true;
			}
		} else {
			//anon
			//compare timestamp from cookie
			if (isset($_COOKIE[$wgCookiePrefix . 'CommunityMessages']) && $_COOKIE[$wgCookiePrefix . 'CommunityMessages'] >= $communityMessagesTimestamp) {
				//old, seen message
				return true;
			}
		}

		wfLoadExtensionMessages('CommunityMessages');

		if ($msg != '') {
			$msg .= '<br/>';
		}
		$msg .= wfMsgExt('communitymessages-notice-msg', array('parseinline'));

		return true;
	}

	/**
	 * hook handler
	 * update timestamp of newest message
	 *
	 * @author Maciej Błaszkowski <marooned at wikia-inc.com>
	 */
	static function onArticleSaveComplete(&$article, &$user, $text, $summary, &$minoredit, $watchthis, $sectionanchor, &$flags, $revision, &$status, $baseRevId) {
		global $wgMemc;
		$title = $article->getTitle();
//TODO: rename 'community-corner' to 'community-messages' here AND in /extensions/wikia/MyHome/templates/communityCorner.tmpl.php
		if ($title->getNamespace() == NS_MEDIAWIKI && strtolower($title->getText()) == 'community-corner') {
			$revision = Revision::newFromTitle($title);
			if ($revision) {
				$wgMemc->set(wfMemcKey('CommunityMessagesTimestamp'), wfTimestamp(TS_UNIX, $revision->getTimestamp()), 86400 /*24h*/);
			}
		}

		return true;
	}

	/**
	 * hook handler
	 * update user's timestamp of seen message
	 *
	 * @author Maciej Błaszkowski <marooned at wikia-inc.com>
	 */
	static function onBeforePageDisplay(&$output, &$skin) {
		global $wgTitle, $wgUser, $wgMemc, $wgCityId, $wgExternalDatawareDB;

		if ($wgTitle->isSpecial('ActivityFeed') || $wgTitle->isSpecial('MyHome')) {
			$communityMessagesTimestamp = $wgMemc->get(wfMemcKey('CommunityMessagesTimestamp'));
			if (!$communityMessagesTimestamp) {
				//do not waste time on getting timestamp from 'community-messages' - `now` will be enough
				$communityMessagesTimestamp = time();
			}

			if ($wgUser->isLoggedIn()) {
				$userTimestamp = self::getUserTimestamp($wgUser);
				//we have newer message - update user's timestamp
				if ($userTimestamp === false || $communityMessagesTimestamp > $userTimestamp) {
					$dbw = wfGetDB(DB_MASTER, array(), $wgExternalDatawareDB);
					$dbw->replace('user_messages', null /*not used*/,
						array(
							'city_id' => $wgCityId,
							'user_id' => $wgUser->getID(),
							'timestamp' => wfTimestamp(TS_DB, $communityMessagesTimestamp)
						),
						__METHOD__
					);
				}
			} else {
				//anon
				WebResponse::setcookie('CommunityMessages', $communityMessagesTimestamp, time() + 86400 /*24h*/);
			}
		}

		return true;
	}

	/**
	 * helper function
	 *
	 * @author Maciej Błaszkowski <marooned at wikia-inc.com>
	 */
	private static function getUserTimestamp($user) {
		global $wgCityId, $wgExternalDatawareDB;
		$dbr = wfGetDB(DB_SLAVE, array(), $wgExternalDatawareDB);
		$userTimestamp = $dbr->selectField(
			'user_messages',
			'timestamp',
			array('city_id' => $wgCityId, 'user_id' => $user->getID()),
			__METHOD__
		);
		return $userTimestamp ? wfTimestamp(TS_UNIX, $userTimestamp) : false;
	}
}