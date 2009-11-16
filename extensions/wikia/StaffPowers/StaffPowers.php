<?php
/**
 * Applies staff powers to select users, like unblockableness, superhuman strenght and
 * general awesomeness.
 *
 * @author Lucas Garczewski <tor@wikia-inc.com>
 */

$wgExtensionMessagesFiles['StaffPowers'] = dirname(__FILE__) . '/StaffPowers.i18n.php';

// Power: unblockableness
$wgHooks['BlockIp'][] = 'efPowersMakeUnblockable';
$wgAvailableRights[] = 'unblockable';
$wgGroupPermissions['staff']['unblockable'] = true;

function efPowersMakeUnblockable( $block, $user ) {
	$blockedUser = User::newFromName( $block->getRedactedName() );

        if ( $blockedUser === null || !$blockedUser->isAllowed( 'unblockable' ) ) {
		return true;
	}

	global $wgMessageCache;

	wfLoadExtensionMessages( 'StaffPowers' );

	// hack to get IpBlock to display the message we want -- hardcoded in core code
	$replacement = wfMsgExt( 'staffpowers-ipblock-aborted', array('parseinline') );
	$wgMessageCache->addMessages( array( 'hookaborted' => $replacement ) );
	wfRunHooks('BlockIpStaffPowersCancel', array($block, $user));
	return false;
}


