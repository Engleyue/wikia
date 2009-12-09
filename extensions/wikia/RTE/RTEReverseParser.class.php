<?php

class RTEReverseParser {

	// DOMdocument used to process HTML
	private $dom;

	// lists handling
	private $listLevel;
	private $listBullets;
	private $listIndent;

	function __construct() {
		$this->dom = new DOMdocument();

		// enable libxml errors and allow user to fetch error information as needed
		libxml_use_internal_errors(true);
	}

	/**
	 * Converts given HTML into wikitext using extra information stored in meta data
	 */
	public function parse($html, $data = array()) {
		wfProfileIn(__METHOD__);

		$out = '';

		if(is_string($html) && $html != '') {
			// apply pre-parse HTML fixes
			$html = strtr($html, array(
				// fix IE bug with &nbsp; being added add the end of HTML
				'<p><br _rte_bogus="true" />&nbsp;</p>' => '',

				// fix &nbsp; entity b0rken by CK
				"\xC2\xA0" => '&nbsp;',
			));

			// try to parse fixed HTML as XML
			$bodyNode = $this->parseToDOM($html);

			// now we should have properly parsed HTML
			if (!empty($bodyNode)) {
				RTE::log('XML (as seen by DOM)' ,$this->dom->saveXML());

				// do recursive reverse parsing
				$out = $this->parseNode($bodyNode);

				// apply post-parse modifications

				// handle HTML entities (&lt; -> <)
				$out = html_entity_decode($out);

				// replace HTML entities markers with entities (\x7f-ENTITY-lt-\x7f -> &lt;)
				$out = preg_replace("%\x7f-ENTITY-(#?[\w\d]+)-\x7f%", '&\1;', $out);

				// fix &nbsp; entity added by MW parser
				$out = strtr($out, array(
					"\xA0" => ' ',
				));

				// trim trailing whitespaces
				$out = rtrim($out, "\n ");

				RTE::log('wikitext', $out);
			}
			else {
				RTE::log('HTML parsing failed!');
				$out = '';
			}
		}

		wfProfileOut(__METHOD__);
		return $out;
	}

	/**
	 * Parses given HTML into DOM tree (using XML/HTML parser)
	 */
	private function parseToDOM($html, $parseAsXML = true) {
		wfProfileIn(__METHOD__);

		$ret = false;

		wfSuppressWarnings();

		if ($parseAsXML) {
			// form proper XML string
			$html = strtr($html, array('&' => '&amp;'));

			$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><body>{$html}</body>";

			// try to parse as XML
			if($this->dom->loadXML($xml)) {
				// parsing successful
				$ret = $this->dom->getElementsByTagName('body')->item(0);
			}
			else {
				$this->handleParseErrors(__METHOD__);
			}
		}
		else {
			// form proper HTML string
			$html = "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/></head><body>{$html}</body></html>";

			// try to parse as HTML
			if($this->dom->loadHTML($html)) {
				$ret = $this->dom->getElementsByTagName('body')->item(0);
			}
		}

		wfRestoreWarnings();

		wfProfileOut(__METHOD__);

		// return <body> node or false if XML parsing failed
		return $ret;
	}

	/**
	 * Handle XML/HTML parsing error errors
	 */
	private function handleParseErrors($method) {
		foreach (libxml_get_errors() as $err) {
			RTE::log("{$method} - XML parsing error '". trim($err->message) ."' (line {$err->line}, col {$err->column})");
		}

		 libxml_clear_errors();
	}

	/**
	 * Recursively parses given DOM node
	 */
	private function parseNode($node, $level = 0) {
		wfProfileIn(__METHOD__);

		RTE::log('node ' . str_repeat('.', $level) . $node->nodeName);

		$childOut = '';

		// parse child nodes
		if($node->hasChildNodes()) {
			$nodes = $node->childNodes;

			// handle lists (update stack)
			if (self::isListNode($node)) {
				$this->handleListOpen($node);
			}

			// recursively parse child nodes
			for($n = 0; $n < $nodes->length; $n++) {
				$childOut .= $this->parseNode($nodes->item($n), $level+1);
			}
		}

		// parse current node
		$out = '';

		$textContent = ($childOut != '') ? $childOut : $node->textContent;

		// handle HTML nodes
		if($node->nodeType == XML_ELEMENT_NODE) {
			$out = $this->handleTag($node, $textContent);
		}
		// handle comments
		else if($node->nodeType == XML_COMMENT_NODE) {
			$out = $this->handleComment($node);
		}
		// handle text
		else if($node->nodeType == XML_TEXT_NODE) {
			$out = $this->handleText($node, $textContent);
		}

		// close lists (update stack)
		if($node->hasChildNodes()) {
			if (self::isListNode($node)) {
				$this->handleListClose($node);
			}
		}

		wfProfileOut(__METHOD__);
		return $out;
	}

