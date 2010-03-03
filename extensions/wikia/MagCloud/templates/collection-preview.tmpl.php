<!-- s:<?= __FILE__ ?> -->
<div id="SpecialMagCloudPdfProcess">&nbsp;</div>

<div id="SpecialMagCloudPreviews" class="clearfix">
	<a id="SpecialMagCloudPreviewPrev" class="bigButton">
		<big>&laquo;</big>
		<small> </small>
	</a>
	<div class="SpecialMagCloudPreviewPage"></div>
	<div class="SpecialMagCloudPreviewPage"></div>
	<a id="SpecialMagCloudPreviewNext" class="bigButton">
		<big>&raquo;</big>
		<small> </small>
	</a>
	<div id="SpecialMagCloudStatusMask"></div>

	<div id="SpecialMagCloudStatusPopup" class="modalWrapper reset SpecialMagCloudPreviewStatusPopup">
		<h1 class="modalTitle color1">Creating your magazine</h1>
		<div id="SpecialMagCloudPublishStatus"><?= wfMsg('magcloud-preview-generating-pdf') ?></div>
	</div>
</div>

<div id="SpecialMagCloudButtons" style="margin-left: auto; margin-right: auto; text-align: center; width: 550px">
	<a id="MagCloudBackToDesign" class="wikia-button secondary_back" href="<?= htmlspecialchars($title->getLocalUrl() . '/Design_Cover') ?>" style="float: left">
		<?= wfMsg('magcloud-preview-back-to-cover') ?>
	</a>

	<a id="MagCloudForwardToPublish" class="wikia-button forward" href="https://magcloud.com/apps/authorizeask/<?= $publicApiKey ?>?ud=<?= $server ?>" style="float: right; visibility: hidden">
		<?= wfMsg('magcloud-preview-publish') ?> &raquo;
	</a>

	<a id="MagCloudSaveMagazine" class="wikia-button secondary">
		<?= wfMsg('magcloud-preview-save-magazine') ?>
	</a>
</div>
<script type="text/javascript">/*<![CDATA[*/
	$('#SpecialMagCloudPreviewPrev').click(function() {
		MagCloud.track('/preview/arrow-prev');
	});
	$('#SpecialMagCloudPreviewNext').click(function() {
		MagCloud.track('/preview/arrow-next');
	});

	$('#MagCloudBackToDesign').click(function() {
		MagCloud.track('/preview/backtodesign');
	});
	$('#MagCloudForwardToPublish').click(function() {
		MagCloud.track('/preview/forwardtopublish');
	});
	$('#MagCloudSaveMagazine').click(function() {
		MagCloud.track('/preview/savemagazine');

		SpecialMagCloud.saveCollection();
	});

	// generate PDF and get preview of 1st and 2nd page
	SpecialMagCloud.renderPdf('<?= $collectionHash ?>', <?= $collectionTimestamp ?>, $('#SpecialMagCloudPdfProcess'));
/*]]>*/</script>
<!-- e:<?= __FILE__ ?> -->
