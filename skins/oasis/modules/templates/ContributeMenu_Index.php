<?php

// render "Contribute" menu
echo wfRenderModule('MenuButton', 'Index', array(
	'action' => array(
		'text' => wfMsg('oasis-button-contribute-tooltip'),
	),
	'class' => 'contribute secondary',
	'image' => MenuButtonModule::CONTRIBUTE_ICON,
	'dropdown' => $dropdownItems,
));
