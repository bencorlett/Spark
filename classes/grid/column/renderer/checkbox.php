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

class Grid_Column_Renderer_Checkbox extends \Grid_Column_Renderer_Abstract
{
	
	/**
	 * Get Checkbox Name
	 * 
	 * Gets the checkbox name
	 * 
	 * @access	protected
	 * @param	Spark\Grid_Column_Cell	Cell
	 * @return	string					Checkbox name
	 */
	public function get_checkbox_name(\Grid_Column_Cell $cell)
	{
		return ($name = $cell->get_column()->get_name()) ? $name : \Inflector::pluralize($cell->get_column()->get_identifier()) . '[]';
	}
	
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
		
		// An array of checked items
		$checked = ($checked = $cell->get_column()->get_checked() and $checked->count()) ? $checked->get_data() : array();
		
		$cell->set_rendered_value(\Form::checkbox($this->get_checkbox_name($cell), $value, array(
			in_array($value, $checked) ? 'checked' : null,
		)));
		
		return $this;
	}
	
	/**
	 * Render Header
	 * 
	 * Renders the header of a column
	 * 
	 * @access	public
	 * @param	Spark\Grid_Column_Header	Header
	 * @return	Spark\Grid_Column_Renderer_Interface
	 */
	public function render_header(\Grid_Column_Header $header)
	{
		// Build a checkbox
		$checkbox = \Form::checkbox(null, 1, array(
			'targets'	=> ($name = $header->get_column()->get_name()) ? $name : $header->get_column()->get_identifier() . '[]',
		));
		
		// Set the rendered value
		$header->set_rendered_value(html_tag('span', array('class' => 'header-checkbox-label'), $checkbox));
		
		return $this;
	}
	
	/**
	 * Allows Action
	 * 
	 * Returns whether the renderer for this
	 * column allows cells within it to have
	 * an action applied to them or not
	 * 
	 * @access	public
	 * @return	bool	Allows action
	 */
	public function allows_action()
	{
		return false;
	}
}