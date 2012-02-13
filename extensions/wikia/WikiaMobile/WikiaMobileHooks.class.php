<?php
/**
 * WikiaMobile Hooks handlers
 *
 * @author Federico "Lox" Lucignano <federico(at)wikia-inc.com>
 */
class WikiaMobileHooks extends WikiaObject{
	const IMAGE_GROUP_MIN = 2;

	public function onParserAfterTidy( &$parser, &$text ){
		$this->wf->profileIn( __METHOD__ );

		//cleanup page output from unwanted stuff
		if ( $parser->getOptions()->getSkin() instanceof SkinWikiaMobile ) {
			//remove inline styling to avoid weird results and optimize the output size
			$text = preg_replace('/\s+(style|color|bgcolor|border|align|cellspacing|cellpadding|hspace|vspace)=(\'|")[^"\']*(\'|")/im', '', $text );

			//transform groups of IMAGE_GROUP_MIN images in a row into a media stack
			$text = preg_replace( '/(\s*<figure[^>]*>(<\/?a|<img|<\/?figcaption|[^<])+<\/figure>\s*){' . self::IMAGE_GROUP_MIN . ',}/im', '<section class="wkImgStk grp">$0<footer>' . $this->wf->Msg('wikiaPhotoGallery-slideshow-view-number', '1', '') . '</footer></section>', $text );
		}

		$this->wf->profileOut( __METHOD__ );
		return true;
	}

	public function onParserLimitReport( $parser, &$limitReport ){
		$this->wf->profileIn( __METHOD__ );

		//strip out some unneeded content to lower the size of the output
		if ( Wikia::isWikiaMobile() ) {
			$limitReport = null;
		}

		$this->wf->profileOut( __METHOD__ );
		return true;
	}

	public function onMakeHeadline( $skin, $level, $attribs, $anchor, $text, $link, $legacyAnchor, $ret ){
		$this->wf->profileIn( __METHOD__ );

		if ( $skin instanceof SkinWikiaMobile ) {
			//remove bold, italics, underline and anchor tags from section headings (also optimizes output size)
			$text = preg_replace( '/<\/?(b|u|i|a|em|strong){1}(\s+[^>]*)*>/im', '', $text );

			//$link contains the section edit link, add it to the next line to put it back
			//ATM editing is not allowed in WikiaMobile
			$ret = "<h{$level} id=\"{$anchor}\"{$attribs}{$text}";

			if ( $level == 2 ) {
				//add chevron to expand the section
				$ret .= '<span class=chev></span>';
			}

			$ret .= "</h{$level}>";
		}

		$this->wf->profileOut( __METHOD__ );
		return true;
	}

	public function onLinkBegin( $skin, $target, &$text, &$customAttribs, &$query, &$options, &$ret ){
		if ( $skin instanceof SkinWikiaMobile && in_array( 'broken', $options ) ) {
			$ret = $text;
			return false;
		}

		return true;
	}

	public function onCategoryPageView( CategoryPage &$categoryPage ) {
		$this->wf->profileIn( __METHOD__ );

		if ( Wikia::isWikiaMobile() ) {
			//converting categoryArticle to Article to avoid circular reference in CategoryPage::view
			F::build( 'Article', array( $categoryPage->getTitle() ) )->view();

			$this->wg->Out->addHTML( $this->app->renderView( 'WikiaMobileCategoryService', 'categoryExhibition', array( 'categoryPage' => $categoryPage ) ) );
			$this->wg->Out->addHTML( $this->app->renderView( 'WikiaMobileCategoryService', 'alphabeticalList', array( 'categoryPage' => $categoryPage ) ) );

			$this->wf->profileOut( __METHOD__ );
			return false;
		}

		$this->wf->profileOut( __METHOD__ );
		return true;
	}

	public function onArticlePurge( Article &$article ) {
		$this->wf->profileIn( __METHOD__ );

		$title = $article->getTitle();

		if ( $title->getNamespace() == NS_CATEGORY ) {
			$category = F::build( 'Category', array( $title ), 'newFromTitle' );
			$model = F::build( 'WikiaMobileCategoryModel' );

			$model->purgeItemsCollectionCache( $category->getName() );
			$model->purgeExhibitionItemsCacheKey( $title->getText() );
		}

		$this->wf->profileOut( __METHOD__ );
		return true;
	}
}
