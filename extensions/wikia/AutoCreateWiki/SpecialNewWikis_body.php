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
		global $wgOut, $wgRequest;

		$format = $wgRequest->getVal( "format", false );
		if( $format === "xml" || $format === "csv" ) {
			$this->generateList( $format );
		}
		else {
			$wgOut->setPageTitle( wfMsg('newwikis') );
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

	/**
	 * @access private
	 *
	 * @param String $format	format of list: csv or xml
	 */
	private function generateList( $format ) {
		global $wgOut, $wgMemc, $wgExternalSharedDB;

		$wgOut->disable();
		if( $format === "xml" ) {
			header( "Content-type: application/xml; charset=UTF-8" );
		}
		else {
			header( "Content-type: text/csv; charset=UTF-8" );
		}
		$wgOut->sendCacheControl();

		$list = $wgMemc->get( wfSharedMemcKey( "xml-city-list" ) );
		#$list = array();
		if( empty( $list ) ) {
			$list = array();
			$dbr = WikiFactory::db( DB_SLAVE );
			$sth = $dbr->select(
				array( "city_list" ),
				array( "city_title", "city_lang", "city_url", "city_id" ),
				array( "city_public = 1" ),
				__METHOD__
			);
			while( $row = $dbr->fetchObject( $sth ) ) {
				$row->category = WikiFactory::getCategory( $row->city_id );
				$list[] = $row;
			}
			$wgMemc->set( wfSharedMemcKey( "xml-city-list" ), $list, 3600 * 6 );
		}
		if( $format === "xml" ) {
			echo Xml::openElement( "citylist" ) . "\n";
		}
		else {
			echo implode( ",", array(
				$this->quote( "id" ),
				$this->quote( "sitename" ),
				$this->quote( "url" ),
				$this->quote( "language" ),
				$this->quote( "category-name" ),
				$this->quote( "category-id" )
			) )  . "\n";
		}
		foreach( $list as $city ) {
			if( $format === "xml" ) {
				echo Xml::element( "siteinfo",
					array(
						"id"            => $city->city_id,
						"sitename"      => $city->city_title,
						"url"           => $city->city_url,
						"language"      => $city->city_lang,
						"category-name" => empty( $city->category->cat_name )
							? 'unknown' : $city->category->cat_name,
						"category-id"   => empty( $city->category->cat_id )
							? 0 : $city->category->cat_id
					)
				) . "\n";
			}
			else {
				echo implode( ",", array(
					$this->quote( $city->city_id ),
					$this->quote( $city->city_title ),
					$this->quote( $city->city_url ),
					$this->quote( $city->city_lang ),
					empty( $city->category->cat_name )
						? $this->quote( 'unknown' )
						: $this->quote( $city->category->cat_name ),
					empty( $city->category->cat_id )
						? $this->quote( 0 )
						: $this->quote( $city->category->cat_id )
				) ) ."\n";
			}
		}
		if( $format === "xml" ) { echo Xml::closeElement( "citylist" ) . "\n" ; }
	}

	/**
	 * using in csv format
	 *
	 * @param String $str	field for quoting
	 *
	 * @return string	quoted field
	 */
	private function quote( $str ) {

		return '"'. str_replace( '"', '\"', $str ). '"';
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

		$name = XML::tags('A', array('href' => $row->city_url, 'target' => 'new'), $row->city_title);
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
