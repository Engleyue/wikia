<?php

class AchRankingService {
	private $mRecentAwardedUsers;

	function __construct() {
		$this->mRecentAwardedUsers = null;
	}

	public function getUsersRanking($limit = null, $compareToSnapshot = false) {
		wfProfileIn(__METHOD__);

		global $wgCityId, $wgWikiaBotLikeUsers, $wgExternalSharedDB;
		$ranking = array();
		$rules = array('ORDER BY' => 'score desc');

		if($limit > 0)
			$rules['LIMIT'] = $limit * 2;//bots and blocked users are filtered after the query has been run, let's admit that ratio is 2:1
		
		$dbr = wfGetDB(DB_SLAVE, array(), $wgExternalSharedDB);
		$res = $dbr->select('ach_user_score', 'user_id, score', array('wiki_id' => $wgCityId), __METHOD__, $rules);
		$rankingSnapshot = ($compareToSnapshot) ? $this->loadFromSnapshot() : null;
		$positionCounter = 1;
		$prevScore = -1;
		$prevPosition = -1;
		
		while ( $row = $dbr->fetchObject( $res ) ) {
			$user = User::newFromId($row->user_id);
			
			if ( $user && !$user->isBlocked() && !in_array( $user->getName(), $wgWikiaBotLikeUsers ) ) {
				// If this user has the same score as previous user, give them the same (lower) rank (RT#67874).
				$position = (($prevScore == $row->score) && ($prevPosition != -1))? $prevPosition : $positionCounter;
				
				$ranking[] = new AchRankedUser($user, $row->score, $position, ($rankingSnapshot != null && isset($rankingSnapshot[$user->getId()])) ? $rankingSnapshot[$user->getId()] : null);
				
				$prevPosition = $position;
				$prevScore = $row->score;
				$positionCounter++;
			}
			
			if ( $limit > 0 && $positionCounter == $limit ) break;
		}
		
		$dbr->freeResult($res);

		wfProfileOut(__METHOD__);

		return $ranking;
	}

	public function getUserRank($user_id) {
		global $wgCityId, $wgExternalSharedDB;

		if (! isset($user_id)) return 0;

		// If three people are tied for 3rd place, they all will have a rank of 3 (RT#67874).
		$sql = "select count(*)+1 as rank from ach_user_score where wiki_id = $wgCityId and score > (select score as s from ach_user_score where user_id = $user_id and wiki_id = $wgCityId)";

		$dbr = wfGetDB(DB_SLAVE, array(), $wgExternalSharedDB);
		$res = $dbr->query( $sql, __METHOD__ );

		while($row = $dbr->fetchObject($res)) {
			$rank = $row->rank;
		}
		return $rank;
	}

	public function getUserScore($user_id) {
		global $wgCityId, $wgExternalSharedDB;

		if (! isset($user_id)) return 0;

		$dbr = wfGetDB(DB_SLAVE, array(), $wgExternalSharedDB);
		$score = $dbr->selectField('ach_user_score', 'score', array('wiki_id' => $wgCityId, 'user_id' => $user_id), __METHOD__);

		// if no score found return zero
		return $score ? $score : 0;
	}

	public function getUserRankingPosition(User $user) {
		if($user) {
			$ranking = $this->getUsersRanking( 20 );

			foreach($ranking as $position => $rankedUser) {
				if($rankedUser->getId() == $user->getId()) return ++$position;
			}

			return count($ranking) + 1;
		}
		else
			return false;
	}

	function serialize(){
	    $ranking = $this->getUsersRanking();

	    $result = array();

	    foreach($ranking as $position => $user) {
		$result[$user->getId()] = $position;
	    }

	    return serialize($result);
	}

	function loadFromSnapshot() {
	    global $wgCityId;
	    
	    $dbr = WikiFactory::db( DB_SLAVE );

	    $res = $dbr->select('ach_ranking_snapshots', array('data'), array('wiki_id' => $wgCityId));

	    if($row = $dbr->fetchObject($res)) return unserialize($row->data);

	    return null;
	}

	/**
	* Returns the list of recently awarded badges for the current wiki and specified level
	*
	* @param $badgeLevel the level of the badges to list 
	* @param $limit limit the list to the specified amount of items Integer
	* @param $daySpan a span of days to subtract to the current date Integer
	* @param $blackList a list of the badge type IDs to exclude from the result Array
	* @return Array
	*/
	public function getRecentAwardedBadges($badgeLevel = null, $limit = null, $daySpan = null, $blackList = null) {
		wfProfileIn(__METHOD__);

		global $wgCityId, $wgWikiaBotLikeUsers, $wgExternalSharedDB;
		$badges = array();

		$dbr = wfGetDB(DB_SLAVE, array(), $wgExternalSharedDB);
		$conds = array('wiki_id' => $wgCityId);
		$rules = array('ORDER BY' => 'date DESC, badge_lap DESC');

		if($badgeLevel != null)
			$conds['badge_level'] = $badgeLevel;

		if($daySpan != null)
			$conds[] = "date >= (CURDATE() - INTERVAL {$daySpan} DAY)";
		
		if(is_array($blackList))
			$conds[] = 'badge_type_id NOT IN (' . implode($blackList) . ')';
		
		if($limit != null)
			$rules['LIMIT'] = $limit * 2; //bots and blocked users are filtered after the query hs been run, let's admit that ratio is 2:1
		
		$res = $dbr->select('ach_user_badges', 'user_id, badge_type_id, badge_lap, badge_level, date', $conds, __METHOD__, $rules);
		
		while(($row = $dbr->fetchObject($res)) && (count($badges) <= $limit)) {
			$user = User::newFromId($row->user_id);

			if($user && !$user->isBlocked() && !in_array( $user->getName(), $wgWikiaBotLikeUsers ) ) {
				$badges[] = array('user' => $user, 'badge' => new AchBadge($row->badge_type_id, $row->badge_lap, $row->badge_level), 'date' => $row->date);
			}
		}

		$dbr->freeResult($res);

		wfProfileOut(__METHOD__);

		return $badges;
	}
}
