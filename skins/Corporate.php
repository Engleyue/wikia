<?php
/**
 * See docs/skin.txt
 *
 * @todo document
 * @file
 * @ingroup Skins
 */

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

/**
 * @todo document
 * @ingroup Skins
 */

require_once(dirname( __FILE__ )."/CorporateBase.php");

class SkinCorporate extends SkinCorporateBase {
	function initPage( OutputPage $out ) {
		parent::initPage( $out );
		$this->skinname  = 'Corporate';
		$this->stylename = 'Corporate';
		$this->template  = 'CorporateTemplate';
	}
}

class CorporateTemplate extends CorporateBaseTemplate {
	var $skin;
	/**
	 * Template filter callback for MonoBook skin.
	 * Takes an associative array of data set from a SkinTemplate-based
	 * class, and a wrapper for MediaWiki's localization database, and
	 * outputs a formatted page.
	 *
	 * @access private
	 */
	function execute() {
		global $wgRequest, $wgOut, $wgUser;
		$this->skin = $skin = $this->data['skin'];
		$action = $wgRequest->getText( 'action' );
		// Suppress warnings to prevent notices about missing indexes in $this->data
		wfSuppressWarnings();

?><!doctype html>
<html lang="<?php $this->text('lang'); ?>">
	<?php $this->htmlHead() ?>
	<body<?php if($this->data['body_ondblclick']) { ?> ondblclick="<?php $this->text('body_ondblclick') ?>"<?php } ?>
<?php if($this->data['body_onload']) { ?> onload="<?php $this->text('body_onload') ?>"<?php } ?>
 class="<?php print $this->htmlBodyClassAttributeValues(); ?>">

<?php print $this->htmlGlobalHeader(); ?>
		<!-- DEV NOTE: This is the dark navigation strip at the top. -->
<?php print $this->htmlGlobalNav(); ?>

		<div id="MainContent">
			<!-- DEV NOTE: This area has the blue-striped background.  -->

			<article id="MainArticle" class="clearfix">
				<?php print $this->htmlMainArticleContents();?>
				<ul id="article-links" class="shrinkwrap">
					<?php if (!$wgUser->isAnon() && $wgOut->isArticleRelated()) { ?>
					<li id="control-watch" class=""><div>&nbsp;</div>
						<?= $this->skin->watchThisPage() ?>
					</li>
					<?php } ?>
				</ul>
			</article>

			<!-- DEV NOTE: These spotlights only show up on non-"homepage" pages. -->
			<section id="wikia-spotlights">
				<?php AdEngine::getInstance()->getSetupHtml(); ?>
				<?php echo AdEngine::getInstance()->getAd('FOOTER_SPOTLIGHT_LEFT'); ?>
				<?php echo AdEngine::getInstance()->getAd('FOOTER_SPOTLIGHT_MIDDLE_LEFT'); ?>
				<?php echo AdEngine::getInstance()->getAd('FOOTER_SPOTLIGHT_MIDDLE_RIGHT'); ?>
				<?php echo AdEngine::getInstance()->getAd('FOOTER_SPOTLIGHT_RIGHT'); ?>
			</section>
		</div><!-- END: #MainContent -->

		<?php print $this->htmlCompanyInfo();?>
		<?php print $this->htmlGlobalFooter();?>

	</body>
</html>

<?php
	}
} // end of class

