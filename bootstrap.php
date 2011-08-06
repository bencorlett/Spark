<?php
/**
 * Spark Fuel Package By Ben Corlett
 * 
 * Spark - Set your fuel on fire!
 * 
 * The Spark Fuel Package is an open-source
 * fuel package consisting of several 'widgets'
 * engineered to make developing various
 * web-based systems easier and quicker.
 * 
 * @package    Fuel
 * @subpackage Spark
 * @version    1.0
 * @author     Ben Corlett (http://www.bencorlett.com)
 * @license    MIT License
 * @copyright  (c) 2011 Ben Corlett
 * @link       http://spark.bencorlett.com
 */
Autoloader::add_core_namespace('Spark');

Autoloader::add_classes(array(
	
	// Exception
	'Spark\\Exception'							=> __DIR__ . '/classes/exception.php',
	
	// Grid
	'Spark\\Grid'								=> __DIR__ . '/classes/grid.php',
	'Spark\\Grid_Component'						=> __DIR__ . '/classes/grid/component.php',
	'Spark\\Grid_Driver_Abstract'				=> __DIR__ . '/classes/grid/driver/abstract.php',
	'Spark\\Grid_Driver_Orm'					=> __DIR__ . '/classes/grid/driver/orm.php',
	
	// Object
	'Spark\\Object'								=> __DIR__ . '/classes/object.php',
	
	// Str
	'Spark\\Str'								=> __DIR__ . '/classes/str.php',
));