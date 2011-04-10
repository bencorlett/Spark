<?php
/**
 * Ignite 'Ben Corlett' Fuel Package
 * 
 * The Ignite Fuel Package is an open-source
 * fuel package constisting of 'widgets'
 * engineered to make developing
 * administration systems easier and quicker.
 * 
 * @package    Fuel
 * @subpackage Ignite
 * @author     Ben Corlett (http://www.bencorlett.com)
 * @license    MIT License
 * @copyright  (c) 2011 Ben Corlett
 * @link       http://www.github.com/bencorlett/bc
 */

Autoloader::add_core_namespace('Ignite');

Autoloader::add_classes(array(
	
	// Grid
	'Ignite\\Grid'										=> __DIR__ . '/classes/grid.php',
	'Ignite\\Grid_Column'								=> __DIR__ . '/classes/grid/column.php',
	'Ignite\\Grid_Column_Filter_Abstract'				=> __DIR__ . '/classes/grid/column/filter/abstract.php',
	'Ignite\\Grid_Column_Filter_Text'					=> __DIR__ . '/classes/grid/column/filter/text.php',
	'Ignite\\Grid_Column_Renderer_Abstract'				=> __DIR__ . '/classes/grid/column/renderer/abstract.php',
	'Ignite\\Grid_Column_Renderer_Text'					=> __DIR__ . '/classes/grid/column/renderer/text.php',
	'Ignite\\Grid_Driver_Abstract'						=> __DIR__ . '/classes/grid/driver/abstract.php',
	'Ignite\\Grid_Driver_KohanaOrm'						=> __DIR__ . '/classes/grid/driver/kohanaorm.php',
	'Ignite\\Grid_Driver_Orm'							=> __DIR__ . '/classes/grid/driver/orm.php',
	
	// Exception
	'Ignite\\Exception'									=> __DIR__ . '/classes/exception.php',
	
	// Object
	'Ignite\\Object'									=> __DIR__ . '/classes/object.php',
	
	// Str
	'Ignite\\Str'										=> __DIR__ . '/classes/str.php',
));