<section class="HuluVideoPanelModule">

<div id="huluPanel" panelpartner="<?= $partnerId ?>" panelLayout="vertical" panelItems="2" panelShow="<?= $wgHuluVideoPanelShow ?>" panelAllowMature="false" 
     panelAutoPlay="true" panelsortdefault="recentlyAdded" panelSearchEnabled="false" panelSortEnabled="true" panelScaleX="1.1" panelScaleY="1.1"
<?php
if (is_array($wgHuluVideoPanelAttributes)) {
	$implodedAttribs = array_map(create_function('$key, $value', 'return $key."=\"".$value."\" ";'), array_keys($wgHuluVideoPanelAttributes), array_values($wgHuluVideoPanelAttributes));
	echo implode($implodedAttribs);
}
?>
></div>
<div id="huluPlayer" class="huluPlayer" PlayerMode="fixed" PlayerScale="1.10" style="position: absolute;"><!-- inline style is necessary. hulu js tries to overwrite it if not included --></div>

<script type="text/javascript">
	wgAfterContentAndJS.push(function() {
		var fileref=document.createElement('script');
		fileref.type = "text/javascript";
		fileref.id = "HULU_VP_JS";
		fileref.src = "http://player.hulu.com/videopanel/js/huluVideoPanel.js?partner=<?= $partnerId ?>";
		document.getElementsByTagName("head")[0].appendChild(fileref);	
	});
</script>

</section>