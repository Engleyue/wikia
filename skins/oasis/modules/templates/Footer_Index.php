<footer id="WikiaFooter" class="WikiaFooter <?= $showToolbar ? '' : 'notoolbar' ?>">

	<?= wfRenderModule('Ad', 'Index', array('slotname' => 'LEFT_SKYSCRAPER_3')) ?>

	<div class="FooterAd"></div>
<?php if($showToolbar) { ?>
	<div class="toolbar">
		<?php if ($showNotifications) {
	 		echo wfRenderModule('Notifications');
	 	} ?>
		<ul class="tools">
			<?php echo wfRenderModule('Footer','Toolbar');
            /* BugId:5497 PerformanceStats are now displayed via OasisToolbarService (see: DevInfoUserCommand) */ ?>
            <? if (!empty($wgEnableAdminDashboardExt) && F::app()->wg->User->isAllowed( 'admindashboard' )) { 
            	echo (string)F::app()->sendRequest( 'AdminDashboardSpecialPage', 'toolbarItem', array());
             } ?>
		</ul>
		<img src="<?= $wgBlankImgUrl; ?>" class="banner-corner-left" height="0" width="0">
		<img src="<?= $wgBlankImgUrl; ?>" class="banner-corner-right" height="0" width="0">
	</div>
<?php } ?>

	<?= wfRenderModule('Spotlights', 'Index', array('mode'=>'FOOTER', 'adslots'=>array( 'SPOTLIGHT_FOOTER_1', 'SPOTLIGHT_FOOTER_2', 'SPOTLIGHT_FOOTER_3' ), 'adGroupName'=>'SPOTLIGHT_FOOTER')) ?>

</footer>
