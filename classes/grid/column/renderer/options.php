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
namespace Spark;

class Grid_Column_Renderer_Options extends \Grid_Column_Renderer_Abstract {
	
	/**
	 * Render
	 * 
	 * Renders a cell and populates
	 * it's rendered value
	 * 
	 * @access	public
	 * @param	Spark\Grid_Column_Cell	Cell
	 * @return	Spark\Grid_Column_Renderer_Options
	 */
	public function render(\Grid_Column_Cell $cell)
	{
		// Get options
		$options = $cell->get_column()->get_options();
		
		// Value fallback
		$value = '';
		
		// Override the value with an option
		if (isset($options[$cell->get_original_value()]))
		{
			$value = $options[$cell->get_original_value()];
		}
		
		$cell->set_rendered_value($value);
	}
}