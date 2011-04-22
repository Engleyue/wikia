<header id="WikiHeader" class="WikiHeader">
	<h1 class="wordmark <?= $wordmarkSize ?> <?= $wordmarkType ?>" <?= $wordmarkStyle ?>>
		<a accesskey="z" href="<?= htmlspecialchars($mainPageURL) ?>">
			<? if (!empty($wordmarkUrl)) { ?>
				<img src="<?= $wordmarkUrl ?>" alt="<?= htmlspecialchars($wordmarkText) ?>">
			<? } else { ?>
				<?= htmlspecialchars($wordmarkText) ?>
			<? } ?>
		</a>
	</h1>
	<nav>
		<h1><?= htmlspecialchars($wordmarkText) ?> Navigation</h1>
		<ul>

<?php
if(is_array($menuNodes) && isset($menuNodes[0])) {
	foreach($menuNodes[0]['children'] as $level0) {
?>
			<li>
				<a href="<?= $menuNodes[$level0]['href'] ?>">
					<?= $menuNodes[$level0]['text'] ?><?php /*cannot be space between text and &nbsp;*/ if(isset($menuNodes[$level0]['children'])) { ?>&nbsp;<img src="<?= $wgBlankImgUrl; ?>" class="chevron" width="0" height="0"><?php } ?>
				</a>
<?php
		if(isset($menuNodes[$level0]['children'])) {
?>
				<ul class="subnav">
<?php
			foreach($menuNodes[$level0]['children'] as $level1) {
?>
					<li>
						<a href="<?= $menuNodes[$level1]['href'] ?>"><?= $menuNodes[$level1]['text'] ?></a>
					</li>
<?php
			}
?>
<?php
			if($editURL) {
?>
					<li class="edit-menu last">
						<a href="<?= $editURL['href'] ?>"><?= $editURL['text'] ?></a>
					</li>
<?php
			}
?>

				</ul>
<?php
		}
?>
			</li>
<?php
	}
}
?>
		</ul>
	</nav>
	<div class="buttons">
<?php if ($wgEnableCorporatePageExt) {
		if (ArticleAdLogic::isMainPage() || BodyModule::isCorporateLandingPage()) echo wfRenderModule('Search');
		echo wfRenderModule('RandomWiki');
} else { ?>
		<?= Wikia::specialPageLink('Random', 'oasis-button-random-page', array('accesskey' => 'x', 'class' => 'wikia-button secondary', 'data-id' => 'randompage'), 'blank.gif', null, 'sprite random') ?>
		<?= Wikia::specialPageLink('WikiActivity', 'oasis-button-wiki-activity', array('accesskey' => 'g', 'class' => 'wikia-button secondary', 'data-id' => 'wikiactivity'), 'blank.gif', null, 'sprite activity') ?>
<?php } ?>
	</div>
	<div style="position: absolute; top: -1000px">
		<?= Wikia::specialPageLink('Watchlist', 'watchlist', array('accesskey' => 'l')) ?>
		<?= Wikia::specialPageLink('RecentChanges', 'recentchanges', array('accesskey' => 'r')) ?>
	</div>
	<img class="shadow-mask" src="<?= $wgBlankImgUrl ?>" width="0" height="0">
</header>