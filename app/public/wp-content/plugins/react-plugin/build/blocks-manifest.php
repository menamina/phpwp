<?php
// This file is generated. Do not modify it manually.
return array(
	'flight-board' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'create-block/flight-board',
		'version' => '0.1.0',
		'title' => 'Flight Board',
		'category' => 'widgets',
		'icon' => 'airplane',
		'description' => 'Display live flight departure and arrival information',
		'example' => array(
			
		),
		'attributes' => array(
			'airport' => array(
				'type' => 'string',
				'default' => 'ORD'
			),
			'limit' => array(
				'type' => 'number',
				'default' => 100
			)
		),
		'supports' => array(
			'html' => false
		),
		'textdomain' => 'react-plugin',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php'
	),
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
				'type' => 'number',
				'default' => 100
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
