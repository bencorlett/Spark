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
	
	// Alert
	'Spark\\Alert'								=> __DIR__ . '/classes/alert.php',
	
	// Breadcrumbs
	'Spark\\Breadcrumbs'						=> __DIR__ . '/classes/breadcrumbs.php',
	
	
	// Exception
	'Spark\\Exception'							=> __DIR__ . '/classes/exception.php',
	
	
	// Grid
	'Spark\\Grid'								=> __DIR__ . '/classes/grid.php',
	'Spark\\Grid_Button'						=> __DIR__ . '/classes/grid/button.php',
	'Spark\\Grid_Column'						=> __DIR__ . '/classes/grid/column.php',
	'Spark\\Grid_Column_Cell'					=> __DIR__ . '/classes/grid/column/cell.php',
	'Spark\\Grid_Column_Filter'					=> __DIR__ . '/classes/grid/column/filter.php',
	'Spark\\Grid_Column_Filter_Abstract'		=> __DIR__ . '/classes/grid/column/filter/abstract.php',
	'Spark\\Grid_Column_Filter_Action'			=> __DIR__ . '/classes/grid/column/filter/action.php',
	'Spark\\Grid_Column_Filter_Checkbox'		=> __DIR__ . '/classes/grid/column/filter/checkbox.php',
	'Spark\\Grid_Column_Filter_Interface'		=> __DIR__ . '/classes/grid/column/filter/interface.php',
	'Spark\\Grid_Column_Filter_Number'			=> __DIR__ . '/classes/grid/column/filter/number.php',
	'Spark\\Grid_Column_Filter_Options'			=> __DIR__ . '/classes/grid/column/filter/options.php',
	'Spark\\Grid_Column_Filter_Text'			=> __DIR__ . '/classes/grid/column/filter/text.php',
	'Spark\\Grid_Column_Header'					=> __DIR__ . '/classes/grid/column/header.php',
	'Spark\\Grid_Column_Renderer_Abstract'		=> __DIR__ . '/classes/grid/column/renderer/abstract.php',
	'Spark\\Grid_Column_Renderer_Action'		=> __DIR__ . '/classes/grid/column/renderer/action.php',
	'Spark\\Grid_Column_Renderer_Checkbox'		=> __DIR__ . '/classes/grid/column/renderer/checkbox.php',
	'Spark\\Grid_Column_Renderer_Interface'		=> __DIR__ . '/classes/grid/column/renderer/interface.php',
	'Spark\\Grid_Column_Renderer_Number'		=> __DIR__ . '/classes/grid/column/renderer/number.php',
	'Spark\\Grid_Column_Renderer_Options'		=> __DIR__ . '/classes/grid/column/renderer/options.php',
	'Spark\\Grid_Column_Renderer_Text'			=> __DIR__ . '/classes/grid/column/renderer/text.php',
	'Spark\\Grid_Component'						=> __DIR__ . '/classes/grid/component.php',
	'Spark\\Grid_Container'						=> __DIR__ . '/classes/grid/container.php',
	'Spark\\Grid_Driver_Abstract'				=> __DIR__ . '/classes/grid/driver/abstract.php',
	'Spark\\Grid_Driver_Database'				=> __DIR__ . '/classes/grid/driver/database.php',
	'Spark\\Grid_Driver_Interface'				=> __DIR__ . '/classes/grid/driver/interface.php',
	'Spark\\Grid_Driver_Orm'					=> __DIR__ . '/classes/grid/driver/orm.php',
	'Spark\\Grid_Massaction'					=> __DIR__ . '/classes/grid/massaction.php',
	'Spark\\Grid_Row'							=> __DIR__ . '/classes/grid/row.php',
	
	
	// Object
	'Spark\\Object'								=> __DIR__ . '/classes/object.php',
	
	
	// Str
	'Spark\\Str'								=> __DIR__ . '/classes/str.php',
	
	
	// View
	'Spark\\View'								=> __DIR__ . '/classes/view.php',
));