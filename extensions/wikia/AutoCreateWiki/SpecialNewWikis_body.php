<?php

/**
 * @package MediaWiki
 * @subpackage SpecialPage
 * @author Piotr Molski <moli@wikia-inc.com> for Wikia.com
 * @version: 1.0
 */

if ( !defined( 'MEDIAWIKI' ) ) { 
	echo "This is MediaWiki extension and cannot be used standalone.\n"; exit( 1 ) ; 
}

class NewWikisSpecialPage extends SpecialPage {

	function __construct() {
		wfLoadExtensionMessages( "AutoCreateWiki" );
		parent::__construct( 'Newwikis' );
	}

	function execute($par) {
		global $wgOut;
		$up = new NewWikisPage($par);

		# getBody() first to check, if empty
		$usersbody = $up->getBody();
		$s = XML::openElement( 'div', array('class' => 'mw-spcontent') );
		$s .= $up->getPageHeader();
		if( $usersbody ) {
			$s .=	$up->getNavigationBar();
			$s .=	'<ul>' . $usersbody . '</ul>';
			$s .=	$up->getNavigationBar() ;
		} else {
			$s .=	'<p>' . wfMsgHTML('listusers-noresult') . '</p>';
		};
		$s .= XML::closeElement( 'div' );
		$wgOut->addHTML( $s );
	}
}


class NewWikisPage extends AlphabeticPager {
	private $firstChar;
	private $lang;

	function __construct( $par = null ) {
		global $wgRequest;
		#---
		$parms = explode( '/', ($par = ( $par !== null ) ? $par : '' ) );
		#---
		if ( isset($parms[0]) && !empty($parms[0]) ) {
			$this->firstChar = $parms[0];
		}
		if ( isset($parms[1]) && !empty($parms[1]) ) {
			$this->lang = $parms[1];
		}
		#---
		$this->lang = ( $this->lang != '' ) ? $this->lang : $wgRequest->getVal( 'language' );
		$this->firstChar = ( $this->firstChar != '' ) ? $this->firstChar : $wgRequest->getText( 'start' );
		
		parent::__construct();
	}


	function getIndexField() {
		return 'city_id';
	}
	
	function getDefaultDirections() {
		return 'desc';
	}

	function getQueryInfo() {
		$dbr = wfGetDB( DB_SLAVE );
		$conds = array();
		// Don't show hidden names
		$conds[] = 'city_public = 1';
		if ( $this->firstChar != "" ) {
			$conds[] = "upper(city_title) like upper('{$this->firstChar}%')";
		}
		if( $this->lang != "" ) {
			$conds[] = 'city_lang = ' . $dbr->addQuotes( $this->lang );
		}

		$query = array(
			'tables' => WikiFactory::table('city_list'),
			'fields' => array('city_id', 'city_dbname', 'city_url', 'city_title', 'city_lang', 'city_created'),
			'options' => array(),
			'conds' => $conds
		);

		return $query;
	}

	function formatRow( $row ) {
		global $wgLang;

		$name = XML::tags('A', array('url' => $row->city_url, 'target' => 'new'), $row->city_title);
		$item = wfSpecialList( $name, $row->city_lang );

		return "<li>{$item}</li>";
	}

	function getBody() {
		if( !$this->mQueryDone ) {
			$this->doQuery();
		}
		return parent::getBody();
	}

	function getPageHeader( ) {
		global $wgScript, $wgRequest;
		$self = $this->getTitle();
		$this->getLangs();

		# Form tag
		$out  = Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript ) ) .
			'<fieldset>' .
			Xml::element( 'legend', array(), wfMsg( 'newwikis' ) );
		$out .= Xml::hidden( 'title', $self->getPrefixedDbKey() );
		# First character in title name
		$out .= Xml::label( wfMsg( 'newwikisstart' ), 'offset' ) . ' ' .
			Xml::input( 'start', 20, $this->firstChar, array( 'id' => 'offset' ) ) . ' ';

		# Group drop-down list
		$out .= Xml::label( wfMsg( 'yourlanguage' ), 'language' ) . ' ' .
			Xml::openElement('select',  array( 'name' => 'language', 'id' => 'language' ) ) .
			Xml::option( wfMsg( 'autocreatewiki-language-all' ), '' );
			$out .= Xml::element( 'optgroup', array('label' => wfMsg('autocreatewiki-language-top', count($this->mTopLanguages)) ), '');

		foreach( $this->mTopLanguages as $sLang)
			$out .= Xml::option( $this->mLanguages[$sLang], $sLang, $sLang == $this->lang );

			$out .= Xml::element( 'optgroup', array('label' => wfMsg('autocreatewiki-language-all')), '');

		foreach( $this->mLanguages as $sLang => $sLangName ) 
			$out .= Xml::option( $sLangName, $sLang, $sLang == $this->lang );

		$out .= Xml::closeElement( 'select' ) . '<br/>';
		$out .= '&nbsp;';

		# Submit button and form bottom
		if( $this->mLimit )
			$out .= Xml::hidden( 'limit', $this->mLimit );
		$out .= Xml::submitButton( wfMsg( 'allpagessubmit' ) );
		$out .= '</fieldset>' .
			Xml::closeElement( 'form' );

		return $out;
	}

	function getDefaultQuery() {
		$query = parent::getDefaultQuery();
		return $query;
	}

	private function getLangs() {
		$this->mTopLanguages = explode(',', wfMsg('autocreatewiki-language-top-list'));
		$this->mLanguages = Language::getLanguageNames();
		$filter_languages = explode(',', wfMsg('requestwiki-filter-language'));
		foreach ($filter_languages as $key) {
			unset($this->mLanguages[$key]);
		}
		asort($this->mLanguages);
		return count($this->mLanguages);
	}
	
}
