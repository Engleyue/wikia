<header id="WikiaPageHeader" class="WikiaPageHeader<?= (empty($revisions) && empty($categories)) ? ' separator' : '' ?>">
	<? if ($isMainPage) { ?>
		<?= wfRenderModule('CommentsLikes', 'Index', array('comments' => $comments, 'likes' => $likes)); ?>
		<?php if( empty( $wgEnableWikiAnswers ) ) {
			$loginClass = empty($wgDisableAnonymousEditing) ? '' : ' require-login';
		?>
		<div class="mainpage-add-page">
			<?= Wikia::specialPageLink('CreatePage', null, 'createpage', 'blank.gif', 'oasis-create-page', 'sprite new' . $loginClass); ?>
			<?= Wikia::specialPageLink('CreatePage', 'oasis-add-page', 'createpage' . $loginClass); ?>
		</div>
		<?php } ?>
		<div class="tally mainpage-tally">
			<?= wfMsgExt('oasis-total-articles-mainpage', array( 'parsemag' ), $total, 'fixedwidth' ) ?>
		</div>
	<? } ?>

	<?
	// comments & like button
	if (empty($isMainPage)) {
		echo wfRenderModule('CommentsLikes', 'Index', array('comments' => $comments, 'likes' => $likes));
	}
	?>

	<h1><?= !empty($displaytitle) ? $title : htmlspecialchars($title) ?></h1>

<?php
	// edit button with actions dropdown
	if (!empty($action)) {
		echo wfRenderModule('MenuButton', 'Index', array('action' => $action, 'image' => $actionImage, 'dropdown' => $dropdown, 'name' => $actionName));
	}

	// "Add a photo" button
	if (!empty($isNewFiles) && !empty($wgEnableUploads)) {
		echo Wikia::specialPageLink('Upload', 'oasis-add-photo', (!$isUserLoggedIn ? 'wikia-button upphotoslogin' :'wikia-button upphotos'), 'blank.gif', 'oasis-add-photo', 'sprite photo');
	}

	// render page type line
	if ($pageSubtitle != '') {
?>
	<h2><?= $pageSubtitle ?></h2>
<?php
	}

	// MW subtitle
	if ($subtitle != '') {
?>
	<div class="subtitle"><?= $subtitle ?></div>
<?php
	}

	// include undelete message (BugId:1137)
	if ($undelete != '') {
?>
	<div id="contentSub2"><?= $undelete ?></div>
<?php
	}

	// render search box
	if ($showSearchBox) {
		echo wfRenderModule('Search');
	}
?>
</header>
