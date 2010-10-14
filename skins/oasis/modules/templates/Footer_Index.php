<footer id="WikiaFooter" class="WikiaFooter">

<?php if($showToolbar) { ?>
	<div class="toolbar">
		<?php if ($showNotifications) { 
	 		echo wfRenderModule('Notifications'); 
	 	} ?> 
		<ul>

<?php if($showMyTools) { ?>
			<li class="mytools">
				<img src="<?= $wgBlankImgUrl; ?>" class="sprite mytools" height="15" width="15">
				<a href="#"><?= wfMsg('oasis-mytools') ?></a>
				<?= wfRenderModule('MyTools') ?>
			</li>
<?php } ?>
<?php if($showFollow && $follow) { ?>
			<li>
				<a href="<?= $follow['href'] ?>"><img src="<?= $wgBlankImgUrl; ?>" class="sprite follow" height="15" width="15"></a>
				<a accesskey= "w" href="<?= $follow['href'] ?>" id="ca-<?= $follow['action'] ?>"><?= $follow['text'] ?></a>
			</li>
<?php } ?>
<?php if($showShare) { ?>
			<li id="ca-share_feature">
				<img src="<?= $wgBlankImgUrl; ?>" class="sprite share" height="15" width="15">
				<a href="#" id="control_share_feature"><?= wfMsg('oasis-share') ?></a>
			</li>
<?php } ?>
<?php if($showLike && false /* we do not have like feature yet available */) { ?>
			<li>
				<img src="/skins/oasis/images/icon_footer_like.png">
				<a href="#"><?= wfMsg('oasis-like') ?></a>
			</li>
<?php } ?>

		</ul>
		<img src="<?= $wgBlankImgUrl; ?>" class="banner-corner-left" height="0" width="0">
		<img src="<?= $wgBlankImgUrl; ?>" class="banner-corner-right" height="0" width="0">
	</div>
<?php } ?>

	<?= wfRenderModule('Spotlights', 'Index', array('mode'=>'FOOTER', 'adslots'=>array( 'SPOTLIGHT_FOOTER_1', 'SPOTLIGHT_FOOTER_2', 'SPOTLIGHT_FOOTER_3' ), 'adGroupName'=>'SPOTLIGHT_FOOTER')) ?>
	
</footer>
