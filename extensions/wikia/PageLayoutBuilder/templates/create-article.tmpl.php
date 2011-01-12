<?php if($isdelete): ?>
	<div class="WikiaConfirmation">
		<p class="plainlinks"><?php echo wfMsg('plb-special-form-layout-delete'); ?></p>
	</div>
<?php endif;?>

<?php if($iserror): ?>
	<div class="plb-form-errorbox" >
		<strong><?php echo wfMsg('plb-special-form-error-info'); ?></strong>
		<ul>
			<?php foreach ($errors as $value): ?>
				<li><?php echo $value; ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>

<?php if($ispreview): ?>
	<div class="plb-preview-div">
		<?php echo $previewdata; ?>
	</div>
	<div class="plb-article-form-space separator" ></div>
<?php endif; ?>

<input type="hidden" id="wpPlbId" name="wpPlbId" value="<?php echo $plbId; ?>" />

<form name="plbForm" id="plbForm" action="<?php echo $url ?>" method="post" >
	<div class="plb-article-form-space" ></div>
	<?php if(!$editmode): ?>
		<p>
			<span class="plb-article-title"><?php echo wfMsg('plb-special-form-article-name'); ?></span><br />
			<input class="plb-article-title plb-input" <?php echo ($title_error ? "style='border-color: red; border-style: solid;'":""); ?> type="text" name="wgArticleName" id="wgArticleName" value="<?php echo $data['articleName'] ?>" />
		</p>
	<?php endif; ?>
	<?php echo $layout; ?>
	<div class="plb-article-form-end" ><?php echo wfMsg('plb-special-form-required'); ?></div>
	<div>
		<?php echo $catHtml; ?>
	</div>	
	<ul class="plb-form-actions" >
		<li>
			<span><?php echo wfMsg('plb-special-form-summary'); ?></span><input tabindex="1" maxlength="200" id="wpSummary" value="" size="50" name="wpSummary">
		</li>
		<li>
			<input type="submit" name="wpSave" value="<?php echo wfMsg( 'plb-special-form-submit-button' ); ?>" />
		</li>
		<li>
			<input type="submit" name="wpPreview" value="<?php echo wfMsg( 'plb-special-form-preview-button' ); ?>" />
		</li>
	</ul>
	<?php echo $wpEditToken; ?>
</form>