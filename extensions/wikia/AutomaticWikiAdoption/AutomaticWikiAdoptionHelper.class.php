<?php
/**
 * AutomaticWikiAdoptionHelper
 *
 * An AutomaticWikiAdoption extension for MediaWiki
 * Helper class
 *
 * @author Maciej Błaszkowski (Marooned) <marooned at wikia-inc.com>
 * @date 2010-10-05
 * @copyright Copyright (C) 2010 Maciej Błaszkowski, Wikia Inc.
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @package MediaWiki
 *
 * To activate this functionality, place this file in your extensions/
 * subdirectory, and add the following line to LocalSettings.php:
 *     require_once("$IP/extensions/wikia/AutomaticWikiAdoption/AutomaticWikiAdoption_setup.php");
 */

class AutomaticWikiAdoptionHelper {

	//1000 articles - maximum amount of articles for wiki to allow adoption
	const MAX_ARTICLE_COUNT = 1000;
	//10 edits - minimum edits for user to allow adoption
	const MIN_EDIT_COUNT = 10;
	//60 days - delay between consecutive adoption (const can't have operators): (24 * 60 * 60) * 60
	const ADOPTION_DELAY = 5184000;
	//used for memcache as true/false/null causes problems
	const USER_ALLOWED = 1;
	const USER_NOT_ALLOWED = 2;
	const REASON_WIKI_NOT_ADOPTABLE = 3;
	const REASON_INVALID_ADOPTION_GROUPS = 4;
	const REASON_USER_BLOCKED = 5;
	const REASON_ADOPTED_RECENTLY = 6;
	const REASON_NOT_ENOUGH_EDITS = 7;
	//used as type in user_flags table
	const USER_FLAGS_AUTOMATIC_WIKI_ADOPTION = 1;
	

	// static variable to store preferences setting between UserSaveOptions and UserSaveOptions2 hooks
	static $saveOption;
	/**
	 * check if user is allowed to adopt particular wiki
	 *
	 * @return 1(AutomaticWikiAdoptionHandler::USER_ALLOWED) if user can adopt, non-1 if not
	 * @author Maciej Błaszkowski <marooned at wikia-inc.com>
	 * @author Hyun Lim
	 */
	static function isAllowedToAdopt($wikiId, $user) {
		global $wgMemc;
		wfProfileIn(__METHOD__);

		//uncachable tests
		//DB in read only mode
		if (wfReadOnly()) {
			wfProfileOut(__METHOD__);
			Wikia::log(__METHOD__, __LINE__, 'not allowed to adopt: DB read only');
			return false;
		}

		//only logged in can be a sysop
		if ($user->isAnon()) {
			wfProfileOut(__METHOD__);
			Wikia::log(__METHOD__, __LINE__, 'not allowed to adopt: anon');
			return false;
		}

		//cachable tests
		$memcKey = wfMemcKey($user->getId(), 'AutomaticWikiAdoption-user-allowed-to-adopt');
		$allowed = $wgMemc->get($memcKey);
		if (!empty($allowed)) {
			wfProfileOut(__METHOD__);
			Wikia::log(__METHOD__, __LINE__, 'value stored in memcache: ' . $allowed);
			return $allowed == self::USER_ALLOWED;
		}
		unset($allowed);

		//wiki has more than 1000 pages && admin not active
		if (!self::isWikiAdoptable($wikiId)) {
			Wikia::log(__METHOD__, __LINE__, 'not allowed to adopt: wiki not adoptable');
			$allowed = self::REASON_WIKI_NOT_ADOPTABLE;
		}

		$groups = $user->getEffectiveGroups();
		//staff - omit other checks
		if (!isset($allowed) && in_array('staff', $groups)) {
			Wikia::log(__METHOD__, __LINE__, 'allowed to adopt: staff');
			$allowed = self::USER_ALLOWED;
		}

		//already a sysop or bot
		if (!isset($allowed) && (in_array('bot', $groups) || in_array('sysop', $groups))) {
			Wikia::log(__METHOD__, __LINE__, 'not allowed to adopt: bot/sysop');
			$allowed = self::REASON_INVALID_ADOPTION_GROUPS;
		}

		//Phalanx - check if user is blocked
		if (!isset($allowed) && $user->isBlocked()) {
			Wikia::log(__METHOD__, __LINE__, 'not allowed to adopt: user blocked');
			$allowed = self::REASON_USER_BLOCKED;
		}

		//user has adopted other wiki in the last 60 days
		$lastAdoption = $user->getOption('LastAdoptionDate', false);
		if (!isset($allowed) && ($lastAdoption !== false && time() - $lastAdoption < self::ADOPTION_DELAY)) {
			Wikia::log(__METHOD__, __LINE__, 'not allowed to adopt: adopted in 60 days');
			$allowed = self::REASON_ADOPTED_RECENTLY;
		}

		//user has less than 10 edits on this wiki
		if (!isset($allowed) && self::countUserEditsOnWiki($wikiId, $user) <= self::MIN_EDIT_COUNT) {
			Wikia::log(__METHOD__, __LINE__, 'not allowed to adopt: small contribution');
			$allowed = self::REASON_NOT_ENOUGH_EDITS;
		}

		//run out of checks - allowing user to adopt
		if (!isset($allowed)) {
			Wikia::log(__METHOD__, __LINE__, 'allowed to adopt: no negative checks');
			$allowed = self::USER_ALLOWED;
		}

		Wikia::log(__METHOD__, __LINE__, 'saving to memcache:' . $allowed);
		$wgMemc->set($memcKey, $allowed, 3600);

		wfProfileOut(__METHOD__);
		return $allowed;
	}
	
