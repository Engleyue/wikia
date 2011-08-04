<?php

class QuickStatsController extends WikiaController {
	
	public function getStats() {

		// First check memcache for our stats
		$memKey = $this->wf->MemcKey('quick_stats');
		$stats = $this->wg->Memc->get($memKey);
		if (!is_array($stats)) {
			$cityID = $this->wg->CityId;
			$stats = array();
			$this->getDailyPageViews( $stats, $cityID );
			$this->getDailyEdits( $stats, $cityID );
			$this->getDailyPhotos( $stats );
			$hasfbdata = $this->getDailyLikes($stats);

			// totals come in from MySQL as the last element with a null date, so just pop it off and give it a keyval 
			// Some of our stats can be empty, so insert zeros as defaults
			for ($i = -7 ; $i <= 0 ; $i ++) {
				$date = date( 'Y-m-d', strtotime("$i day") );
				if ($i == 0) $date = 'totals';  // last time around check the totals
				if (!isset($stats[$date])) $stats[$date] = array();
				if (!isset($stats[$date]['pageviews'])) $stats[$date]['pageviews'] = 0;
				if (!isset($stats[$date]['edits'])) $stats[$date]['edits'] = 0;
				if (!isset($stats[$date]['photos'])) $stats[$date]['photos'] = 0;
				if ($hasfbdata && !isset($stats[$date]['likes'])) $stats[$date]['likes'] = 0;
			}
			$this->wg->Memc->set($memKey, $stats, 60*60*12);  // Stats are daily, 12 hours lag seems reasonable
		} 
		$this->totals = $stats['totals'];
		unset($stats['totals']);
		krsort($stats);
		$this->stats = $stats;
	}
	
	// This should probably be Unique Users but we don't have that stat
	protected function getDailyPageViews( Array &$stats, $cityID) {
		$this->wf->ProfileIn( __METHOD__ );

		$dailyPageViews = array(); 
		if ( !empty( $this->wg->StatsDBEnabled ) ) {
			$db = $this->wf->GetDB(DB_SLAVE, array(), $this->wg->StatsDB);

			$today = date( 'Ymd', strtotime('-1 day') );
			$week = date( 'Ymd', strtotime('-7 day') );

			// Just for testing 
			if ($this->wg->DevelEnvironment) {
				$oRes = $db->select(
					array( 'page_views' ),
					array( "date_format(pv_use_date, '%Y-%m-%d') date", 'sum(pv_views) as cnt'  ),
					array(  "pv_use_date between '$week' and '$today' ", 'pv_city_id' => $cityID ),
					__METHOD__,
					array('GROUP BY'=> 'date WITH ROLLUP')
				); 	
			} else {
				$oRes = $db->select(
					array( 'google_analytics.pageviews' ),
					array( "date_format(date, '%Y-%m-%d') date", 'sum(pageviews) as cnt'  ),
					array(  "date between '$week' and '$today' ", 'city_id' => $cityID ),
					__METHOD__,
					array('GROUP BY'=> 'date WITH ROLLUP')
				);
			}
			while ( $oRow = $db->fetchObject ( $oRes ) ) {
				if (!$oRow->date) { // rollup row
					$stats['totals']['pageviews'] = $oRow->cnt; 
				} else {  
					$stats[ $oRow->date ]['pageviews'] = $oRow->cnt;
				}
			} 
		}
			
		wfProfileOut( __METHOD__ );
		return $dailyPageViews;				
	}	

		
	public function getDailyEdits (Array &$stats, $cityID) {
		$this->wf->ProfileIn( __METHOD__ );
		
		if ( !empty( $this->wg->StatsDBEnabled ) ) {
			$today = date( 'Y-m-d', strtotime('-1 day') );
			$week = date( 'Y-m-d', strtotime('-7 day') );
			
			$db = $this->wf->GetDB(DB_SLAVE, array(), $this->wg->StatsDB);

			$oRes = $db->select( 
				array( 'events' ), 
				array( "date_format(event_date, '%Y-%m-%d') date", 'count(0) as cnt' ),
				array(  "event_date between '$week 00:00:00' and '$today 23:59:59' ", 'wiki_id' => $cityID ),
				__METHOD__, 
				array( 'GROUP BY' => 'date WITH ROLLUP' )
			);
			while ( $oRow = $db->fetchObject ( $oRes ) ) { 
				if (!$oRow->date) { // rollup row
					$stats['totals']['edits'] = $oRow->cnt; 
				} else {  
					$stats[ $oRow->date ]['edits'] = $oRow->cnt;
				}
			} 
		}
		
		$this->wf->ProfileOut( __METHOD__ );		
	}
	
	
	protected function getDailyPhotos(Array &$stats) {
		$this->wf->ProfileIn( __METHOD__ );
		
		$db = $this->wf->GetDB(DB_SLAVE, array());
		
		$today = date( 'Ymd', strtotime('-1 day') ) . '235959';
		$week = date( 'Ymd', strtotime('-7 day') ) . '000000';
		
		$oRes = $db->select( 
			array( 'image' ), 
			array( "date_format(img_timestamp, '%Y-%m-%d') date", 'count(0) as cnt' ),
			array(  "img_timestamp between '$week' and '$today'" ),
			__METHOD__,
			array( 'GROUP BY' => 'date WITH ROLLUP')
		);
		while ( $oRow = $db->fetchObject ( $oRes ) ) { 
			if (!$oRow->date) { // rollup row
				$stats['totals']['photos'] = $oRow->cnt; 
			} else {  
				$stats[ $oRow->date ]['photos'] = $oRow->cnt;
			}
		} 
		
		$this->wf->ProfileOut( __METHOD__ );		
	}
	
	protected function getDailyLikes(Array &$stats) {
		global $fbApiKey, $fbApiSecret, $fbAccessToken;
		
		$result = FALSE;
		$domain_id = Wikia::getFacebookDomainId();
		if (!$domain_id)
			return $result;

		$this->wf->ProfileIn(__METHOD__);
		
		$since = strtotime("-7 day 00:00:00");
		$until = strtotime("-0 day 00:00:00");
		$url = 'https://graph.facebook.com/'.$domain_id.'/insights/domain_widget_likes/day?access_token='.$fbAccessToken.'&since='.$since.'&until='.$until;
		$response = json_decode(Http::get($url));
		
		if($response) {
			$data = array_pop($response->data);
			if(isset($data->values)) {
				$stats['totals']['likes'] = 0;
				foreach($data->values as $value) {
					if (preg_match('/([\d\-]*)/', $value->end_time, $matches)) {
						$day = $matches[1];
						$stats[$day]['likes'] = $value->value;
						$stats['totals']['likes'] += $value->value;
					}
				}
				$result = TRUE;
			}
		}
		$this->wf->ProfileOut(__METHOD__);
		
		return $result;
	}
	
	public static function shortenNumberDecorator($number) {
		$number = intval($number);
		$d = $number / 1000;
		if ($d >= 10) {
			return wfMsg('quickstats-number-shortening', array(round($d, 1)));
		} else {
			return $number;
		}
	}
}
