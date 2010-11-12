<?php

class AdSS_Ad {

	public $id;
	public $type;
	public $userId;
	public $wikiId;
	public $pageId;
	public $status;
	public $created;
	public $closed;
	public $expires;
	public $weight;
	public $price;

	protected $user;

	function __construct() {
		global $wgCityId;
		$this->id = 0;
		$this->userId = 0;
		$this->wikiId = $wgCityId;
		$this->pageId = 0;
		$this->status = 0;
		$this->created = null;
		$this->closed = null;
		$this->expires = null;
		$this->weight = 1;
		$this->price = 0;
		$this->user = null;
	}

	function loadFromForm( $f ) {
		switch( $f->get( 'wpType' ) ) {
			case 'page':
				$title = Title::newFromText( $f->get( 'wpPage' ) );
				if( $title && $title->exists() ) {
					$this->pageId = $title->getArticleId();
					$this->price = AdSS_Util::getPagePricing( $title );
					$this->weight = 1;
				}
				break;
			case 'site-premium':
				$this->weight = 4;
				$this->price = AdSS_Util::getSitePricing();
				$this->price['price'] = 3 * $this->price['price'];
				break;
			default /* site */:
				$this->weight = $f->get( 'wpWeight' );
				$this->price = AdSS_Util::getSitePricing();
				$this->price['price'] = $this->weight * $this->price['price'];
				break;
		}
	}

	function loadFromRow( $row ) {
		if( isset( $row->ad_id ) ) {
			$this->id = intval( $row->ad_id );
		}
		$this->userId = $row->ad_user_id;
		$this->wikiId = $row->ad_wiki_id;
		$this->pageId = $row->ad_page_id;
		$this->status = $row->ad_status;
		$this->created = wfTimestampOrNull( TS_UNIX, $row->ad_created );
		$this->closed = wfTimestampOrNull( TS_UNIX, $row->ad_closed );
		$this->expires = wfTimestampOrNull( TS_UNIX, $row->ad_expires );
		$this->weight = $row->ad_weight;
		$this->price = array(
				'price'  => $row->ad_price,
				'period' => $row->ad_price_period,
				);
	}

	function save() {
		global $wgAdSS_DBname;

		$dbw = wfGetDB( DB_MASTER, array(), $wgAdSS_DBname );
		if( $this->id == 0 ) {
			$dbw->insert( 'ads',
					array(
						'ad_user_id'      => $this->userId,
						'ad_url'          => $this->url,
						'ad_text'         => $this->text,
						'ad_desc'         => $this->desc,
						'ad_wiki_id'      => $this->wikiId,
						'ad_page_id'      => $this->pageId,
						'ad_status'       => $this->status,
						'ad_created'      => wfTimestampNow( TS_DB ),
						'ad_expires'      => wfTimestampOrNull( TS_DB, $this->expires ),
						'ad_weight'       => $this->weight,
						'ad_price'        => $this->price['price'],
						'ad_price_period' => $this->price['period'],
					     ),
					__METHOD__
				    );
			$this->id = $dbw->insertId();
		} else {
			$dbw->update( 'ads',
					array(
						'ad_user_id'      => $this->userId,
						'ad_url'          => $this->url,
						'ad_text'         => $this->text,
						'ad_desc'         => $this->desc,
						'ad_wiki_id'      => $this->wikiId,
						'ad_page_id'      => $this->pageId,
						'ad_status'       => $this->status,
						'ad_closed'       => wfTimestampOrNull( TS_DB, $this->closed ),
						'ad_expires'      => wfTimestampOrNull( TS_DB, $this->expires ),
						'ad_weight'       => $this->weight,
						'ad_price'        => $this->price['price'],
						'ad_price_period' => $this->price['period'],
					     ),
					array(
						'ad_id' => $this->id
					     ),
					__METHOD__
				    );
		}
	}

	abstract function render();

	function refresh() {
		$now = time();
		if( is_null( $this->expires ) ) {
			$this->expires = $now;
		} else {
			if( $this->expires < $now ) {
				$this->expires = $now;
			}
		}
		switch( $this->price['period'] ) {
			case 'd': $period = "+1 day"; break;
			case 'w': $period = "+1 week"; break;
			case 'm': $period = "+1 month"; break;
		}
		$this->expires = strtotime( $period, $this->expires );
		$this->save();
	}

	function close() {
		$this->closed = wfTimestampNow( TS_DB );
		$this->save();
	}

	function getUser() {
		if( !$this->user ) {
			$this->user = AdSS_User::newFromId( $this->userId );
		}
		return $this->user;
	}

	function setUser( $user ) {
		$this->user = $user;
		$this->userId = $user->id;
	}

}
