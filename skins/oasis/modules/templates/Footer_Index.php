<footer id="WikiaFooter" class="WikiaFooter <?= $showToolbar ? '' : 'notoolbar' ?>">

	<div class="FooterAd"></div>
<?php if($showToolbar) { ?>
	<div class="toolbar">
		<?php if ($showNotifications) {
	 		echo wfRenderModule('Notifications');
	 	} ?>
		<ul class="tools">
			<?php echo wfRenderModule('Footer','Toolbar'); ?>
<?php if($showLoadTime) { ?>
			<li class="loadtime">
				<?= $loadTimeStats ?>
			</li>
<?php } ?>
		</ul>
		<img src="<?= $wgBlankImgUrl; ?>" class="banner-corner-left" height="0" width="0">
		<img src="<?= $wgBlankImgUrl; ?>" class="banner-corner-right" height="0" width="0">
	</div>
<?php } ?>

	<?= wfRenderModule('Spotlights', 'Index', array('mode'=>'FOOTER', 'adslots'=>array( 'SPOTLIGHT_FOOTER_1', 'SPOTLIGHT_FOOTER_2', 'SPOTLIGHT_FOOTER_3' ), 'adGroupName'=>'SPOTLIGHT_FOOTER')) ?>

</footer>
