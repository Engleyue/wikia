<header id="WikiHeader" class="WikiHeaderRestyle">
	<h1 class="wordmark <?= $wordmarkSize ?> <?= $wordmarkType ?>">
		<a accesskey="z" href="<?= htmlspecialchars($mainPageURL) ?>">
			<? if (!empty($wordmarkUrl)) { ?>
				<img src="<?= $wordmarkUrl ?>" alt="<?= htmlspecialchars($wordmarkText) ?>">
			<? } else { ?>
				<?= htmlspecialchars($wordmarkText) ?>
			<? } ?>
		</a>
	</h1>
	<nav>
		<h1><?= wfMsg( 'oasis-wiki-navigation', $wordmarkText ); ?></h1>
		<?php
			// render wiki navigation
			echo wfRenderModule('WikiNavigation');
		?>
	</nav>
	<div class="buttons">
		<?php
			// render "Contribute" menu
			echo wfRenderModule('ContributeMenu');
			echo wfRenderModule('SharingToolbar', 'ShareButton');
		?>
	</div>

	<div style="position: absolute; top: -1000px"><?php
			echo Wikia::specialPageLink('Watchlist', 'watchlist', array('accesskey' => 'l'));
			echo Wikia::specialPageLink('Random', 'randompage', array('accesskey' => 'x'));
			echo Wikia::specialPageLink('RecentChanges', 'recentchanges', array('accesskey' => 'r'));
	?></div>
	<img class="shadow-mask" src="<?= $wgBlankImgUrl ?>" width="0" height="0">

	<? echo wfRenderModule('SharingToolbar'); ?>
</header>
<?= $displaySearch ? '<div class="adm-dash-search">'.wfRenderModule('Search').'</div>' : '' ?>