	/**
	 * Handles HTML tags
	 */
	private function handleTag($node, $textContent) {
		$out = '';

		// handle placeholders
		if ($node->hasAttribute('_rte_placeholder')) {
			$out = $this->handlePlaceholder($node, $textContent);
			return $out;
		}
		// handle nodes with wasHTML
		else if ($node->hasAttribute('_rte_washtml')) {
			$out = $this->handleHtml($node, $textContent);
		}
		// handle nodes wrapping HTML entities
		else if ($node->hasAttribute('_rte_entity')) {
			$out = $this->handleEntity($node, $textContent);
		}
		// handle other elements
		else switch($node->nodeName) {
			case 'body':
				$out = $textContent;
				break;

			// paragraphs
			case 'p':
				$out = $this->handleParagraph($node, $textContent);
				break;

			// line breaks
			case 'br':
				$out = $this->handleLineBreaks($node);
				break;

			// links
			case 'a':
				$out = $this->handleLink($node, $textContent);
				break;

			// bold / italics
			case 'b':
			case 'i':

			// strike/underline
			case 'strike':
			case 'u':

			// indexes
			case 'sub':
			case 'sup':
				$out = $this->handleFormatting($node, $textContent);
				break;

			// preformatted text
			case 'pre':
				$out = $this->handlePre($node, $textContent);
				break;

			// lists ...
			case 'ul':
			case 'ol':
			case 'li':
			// ... and definition lists
			case 'dl':
			case 'dt':
			case 'dd':
				$out = $this->handleListItem($node, $textContent);
				break;

			// headers
			case 'h1':
			case 'h2':
			case 'h3':
			case 'h4':
			case 'h5':
			case 'h6':
				$out = $this->handleHeader($node, $textContent);
				break;

			// tables
			case 'tbody':
				$out = $textContent;
				break;

			case 'table':
			case 'caption':
			case 'tr':
			case 'th':
			case 'td':
				$out = $this->handleTable($node, $textContent);
				break;

			// images
			case 'img':
				$out = $this->handleImage($node, $textContent);
				break;

			// rest of tags
			default:
				$out = $textContent;
				break;
		}

		// support _rte_empty_lines_before attribute
		if (self::getEmptyLinesBefore($node) % 2 == 1) {
			$out = "\n{$out}";
		}

		return $out;
	}

	/**
	 * Handle RTE placeholders
	 */
	private function handlePlaceholder($node, $textContent) {
		// get meta data
		$data = self::getRTEData($node);

		return $data['wikitext'];
	}

	/**
	 * Handle HTML nodes
	 */
	private function handleHtml($node, $textContent) {
		$prefix = $suffix = $beforeText = $beforeClose = '';

		// add line break
		if ($node->hasAttribute('_rte_line_start')) {
			$parentWasHtml = !empty($node->parentNode) && ($node->parentNode->hasAttribute('_rte_washtml'));

			if ($parentWasHtml) {
				// nested HTML
				$prefix = "\n";
			}
			else {
				// only add line break if there's no empty line before
				if ( self::getEmptyLinesBefore($node) == 0 && !self::isFirstChild($node) ) {
					$prefix = "\n";
				}

				// if node follows LINE_BREAK comment, don't add line break
				if (self::previousCommentIs($node, 'LINE_BREAK')) {
					$prefix = '';
				}

				// if first child of this HTML block have _rte_line_start, add line break before closing tag
				if ( !empty($node->firstChild) && ($node->firstChild->nodeType == XML_ELEMENT_NODE) ) {
					if ($node->firstChild->hasAttribute('_rte_line_start')) {
						$beforeClose = "\n";
					}
				}

				// always end HTML block with line break
				$suffix = "\n";
			}
		}

		// wasHTML nodes inside paragraphs - don't add any line breaks
		if (self::isChildOf($node, 'p')) {
			$suffix = $prefix = '';
		}

		// fix for first paragraph / pre / table / .. inside <div>
		if ( ($node->nodeName == 'div') && (self::firstChildIs($node, array('p', 'pre', 'table', 'ul', 'ol', 'dl'))) ) {
			$beforeText = "\n";
		}

		// generate HTML
		$attr = self::getAttributesStr($node);

		$isShort = in_array($node->nodeName, array('br', 'hr'));
		if ($isShort) {
			if ($attr == '') {
				// render br with no attributes as <br />
				$attr = ' ';
			}
			$out = "{$prefix}<{$node->nodeName}{$attr}/>";
		}
		else {
			$out = "{$prefix}<{$node->nodeName}{$attr}>{$beforeText}{$textContent}{$beforeClose}</{$node->nodeName}>{$suffix}";
		}

		return $out;
	}

