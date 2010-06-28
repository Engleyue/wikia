<?php

class PhalanxStats extends UnlistedSpecialPage {
	function __construct( ) {
		parent::__construct( 'PhalanxStats', 'phalanx' );
	}

	function execute( $par ) {
		global $wgOut, $wgLang, $wgUser;

		// check restrictions
		if ( !$this->userCanExecute( $wgUser ) ) {
			$this->displayRestrictionError();
			return;
		}

		if ( empty( $par ) ) {
			return true;
		}

		$block = array();
		$block = Phalanx::getFromId( intval($par) );

		if ( empty( $block ) ) {
			$wgOut->addWikiMsg( 'phalanx-stats-block-notfound' );
			return true;
		}

		// process block data for display
		$block['author_id'] = User::newFromId( $block['author_id'] )->getName();
		$block['timestamp'] = $wgLang->timeanddate( $block['timestamp'] );
		if ( $block['expire'] == null ) {
			$block['expire'] = 'infinte';
		} else {
			$block['expire'] = $wgLang->timeanddate( $block['expire'] );
		}
		$block['exact'] = $block['exact'] ? 'Yes' : 'No';
		$block['regex'] = $block['exact'] ? 'Yes' : 'No';
		$block['case'] = $block['case'] ? 'Yes' : 'No';
		$block['type'] = implode( ', ', Phalanx::getTypeNames( $block['type'] ) );
		$block['lang'] = empty($block['case']) ? '*' : $block['lang'];

		//TODO: add i18n
		$headers = array(
			'Block ID',
			'Added by',
			'Text',
			'Type',
			'Created on',
			'Expires on',
			'Exact',
			'Regex',
			'Case',
			'Reason',
			'Language',
		);

		$html = '';

		$tableAttribs = array(
			'border' => 1,
			'cellpadding' => 4,
			'cellspacing' => 0,
		);
		$html .=  Xml::buildTable( array( $block ), $tableAttribs, $headers );
		$html .=  Xml::element( 'br', null, '', true );

		$pager = new PhalanxStatsPager( $par );

		$html .= $pager->getNavigationBar();
		$html .= $pager->getBody();
		$html .= $pager->getNavigationBar();

		$wgOut->addHTML( $html );
	}
}

class PhalanxStatsPager extends ReverseChronologicalPager {
	public function __construct( $id ) {
		global $wgExternalSharedDB;

		parent::__construct();
		$this->mDb = wfGetDB( DB_SLAVE, array(), $wgExternalSharedDB );

		$this->mBlockId = (int) $id;

	}

	function getQueryInfo() {
		$query['tables'] = 'phalanx_stats';
		$query['fields'] = '*';
		$query['conds'] = array(
			'ps_blocker_id' => $this->mBlockId,
		);

		return $query;
	}

	function getIndexField() {
		return 'ps_timestamp';
	}

	function getStartBody() {
		return '<ul id="phalanx-block-' . $this->mBlockId . '-stats">';
	}

	function getEndBody() {
		return '</ul>';
	}

	function formatRow( $row ) {
		global $wgLang;

		wfLoadExtensionMessages( 'Phalanx' );

		$type = implode( Phalanx::getTypeNames( $row->ps_blocker_type ) );
		
		$username = $row->ps_blocked_user;

		$timestamp = $wgLang->timeanddate( $row->ps_timestamp );

		$oWiki = WikiFactory::getWikiById( $row->ps_wiki_id );
		$url = $oWiki->city_url;

		$html = '<li>';
		$html .= wfMsg( 'phalanx-stats-row', $type, $username, $url, $timestamp );
		$html .= '</li>';

		return $html;
	}
}
