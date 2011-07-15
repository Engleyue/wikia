<section id="CustomizeTab" class="CustomizeTab">
	<fieldset class="background">
		<h1><?= wfMsg('themedesigner-background') ?></h1>
		<ul>
			<li>
				<h2><?= wfMsg('themedesigner-color') ?></h2>
				<img src="<?= $wgBlankImgUrl ?>" class="color-body" id="swatch-color-background">
			</li>
			<li>
				<h2><?= wfMsg('themedesigner-graphic') ?></h2>
				<img src="<?= $wgBlankImgUrl ?>" class="background-image" id="swatch-image-background">
				<input type="checkbox" id="tile-background"> <label for="tile-background"><?= wfMsg('themedesigner-tile-background') ?></label>
				<input type="checkbox" id="fix-background"> <label for="fix-background"><?= wfMsg('themedesigner-fix-background') ?></label>
			</li>
		</ul>
	</fieldset>
	<fieldset class="page">
		<h1><?= wfMsg('themedesigner-page') ?></h1>
		<ul>
			<li>
				<h2><?= wfMsg('themedesigner-buttons') ?></h2>
				<img src="<?= $wgBlankImgUrl ?>" class="color-buttons" id="swatch-color-buttons">
			</li>
			<li>
				<h2><?= wfMsg('themedesigner-links') ?></h2>
				<img src="<?= $wgBlankImgUrl ?>" class="color-links" id="swatch-color-links">
			</li>
			<li>
				<h2><?= wfMsg('themedesigner-header') ?></h2>
				<img src="<?= $wgBlankImgUrl ?>" class="color-header" id="swatch-color-header">
			</li>
			<li>
				<h2><?= wfMsg('themedesigner-color') ?></h2>
				<img src="<?= $wgBlankImgUrl ?>" class="color-page" id="swatch-color-page">
			</li>
		</ul>
	</fieldset>
</section>