	/**
	 * Handle HTML entities
	 */
	private function handleEntity($node, $textContent) {
		$entity = $node->getAttribute('_rte_entity');

		RTE::log(__METHOD__, $entity);

		// compare stored entity with text content
		$matches = ( html_entity_decode($textContent) == html_entity_decode("&{$entity};") );

		RTE::log(__METHOD__ . '::compare', $matches ? 'true' : 'false');

		if ($matches) {
			// return entity marker
			$out = self::getEntityMarker($entity);
		}
		else {
			$out = $textContent;
		}

		return $out;
	}

	/*
	 * Returns marker for given entity
	 */
	private static function getEntityMarker($entity) {
		$out = "\x7f-ENTITY-{$entity}-\x7f";

		return $out;
	}

	/**
	 * Handles comments
	 */
	private function handleComment($node) {
		$out = '';

		// (try to) parse special comments
		$comment = self::parseComment($node);

		if (empty($comment)) {
			return '';
		}

		//RTE::log(__METHOD__, $comment);

		// handle <!-- RTE_LINE_BREAK --> comment
		// used for following wikitext (line breaks within single paragraph)
		// 123
		// 456
		// 789
		//
		// ignore cases like: <p><br /><!-- RTE_LINE_BREAK -->foo</p>
		if ($comment['type'] == 'LINE_BREAK') {
			$spaces = str_repeat(' ', intval($comment['data']['spaces']));
			$out = "{$spaces}\n";
		}

		return $out;
	}

	/**
	 * Handles text nodes
	 */
	private function handleText($node, $textContent) {
		$out = $textContent;

		// remove trailing space from text node which is followed by <!-- RTE_LINE_BREAK --> comment
		if (self::nextCommentIs($node, 'LINE_BREAK')) {
			$out = substr($out, 0, -1);
		}

		// fix for tables created in CK
		// <td>&nbsp;</td> -> empty cell
		if ( ($out == '&nbsp;') && (self::isChildOf($node, 'td')) ) {
			$out = '';
		}

		// remmove spaces from the beginning of paragraph (people use spaces to centre text)
		if (self::isChildOf($node, 'p') && self::isFirstChild($node)) {
			// grab &nbsp; and "soft" spaces
			preg_match('%^(&nbsp;)+[ ]?%', $out, $matches);

			if (!empty($matches)) {
				RTE::log(__METHOD__ . '::removeSpaces', $matches[0]);

				// remove those spaces
				$out = substr($out, strlen($matches[0]));
			}
		}

		$out = $this->fixForTableCell($node, $out);

		return $out;
	}

	/**
	 * Handle paragraphs
	 */
	private function handleParagraph($node, $textContent) {
		// handle empty paragraphs (<p>&nbsp;</p>)
		// empty paragraphs added in CK / already existing in wikitext
		if (($textContent == self::getEntityMarker('nbsp')) || ($textContent == '&nbsp;')) {
			$textContent = "\n";
		}

		// handle paragraphs alignment and indentation
		if ($node->hasAttribute('style')) {
			// parse "text-align" style attribute
			$align = self::getCssProperty($node, 'text-align');
			if (!empty($align)) {
				// wrap text content inside HTML
				$textContent = "<p style=\"text-align:{$align}\">{$textContent}</p>";
			}

			// parse "margin-left" style attribute
			$marginLeft = self::getCssProperty($node, 'margin-left');
			if (!empty($marginLeft)) {
				$textContent = str_repeat(':', $marginLeft / 40) . " {$textContent}";
			}
		}

		$out = "{$textContent}\n";

		// if next paragraph has been pasted into CK add extra newline
		if (self::nextSiblingIs($node, 'p') && self::isPasted($node->nextSibling)) {
			$out = "{$out}\n";
		}

		// this node was added in CK
		else if ( self::isNewNode($node)) {
			// previous element is paragraph
			if (self::previousSiblingIs($node, 'p') && !self::isNewNode($node->previousSibling)) {
				$out = "\n{$out}";
			}

			// next element is (not pasted) paragraph
			if (self::nextSiblingIs($node, 'p') && self::isNewNode($node->nextSibling)) {
				$out = "{$out}\n";
			}
		}

		$out = $this->fixForTableCell($node, $out);
		return $out;
	}

