<?php

/**
 * Main part of Special:Sitemap
 *
 * @file
 * @ingroup Extensions
 * @author Krzysztof Krzyżaniak <eloy@wikia-inc.com> for Wikia Inc.
 * @copyright © 2010, Wikia Inc.
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @version 1.0
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	echo "This is a MediaWiki extension and cannot be used standalone.\n";
	exit( 1 );
}

class SitemapPage extends UnlistedSpecialPage {

	private $mType, $mTitle, $mNamespaces, $mNamespace, $mPriorities, $mSizeLimit;

	/**
	 * standard constructor
	 * @access public
	 */
	public function __construct( $name = "Sitemap" ) {
		parent::__construct( $name );

		$this->mPriorities = array(
			// MediaWiki standard namespaces
			NS_MAIN                 => '1.0',
			NS_TALK                 => '1.0',
			NS_USER                 => '1.0',
			NS_USER_TALK            => '1.0',
			NS_PROJECT              => '1.0',
			NS_PROJECT_TALK         => '1.0',
			NS_FILE                 => '1.0',
			NS_FILE_TALK            => '1.0',
			NS_MEDIAWIKI            => '0.5',
			NS_MEDIAWIKI_TALK       => '0.5',
			NS_TEMPLATE             => '0.5',
			NS_TEMPLATE_TALK        => '0.5',
			NS_HELP                 => '0.5',
			NS_HELP_TALK            => '0.5',
			NS_CATEGORY             => '1.0',
			NS_CATEGORY_TALK        => '1.0',
        );

		$this->mSizeLimit = ( pow( 2, 20 ) * 10 ) - 20; // safe margin
		$this->mLinkLimit = 50000;
	}


	/**
	 * Main entry point
	 *
	 * @access public
	 *
	 * @param $subpage Mixed: subpage of SpecialPage
	 */
	public function execute( $subpage ) {
		global $wgRequest, $wgUser, $wgOut;

		/**
		 * subpage works as type param, param has precedence, default is "index"
		 */
		$this->mType = "index";
		if ( !empty( $subpage ) ) {
			$this->mType = $subpage;
		}

		$t = $wgRequest->getText( "type", "" );
		if ( $t != "" ) {
			$this->mType = $t;
		}

		$this->mTitle = SpecialPage::getTitleFor( "Sitemap", $subpage );
		$this->parseType();
		$this->getNamespacesList();
		if ( $this->mType == "namespace" ) {
			$this->generateNamespace();
		}
		else {
			$this->generateIndex();
		}
	}

	/**
	 * get all namespaces, take them from article so will only have
	 * pages for existed namespaces
	 *
	 * @access public
	 */
	public function getNamespacesList() {
		global $wgSitemapNamespaces;

		if ( is_array( $wgSitemapNamespaces ) ) {
			$this->mNamespaces = $wgSitemapNamespaces;
			return;
		}

		wfProfileIn( __METHOD__ );
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select(
			'page',
			array( 'page_namespace' ),
			array(),
			__METHOD__,
			array(
				'GROUP BY' => 'page_namespace',
				'ORDER BY' => 'page_namespace',
			)
		);

		while ( $row = $dbr->fetchObject( $res ) ) {
			$this->mNamespaces[] = $row->page_namespace;
		}
		wfProfileOut( __METHOD__ );

		return $this->mNamespaces;
	}

	/**
	 * parse type and set mType and mNamespace
	 */
	private function parseType() {
		/**
		 * requested files are named like sitemap-wikicities-NS_150-0.xml.gz
		 * index is named like sitemap-index-wikicities.xml
		 */
		if ( preg_match( "/^sitemap\-.+NS_(\d+)\-(\d+)/", $this->mType, $match ) ) {
			$this->mType = "namespace";
			$this->mNamespace = $match[ 1 ];
		}
		else {
			$this->mType = "index";
			$this->mNamespace = false;
		}
	}

	private function generateIndex() {
		global $wgServer, $wgOut;

		$timestamp = wfTimestamp( TS_ISO_8601, wfTimestampNow() );
		$id = wfWikiID();

		$wgOut->disable();

		header( "Content-type: application/xml; charset=UTF-8" );
		header( "Cache-control: max-age=86400", true );

		$out = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$out .= sprintf( "<!-- generated on fly by %s -->\n", $this->mTitle->getFullURL() );
		$out .= "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
		foreach ( $this->mNamespaces as $namespace ) {
			$out .= "\t<sitemap>\n";
			$out .= "\t\t<loc>{$wgServer}/sitemap-{$id}-NS_{$namespace}-0.xml.gz</loc>\n";
			$out .= "\t\t<lastmod>{$timestamp}</lastmod>\n";
			$out .= "\t</sitemap>\n";
		}
		$out .= "</sitemapindex>\n";

		print $out;
	}

	/**
	 * @access private
	 */
	private function generateNamespace() {
		global $wgServer, $wgOut;

		$dbr = wfGetDB( DB_SLAVE );

		$wgOut->disable();

		header( "Content-type: application/x-gzip" );
		header( "Cache-control: max-age=86400", true );

		$out = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$out .= sprintf( "<!-- generated on the fly by %s -->\n", $this->mTitle->getFullURL() );

		$sth = $dbr->select(
			'page',
			array(
				'page_namespace',
				'page_title',
				'page_touched',
			),
			array( 'page_namespace' => $this->mNamespace ),
			__METHOD__
		);

		$out .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
		while ( $row = $dbr->fetchObject( $sth ) ) {
			$size = strlen( $out );
			$title = Title::makeTitle( $row->page_namespace, $row->page_title );
			$stamp = wfTimestamp( TS_ISO_8601, $row->page_touched );
			$prior = isset( $this->mPriorities[ $row->page_namespace ] )
				? $this->mPriorities[ $row->page_namespace ]
				: "0.5";

			$entry = $this->titleEntry( $title->getFullURL(), $stamp, $prior );

			/**
			 * break if it's to big
			 */
			if ( strlen( $entry ) + $size > $this->mSizeLimit ) {
				break;
			}

			$out .= $entry;
		}
		$out .= "</urlset>\n";

		print gzencode( $out );
	}

	private function titleEntry( $url, $date, $priority ) {
		return
			"\t<url>\n" .
			"\t\t<loc>$url</loc>\n" .
			"\t\t<lastmod>$date</lastmod>\n" .
			"\t\t<priority>$priority</priority>\n" .
			"\t</url>\n";
	}

	public function cachePages( $namespace ) {
		wfProfileIn( __METHOD__ );

		$dbr = wfGetDB( DB_SLAVE, "vslow" );
		$sth = $dbr->select(
			array( "page" ),
			array( "page_title, page_id, page_namespace" ),
			array( "page_namespace" => $namespace ),
			__METHOD__,
			array( "ORDER BY" => "page_id" )
		);
		$pCounter = 0; // counter for pages in index
		$rCounter = 0; // counter for rows (titles)
		$index = array();
		$sPage = false; // lowest page_id for page
		$ePage = false; // highest page_id for page
		while( $row = $dbr->fetchObject( $sth ) ) {
			$index[ $pCounter ] = array( );
			if( $sPage === false ) {
				$sPage = $row->page_id;
				$index[ $pCounter ][ "start" ] = $sPage;
			}
			if( $rCounter >= $this->mLinkLimit ) {
				$index[ $pCounter ][ "end" ] = $row->page_id;
				$pCounter++;
				$rCounter = 0;
			}
			$rCounter++;
		}

		wfProfileOut( __METHOD__ );

		return $index;
	}
}
