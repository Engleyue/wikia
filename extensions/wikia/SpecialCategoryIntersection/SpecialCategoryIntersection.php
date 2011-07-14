<?php
/**
 * @author Sean Colombo
 *
 * Special page to demonstrate the CategoryIntersection API call, specifically as it relates to LyricWiki.
 *
 * This extension is designed to be portable, so it doesn't use the Nirvana framework.
 *
 * TODO:Autocompletion for the text-fields (if not, then pull "Category:" out in front of the textfield so they don't have to type it).
 * TODO: Autocompletion for the text-fields (if not, then pull "Category:" out in front of the textfield so they don't have to type it).
 *
 * @file
 * @ingroup SpecialPage
 */

if ( !defined( 'MEDIAWIKI' ) ) die();

$wgSpecialPages[ "CategoryIntersection" ] = "SpecialCategoryIntersection";
$wgExtensionMessagesFiles['CategoryIntersection'] = dirname( __FILE__ ) . '/SpecialCategoryIntersection.i18n.php';
$wgExtensionCredits['specialpage'][] = array(
	'name' => 'CategoryIntersection',
	'url' => 'http://lyrics.wikia.com/User:Sean_Colombo', // TODO: Update with a link to appropriate extension info page (such as MediaWiki.org) if this extension gets committed upstream.
	'author' => '[http://www.seancolombo.com Sean Colombo]',
	'descriptionmsg' => 'categoryintersection-desc',
	'version' => '1.0',
);


/**
 * A class to deal with displaying CategoryIntersections provided by the MediaWiki API.
 * @ingroup SpecialPage
 */
class SpecialCategoryIntersection extends SpecialPage {
	public $defaultLimit = 25;
	
	static private $CAT_PREFIX = "category_";
	static private $CATEGORY_NS_PREFIX = "Category:"; // the actual namespace prefix (includes the colon at the end).  FIXME: There must be a way to get this programatically.
	static private $DOCUMENTATION_URL = "http://lyrics.wikia.com/api.php"; // TODO: URL OF DOCS HERE.

	public function __construct() {
		parent::__construct( 'CategoryIntersection' );
	}