	/**
	 * adopt a wiki - set admin rights for passed user and remove bureacrat rights for current users
	 *
	 * @return boolean success/fail
	 * @author Maciej Błaszkowski <marooned at wikia-inc.com>
	 */
	static function adoptWiki($user) {
		wfProfileIn(__METHOD__);

		$dbr = wfGetDB(DB_SLAVE);
		//get all current admins of this wiki
		$res = $dbr->select(
			array('user', 'user_groups'),
			'user_id',
			array('user_id = ug_user', 'ug_group' => 'sysop'),
			__METHOD__
		);

		//group to remove
		$removedGroup = 'bureaucrat';
		//group to add
		$addGroup = 'sysop';

		//remove bureacrat for current admins
		while ($row = $dbr->fetchObject($res)) {
			$admin = User::newFromId($row->user_id);
			if ($admin) {
				//get old groups - for log purpose
				$oldGroups = $admin->getGroups();
				//do not remove groups for staff or for user who is now adopting (he might be a bureaucrat)
				if (in_array('staff', $oldGroups) || $admin->getId() == $user->getId()) {
					continue;
				}
				//create new groups list - for log purpose
				$newGroups = array_diff($oldGroups, array($removedGroup));
				if ($oldGroups != $newGroups) {
					//remove group
					$admin->removeGroup($removedGroup);
					//sent e-mail
					$admin->sendMail(
						wfMsgForContent("automaticwikiadoption-mail-adoption-subject"),
						wfMsgForContent("automaticwikiadoption-mail-adoption-content"),
						null, //from
						null, //replyto
						'AutomaticWikiAdoption',
						wfMsgForContent("automaticwikiadoption-mail-adoption-content-HTML")
					);
					//log
					self::addLogEntry($admin, $oldGroups, $newGroups);
				}
			}
		}

		//get old groups - for log purpose
		$oldGroups = $user->getGroups();
		//create new groups list - for log purpose
		$newGroups = array_unique(array_merge($oldGroups, array($addGroup)));
		if ($oldGroups != $newGroups) {
			//add new admin - user who just adopted this wiki
			$user->addGroup($addGroup);
			//log
			self::addLogEntry($user, $oldGroups, $newGroups);
		}
		//set date of adoption - this will be used to check when next adoption is possible
		$user->setOption('LastAdoptionDate', time());
		$user->saveSettings();

		//TODO: log on central that wiki has been adopted (by who)

		wfProfileOut(__METHOD__);
		//TODO: is there a way to check if user got sysop rights to return true/false as a result?
		return true;
	}

