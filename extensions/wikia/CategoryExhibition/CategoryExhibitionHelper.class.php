<?php

	/**
	 * Helper class for Category Exhibition
	 *
	 * @author Jakub
	 */
	class CategoryExhibitionHelper {

		/**
		 * Hook entry for showing Category Exhibition
		 */
		
		static public function onArticleFromTitle( &$title, &$article ){
			if( !Wikia::isWikiaMobile() ) {
				if ( $title->getNamespace() == NS_CATEGORY ) {
					
					$categoryExhibition = new CategoryExhibitionSection( $title );
					$categoryExhibition->setDisplayTypeFromParam();
					$categoryExhibition->setSortTypeFromParam();
					$displayType = $categoryExhibition->getDisplayType();
					if ( $displayType == 'exhibition' ){
						$article = new CategoryExhibitionPage( $title );
					} else {
						$article = new CategoryPageII( $title );
					};
	
					$magicWord = MagicWord::get( CATEXHIBITION_DISABLED );
					$disabled = ( 0 < $magicWord->match( $article->getRawText() ) );
					if ( $disabled ){
						$article = false;
					};
				}
			}
			return true;
		}

		/**
		 * Hook entry for adding parser magic words
		 */
		static public function onLanguageGetMagic(&$magicWords, $langCode){
			$magicWords[CATEXHIBITION_DISABLED] = array( 0, '__NOCATEGORYEXHIBITION__' );
			return true;
		}

		/**
		 * Hook entry for removing the magic words from displayed text
		 */
		static public function onInternalParseBeforeLinks(&$parser, &$text, &$strip_state) {
			global $wgRTEParserEnabled;
			if (empty($wgRTEParserEnabled)) {
				MagicWord::get('CATEXHIBITION_DISABLED')->matchAndRemove($text);
			}
			return true;
		}

		/**
		 * Hook entry when article is purged (purge the gallery cache if purging the category page
		 */
		static public function onArticlePurge( Article $article ) {
			global $wgVideoHandlersVideosMigrated;
			$title = $article->getTitle();

			$a = new Title;

			$oMemCache = F::App()->wg->memc;
			$sKey = F::App()->wf->sharedMemcKey(
				'category_exhibition_article_cache',
				$title->getArticleId(),
				F::App()->wg->cityId,
				$this->isVerify(),
				$wgVideoHandlersVideosMigrated ? 1 : 0
			);

			$oMemCache->delete( $sKey );

			return true;
		}
	}
