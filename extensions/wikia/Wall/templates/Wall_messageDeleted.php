<header id="WikiaPageHeader" class="WikiaPageHeader separator">
	<h1><?= wfMsg('wall-deleted-msg-pagetitle'); ?></h1>
</header>
<div class="WikiaArticle" id="WikiaArticle">
	<?= wfMsg('wall-deleted-msg-text'); ?>
	<a href="<?= $wallUrl ?>"><?=  wfMsg('wall-deleted-msg-return-to', $wallOwner) ?></a>
</div>
