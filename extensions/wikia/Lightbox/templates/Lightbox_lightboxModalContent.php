<div class="WikiaLightbox">
	<img id='lightbox-preload' src='<?=$initialFileDetail['imageUrl'] ?>' />
	<script id="LightboxPhotoTemplate" type="text/template">
		<div class="media">
			<img src="{{imageSrc}}" height="{{imageHeight}}" />
		</div>
	</script>

	<script>
		var LightboxVar = {
			file: <?= json_encode($initialFileDetail) ?>
		};
	</script>
</div>