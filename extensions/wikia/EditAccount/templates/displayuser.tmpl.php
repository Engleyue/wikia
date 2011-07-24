<!-- s:<?= __FILE__ ?> -->
<small><a href="<?php print $returnURL; ?>">Return</a></small>
<?php if (!is_null($status)) { ?>
<fieldset>
	<legend><?php echo wfMsg('editaccount-status') ?></legend>
	<?php echo $status ? Wikia::successmsg($statusMsg) : Wikia::errormsg($statusMsg) ?>
	<?php if( !empty($statusMsg2) ){ echo Wikia::errormsg($statusMsg2); } ?>
</fieldset>
<?php } ?>
<fieldset>
	<legend><?php echo wfMsg('editaccount-frame-account', $user) ?></legend>
	<?php echo $userEncoded ?><br />
	ID: <?php echo $userId; ?><br />
	Reg: <?php echo $userReg ; ?><br />
	<table>
	<tr>
		<form method="post" action="">
		<td>
			<label for="wpNewEmail"><?php echo wfMsg('editaccount-label-email') ?></label>
		</td>
		<td>
			<input type="text" name="wpNewEmail" value="<?php echo $userEmail ?>" />
			<input type="submit" value="<?php echo wfMsg('editaccount-submit-email') ?>" />
			<input type="hidden" name="wpAction" value="setemail" />
			<input type="hidden" name="wpUserName" value="<?php echo $user_hsc ?>" />
		</td>
		</form>
	</tr>
	<tr>
		<form method="post" action="">
		<td>
			<label for="wpNewPass"><?php echo wfMsg('editaccount-label-pass') ?></label>
		</td>
		<td>
			<input type="text" name="wpNewPass" />
			<input type="submit" value="<?php echo wfMsg('editaccount-submit-pass') ?>" />
			<input type="hidden" name="wpAction" value="setpass" />
			<input type="hidden" name="wpUserName" value="<?php echo $user_hsc ?>" />
		</td>
		</form>
	</tr>
	<tr>
		<form method="post" action="">
		<td>
			<label for="wpNewRealName"><?php echo wfMsg('editaccount-label-realname') ?></label>
		</td>
		<td>
			<input type="text" name="wpNewRealName" value="<?php echo $userRealName ?>" />
			<input type="submit" value="<?php echo wfMsg('editaccount-submit-realname') ?>" />
			<input type="hidden" name="wpAction" value="setrealname" />
			<input type="hidden" name="wpUserName" value="<?php echo $user_hsc ?>" />
		</td>
		</form>
	</tr>
<?php if( $isUnsub ) { ?>
	<tr>
		<form method="post" action="">
		<td><?php echo wfMsg('editaccount-label-clearunsub') ?></td>
		<td>
			<input type="submit" value="<?php echo wfMsg('editaccount-submit-clearunsub') ?>" />
			<input type="hidden" name="wpAction" value="clearunsub" />
			<input type="hidden" name="wpUserName" value="<?php echo $user_hsc ?>" />
		</td>
		</form>
	</tr>
<?php } //end unsub ?>
	</table>
</fieldset>
<fieldset>
	<legend><?php echo wfMsg('editaccount-frame-close', $user) ?></legend>
	<p><?php echo wfMsg('editaccount-usage-close') ?></p>
	<form method="post" action="">
		<input type="submit" value="<?php echo wfMsg('editaccount-submit-close') ?>" />
		<input type="hidden" name="wpAction" value="closeaccount" />
		<input type="hidden" name="wpUserName" value="<?php echo $user_hsc ?>" />
	</form>
<?php if( $isDisabled ) { ?>
<?php print wfMsg('edit-account-closed-flag'); ?>
	<form method="post" action="">
		<input type="submit" value="<?php echo wfMsg('editaccount-submit-cleardisable') ?>" />
		<input type="hidden" name="wpAction" value="cleardisable" />
		<input type="hidden" name="wpUserName" value="<?php echo $user_hsc ?>" />
	</form>
<?php } //end undisable ?>
</fieldset>
<!-- e:<?= __FILE__ ?> -->
