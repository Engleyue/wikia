<form method="POST" id="dataForm">
	<? if ( !empty( $message ) ) :?>
		<p class="successbox"><?= $message ;?></p>
	<? endif ;?>
	
	<? if ( !empty( $errors['db'] ) ) :?>
		<p class="errorbox"><?= implode( '<br/>', $errors['db'] ) ;?></p>
	<? endif ;?>
	
	<label for="categoryName"><?= $wf->Msg( 'photopop-setup-category-label' ) ;?></label>
	<input type="text" name="category" id="categoryName" value="<?= $category ;?>"<?=
		( !empty( $errors['category'] ) ) ? ' class="error"' : null;
	?>placeholder="<?= $wf->Msg( 'photopop-setup-category-tip' ) ;?>"/>
	
	<? if ( !empty ( $errors['category'] ) ) :?>
		<ul class="error">
			<? foreach ( $errors['category'] as $error ) :?>
				<li><?= $error ;?></li>
			<? endforeach ;?>
		</ul>
	<? endif ;?>
	
	<label for="gameIcon"><?= $wf->Msg( 'photopop-setup-icon-label' ) ;?></label>
	<input type="text" name="icon" id="gameIcon" value="<?= $icon ;?>"<?=
		( !empty( $errors['icon'] ) ) ? ' class="error"' : null;
	?>placeholder="<?= $wf->Msg( 'photopop-setup-icon-tip' ) ;?>"/>
	
	<? if ( !empty ( $errors['icon'] ) ) :?>
		<ul class="error">
			<? foreach ( $errors['icon'] as $error ) :?>
				<li><?= $error ;?></li>
			<? endforeach ;?>
		</ul>
	<? endif ;?>
	
	<input type="submit" value="<?= $wf->Msg( 'photopop-setup-submit-label' ) ;?>"/>
</form>
<div id="currentSettings">
	<span><?= $wf->Msg( 'photopop-current-settings-title' ) ;?></span>
	<ul>
		<li>
			<?= $wf->Msg( 'photopop-setup-category-label' ) ;?>:
			<? if ( !empty( $currentCategoryUrl ) ) :?>
				<a<?= ( !empty( $currentCategoryUrl ) ) ? " href=\"{$currentCategoryUrl}\"" : null ;?>><?= $currentCategory ;?></a>
			<? else: ?>
				<?= $currentCategory ;?>
			<? endif ;?>
		</li>
		<li>
			<?= $wf->Msg( 'photopop-setup-icon-label' ) ;?>:
			<img src="<?= $currentIconUrl ;?>"/>
		</li>
	</ul>
</div>