	/**
	 * Handle <br /> tags
	 */
	private function handleLineBreaks($node) {
		$out = "\n";

		// handle <br /> added by Shift+Enter
		if ($node->hasAttribute('_rte_shift_enter')) {
			$out = "<br />\n";
		}

		// don't break lists added in CK
		if ( self::isChildOf($node, 'li') && self::nextSiblingIs($node, array('ul', 'ol')) ) {
			$out = '';
		}

		return $out;
	}

	/**
	 * Handle links
	 *
	 * @see http://www.mediawiki.org/wiki/Help:Links
	 */
	private function handleLink($node, $textContent) {
		// get RTE data
		$data = self::getRTEData($node);

		// handle pasted links
		if (empty($data)) {
			$data = array(
				'type' => 'pasted',
				'link' => $node->getAttribute('href'),
			);
		}

		// generate wikitext
		$out = '';

		switch($data['type']) {
			case 'internal':
				// following wikitext optimization will be performed:
				//
				// [[foo|foo]] -> [[foo]]
				// [[foo|foos]] -> [[foo]]s

				// start link wikitext
				$out = "[[";

				// handle [[:Category:foo]]
				if (isset($data['noforce']) && $data['noforce'] == false) {
					$out .= ':';
				}

				// [[<current_page_name>/foo|/foo]] -> [[/foo]]
				global $wgTitle;
				$pageName = $wgTitle->getPrefixedText();
				if ($data['link'] == $pageName . $textContent) {
					$data['link'] = $textContent;
				}

				$out .= $data['link'];

				// check for possible trail
				$trail = false;

				// [[foo|foos]] -> [[foo]]s
				if ( (strlen($textContent) > strlen($data['link'])) ) {
					if (substr($textContent, 0, strlen($data['link'])) == $data['link']) {
						$possibleTrail = substr($textContent, strlen($data['link']));

						// check against trail valid characters regexp
						if (preg_match(self::getTrailRegex(), $possibleTrail)) {
							$trail = $possibleTrail;
						}
					}
				}

				// link description after pipe
				if ( ($trail === false) && ($data['link'] != $textContent) ) {
					$out .= "|{$textContent}";
				}

				// close link wikitext + trail
				$out .= "]]{$trail}";
				break;

			case 'external':
				// optimize external links
				// [http://wp.pl http://wp.pl] -> http://wp.pl
				if ($textContent == $data['link']) {
					$out = $data['link'];
					break;
				}

				// handle autonumbered links
				$autonumber = false;
				if ( isset($data['linktype']) && ($data['linktype'] == 'autonumber') ) {
					// validate text content - should be [x]
					if (preg_match("%\[(\d+)\]%", $textContent)) {
						// yes, this is autonumbered external link
						$autonumber = true;
					}
				}

				$out = "[{$data['link']}";

				if (!$autonumber) {
					// add link description
					$out .= " {$textContent}";
				}

				$out .= ']';
				break;

			case 'external-raw':
				// validate textContent (should be valid URL)
				$regex = '%' . self::getUrlProtocols() . '%';

				if (preg_match($regex, $textContent)) {
					// let's return it as raw link
					$out = $textContent;
				}
				else {
					// URL text content has changed -> use external link like [http://wp.pl link]
					$out = "[{$data['link']} {$textContent}]";
				}

				break;

			case 'pasted':
				// validate link (should be valid URL)
				$regex = '%' . self::getUrlProtocols() . '%';

				if (preg_match($regex, $data['link'])) {
					// optimize wikisyntax
					if ($data['link'] == $textContent) {
						$out = $data['link'];
					}
					else {
						$out = "[{$data['link']} {$textContent}]";
					}
				}
				else {
					// just return link content
					$out = $textContent;
				}

				break;
		}

		return $out;
	}

