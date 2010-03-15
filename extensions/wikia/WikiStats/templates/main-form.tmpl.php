<?php
$tabsUrl = array(
	0 => sprintf("%s/%d/main", $mTitle->getLocalUrl(), $wgCityId),
	1 => sprintf("%s/%d/month", $mTitle->getLocalUrl(), $wgCityId),
	2 => sprintf("%s/%d/current", $mTitle->getLocalUrl(), $wgCityId)
);
$tabsName = array( "ws-main", "ws-month", "ws-day" );
?>
<script type="text/javascript">
var tabsName = new Array( <?= "'" . implode("','", $tabsName) . "'" ?> );
var tabsUrl = new Array( <?= "'" . implode("','", $tabsUrl ) . "'" ?> );
var activeTab = 0;
$(function() {
	$('#ws-tabs').tabs({
		fxFade: true,
		fxSpeed: 'fast',
		onClick: function() {
			activeTab = $('#ws-tabs').activeTab();
			reloadTab();
		},
		onHide: function() {
		},
		onShow: function() {
		}
	});
	$('#ws-addinfo').load(wgServer+wgScript+'?action=ajax&rs=axWStats&ws=addinfo');
	reloadTab();
});

function reloadTab() {
	var dateFrom = $('#ws-date-year-from').val();
	var monthFrom = $('#ws-date-month-from').val();
	dateFrom += ( monthFrom < 10 ) ? '0' + monthFrom : monthFrom;

	var dateTo = $('#ws-date-year-to').val();
	var monthTo = $('#ws-date-month-to').val();
	dateTo += ( monthTo < 10 ) ? '0' + monthTo : monthTo;

	var ws_domain = $('#ws-domain').val();
	var data = {
		'from': ( dateFrom != NaN) ? dateFrom : 0,
		'to': ( dateTo != NaN ) ? dateTo : 0,
		'ws-domain': ( ws_domain != undefined ) ? ws_domain : ''
	};
	$('#' + tabsName[activeTab]).load(tabsUrl[0], data, function() {
		$("#ws-loader").css('display', 'none'); 
		$('#ws-show-stats').click(function() {
			reloadTab();
		});	
		//$('table').visualize({type: 'line'});
	});
	var refreshId = setInterval( function() {
		$("#ws-loader").css('display', 'block');  
		$('#' + tabsName[activeTab]).load(tabsUrl[0], data, function() {
			$("#ws-loader").css('display', 'none'); 
			$('#ws-show-stats').click(function() {
				reloadTab();
			});	
			//$('table').visualize({type: 'line'});
		}); 
	}, 500000 ); 
}

</script>
<div id="ws-addinfo" class="ws-addinfo"></div>
<div id="ws-loader" class="ws-loader"><img src="<?=$wgStylePath?>/common/images/ajax.gif" width="16" height="16"></div>
<div id="ws-tabs">	
	<ul>
		<li><a href="<?=$tabsUrl[0]?>#ws-main"><span><?=wfMsg('wikistats_main_statistics_legend')?></span></a></li>
		<li><a href="<?=$tabsUrl[1];?>#ws-month"><span><?=wfMsg('wikistats_current_month')?></span></a></li>
		<li><a href="<?=$tabsUrl[2];?>#ws-day"><span><?=wfMsg('wikistats_pageviews_daily')?></span></a></li>
	</ul>
	<div id="<?=$tabsName[0]?>"></div>
	<div id="<?=$tabsName[1]?>"></div>
	<div id="<?=$tabsName[2]?>"></div>
</div>
