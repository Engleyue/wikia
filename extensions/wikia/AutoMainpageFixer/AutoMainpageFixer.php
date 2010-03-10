<?php
/**
 * keeps MediaWiki:Mainpage upto date when the main page is moved.
 * @package MediaWiki
 *
 * @author Chris Stafford <uberfuzzy@wikia-inc.com>
 */

$wgExtensionCredits['parserhook'][] = array(
	'name' => 'AutoMainpageFixer',
	'author' => array('[http://central.wikia.com/wiki/User:Uberfuzzy Chris Stafford (uberfuzzy)]', ),
	'version' => '1.0',
	'description' => 'Keeps MediaWiki:Mainpage upto date as the main page is moved.',
);

$wgHooks['TitleMoveComplete'][] = 'fnAutoMWMainpageFixer';

function fnAutoMWMainpageFixer( &$title, &$newtitle, &$user, $oldid, $newid ) {

	$mp = Title::newMainPage();
	if( $mp->getFullText() != $title ) {
		#NOT moving mainpage
		return true;
	}

	$title = Title::newFromText('Mainpage', NS_MEDIAWIKI);

	$article = new Article($title);
	$article_text = $newtitle;
	$edit_summary = '';
	#we REALLY dont want this to show up
	$flags = EDIT_UPDATE + EDIT_NEW + EDIT_FORCE_BOT + EDIT_SUPPRESS_RC;

	$article->doEdit($article_text, $edit_summary, $flags);

	return true;
}