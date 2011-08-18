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

class Grid_Column_Renderer_Checkbox extends \Grid_Column_Renderer_Abstract {
	
	/**
	 * Render
	 * 
	 * Renders a cell and populates
	 * it's rendered value
	 * 
	 * @access	public
	 * @param	Spark\Grid_Column_Cell	Cell
	 * @return	Spark\Grid_Column_Renderer_Checkbox
	 */
	public function render(\Grid_Column_Cell $cell)
	{
		// Get the value
		$value = $cell->get_original_value();
		
		// Get the name for the checkbox
		$name = ($name = $cell->get_column()->get_name()) ? $name : $cell->get_column()->get_identifier() . '[]';
		
		// An array of checked items
		$checked = ($checked = $cell->get_column()->get_checked() and $checked->count()) ? $checked->get_data() : array();
		
		$cell->set_rendered_value(\Form::checkbox($name,
												  $value,
												  array(
													in_array($value, $checked) ? 'checked' : null,
												  )
		));
		
		return $this;
	}
}