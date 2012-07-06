<section id="mediaToolDialog">
<ul class="tabs">
	<li data-tab="find-media" class="selected">
		<a><?= wfMsg('mediatool-tab-findmedia'); ?></a>
	</li>
	<li data-tab="edit-media" class="disabled">
		<a><?= wfMsg('mediatool-tab-editmedia'); ?></a>
	</li>
</ul>

<div class="MediaToolContainer">

	<div data-tab-body="find-media" class="tabBody selected border">

		<div class="mediatool-left-bar">

			<ul class="tabs minor-tabs">
				<li data-tab="find-media-wiki" class="selected">
					<a><?= wfMsg('mediatool-tab-wiki'); ?></a>
				</li>
				<li data-tab="find-media-online">
					<a><?= wfMsg('mediatool-tab-online'); ?></a>
				</li>
				<li data-tab="find-media-computer">
					<a><?= wfMsg('mediatool-tab-computer'); ?></a>
				</li>
			</ul>

			<div data-tab-body="find-media-wiki" class="tabBody selected">
				<input type="radio" id="source-recently-added" checked="checked"> <label for="source-recently-added"><?= wfMsg('mediatool-collection-recentlyadded'); ?></label>
			</div>
			<div data-tab-body="find-media-online" class="tabBody">
				<label for="mediatool-online-url"><?= wfMsg('mediatool-addviaurl-label'); ?></label>
				<input type="text" id="mediatool-online-url" value="" />
				<button name="addviaurl"><?= wfMsg('mediatool-button-add');?></button>
			</div>
			<div data-tab-body="find-media-computer" class="tabBody">
			</div>

		</div>
		<div class="mediatool-content">
			<div class="mediatool-basket">
				{{{cart}}}
			</div>
			<div id="mediatool-thumbnail-browser" class="mediatool-thumbnail-browser">
				{{{itemsCollection}}}
			</div>
		</div>

	</div>
	<div data-tab-body="edit-media" class="tabBody border">

		<div class="mediatool-left-bar">

			<ul class="tabs minor-tabs">
				<li data-tab="edit-media-options" class="selected">
					<a><?= wfMsg('mediatool-mediaoptions'); ?></a>
				</li>
			</ul>

			<div data-tab-body="edit-media-options" class="tabBody selected">

				<div class="media-tool-thumbnail-style">
					<h4>Thumbnail Style</h4>
					<div><img data-thumb-style="border" src="<?= F::app()->wg->ExtensionsPath.'/wikia/MediaTool/images/thumbnail_with_border.png' ?>" />
						 <img data-thumb-style="no-border" src="<?= F::app()->wg->ExtensionsPath.'/wikia/MediaTool/images/thumbnail_without_border.png' ?>" />
					</div>
					<span class="thumb-style-desc">Border and Caption</span>
				</div>

				<div class="media-tool-thumbnail-size">
					<h4>Thumbnail Size</h4>
					<input type="radio" name="thumbsize" checked="checked" id="mediaToolLargeThumbnail"/>
					<label for="mediaToolLargeThumbnail">Large (300px)</label>
					<input type="radio" name="thumbsize" id="mediaToolSmallThumbnail"/>
					<label for="mediaToolSmallThumbnail">Small (250px)</label>
					<input type="radio" name="thumbsize" id="mediaToolCustomThumbnail"/>
					<label for="mediaToolCustomThumbnail">Custom</label>
					<div id="mediaToolThumbnailSizeSlider" class="WikiaSlider"></div>
					<span id="VideoEmbedInputWidth">
						<input type="text" id="VideoEmbedManualWidth" name="VideoEmbedManualWidth" value="" onchange="" onkeyup="" /> px
					<span>
				</div>
				<div class="media-tool-thumbnail-position">
					<h4>Media Position</h4>
					<div>positions.....</div>
				</div>
			</div>

		</div>
		<div class="mediatool-content">
			<div class="mediatool-preview">

			</div>
		</div>

	</div>
</div>


<div class="MediaTool-buttons">
	<button class="secondary" name="cancel"><?= wfMsg('mediatool-button-cancel'); ?></button>
	<button  name="continue" disabled="disabled"><?= wfMsg('mediatool-button-continue'); ?></button>
	<button  name="done"><?= wfMsg('mediatool-button-done'); ?></button>
</div>
</section>