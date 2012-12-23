<?php

Autoloader::add_core_namespace('GPX');

Autoloader::add_classes(array(

	/**
	 * GPX classes.
	 */
	'GPX\\GPX'							=> __DIR__.'/classes/gpx.php',
	'GPX\\Importer'						=> __DIR__.'/classes/gpx/importer.php',

));