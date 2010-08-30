		<ul class="history">
			<li>
<?php
	if (!empty($revisions['current']['avatarUrl'])) {
?>
					<img src="<?= $revisions['current']['avatarUrl'] ?>" width="20" height="20" class="avatar">
<?php
	}

	if (isset($revisions['current']['link'])) {
		// edit with contributor
		echo wfMsg('oasis-page-header-edited-by', $revisions['current']['timestamp'], $revisions['current']['link']);
	}
	else {
		// edit without contributor (if it's bot or blocked user)
		echo '<span class="no-avatar">' . wfMsg('oasis-page-header-edited', $revisions['current']['timestamp']) . '</span>';
	}
?>
				<img src="<?= $wgBlankImgUrl ?>" class="chevron">
			</li>
<?php
	// remove current revision
	array_shift($revisions);

	foreach($revisions as $entry) {
?>
			<li><img src="<?= $wgBlankImgUrl ?>" data-realUrl="<?= $entry['avatarUrl'] ?>" width="20" height="20" class="avatar"> <?= wfMsg('oasis-page-header-edited-by', $entry['timestamp'], $entry['link']) ?></li>
<?php
	}
?>
<?php
	// render link to history
	if (isset($content_actions['history'])) {
?>
			<li class="view-all">
				<a accesskey="h" href="<?= htmlspecialchars($content_actions['history']['href']) ?>"><?= wfMsg('oasis-page-header-history-link') ?></a>
			</li>
<?php
	}
?>
		</ul>
