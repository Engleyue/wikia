<?php
/**
 * Modified edit page for Oasis
 *
 * @author Maciej Brencz
 */

class EditPageModule extends Module {

	/**
	 * Disables edit form when in read-only mode (RT #85688)
	 */
	public static function onAlternateEdit($editPage) {
		global $wgOut, $wgTitle;
		wfProfileIn(__METHOD__);

		// disable edit form when in read-only mode
		if (wfReadOnly()) {
			$wgOut->setPageTitle(wfMsg('editing', $wgTitle->getPrefixedText()));
			$wgOut->addHtml(
				'<div id="mw-read-only-warning">'.
				wfMsg('oasis-editpage-readonlywarning', wfReadOnlyReason()).
				'</div>');

			wfDebug(__METHOD__ . ": edit form disabled because read-only mode is on\n");
			wfProfileOut(__METHOD__);
			return false;
		}

		wfProfileOut(__METHOD__);
		return true;
	}

	/**
	 * Loads YUI on edit pages
	 */
	public static function onShowEditFormInitial($editPage) {
		global $wgOut, $wgJsMimeType;
		wfProfileIn(__METHOD__);

		// macbre: load YUI on edit page (it's always loaded using $.loadYUI)
		// PLB has problems with $.loadYUI not working correctly in Firefox (callback is fired to early)
		$staticChute = new StaticChute('js');
		$staticChute->useLocalChuteUrl();

		$wgOut->addScript($staticChute->getChuteHtmlForPackage('yui'));

		wfProfileOut(__METHOD__);
		return true;
	}
}