	/**
	 * Handle formattings (bold / italic / underline / strike / sub / sup)
	 *
	 * This is "reverse" implementation of code from Parser handling bolds and italics
	 */
	private function handleFormatting($node, $textContent) {
		switch($node->nodeName) {
			case 'u':
			case 'strike':
			case 'sup':
			case 'sub':
				$attributes = self::getAttributesStr($node);

				$out = "<{$node->nodeName}{$attributes}>{$textContent}</{$node->nodeName}>";
				return $out;

			// 1 '</b><i><b>' => '<i>'
			// 2 '</i><b><i>' => '<b>'
			// 3 '</b></i><b>' => '</i>'
			// 4 '</i></b><i>' => '</b>'
			case 'i':
				$open = $close = "''";
				break;
			case 'b':
				$open = $close = "'''";
				break;
		}

		// A) opening tags
		// 1, 2
		if($node->parentNode && $node->parentNode->previousSibling &&
			$node->isSameNode($node->parentNode->firstChild) &&
			in_array($node->parentNode->nodeName, array('i','b')) &&
			$node->parentNode->nodeName != $node->nodeName &&
			$node->parentNode->previousSibling->nodeName == $node->nodeName) {
				// don't open bold (1) / italic (2)
				$open = '';
		}

		// 3, 4
		if($node->previousSibling && $node->previousSibling->hasChildNodes() &&
			in_array($node->previousSibling->nodeName, array('i','b')) &&
			$node->previousSibling->nodeName != $node->nodeName &&
			$node->previousSibling->lastChild->nodeName == $node->nodeName) {
				// don't open bold (3) / italic (4)
				$open = '';
		}

		// B) closing tags
		// 1, 2
		if($node->nextSibling && $node->nextSibling->hasChildNodes() &&
			in_array($node->nextSibling->nodeName, array('i','b')) &&
			$node->nextSibling->nodeName != $node->nodeName &&
			$node->nextSibling->firstChild->nodeName == $node->nodeName) {
				// don't close bold (1) / italic (2)
				$close = '';
		}

		// 3, 4
		if($node->parentNode && $node->parentNode->nextSibling &&
			$node->isSameNode($node->parentNode->lastChild) &&
			in_array($node->parentNode->nodeName, array('i','b')) &&
			$node->parentNode->nodeName != $node->nodeName &&
			$node->parentNode->nextSibling->nodeName == $node->nodeName) {
				// don't close bold (3) / italic (4)
				$close = '';
		}

		$out = "{$open}{$textContent}{$close}";

		return $out;
	}

	/**
	 * Handle preformatted text
	 */
	private function handlePre($node, $textContent) {
		$textContent = rtrim($textContent);

		// add spaces after every line break
		$textContent = str_replace("\n", "\n ", $textContent);

		$out = " {$textContent}\n";

		$out = $this->fixForTableCell($node, $out);

		return $out;
	}

	/**
	 * Handle list item
	 */
	private function handleListItem($node, $textContent) {
		$out = '';

		switch($node->nodeName) {
			case 'ul':
			case 'ol':
				$out = rtrim($textContent, "\n");

				// this list (789) is nested list
				// <ul><li>abc<ul><li>789</li></ul></li></ul>
				if ( self::isChildOf($node, 'li') && !self::isFirstChild($node) ) {
					// check wrapping list node
					$parentListType = $node->parentNode->parentNode->nodeName;
					$parentListIsFirstChild = self::isFirstChild($node->parentNode->parentNode);

					// only add newline if current list is wrapped within same type
					if ( $parentListIsFirstChild || ($parentListType == $node->nodeName) ) {
						$out = "\n{$out}";
					}
				}

				$out = "{$out}\n";

				$out = $this->fixForTableCell($node, $out);
				break;

			case 'li':
				$textContent = rtrim( self::addSpaces($node, $textContent) );

				// check for <ul><li><ul><li>789</li></ul></li></ul>
				// this is list item with nested list inside
				if ( self::startsWithListWikitext($textContent) ) {
					$out = "{$textContent}\n";
				}
				else {
					$out = "{$this->listBullets}{$textContent}\n";
				}
				break;

			// definition lists
			case 'dl':
				$out = rtrim($textContent, "\n");

				// handle nested intended list
				// :1
				// ::2
				// and
				// ::*: foo
				// :::# bar
				if ( self::isChildOf($node, 'dd') && !self::isFirstChild($node)
					&& !self::isListNode($node->parentNode->firstChild) ) {
					$out = "\n{$out}";
				}

				// fix for lists like
				// *: a
				// * b
				$isIntended = (strspn($this->listBullets, ':') > 0);
				if ( self::isChildOf($node, 'li') && !$isIntended ) {
					$out = "\n{$out}";
				}
				else {
					$out = "{$out}\n";
				}

				$out = $this->fixForTableCell($node, $out);
				break;

			case 'dt':
			case 'dd':
				$textContent = rtrim( self::addSpaces($node, $textContent) );

				// fix for single ":::: foo" gaining extra :
				if (strspn($textContent, ':') > 0) {
					$out = "{$textContent}\n";
				}
				// check for <dl><dd><ul><li>1</li></ul></dd></dl>
				// this is intended list item with nested list inside
				else if ( self::startsWithListWikitext($textContent) ) {
					$out = "{$textContent}\n";
				}
				else {
					$out = "{$this->listBullets}{$textContent}\n";
				}

				break;
		}

		return $out;
	}

