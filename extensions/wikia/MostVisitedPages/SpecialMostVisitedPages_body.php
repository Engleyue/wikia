<?php

class MostvisitedpagesSpecialPage extends SpecialPage {
    private $mpa = null;

	function __construct() {
		parent::__construct( 'Mostvisitedpages' );
	}


	function execute($article_id = null, $limit = "", $offset = "", $show = true) {
		wfLoadExtensionMessages("Mostvisitedpages");

		if (empty($limit) && empty($offset)) { 
            list( $limit, $offset ) = wfCheckLimits();
        }
        $this->mpp = new MostvisitedpagesPage($article_id, $show);
        
		if (!empty($show)) {
            $this->setHeaders();
        } else {
            // return data as array - not like <LI> list
            $this->mpp->setListoutput(TRUE);
        }
        $this->mpp->doQuery( $offset, $limit, $show );
    }
    
    function getResult() { return $this->mpp->getResult(); }
}

class MostvisitedpagesPage extends QueryPage {
	private $data = array();
	private $show = false;
	var $mArticle = "";
	var $mArticleId = "";
	var $mTitle = "";
	var $mName = "Mostvisitedpages";

	function __construct($page_id, $show) { 
		global $wgRequest;
		$this->show = $show; 
		$this->mArticle = $wgRequest->getVal('target');
		$this->mArticleId = $page_id;
		$this->mTitle = Title::makeTitle( NS_SPECIAL, $this->mName );
	}

	function getName() { return $this->mName; }
	function isExpensive() { return false; }
	function isSyndicated() { return false; }
	function sortDescending() { return true; }

	function getPageHeader() { 
        wfProfileIn( __METHOD__ );
        if (empty($this->show)) {
        	wfProfileOut( __METHOD__ );
        	return "";
		}
		$action = $this->mTitle->escapeLocalURL("");

        $oTmpl = new EasyTemplate( dirname( __FILE__ ) . "/templates/" );
        $oTmpl->set_vars( array(
            "action"		=> $action,
            "articleName"	=> $this->mArticle,
        ));
        wfProfileOut( __METHOD__ );
        return $oTmpl->execute("main-form");
	}
	public function getResult() { return $this->data; }

	function getSQL() {
		global $wgContentNamespaces, $wgEnableBlogArticles;
		$dbr = wfGetDB( DB_SLAVE );
		list( $page, $page_visited ) = $dbr->tableNamesN( 'page', 'page_visited' );
		$namespaces = array( NS_MAIN, NS_USER, NS_TALK, NS_USER_TALK, NS_IMAGE, NS_IMAGE_TALK );
		if ( !empty($wgContentNamespaces) && is_array($wgContentNamespaces) ) {
			foreach ($wgContentNamespaces as $nspace) {
				if ( !in_array($nspace, $namespaces) ) {
					$namespaces[] = $nspace;
				}
			}
		}
		if ( !empty($wgEnableBlogArticles) ) {
			if ( !in_array(NS_BLOG_ARTICLE, $namespaces) ) {
				$namespaces[] = NS_BLOG_ARTICLE;
			}			
		}
		$where = " page_id = article_id and page_namespace in ('".implode("','", $namespaces)."') ";
		if (!empty($this->mArticle)) {
			$where .= " and lower(page_title) like lower('%".htmlspecialchars($this->mArticle)."%') ";
		}
		if (!empty($this->mArticleId)) { 
			$where .= " and page_id = '".$this->mArticleId."' ";
		}
		$sql = "SELECT 'Mostpopularpages' as type, page_namespace as namespace, page_title as title, count as value FROM $page, $page_visited where $where";
		return $sql;
	}

	function formatResult( $skin, $result ) {
		$res = false;
		if (empty($this->show)) {
			$this->data[$result->title] = array('value' => $result->value, 'namespace' => $result->namespace);
		} else {
			$title = Title::newFromText($result->title, $result->namespace);
			if ($title) {
				$result->title = Xml::element("a", array("href" => $title->getLocalURL()), $title->getFullText()) ;
			} 
			$res = wfSpecialList( $result->title, $result->value );
		}
		return $res;
	}
}