	/**
	 * Manage forms to be shown according to posted data.
	 *
	 * @param $subpage Mixed: string if any subpage provided, else null
	 */
	public function execute( $subpage ) {
		global $wgOut;
		wfProfileIn( __METHOD__ );
	
		wfLoadExtensionMessages( 'CategoryIntersection' );

		$wgOut->setPagetitle( wfMsg('categoryintersection') );
		
		// Just splurt some CSS onto the page for now (TODO: Make this an external file.. do it in a way that works for both AssetsManager and for MediaWiki in general)
		$wgOut->addHTML("
			<style type='text/css'>
				h3{
					font-weight:bold;
				}
				table{
					width:100%;
				}
				td{
					vertical-align:top;
					width: 50%;
				}
				td.form{
					background-color:#eee;
				}
				td.form input[type=text]{
					width:95%;
				}
				td.results{
					background-color:#ccc;
				}
			</style>
		");

		// Show the header
		$wgOut->addHTML( "<h2>" . wfMsg('categoryintersection-header-title') . "</h2>" );
		$docLink = "<a href='".self::$DOCUMENTATION_URL."'>". wfMsg('categoryintersection-docs-linktext') ."</a>";
		$wgOut->addHTML( wfMsg('categoryintersection-header-body', $docLink) );
		$wgOut->addHTML( "<br/><br/>" );

		$wgOut->addHTML("<table><tr><td class='form'>"); // oh snap, tables for layout!
			$this->showForm( $wgOut );
		$wgOut->addHTML("</td><td class='results'>");
			$this->showResults( $wgOut );
		$wgOut->addHTML("</td></tr></table>\n");

		// Show a footer w/links to more info and some example queries
		$this->showFooter( $wgOut );

		wfProfileOut( __METHOD__ );
	} // end execute()
	
	/**
	 * Prints a form to the OutputPage provided, which will alow the user to make a query for multiple categories.
	 *
	 * @param out - OutputPage to add HTML to.
	 */
	private function showForm($out){
		global $wgRequest;
		wfProfileIn( __METHOD__ );

		$html = "";
		$html .= "<h3>". wfMsg('categoryintersection-form-title') ."</h3>";

		$html .= "<form name='categoryintersection' action='' method='GET'>\n";

			// Display a couple of rows
			$html .= $this->getHtmlForCategoryBox(1);
			$html .= wfMsg('categoryintersection-and') . "<br/>\n";
			$html .= $this->getHtmlForCategoryBox(2);

// TODO: Display a button to make more rows....
// TODO: Display a button to make more rows....

			// Display limit (default to this->defaultLimit)
			$html .= wfMsg('categoryintersection-limit') . " <select name='limit'>";
			$limit = $wgRequest->getVal('limit', $this->defaultLimit);
			$limits = array(10, 25, 50, 100);
			foreach($limits as $currLimit){
				$selected = (($currLimit == $limit)? " selected='selected'" : "");
				$html .= "\t<option value='$currLimit'$selected>$currLimit</option>\n";
			}
			$html .= "</select><br/>\n";

			// Display submit button
			$html .= "<input class='wikia-button' type='submit' name='wpSubmit' value='". wfMsg('categoryintersection-form-submit') ."'/>\n";

		$html .= "</form>\n";

		$out->addHTML($html);
		
		wfProfileOut( __METHOD__ );
	} // end showForm()
	
	/**
	 * @param num - a sequential number for the category box so that a bunch of them can be made.  The first should be "1"
	 * @return a string which contains HTML for a text field for a category.  Will be pre-populated with a value if this page
	 * is a form submission
	 */
	private function getHtmlForCategoryBox($num){
		global $wgRequest;
		$id = self::$CAT_PREFIX . "$num";
		$value = $wgRequest->getVal($id);
		return "<input type='text' name='$id' value='$value' placeholder='".self::$CATEGORY_NS_PREFIX."...'/><br/>\n";
	} // end getHtmlForCategoryBox()

	/**
	 * Prints results to the OutputPage provided, if there was a query for an intersection of categories. Otherwise
	 * prints some placeholder text.
	 *
	 * @param out - OutputPage to add HTML to.
	 */
	private function showResults($out){
		wfProfileIn( __METHOD__ );
		global $wgRequest, $wgServer, $wgScriptPath;

		$html = "";
		$html .= "<div class='ci_results'>\n";
		
			$html .= "<h3>". wfMsg('categoryintersection-results-title') ."</h3>\n";

// TODO: Summarize the results here (what was searched for, the limit, and the number of results found (because there may be some missing if the limit was less than the total number of possible matches).
// TODO: Summarize the results here (what was searched for, the limit, and the number of results found (because there may be some missing if the limit was less than the total number of possible matches).

			$submit = $wgRequest->getVal('wpSubmit');
			if(!empty($submit)){
				$limit = $wgRequest->getVal('limit', $this->defaultLimit);

				$categories = array();
				$keys = array_keys($_GET);
				foreach($keys as $key){
					if(startsWith($key, self::$CAT_PREFIX)){
						$cat = $wgRequest->getVal($key);
						if(!empty($cat)){
							$categories[] = $cat;

							if(!startsWith($cat, self::$CATEGORY_NS_PREFIX)){
								$html .= "<em>Warning: \"$cat\" does not start with \"{self::$CATEGORY_NS_PREFIX}\".</em><br/>\n";
							}
						}
					}
				}

				// Use the API to get actual results.
				$apiParams = array(
					'action' => 'query',
					'list' => 'categoryintersection',
					'limit' => $limit,
					'categories' => implode("|", $categories)
				);
				$apiData = ApiService::call($apiParams);
				if (empty($apiData)) {
					$html .= "<em>". wfMsg('categoryintersection-noresults'). "</em>\n";
				} else {
					$articles = $apiData['query']['categoryintersection'];
					print "<ul>\n";
					foreach($articles as $articleData){
						$title = $articleData['title'];
						$titleObj = Title::newFromText($title);
						$html .= "<li><a href='".$titleObj->getFullURL()."'>$title</a></li>\n";
					}
					print "</ul>\n";
				}

				// Display the URL that could be used to make that API call.
				$apiUrl = $wgServer.$wgScriptPath."/api.php?".http_build_query($apiParams);
				$apiUrl = strtr($apiUrl, array( // several of the very commonly used characters shouldn't be encoded (less confusing URL this way)
								"%3A" => ":",
								"%2F" => "/",
								"%7C" => "|"
							));
				$html .= "<br/><strong>" . wfMsg('categoryintersection-query-used') . "</strong><br/>\n";
				$html .= "<a href='$apiUrl'>$apiUrl</a>\n";
			}

		$html .= "</div>\n";

		$out->addHTML( $html );

		wfProfileOut( __METHOD__ );
	} // end showResults()
	
	/**
	 * Prints a footer to the OutputPage provided, which contains example queries to get people thinking and show them some options.
	 * In addition, links to the documentation.
	 *
	 * @param out - OutputPage to add HTML to.
	 */
	private function showFooter($out){
		wfProfileIn( __METHOD__ );
		global $wgServer, $wgScriptPath;

		$html = "";
		$html .= "<h2>" . wfMsg('categoryintersection-footer-title') . "</h2>";
		$html .= wfMsg('categoryintersection-footer-body') . "<br/>\n";

// TODO: Think of some other cool examples (with more than 2 dimensions)
// TODO: Think of some other cool examples (with more than 2 dimensions)
		$examples = array(
			array(
				"Category:Artists_S",
				"Category:Hometown/Sweden/Stockholm",
			),
			array(
				"Category:Artist",
				"Category:Hometown/United_States/Pennsylvania/Pittsburgh",
			),
			array(
				"Category:Hometown/Germany/North_Rhine-Westphalia",
				"Category:Genre/Rock"
			),
			array(
				"Category:Artists_S",
				"Category:Genre/Rock"
			),
			array(
				"Category:Album",
				"Category:Genre/Nerdcore_Hip_Hop"
			),
			array(
				"Category:Language/Simlish"
			),
			array(
				"Category:Label/Ultra_Records",
				"Category:Hometown/Canada"
			),
			array(
				"Category:Genre/Hip_Hop",
				"Category:Hometown/United_States/California"
			),
		);

		// Format and output the examples.
		$html .= "<ul>\n";
		foreach($examples as $exampleCategories){
			$readableCats = array();
			$queryParams = array(
				"wpSubmit" => "Example" // so that the page can detect that there was a request for API data
			);
			$catNum = 1;
			foreach($exampleCategories as $cat){
				$queryParams[self::$CAT_PREFIX . $catNum++] = $cat;
				if(startsWith($cat, self::$CATEGORY_NS_PREFIX)){
					$readableCats[] = substr($cat, strlen(self::$CATEGORY_NS_PREFIX));
				} else {
					$readableCats[] = $cat;
				}
			}

			// Create URL
			$baseUrl = $this->getTitle()->getFullURL();
			$baseUrl .= ((strpos($baseUrl, "?")===false) ? "?" : "&" ); // first delimiter depends on whether there has been a '?' in the url already
			$link = $baseUrl . http_build_query($queryParams);

			// Create readable text
			$html .= "<li><a href='$link'>(". implode($readableCats, "), (") .")</a></li>\n";
		}
		$html .= "</ul>\n";

		$out->addHTML( $html );

		wfProfileOut( __METHOD__ );
	} // end showFooter()

} // end class SpecialCategoryIntersection
