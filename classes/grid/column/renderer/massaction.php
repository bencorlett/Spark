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

class Grid_Column_Renderer_Massaction extends \Grid_Column_Renderer_Checkbox {
	
	/**
	 * Render
	 * 
	 * Renders a cell and populates
	 * it's rendered value
	 * 
	 * @access	public
	 * @param	Spark\Grid_Column_Cell	Cell
	 * @return	Spark\Grid_Column_Renderer_Text
	 */
	public function arender(\Grid_Column_Cell $cell)
	{
		// echo $cell->get_grid()->get_primary_key();
		// $cell->set_rendered_value($cell->get_original_value());
		
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
		return true;
	}
}