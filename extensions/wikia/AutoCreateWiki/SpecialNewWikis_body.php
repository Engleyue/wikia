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
		wfLoadExtensionMessages( "Newwikis" );
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

		$res = $wgMemc->get( wfSharedMemcKey( "{$format}-city-list" ) );
		$filename = "{$format}_city_list.{$format}";

		$wgOut->disable();
		if( $format === "xml" ) {
				header( "Content-type: application/xml; charset=UTF-8" );
		}
		else {
				header( "Content-type: text/csv; charset=UTF-8" );
		}
		$wgOut->sendCacheControl();

		print gzinflate($res);
		exit;
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
	private $more_details = false;

	/**
	 * constructor
	 *
	 * @access public
	 */
	function __construct( $par = null ) {
		global $wgRequest, $wgUser;
		$parms = explode( '/', ($par = ( $par !== null ) ? $par : '' ) );
		if ( isset($parms[0]) && !empty($parms[0]) ) {
			$this->firstChar = $parms[0];
		}
		if ( isset($parms[1]) && !empty($parms[1]) ) {
			$this->lang = $parms[1];
		}
		$this->lang = ( $this->lang != '' ) ? $this->lang : $wgRequest->getVal( 'language' );
		$this->firstChar = ( $this->firstChar != '' ) ? $this->firstChar : $wgRequest->getText( 'start' );
		$this->hub = $wgRequest->getText( 'hub' );
		
		if ($wgUser->isAllowed('newwikislist'))
			$this->more_details = true;

		parent::__construct();

		/**
		 * overwrite database handler
		 */
		$this->mDb = WikiFactory::db( DB_SLAVE );
	}


	function getIndexField() {
		return 'city_id';
	}

	function getDefaultDirections() {
		return 'desc';
	}

	function getQueryInfo() {
		$query = array(
			'tables' => array( 'city_list' ),
			'fields' => array('city_list.city_id', 'city_dbname', 'city_url', 'city_title', 'city_lang', 'city_created'),
			'options' => array(),
			'conds' => array(),
			'join_conds' => array(),
		);

		// Don't show hidden names
		$query['conds'][] = 'city_public = 1';
		
		// founder info
		if ($this->more_details) {
			$query['tables'][] = 'user';
			$extra_fields = array('user.user_name', 'user.user_email', 'user.user_email_authenticated');
			$query['fields'] = array_merge($query['fields'], $extra_fields);
			$query['join_conds']['user'] = array('left join', 'city_list.city_founding_user=user.user_id');
		}

		if ( $this->firstChar != "" ) {
			$query['conds'][] = sprintf( "upper(city_title) like upper('%s%%')", $this->mDb->escapeLike( $this->firstChar ) );
		}
		if( $this->lang != "" ) {
			$query['conds'][] = 'city_lang = ' . $this->mDb->addQuotes( $this->lang );
		}
		if( !empty( $this->hub ) ) {
			$query['tables'][] = 'city_cat_mapping';
			$query['conds'][] = 'cat_id = ' . $this->hub;
			$query['join_conds']['city_cat_mapping'] = array( 'LEFT JOIN', 'city_cat_mapping.city_id = city_list.city_id' );
		}

		return $query;
	}
        /* need to be overwrite because of not unique col name (city_id) */

	function reallyDoQuery( $offset, $limit, $descending ) {
		$fname = __METHOD__ . ' (' . get_class( $this ) . ')';
		$info = $this->getQueryInfo();
		$tables = $info['tables'];
		$fields = $info['fields'];
		$conds = isset( $info['conds'] ) ? $info['conds'] : array();
		$options = isset( $info['options'] ) ? $info['options'] : array();
		$join_conds = isset( $info['join_conds'] ) ? $info['join_conds'] : array();
		if ( $descending ) {
			$options['ORDER BY'] = "city_list." . $this->mIndexField;
			$operator = '>';
		} else {
			$options['ORDER BY'] = "city_list." . $this->mIndexField . ' DESC';
			$operator = '<';
		}
		if ( $offset != '' ) {
			$conds[] = "city_list." . $this->mIndexField . $operator . $this->mDb->addQuotes( $offset );
		}
		$options['LIMIT'] = intval( $limit );
		$res = $this->mDb->select( $tables, $fields, $conds, $fname, $options, $join_conds );
		return new ResultWrapper( $this->mDb, $res );
	}

	function formatRow( $row ) {
		global $wgLang;

		$name = XML::tags('A', array('href' => $row->city_url), $row->city_title);
		if ($this->more_details) {
			$confirm = ($row->user_email_authenticated) ? 'Y' : 'N';
			$detail = "$row->city_lang, $row->city_created, FounderName:$row->user_name, Email:$row->user_email, Confirm:$confirm";
		} else {
			$detail = $row->city_lang;
		}
		$item = wfSpecialList( $name, $detail );

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

		$hubs = WikiFactoryHub::getInstance();
		$hubs = $hubs->getCategories();
		$this->hubs = array( 0 => 'All' );
		if ( !empty($hubs) ) {
			foreach ($hubs as $id => $hub_options) {
				$this->hubs[$id] = $hub_options['name'];
			}
		}

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

		$out .= Xml::closeElement( 'select' );
		$out .= '&nbsp;';

		$out .= Xml::label( wfMsg( 'autocreatewiki-category-label' ), 'hub' ) . ' ';
		$out .= Xml::openElement( 'select', array( 'name' => 'hub', 'id' => 'hub' ) );

		foreach( $this->hubs as $sHub => $sHubName ) {
			$out .= Xml::option( $sHubName, $sHub, $sHub == $this->hub );
		}
		
		$out .= Xml::closeElement( 'select' );

		$out .= '<br />';

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
