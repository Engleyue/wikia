<section class="WikiaPoll<?= $embedded ? ' WikiaPollEmbedded' : '' ?>" data-id="<?= $poll->getId() ?>">
<?php
	if ($embedded) {
?>
	<h2><?= htmlspecialchars(wfMsg('wikiapoll-question', $poll->getTitle())) ?></h2>
<?php
	}
?>
	<form>
		<ul>
<?php
	foreach($data['answers'] as $n => $answer) {
		$class = $n % 2 ? ' class="odd"' : '';
?>
			<li<?= $class ?>>
				<label>
					<input type="radio" value="<?= $n ?>" name="wpAnswer" />
					<?= htmlspecialchars($answer['text']) ?>
				</label>
				<span class="bar" style="width: <?= $answer['bar-width'] ?>%">
					<span class="percentage"><?= $answer['percentage'] ?>%</span>
					<span class="votes"><?= wfMsgExt('wikiapoll-votes', array('parsemag'), $answer['votes']) ?></span>
				</span>
			</li>
<?php
	}
?>
		</ul>
		<details>
			<span class="votes"><?= wfMsgExt('wikiapoll-people-voted', array('parsemag'), $data['votes']) ?></span>
			<input type="submit" name="wpVote" value="<?= wfMsg('wikiapoll-vote') ?>" style="display:none" />
		</details>
	</form>

	<span class="progress"><?= wfMsg('wikiapoll-thanks-for-vote') ?></span>

<?php /*<pre><?= print_r($data, true) ?></pre> */ ?>

</section>
