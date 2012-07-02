<div class="WikiaMosaicSlider" style="display:none">
	<div class="wikia-mosaic-slider-region">
		<a href="" class="wikia-mosaic-link">
			<div class="wikia-mosaic-slider-panorama">
			</div>
			<div class="wikia-mosaic-slider-description-mask">
			</div>
			<div class="wikia-mosaic-slider-description">
			</div>
		</a>
	</div>
	<ul class="wikia-mosaic-thumb-region">
	<?php
		$index = 0;
		foreach ( $images as $key => $val ) {
			$index++;
	?>
		<li class="wikia-mosaic-slide<?= $index === 5 ? ' last' : ''?>">
			<?php if ( !empty( $val['imageLink'] ) ): ?>
					<a href='<?= htmlspecialchars($val['imageLink'],ENT_QUOTES); ?>' class='wikia-mosaic-link'>
			<?php endif; ?>

			<img width='<?= $imagesDimensions['w']; ?>' height='<?= $imagesDimensions['h'] ?>' src='<?=$val['imageUrl']?>' class="wikia-mosaic-hero-image">
			<img width='<?= $thumbDimensions['w'] ?>' height='<?= $thumbDimensions['h'] ?>' src='<?= $val['imageThumbnail'] ?>' class="wikia-mosaic-thumb-image">

			<div class="wikia-mosaic-description-mask">
			</div>
			<div class="wikia-mosaic-description">
				<h3><?= $val['imageTitle'] ?></h3>
				<div class="wikia-mosaic-short-title"><?= $val['imageShortTitle'] ?></div>
				<p><?= $val['imageDescription'] ?></p>
			</div>
			<?php if ( !empty( $val['imageLink'] ) ): ?>
					</a>
			<?php endif; ?>
		</li>
	<?php } ?>
	</ul>
</div>