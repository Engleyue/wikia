<section class="LatestPhotosModule module">	
	<h1>Latest Photos</h1>
	<?php $disabled = Wikia::specialPageLink('Upload', 'oasis-add-photo', (!$wgUser->isLoggedIn() ? 'wikia-button upphotoslogin' :'wikia-button upphotos'), 'blank.gif', 'oasis-add-photo', 'sprite photo'); ?>
	<details class="tally counter">
		<?= wfMsgExt('oasis-latest-photos-header', array( 'parsemag' ), $total, ($total < 100000 ? 'fixedwidth' : '') ) ?>
	</details>

<?php
if ($enableEmptyGallery == true) { ?>
	<details class="empty-photos">
		<div class="temp-image">
			 <?= Wikia::specialPageLink('Upload', 'oasis-latest-photos-empty'); ?>
		</div>
	</details>
<?php }
else {
	 ?>
	<?php
	$class = "";
	if ($enableScroll == false) {
		$class = " hidden";
	}
		?>
		<a href="#" class="previous<?= $class ?>"><img src="<?= $wgBlankImgUrl; ?>" class="latest-images-left" height="0" width="0"></a>
		<a href="#" class="next<?= $class ?>"><img src="<?= $wgBlankImgUrl; ?>" class="latest-images-right" height="0" width="0"></a>
	<div class="carousel-container">
		<div>
			<ul class="carousel">
	<?php
	// macbre: how many images to show at the page load, next images will be lazy loaded
	$load = 3;
	foreach ($thumbUrls as $i => $url) {?>
		<li class="thumbs"><a class="image" ref="<?= $url["image_filename"] ?> " href="<?= $url["file_url"] ?>">
			<img class="thumbimage" <?= $i < $load ? 'src' : 'data-src' ?>="<?= $url["thumb_url"] ?>" />
		</a>

		<span class="thumbcaption">
			<?= wfMsg('oasis-latest-photos-by'); ?> <?= $url["user_href"] ?><br/>
			<?= $url["date"] ?><br/>

			<?php
		if (count($url["links"]) >= 1) {?>
			<?= wfMsg('oasis-latest-photos-posted-in'); ?>
			<?= implode(',&nbsp;', $url["links"] ); ?>
		<?php
		}
			?>
		</span>
		</li>
	<?php
	}
	?>
	<?php
	if (count($thumbUrls) > 2) { ?>
		<li class="see-all">
			<?= Wikia::specialPageLink('NewFiles', 'oasis-latest-photos-inner-message') ?>
			<img data-src="<?= $wgStylePath ?>/oasis/images/empty_gallery.png" />
		</li>
	<?php
	}
	else {?>
		<li class="add-more single-photo"><?= Wikia::specialPageLink('Upload', 'oasis-latest-photos-single') ?></li>
		<?php

	}?>

			</ul>
		</div>
	</div>
	<?= Wikia::specialPageLink('NewFiles', 'oasis-latest-photos-more', array('class' => 'more')) ?>


<?php }
	?>
</section>
