<?php
/**
 * Spark Fuel Package By Ben Corlett
 * 
 * Spark - Set your fuel on fire!
 * 
 * The Spark Fuel Package is an open-source
 * fuel package consisting of several 'widgets'
 * engineered to make developing
 * administration systems easier and quicker.
 * 
 * @package    Fuel
 * @subpackage Spark
 * @author     Ben Corlett (http://www.bencorlett.com)
 * @license    MIT License
 * @copyright  (c) 2011 Ben Corlett
 * @link       http://www.github.com/bencorlett/bc
 */

Autoloader::add_core_namespace('Spark');

Autoloader::add_classes(array(
	
	// Grid
	'Spark\\Grid'										=> __DIR__ . '/classes/grid.php',
	'Spark\\Grid_Column'								=> __DIR__ . '/classes/grid/column.php',
	'Spark\\Grid_Column_Filter_Abstract'				=> __DIR__ . '/classes/grid/column/filter/abstract.php',
	'Spark\\Grid_Column_Filter_Text'					=> __DIR__ . '/classes/grid/column/filter/text.php',
	'Spark\\Grid_Column_Renderer_Abstract'				=> __DIR__ . '/classes/grid/column/renderer/abstract.php',
	'Spark\\Grid_Column_Renderer_Text'					=> __DIR__ . '/classes/grid/column/renderer/text.php',
	'Spark\\Grid_Driver_Abstract'						=> __DIR__ . '/classes/grid/driver/abstract.php',
	'Spark\\Grid_Driver_KohanaOrm'						=> __DIR__ . '/classes/grid/driver/kohanaorm.php',
	'Spark\\Grid_Driver_Orm'							=> __DIR__ . '/classes/grid/driver/orm.php',
	
	// Exception
	'Spark\\Exception'									=> __DIR__ . '/classes/exception.php',
	
	// Object
	'Spark\\Object'									=> __DIR__ . '/classes/object.php',
	
	// Str
	'Spark\\Str'										=> __DIR__ . '/classes/str.php',
));