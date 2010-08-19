<div id="ws-upload">
	<div id="ws-progress-bar"></div>
	<div class="formblock ws-select-stats">
	<form id="ws-form" action="<?= $mTitle->getLocalUrl() ?>/<?=$mAction?>" method="get" onsubmit="ws_make_param();">
	<input type="hidden" name="wsfrom" value="" id="wsfrom">
	<input type="hidden" name="wsto" value="" id="wsto">
	<input type="hidden" name="wsxls" value="0" id="wsxls">
		<ul>
			<li><span><?= wfMsg('wikistats_daterange_from') ?></span>
			<select id="ws-month-from">
<?php foreach ($dateRange['months'] as $id => $month) { $selected = ( $fromMonth == ($id+1) ) ? " selected=\"selected\" " : ""; ?>
				<option value="<?= ($id+1) ?>" <?=$selected?>><?= ucfirst($month) ?></option>
<?php } ?>
				</select>
				<select id="ws-year-from">
<?php 
$minYear = intval($dateRange['minYear']); $maxYear = intval($dateRange['maxYear']);
while ( $minYear <= $maxYear ) { $selected = ($fromYear == $minYear) ? " selected=\"selected\" " : ""; ?>
				<option <?= $selected ?> value="<?= $minYear ?>"><?= $minYear ?></option>
<?php $minYear++; } ?>
				</select>
			</li>	
			<li><span><?= wfMsg('wikistats_daterange_to') ?></span>
				<select id="ws-month-to">
<?php foreach ($dateRange['months'] as $id => $month) { $k = $id+1; $selected = ($curMonth == $k) ? " selected=\"selected\" " : ""; ?>
				<option <?= $selected ?> value="<?= $k ?>"><?= ucfirst($month) ?></option>
<?php } ?>
				</select>
				<select id="ws-year-to">
<?php 
$minYear = intval($dateRange['minYear']); $maxYear = intval($dateRange['maxYear']);
while ($minYear <= $maxYear) { $selected = ($curYear == $minYear) ? " selected=\"selected\" " : ""; ?>
				<option <?= $selected ?> value="<?= $minYear ?>"><?= $minYear ?></option>
<?php $minYear++; } ?>
				</select>
			</li>
		</ul>
		<ul>
			<li><input type="submit" id="wsshowbtn" value="<?= wfMsg("wikistats_showstats_btn") ?>"  /></li>
			<!--<li><input type="button" id="ws-show-charts" value="<?= wfMsg("wikistats_showcharts") ?>" name="ws-show-charts" /></li>-->
			<li><input type="submit" id="wsxlsbtn" value="<?= wfMsg("wikistats_export_xls") ?>" onclick="$('#wsxls').val('1');" /></li>
		</ul>
	</form>	
	</div>
	<div class="ws-info-generated">
		<?=wfMsg("wikistats_date_of_generate", ( !empty($updateDate) ) ? $updateDate : " - " );?>
	</div>	
</div>
<script type="text/javascript">
$(document).ready(function() {
	ws_focus('wswiki','axWFactoryDomainQuery');
	window.sf_initiated = false;
});
</script>
