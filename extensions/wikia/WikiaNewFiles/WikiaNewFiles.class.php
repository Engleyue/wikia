<?php
/**
 * @file
 * @ingroup SpecialPage
 * Extends the IncludeableSpecialPage to override some of the header formatting
 *
 */

class WikiaNewFiles extends IncludableSpecialPage {
	function execute( $par ) {
		global $wgExtensionMessagesFiles;
	
		wfLoadExtensionMessages("WikiaNewFiles");

		$this->name('WikiaNewFiles');
		$this->setHeaders();

		wfSpecialWikiaNewFiles($par, $this);
	}
}
