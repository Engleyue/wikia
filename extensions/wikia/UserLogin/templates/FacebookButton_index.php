<?php
	echo wfRenderModule('MenuButton', 'Index', array(
		'name' => 'facebook',
		'action' => array(
			'text' => $text,
			'href' => '#',
			'accesskey' => false,
		),
		'class' => trim("wikia-button-facebook {$class}"),
		'image' => MenuButtonModule::FACEBOOK_ICON,
		'tooltip' => $tooltip,
	));