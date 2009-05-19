<?php
/**
 * WikiaIrcGateway
 *
 * Allows users to add an IRC gateway login form to any article
 *
 * @file
 * @ingroup Extensions
 * @author Łukasz Garczewski (TOR) <tor@wikia-inc.com>
 * @date 2009-05-19
 * @copyright Copyright © 2009 Łukasz Garczewski, Wikia Inc.
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

if( !defined( 'MEDIAWIKI' ) ) {
        echo "This is a MediaWiki extension named WikiaIrcGateway.\n";
        exit( 1 );
}

// Extension credits that will show up on Special:Version
$wgExtensionCredits['parserhook'][] = array(
	'name' => 'Wikia IRC Gateway',
	'author' => "[http://www.wikia.com/wiki/User:TOR Łukasz 'TOR' Garczewski]",
	'description' => 'Lets users insert IRC login form on any page'
);

$wgExtensionFunctions[] = "wfWikiaIrcGateway";

function wfWikiaIrcGateway() {
	global $wgParser, $wgExtensionMessagesFiles;
	$wgParser->setHook( "irclogin", "printWikiaIrcGatewayLoginForm" );
	$wgExtensionMessagesFiles['WikiaIrcGateway'] = dirname( __FILE__ ) . '/WikiaIrcGateway.i18n.php';
}

function printWikiaIrcGatewayLoginForm( $input, $argv ) {

	wfLoadExtensionMessages('WikiaIrcGateway');

	$output = '<div id="ircform_container">
<form id="ircform" method="post" action="http://irc.wikia.com/irc.cgi" name="loginform">
	<input type="hidden" name="interface" value="nonjs">
	<input type="hidden" name="Server" value="irc.freenode.net" disabled="1">
	<table>
		<tr>
			<td> ' . wfMsg('ircgate-username') . '</td>
			<td>
				<input type="text" name="Nickname" value="">
				<input type="submit" value="Login">
			</td>
		</tr>
		<tr>
			<td>' . wfMsg('ircgate-channel') . '</td>
			<td>
				<select name="Channel">';

	$array = explode( "\n*", wfMsgForContent('ircgate-channellist') );

	foreach ( $array as $line ) {
		if ( strpos( ltrim( $line, '* ' ), 'group: ' ) === 0 ) {
			$output .= '<optgroup label="' . htmlspecialchars( substr( ltrim( $line, '* ' ), 7 ) ) . '">';
		} elseif ( strpos( ltrim( $line, '* ' ), 'group-end' ) === 0 ) {
			$output .= "</optgroup>\n";
		} else {
			$output .= '<option>' . htmlspecialchars( ltrim( $line, '* ') ) . "</option>\n";
		}
	}

	$output .= '			</select>
			</td>
		</tr>
	</table>   
</form>
</div>';

	return $output;

}
