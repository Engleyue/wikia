<?php

$wgHooks['SkinTemplateOutputPageBeforeExec'][] = 'UserProfile_handler';

function UserProfile_handler(&$skin, &$tpl) {
	global $wgTitle, $wgOut, $wgRequest;
	$wgOut->addStyle( "common/userpage_sidebar.css" );

	wfProfileIn(__METHOD__);
	$action = $wgRequest->getVal('action', 'view');
	if ($wgTitle->getNamespace() != NS_USER || ($action != 'view' && $action != 'purge')) {
		return true;
	};
	$html = '';

	wfRunHooks('AddToUserProfile', array(&$out));

	if(count($out) > 0) {
		$html .= "<div id='profile-content'>";
		$html .= "<div id='profile-content-inner'>";
		$html .= $tpl->data['bodytext'];
		$html .= "</div>";
		
		$html .= '<div id="profile-sidebar">';
		if(isset($out['UserProfile1'])) {
			$html .= $out['UserProfile1'];
		}
		if(isset($out['followedPages'])) {
			$html .= $out['followedPages'];
		}
		$html .= '</div>';		
		
		$tpl->data['bodytext'] = $html;
	}
	wfProfileOut(__METHOD__);
	return true;
}
