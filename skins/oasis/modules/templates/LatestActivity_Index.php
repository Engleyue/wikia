<section class="WikiaActivityModule">
	<? if (!$wgSingleH1) { ?>
		<h1 class="activity-heading"><?= wfMsg('oasis-activity-header') ?></h1>
	<? } else { ?>
		<div class="headline-div activity-heading"><?= wfMsg('oasis-activity-header') ?></div>
	<? } ?>
	<ul>
<?php
	foreach ($changeList as $item) {
?>
		<li>
			<img src="<?= $wgBlankImgUrl ?>" class="avatar osprite <?= $item['changeicon'] ?>" height="20" width="20">
			<em><?= $item['page_href'] ?> <?= $item['changetype'] ?> <?= wfMsg('myhome-feed-by', $item['user_href']) ?></em>
			<details><?= $item['time_ago'] ?></details>
		</li>
<?php
	}
?>
	</ul>
	<?= View::specialPageLink('WikiActivity', 'oasis-more', 'more') ?>
</section>
