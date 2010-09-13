<div id="WikiaPageHeader" class="WikiaPageHeader">
	<a name="EditPage"></a>
	<h1><?= $displaytitle != "" ? $title : htmlspecialchars($title) ?></h1>
<?php
	// edit button
	if (!empty($action)) {
		echo wfRenderModule('MenuButton', 'Index', array('action' => $action, 'dropdown' => $dropdown, 'image' => $actionImage, 'name' => $actionName));
	}
?>
	<p><?= $subtitle ?></p>

<?php
	// render search box
	if ($showSearchBox) {
		echo wfRenderModule('Search');
	}
?>
</div>