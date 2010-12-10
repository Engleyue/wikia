<?php
/**
 * Renders page content: adds picture attribution info and replaces section edit links with pencil icon and link
 *
 * @author Maciej Brencz
 */

class ContentDisplayModule extends Module {

	var $bodytext;

	/**
	 * Render picture attribution
	 *
	 * This method is called by MakeThumbLink2 hook
	 */
	static function renderPictureAttribution($skin, $title, $file, $frameParams, $handlerParams, &$s) {
		global $wgUser;
		wfProfileIn(__METHOD__);

		// prevent fatal errors
		if ( empty( $file ) || get_class( $wgUser->getSkin() ) != 'SkinOasis' ) {
			wfProfileOut(__METHOD__);
			return true;
		}

		// get the name of the user who uploaded the file
		$userName = $file->getUser();

		// render avatar and link to user page
		$avatar = AvatarService::renderAvatar($userName, 16);
		$link = AvatarService::renderLink($userName);

		$html = Xml::openElement('aside', array('class' => 'picture-attribution')) .
			$avatar .
			wfMsg('oasis-content-picture-added-by', $link) .
			Xml::closeElement('aside');

		// replace placeholder
		$s = str_replace('<!-- picture-attribution -->', $html, $s);

		#print_pre($html); print_pre(htmlspecialchars($s));

		wfProfileOut(__METHOD__);

		return true;
	}

	/**
	 * Show section edit link for anons (RT #79897)
	 */
	static function onShowEditLink(&$parser, &$showEditLink) {
		global $wgUser;
		wfProfileIn(__METHOD__);

		if ($wgUser->isAnon() && $parser->mOptions->getEditSection()) {
			$showEditLink = true;
		}

		wfProfileOut(__METHOD__);
		return true;
	}

	public static function onDoEditSectionLink( $skin, $title, $section, $tooltip, $result, $lang = false ) {
                global $wgBlankImgUrl, $wgTitle, $wgUser;

                wfProfileIn(__METHOD__);

		$result = ''; // reset result first

		$url = $title->getFullUrl( array( 'action' => 'edit', 'section' => $section ) );

		$class = 'editsection';

                // RT#84733 - prompt to login if the user is an anon and can't edit right now (protected pages and wgDisableAnonEditing wikis).
                $extraClass = "";
                if ( !$wgTitle->userCanEdit() && $wgUser->isAnon() ) {
                        $class .= " loginToEditProtectedPage";
                }

		$result .= Xml::openElement( 'span', array( 'class' => $class ) );

		$result .= Xml::openElement( 'a', array( 'href' => $url ) );
		$result .= Xml::element(
			'img',
			array(
				'src' => $wgBlankImgUrl,
				'class' => 'sprite edit-pencil',
				'alt' => wfMsg( 'oasis-section-edit-alt', $tooltip ) 
			)
		);
		$result .= Xml::closeElement( 'a' );

		$result .= Xml::element( 'a', array( 'href' => $url ), wfMsg( 'oasis-section-edit' ) );

		$result .= Xml::closeElement( 'span' );

                wfProfileOut(__METHOD__);

		return true;
	}

	public function executeIndex() {
		$this->bodytext = preg_replace(
			'#<span class="editsection(.*)</span>\s?<span(.*)</span>\s?</h#',
			'<span$2</span><span class="editsection$1</span></h',
			$this->bodytext
		);

	}

}
