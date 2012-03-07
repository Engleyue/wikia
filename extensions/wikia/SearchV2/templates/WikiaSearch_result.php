<!-- single search result -->
<?php if($result->hasCanonical()): ?>
	<?=$debug?$pos.'. ':'';?><a href="<?= $result->getUrl(); ?>"><?=$result->getTitle();?></a> (Redirect: <?=$result->getCanonical();?>)<br />
<?php else: ?>
	<?=$debug?$pos.'. ':'';?><a href="<?= $result->getUrl(); ?>"><?=$result->getTitle();?></a><br />
<?php endif; ?>
<div <?=empty($inGroup)?'class="searchresult"':'';?>>
	<?= $result->getText(); ?>
</div>
<?php if(empty($inGroup)): ?>
	<a href="<?= $result->getUrl(); ?>"><?=$result->getUrl();?></a><br />
<?php endif; ?>

<?php if($debug): ?>
	<?php
		switch($rankExpr) {
			case '-indextank':
				$rankValue = $result->getVar('rank_indextank');
				break;
			case '-bl':
				$rankValue = $result->getVar('rank_bl');
				break;
			case '-bl2':
				$rankValue = $result->getVar('rank_bl2');
				break;
			case '-bl3':
				$rankValue = $result->getVar('rank_bl3');
				break;
			default:
				$rankValue = '?';
		}
	?>
	<i>[id: <?=$result->getId();?>, text_relevance: <?=$result->getVar('text_relevance', '?');?>, backlinks: <?=$result->getVar('backlinks', '?');?>, rank: <?= $rankValue; ?>]</i><br />
<?php endif; //debug ?>
<br />
