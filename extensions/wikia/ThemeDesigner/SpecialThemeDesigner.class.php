<?php

class SpecialThemeDesigner extends UnlistedSpecialPage {

	public function __construct() {
		parent::__construct( 'ThemeDesigner', 'themedesigner' );
	}

	public function execute() {
		wfProfileIn( __METHOD__ );
		global $wgUser;

		// check rights
		// @FIXME when we're out of beta editinterface needs to be removed and themedesgner set to true for sysops
		if ( !$wgUser->isAllowed( 'themedesigner' ) ) {
			$this->displayRestrictionError();
			wfProfileOut( __METHOD__ );
			return;
		}

		Wikia::setVar( 'OasisEntryModuleName', 'ThemeDesigner' );

		wfLoadExtensionMessages( 'ThemeDesigner' );

		wfProfileOut( __METHOD__ );
	}
}
