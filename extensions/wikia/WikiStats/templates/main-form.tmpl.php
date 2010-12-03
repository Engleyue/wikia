<?
$tabs = array(
	'main' 			=> array('url' => sprintf( "%s/main", $mTitle->getLocalUrl() ), 'text' => wfMsg('wikistats_main_statistics_legend') )
);

if ( $userIsSpecial ) {
	$tabs['rollup']			= array('url' => sprintf( "%s/rollup", $mTitle->getLocalUrl() ), 'text' => wfMsg('wikistats_rollups') );
	$tabs['namespaces']		= array('url' => sprintf( "%s/namespaces", $mTitle->getLocalUrl() ), 'text' => wfMsg('wikistats_ns_statistics_legend') );
	$tabs['breakdown'] 		= array('url' => sprintf( "%s/breakdown", $mTitle->getLocalUrl() ), 'text' => wfMsg('wikistats_breakdown_editors') );
	$tabs['anonbreakdown'] 	= array('url' => sprintf( "%s/anonbreakdown", $mTitle->getLocalUrl() ), 'text' => wfMsg('wikistats_breakdown_anons') );
	$tabs['latestview']		= array('url' => sprintf( "%s/latestview", $mTitle->getLocalUrl() ), 'text' => wfMsg('wikistats_latest_pageviews') );
	$tabs['userview'] 		= array('url' => sprintf( "%s/userview", $mTitle->getLocalUrl() ), 'text' => wfMsg('wikistats_latest_userviews') );
	$tabs['activity'] 		= array('url' => sprintf( "%s/activity", $mTitle->getLocalUrl() ), 'text' => wfMsg('wikistats_active_useredits') );
}
?>
<div id="ws-addinfo" class="ws-addinfo"></div>
<div id="ws-tabs" class="wikia-tabs">
	<ul id="ws_action_tabs">
<? foreach ( $tabs as $id => $values ) : ?>		
		<li id="ws_tab_<?=$id?>" class="<?= ($id == $mAction) ? 'selected' : ''?>"><a rel="nofollow" href="<?=$values['url']?>"><?=$values['text']?></a></li>
<? endforeach ?>
	</ul>
</div>