	/**
	 * Handles lists opening
	 */
	private function handleListOpen($node) {
		if ($node->hasAttribute('_rte_washtml')) {
			return;
		}

		// build bullets stack
		switch ($node->nodeName) {
			case 'ul':
				$bullet = '*';
				break;
			case 'ol':
				$bullet = '#';
				break;
			case 'dd':
				$bullet = ':';
				break;
			case 'dt':
				$bullet = ';';
				break;
		}

		// update lists stack ("push")
		$this->listLevel++;
		$this->listBullets .= $bullet;

		//RTE::log("list level #{$this->listLevel}: {$this->listBullets}");
	}

	/**
	 * Handles lists closing
	 */
	private function handleListClose($node) {
		if ($node->hasAttribute('_rte_washtml')) {
			return;
		}

		// update lists stack ("pop")
		$this->listLevel--;
		$this->listBullets = substr($this->listBullets, 0, -1);
	}

	/**
	 * Handle header
	 */
	private function handleHeader($node, $textContent) {
		$level = str_repeat('=', intval($node->nodeName{1}));

		$textContent = self::addSpaces($node, $textContent);

		$out = "{$level}{$textContent}{$level}\n";

		$out = $this->fixForTableCell($node, $out);

		return $out;
	}

	/**
	 * Handle row/cell/table
	 */
	private function handleTable($node, $textContent) {
		$out = '';

		// get node attributes
		$attributes = self::getAttributesStr($node);

		// support syntax using || and !!
		$shortRowMarkup = $node->hasAttribute('_rte_short_row_markup');

		switch($node->nodeName) {
			case 'table':
				// remove row line breaks
				$textContent = trim($textContent, "\n");

				// don't render empty tables (<table ... />)
				if ($textContent == '') {
					RTE::log(__METHOD__, 'empty table found');
					$out = '';
					break;
				}

				$out = "{|{$attributes}\n{$textContent}\n|}\n";

				$out = $this->fixForTableCell($node, $out);
				break;

			case 'caption':
				// add pipe after attributes
				if ($attributes != '') {
					$attributes .= '|';
				}

				$out = "|+{$attributes}{$textContent}";
				break;

			case 'tr':
				// remove cell line breaks
				$textContent = trim($textContent, "\n");

				// don't add |- for first row without attributes
				if (!self::isFirstChild($node) || $attributes != '') {
					$out = "\n|-{$attributes}";
				}

				$out .= "\n{$textContent}";
				break;

			case 'th':
			case 'td':
				$char = ($node->nodeName == 'td') ? '|' : '!';

				// support cells separated using double pipe
				$out = $shortRowMarkup ? "{$char}{$char}" : "\n{$char}";

				// add pipe after attributes
				if ($attributes != '') {
					$attributes .= '|';
				}

				// remove trailing line breaks
				$textContent = rtrim(self::addSpaces($node, $textContent), "\n");

				$out .= "{$attributes}{$textContent}";
				break;
		}

		return $out;
	}

	/**
	 * Handle image
	 *
	 * @see http://www.mediawiki.org/wiki/Images
	 */
	private function handleImage($node, $textContent) {
		// get RTE data
		$data =self::getRTEData($node);

		// TODO: try to generate wikitext based on data
		$out = $data['wikitext'];

		return $out;
	}

