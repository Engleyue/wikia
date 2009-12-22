<?php
class NewWikiBuilder extends SpecialPage {
	function __construct() {
		parent::__construct( 'NewWikiBuilder' , 'newwikibuilder');
		wfLoadExtensionMessages('NewWikiBuilder');
	}
 
	function execute( $par ) {
		global $wgRequest, $wgOut, $wgUser, $wgAdminSkin, $wgLanguageCode, $NWBmessages;

		if ( !$this->userCanExecute($wgUser) ) {
			$this->displayRestrictionError();
			return;
		}

		// Default the skin
		if (empty($wgAdminSkin)){
			$wgAdminSkin = "monaco-sapphire";
		}
 
		// Set up the messages variable for other languages
		// Don't bother with user language here and just go with content language
		$this->lang = $wgLanguageCode;
		foreach($NWBmessages["en"] as $name => $value) {
			// wfMsgForContent is used here to take into account the messaging.wikia layer
			$NWBmessages[$this->lang][$name] = wfMsgForContent($name);
		}

		$this->setHeaders();
 
		// output only template content
		$wgOut->setArticleBodyOnly(true);
	
		// Put the html in a separate file 
		ob_start();
		include dirname(__FILE__) . '/NewWikiBuilder.html.php';
 
		// Output
		$wgOut->addHTML( ob_get_clean() );

	}
}
