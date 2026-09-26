<?php
// This file is generated. Do not modify it manually.
return array(
	'react-plugin' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'create-block/react-plugin',
		'version' => '0.1.0',
		'title' => 'React Plugin',
		'category' => 'widgets',
		'icon' => 'smiley',
		'description' => 'Example block scaffolded with Create Block tool.',
		'example' => array(
			
		),
		'attributes' => array(
			'airport' => array(
				'type' => 'string'
			),
			'limit' => array(
				'type' => 'number'
			)
		),
		'supports' => array(
			'html' => false
		),
		'textdomain' => 'react-plugin',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	)
);
