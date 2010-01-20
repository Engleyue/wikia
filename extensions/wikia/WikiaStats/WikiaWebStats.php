<?php
if(!defined('MEDIAWIKI')) {
    exit( 1 ) ;
}

$wgHooks['SkinAfterBottomScripts'][] = 'wfWikiaWebStatsScript';

function wfWikiaWebStatsScript($this, $bottomScriptText) {
	global $wgUser, $wgArticle, $wgTitle, $wgCityId, $wgDBname, $wgDBcluster, $wgDotDisplay, $wgAdServerTest, $wgReadOnly;
	if ( !empty($wgCityId) && !empty($wgDotDisplay) && empty($wgReadOnly) ) {
		$url = 'http://10.10.10.145/onedot.php?c='.$wgCityId.'&amp;x='.$wgDBname.'&amp;y='.$wgDBcluster.'&amp;u='.$wgUser->getID().'&amp;a='.(is_object($wgArticle) ? $wgArticle->getID() : null).'&amp;n='.$wgTitle->getNamespace().(!empty($wgAdServerTest) ? '&amp;db_test=1' : '');
		$bottomScriptText .= '<script type="text/javascript">/*<![CDATA[*/document.write("<img src=\"'.$url.'"+((typeof document.referrer != "undefined") ? "&amp;r="+escape(document.referrer) : "")+"&amp;cb="+(new Date).valueOf()+"\" width=\"1\" height=\"1\" border=\"0\" alt=\"\" />");/*]]>*/</script><noscript><img src="'.$url.'" width="1" height="1" border="0" alt="" /></noscript>';
		$bottomScriptText .= "\n";
	}
	return true;
}