	/**
	 * check if provided wiki is adoptable - uses data gathered by maintenance script in the background
	 *
	 * @return boolean success/fail
	 * @author Maciej Błaszkowski <marooned at wikia-inc.com>
	 */
	private static function isWikiAdoptable($wikiId) {
		global $wgExternalSharedDB;
		wfProfileIn(__METHOD__);

		$canAdopt = false;

		$dbr = wfGetDB(DB_SLAVE, array(), $wgExternalSharedDB);
		$row = $dbr->selectRow(
			'city_list',
			'city_flags',
			array('city_id' => $wikiId),
			__METHOD__
		);

		//this flag is set by maintenance script
		if ($row !== false && $row->city_flags & WikiFactory::FLAG_ADOPTABLE) {
			$canAdopt = true;
		}

		wfProfileOut(__METHOD__);
		return $canAdopt;
	}

	/**
	 * check if provided wiki is adoptable - uses data gathered by maintenance script in the background
	 *
	 * @return boolean success/fail
	 * @author Maciej Błaszkowski <marooned at wikia-inc.com>
	 */
	private static function countUserEditsOnWiki($wikiId, $user) {
		global $wgStatsDB;
		wfProfileIn(__METHOD__);

		$result = 0;
		$dbr = wfGetDB(DB_SLAVE, array(), $wgStatsDB);

		$row = $dbr->selectRow(
			'specials.events_local_users',
			'edits',
			array('wiki_id' => $wikiId, 'user_id' => $user->getId()),
			__METHOD__
		);

		if ($row !== false) {
			$result = $row->edits;
		}

		wfProfileOut(__METHOD__);
		return $result;
	}

	/**
	 * Add a rights log entry for an action.
	 * Partially copied from SpecialUserrights.php
	 *
	 * @param object $user User object
	 * @param boolean $addGroups adding or removing groups?
	 * @param array $groups names of groups
	 * @author Maciej Błaszkowski <marooned at wikia-inc.com>
	 */
	private static function addLogEntry($user, $oldGroups, $newGroups) {
		global $wgRequest;

		wfProfileIn(__METHOD__);
		$log = new LogPage('rights');

		$log->addEntry('rights',
			$user->getUserPage(),
			wfMsg('automaticwikiadoption-log-reason'),
			array(
				implode(', ', $oldGroups),
				implode(', ', $newGroups)
			)
		);
		wfProfileOut(__METHOD__);
	}

	/**
	 * Hook handler
	 * Display notification
	 *
	 * @author Maciej Błaszkowski <marooned at wikia-inc.com>
	 */
	static function onSkinTemplateOutputPageBeforeExec(&$skin, &$tpl) {
		global $wgUser, $wgCityId, $wgScript;
		wfProfileIn(__METHOD__);

		if (Wikia::isOasis() && !self::getDismissNotificationState($wgUser) && self::isAllowedToAdopt($wgCityId, $wgUser) == self::USER_ALLOWED) {

			NotificationsModule::addNotification('custom notifiation', array(
				'name' => 'AutomaticWikiAdoption',
				'dismissUrl' => $wgScript . '?action=ajax&rs=AutomaticWikiAdoptionAjax&method=dismiss',
			), NotificationsModule::NOTIFICATION_CUSTOM);
		}

		wfProfileOut(__METHOD__);
		return true;
	}

