<?php
	if (is_array($action) && !empty($action)) {
		if (empty($dropdown)) {
			// user is not logged in
			if (!empty( $promptLogin )) {
?>
			<?= View::specialPageLink('SignUp', 'oasis-edit-protected-article', 'wikia-button loginToEditProtectedPage', 'blank.gif', 'oasis-edit-protected-article', 'sprite edit-pencil', $loginURL); ?>
<?php
			}
			// render simple edit button
			else {
?>
			<a accesskey="e" href="<?= htmlspecialchars($action['href']) ?>" class="<?= $class ?>" data-id="<?= $actionName ?>"><?= $icon ?> <?= htmlspecialchars($action['text']) ?></a>
<?php
			}
		}
		// render edit button with dropdown
		else {
?>
<ul class="<?= $class ?>">
	<li>
<?php
			if ( !empty( $promptLogin ) ) {
?>
			<?= View::specialPageLink('SignUp', 'oasis-edit-protected-article', 'wikia-button loginToEditProtectedPage', 'blank.gif', 'oasis-edit-protected-article', 'sprite edit', $loginURL) ?>
<?php
			} else {
?>
			<a <?= !empty($actionAccessKey) ? "accesskey=\"{$actionAccessKey}\"" : '' ?> href="<?= empty($action['href']) ? '' : htmlspecialchars($action['href']) ?>" data-id="<?= $actionName ?>"><?= $icon ?> <?= htmlspecialchars($action['text']) ?></a>
<?php 
			} 
?>
		<img src="<?= $wgBlankImgUrl ?>" class="chevron">
		<ul>
<?php
			foreach($dropdown as $key => $item) {
				// render accesskeys
				if (isset($item['accesskey'])) {
					$accesskey = ' accesskey="' . $item['accesskey'] . '"';
				}
				else {
					$accesskey = '';
				}
?>
			<li><a href="<?= htmlspecialchars($item['href']) ?>"<?= $accesskey ?> data-id="<?= $key ?>"><?= htmlspecialchars($item['text']) ?></a></li>
<?php
			}
?>
		</ul>
	</li>
</ul>
<?php
		}
	}
?>
