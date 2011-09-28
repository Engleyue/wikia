<h1>Wikia</h1>
<div class="skiplinkcontainer">
<a class="skiplink" rel="nofollow" href="#WikiaArticle"><?= wfMsg( 'oasis-skip-to-content' ); ?></a>
<a class="skiplink wikinav" rel="nofollow" href="#WikiHeader"><?= wfMsg( 'oasis-skip-to-wiki-navigation' ); ?></a>
<a class="skiplink sitenav" rel="nofollow" href="#GlobalNavigation"><?= wfMsg( 'oasis-skip-to-site-navigation' ); ?></a>
</div>
<?= $afterBodyHtml ?>

<div id="ad-skin" class="wikia-ad noprint"></div>
<?= wfRenderModule('Ad', 'Index', array('slotname' => 'INVISIBLE_TOP')) ?>
<?= wfRenderModule('Ad', 'Index', array('slotname' => 'HOME_INVISIBLE_TOP')) ?>
<?= wfRenderModule('GlobalHeader') ?>

<section id="<?= !empty($wgOasisNavV2) ? 'WikiaAdvertPage' : 'WikiaPage' ?>" class="<?= !empty($wgOasisNavV2) ? 'WikiaAdvertPage' : 'WikiaPage' ?>">
	<div id="WikiaPageBackground" class="WikiaPageBackground"></div>
	<div class="WikiaPageContentWrapper">
		<?= wfRenderModule('Notifications', 'Confirmation') ?>

		<?
		if ($isMainPage) {
			echo '<div class="WikiaMainPageBanner">';
		}
		if ($wgEnableTopButton) {
			echo '<div class="WikiaTopAds'.$topAdsExtraClasses.'">';
		}
		if ($wgEnableCorporatePageExt) {
			echo wfRenderModule('Ad', 'Index', array('slotname' => 'CORP_TOP_LEADERBOARD'));
		} else {
			if (in_array('leaderboard', $wgABTests)) {
				// no leaderboard ads
			} else {
				echo wfRenderModule('Ad', 'Index', array('slotname' => 'TOP_LEADERBOARD'));
				echo wfRenderModule('Ad', 'Index', array('slotname' => 'HOME_TOP_LEADERBOARD'));
			}
		}
		if ($wgEnableTopButton) {
			echo wfRenderModule('Ad', 'Index', array('slotname' => 'TOP_BUTTON'));
			echo '</div>';
		}
		if ($isMainPage) {
			echo '</div>';
		}
		?>

<? if($wgOasisNavV2): ?>
	</div>
</section>

<section id="WikiaPage" class="WikiaPage<?= empty( $wgOasisNavV2 ) ? '' : ' V2' ?>">
	<div id="WikiaPageBackground" class="WikiaPageBackground"></div>
	<div class="WikiaPageContentWrapper">
<? endif; ?>

		<?php
			if ( empty( $wgSuppressWikiHeader ) ) {
				echo empty( $wgOasisNavV2  )
					? wfRenderModule( 'WikiHeader' )
					: wfRenderModule( 'WikiHeaderV2' );
			}
		?>

		<?php
			if (!empty($wgEnableWikiAnswers)) {
				echo wfRenderModule('WikiAnswers', 'QuestionBox');
			}
		?>

		<?php
		if (!empty( $wgInterlangOnTop ) ) {
			echo wfRenderModule('ArticleInterlang');
		}
		?>

		<?php
		if ( !empty($isUserProfilePageV3Enabled) && $headerModuleName == 'UserPagesHeader' && ($headerModuleAction != 'BlogPost' && $headerModuleAction != 'BlogListing') ) {
			//echo F::app()->renderView( 'UserProfilePage', 'renderUserIdentityBox', array() );
			echo wfRenderModule($headerModuleName, $headerModuleAction, $headerModuleParams);
		}
		?>

		<article id="WikiaMainContent" class="WikiaMainContent">
			<?php

				// Needs to be above page header so it can suppress page header
				if ($displayAdminDashboard) {
					echo wfRenderModule('AdminDashboard', 'Chrome');
				}

				// render UserPagesHeader or PageHeader or nothing...
				if (empty($wgSuppressPageHeader) && $headerModuleName) {
					if (!empty($isUserProfilePageV3Enabled) && $headerModuleName == 'UserPagesHeader') {
						if ($headerModuleAction == 'BlogPost' || $headerModuleAction == 'BlogListing') {
							// Show blog post header
							echo F::app()->renderView( $headerModuleName, $headerModuleAction, $headerModuleParams );
						} else {
							// Show just the edit button
							echo F::app()->renderView( 'UserProfilePage', 'renderActionButton', array() );
						}
					} else {
						echo wfRenderModule($headerModuleName, $headerModuleAction, $headerModuleParams);
					}
				}
			?>


			<?php if ($subtitle != '' && $headerModuleName == 'UserPagesHeader' ) { ?>
				<div id="contentSub"><?= $subtitle ?></div>
			<?php } ?>


			<div id="WikiaArticle" class="WikiaArticle<?= $displayAdminDashboardChromedArticle ? ' AdminDashboardChromedArticle' : '' ?>">
				<? if($displayAdminDashboardChromedArticle) { ?>
					<?= (string)F::app()->sendRequest( 'AdminDashboardSpecialPage', 'chromedArticleHeader', array('headerText' => $wgTitle->getText() )) ?>
				<? } ?>
				<div class="home-top-right-ads">
				<?php
					if (in_array('leaderboard', $wgABTests)) {
						echo wfRenderModule('Ad', 'Index', array('slotname' => 'TEST_HOME_TOP_RIGHT_BOXAD'));
					} else {
						echo wfRenderModule('Ad', 'Index', array('slotname' => 'HOME_TOP_RIGHT_BOXAD'));
					}

					echo wfRenderModule('Ad', 'Index', array('slotname' => 'HOME_TOP_RIGHT_BUTTON'));
				?>
				</div>


				<?php
				// for InfoBox-Testing
				if ($wgEnableInfoBoxTest) {
					echo wfRenderModule('ArticleInfoBox');
				}

				if ($wgEnableCorporatePageExt) {
					echo wfRenderModule('CorporateSite', 'Slider');
				} ?>

				<?= $bodytext ?>

			</div>

			<?php
			if (empty($wgSuppressArticleCategories)) {
				echo wfRenderModule('ArticleCategories');
			} ?>
			<?php
			if (empty( $wgInterlangOnTop ) ) {
				 echo wfRenderModule('ArticleInterlang');
			}
			?>

			<?php if (!empty($afterContentHookText)) { ?>
			<div id="WikiaArticleFooter" class="WikiaArticleFooter">
				<?= $afterContentHookText ?>
			</div>
			<?php } ?>

			<div id="WikiaArticleBottomAd" class="noprint">
				<?= wfRenderModule('Ad', 'Index', array('slotname' => 'PREFOOTER_LEFT_BOXAD')) ?>
				<?= wfRenderModule('Ad', 'Index', array('slotname' => 'PREFOOTER_RIGHT_BOXAD')) ?>
			</div>

	<?php
		if ($displayComments) {
			echo wfRenderModule('ArticleComments');
		}
	?>

		</article><!-- WikiaMainContent -->

	<?php
		if (count($railModuleList) > 0) {
			echo wfRenderModule('Rail', 'Index', array('railModuleList' => $railModuleList));
		}
	?>

		<?= empty($wgSuppressFooter) ? wfRenderModule('Footer') : '' ?>
		<?= wfRenderModule('CorporateFooter') ?>
	</div>
</section><!--WikiaPage-->