	/**
	 * Adds extra line break before/after given element being first child of table cell (td/th)
	 */
	private function fixForTableCell($node, $out) {
		if ( self::isFirstChild($node) && (self::isChildOf($node, 'td') || self::isChildOf($node, 'th')) ) {
			if ($node->nodeType == XML_ELEMENT_NODE) {
				// for HTML elements add extra line break before
				$out = "\n{$out}";
			}
			else {
				if (!empty($node->nextSibling) && self::isPlaceholder($node->nextSibling)) {
					// {|
					// | a [[Category::a]]
					// |}
				}
				else {
					$out = "{$out}\n";
				}
			}
		}

		return $out;
	}

	/**
	 * Checks if given node is list node
	 */
	private static function isListNode($node) {
		return in_array($node->nodeName, array('ul', 'ol', 'dt', 'dd'));
	}

	/**
	 * Checks if given text starts with list wikisyntax
	 */
	private static function startsWithListWikitext($text) {
		$text = ltrim($text, ' :');

		return ( (strspn($text, '*') > 0) || (strspn($text, '#') > 0) );
	}

	/**
	 * Checks name of previous node
	 */
	private static function previousSiblingIs($node, $nodeName) {
		if (is_string($nodeName)) {
			$nodeName = array($nodeName);
		}

		return ( !empty($node->previousSibling->nodeName) && in_array($node->previousSibling->nodeName, $nodeName) );
	}

	/**
	 * Checks name of next node
	 */
	private static function nextSiblingIs($node, $nodeName) {
		if (is_string($nodeName)) {
			$nodeName = array($nodeName);
		}

		return ( !empty($node->nextSibling->nodeName) && in_array($node->nextSibling->nodeName, $nodeName) );
	}