	/**
	 * Hook handler
	 *
	 * @author Maciej Błaszkowski <marooned at wikia-inc.com>
	 * @author Hyun Lim
	 */
	public static function onGetPreferences($user, &$defaultPreferences) {
		wfProfileIn(__METHOD__);
		global $wgSitename;
		
		//for admins only 
		if (in_array('sysop', $user->getGroups())) {
			// will need to move this out of adoption extension when this becomes generalized
			$defaultPreferences['adoptionmails-label'] = array(
				'type' => 'info',
				'label' => '',
				'help' => wfMsg('automaticwikiadoption-pref-label', $wgSitename),
				'section' => 'personal/wikiemail',
			);
			$defaultPreferences['adoptionmails'] = array(
				'type' => 'toggle',
				'label-message' => array('tog-adoptionmails', $wgSitename),
				'section' => 'personal/wikiemail',
			);
			AutomaticWikiAdoptionHelper::UserLoadOptions($user, $user->mOptions);
		}

		wfProfileOut(__METHOD__);
		return true;
	}

	// force load adoptionmail from local database
	public static function UserLoadOptions($user, &$options) {
		wfProfileIn(__METHOD__);

		if (in_array('sysop', $user->getGroups())) {
			$dbr = wfGetDB( DB_SLAVE );
			$res = $dbr->select(
				'`user_properties`',
				'*',
				array( 'up_user' => $user->getId(), 'up_property' =>'adoptionmails' ),
				__METHOD__
			);
			while( $row = $dbr->fetchObject( $res ) ) {
				$options[$row->up_property] = $row->up_value;
			}
		}
		wfProfileOut(__METHOD__);
		return true;
	}

	// remove adoptionmails from global settings so it is not saved in normal Shared DB
	public static function UserSaveOptions($user, &$options) {
		if (in_array('sysop', $user->getGroups())) {
			self::$saveOption = $options['adoptionmails'];
			unset($options['adoptionmails']);
		}
		return true;
	}

	// save option in local wiki database
	public static function UserSaveOptions2($user, &$options) {
		wfProfileIn(__METHOD__);

		if (in_array('sysop', $user->getGroups()) && isset(self::$saveOption)) {
			$dbw = wfGetDB( DB_MASTER );

			$insert_rows[] = array(
					'up_user' => $user->getId(),
					'up_property' => 'adoptionmails',
					'up_value' => self::$saveOption,
				);
			$dbw->begin();
			$dbw->delete( '`user_properties`', array( 'up_user' => $user->getId() ), __METHOD__ );
			$dbw->insert( '`user_properties`', $insert_rows, __METHOD__ );
			$dbw->commit();
			// restore option value
			$options['adoptionmails'] = self::$saveOption;
		}
		wfProfileOut(__METHOD__);
		return true;
	}

	/**
	 * Dismisses notification about wiki adoption
	 *
	 * @author Maciej Błaszkowski <marooned at wikia-inc.com>
	 */
	static function dismissNotification() {
		wfProfileIn(__METHOD__);
		global $wgUser, $wgCityId, $wgExternalDatawareDB;

		if (!wfReadOnly()) {
			$dbw = wfGetDB(DB_MASTER, array(), $wgExternalDatawareDB);
			$dbw->insert('user_flags',
				array(
					'city_id' => $wgCityId,
					'user_id' => $wgUser->getID(),
					'type' => self::USER_FLAGS_AUTOMATIC_WIKI_ADOPTION,
					'data' => wfTimestamp(TS_DB)
				),
				__METHOD__,
				'IGNORE'
			);

			// fix for AJAX calls
			$dbw->commit();
		}

		wfProfileOut(__METHOD__);
	}

	/**
	 * Get dismiss state of notification about wiki adoption
	 *
	 * @author Maciej Błaszkowski <marooned at wikia-inc.com>
	 */
	private static function getDismissNotificationState($user) {
		global $wgCityId, $wgExternalDatawareDB;
		$dbr = wfGetDB(DB_SLAVE, array(), $wgExternalDatawareDB);
		$dismissed = $dbr->selectField(
			'user_flags',
			'data',
			array('city_id' => $wgCityId, 'user_id' => $user->getID(), 'type' => self::USER_FLAGS_AUTOMATIC_WIKI_ADOPTION),
			__METHOD__
		);
		return $dismissed ? true : false;
	}
}