	/**
	 * Checks name of previous special comment node
	 */
	private static function previousCommentIs($node, $type) {
		if ( !empty($node->previousSibling) && ($node->previousSibling->nodeType == XML_COMMENT_NODE) ) {
			// try to parse the comment
			$comment = self::parseComment($node->previousSibling);

			if ( !empty($comment) && ($comment['type'] == $type) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Checks name of next special comment node
	 */
	private static function nextCommentIs($node, $type) {
		if ( !empty($node->nextSibling) && ($node->nextSibling->nodeType == XML_COMMENT_NODE) ) {
			// try to parse the comment
			$comment = self::parseComment($node->nextSibling);

			if ( !empty($comment) && ($comment['type'] == $type) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Checks if given node is first child of its parent
	 */
	private static function isFirstChild($node) {
		return ( !empty($node->parentNode) && $node->parentNode->firstChild->isSameNode($node) );
	}

	/**
	 * Checks name of parent of given node
	 */
	private static function isChildOf($node, $parentName) {
		return ( !empty($node->parentNode) && $node->parentNode->nodeName == $parentName );
	}

	/**
	 * Checks if first child of given node has given name
	 */
	private static function firstChildIs($node, $nodeName) {
		if (is_string($nodeName)) {
			$nodeName = array($nodeName);
		}
		$firstChild = $node->firstChild;

		return ( !empty($firstChild) && $firstChild->nodeType == XML_ELEMENT_NODE && in_array($firstChild->nodeName, $nodeName) );
	}

	/**
	 * Checks if given node (check is only performed for paragraphs) was pasted
	 */
	private static function isPasted($node) {
		return !$node->hasAttribute('_rte_fromparser') && !$node->hasAttribute('_rte_new_mode');
	}

	/**
	 * Checks if given node was added into CK
	 */
	private static function isNewNode($node) {
		return $node->hasAttribute('_rte_new_node') && (self::getEmptyLinesBefore($node) == 0);
	}

	/**
	 * Checks if given node is placeholder
	 */
	private static function isPlaceholder($node) {
		return $node->hasAttribute('_rte_placeholder');
	}

	/**
	 * Get value of _rte_empty_lines_before attribute
	 */
	private static function getEmptyLinesBefore($node) {
		return intval($node->getAttribute('_rte_empty_lines_before'));
	}

	/**
         * Get string with "HTML" formatted list of node attributes (attributes with _rte prefixes will be removed)
         */
        private static function getAttributesStr($node) {
                if(!$node->hasAttributes()) {
                        return '';
                }

                // replace style attribute with _rte_style
                if ($node->hasAttribute('_rte_style')) {
                        $node->setAttribute('style', $node->getAttribute('_rte_style'));
                }

                // try to get attributes from previously stored attribute (RT #23998)
                $attrStr = $node->getAttribute('_rte_attribs');

                if ( $attrStr != '' ) {
			// decode entities
			$attrStr = str_replace("\x7f", '&quot;', $attrStr);
                        $attrStr = htmlspecialchars_decode($attrStr);
                }
                else {
                        foreach ($node->attributes as $attrName => $attrNode) {
                                // ignore attributes used internally by RTE ("washtml" and with "_rte_" prefix)
                                if ( ($attrName == 'washtml') || (substr($attrName, 0, 5) == '_rte_') ) {
                                        continue;
                                }
                                $attrStr .= ' ' . $attrName . '="' . $attrNode->nodeValue  . '"';
                        }
                }
                return $attrStr;
        }

	/**
	 * Get value of given CSS property
	 */
	private static function getCssProperty($node, $property) {
		if ($node->hasAttribute('style')) {
			$style = $node->getAttribute('style');

			// parse style attribute
			preg_match('%' . preg_quote($property) . ':([^;]+)%', $style, $matches);

			if (!empty($matches[1])) {
				$value = trim($matches[1]);
				RTE::log(__METHOD__, "{$property}: {$value}");

				return $value;
			}
		}

		return false;
	}

	/**
	 * Generate _rte_attribs attribute storing original list of HTML node attributes
	 */
	public static function encodeAttributesStr($attribs) {
		$encoded = Sanitizer::encodeAttribute($attribs);

		// encode &quot; entity (fix for IE)
		$encoded = str_replace('&quot;', "\x7f", $encoded);

		$ret = "_rte_attribs=\"{$encoded}\"";

		return $ret;
	}

	/**
	 * Decode and return data stored in _rte_data attribute
	 */
	private static function getRTEData($node) {
		$value = $node->getAttribute('_rte_data');

		if (!empty($value)) {
			$value = htmlspecialchars_decode($value);
			$value = rawurldecode($value);

			RTE::log(__METHOD__, $value);

			$data = json_decode($value, true);

			if (!empty($data)) {
				RTE::log(__METHOD__, $data);
				return $data;
			}
		}

		return null;
	}

	/**
	 * Encode data to be stored in _rte_data attribute
	 */
	public static function encodeRTEData($data) {
		$encoded = rawurlencode(Wikia::json_encode($data));

		return $encoded;
	}

	/**
	 * Build RTE special comment with extra data in it
	 */
	public static function buildComment($type, $data = null) {
		$data['type'] = $type;
		$data = Wikia::json_encode($data);

		return "<!-- RTE::{$data} -->";
	}

	/**
	 * Parse special comment and returns its name and data
	 */
	private static function parseComment($node) {
		$fields = explode('::', trim($node->data, ' '));

		// validate comment
		if ( (count($fields) != 2) || ($fields[0] != 'RTE') ) {
			return false;
		}

		$data = json_decode($fields[1], true);

		return array(
			'type' => $data['type'],
			'data' => $data,
		);
	}

	/*
	 * Helper caching methods
	 */
	private static function getTrailRegex() {
		static $regex = false;
		if ( $regex === false ) {
			global $wgContLang;
			$regex = $wgContLang->linkTrail();

			RTE::log(__METHOD__, $regex);
		}

		return $regex;
	}

	private static function getUrlProtocols() {
		static $regex = false;
		if ( $regex === false ) {
			$regex = wfUrlProtocols();

			RTE::log(__METHOD__, $regex);
		}

		return $regex;

	}

	/**
	 * Returns textContent of given node with spaces added
	 *
	 * Number of spaces is based on _rte_spaces_after and _rte_spaces_before attributes
	 */
	private static function addSpaces($node, $textContent) {
		$spacesAfter = intval($node->getAttribute('_rte_spaces_after'));
		$spacesBefore = intval($node->getAttribute('_rte_spaces_before'));

		$out = str_repeat(' ', $spacesBefore) . trim($textContent, ' ') . str_repeat(' ', $spacesAfter);

		return $out;
	}

	/**
	 * Returns special comment with number of empty lines before
	 *
	 * Value stored in this comment will be moved to attribute of following HTML element
	 */
	public static function addEmptyLinesBeforeComment($count) {
		RTE::log(__METHOD__, $count);

		return "<!-- RTE_EMPTY_LINES_BEFORE_{$count} -->";
	}
}
