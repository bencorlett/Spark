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
 * @link       http://www.github.com/bencorlett/spark
 */

namespace Spark;

abstract class Grid_Column_Renderer_Abstract extends \Object {
	
	/**
	 * Render Cell for Row and Column
	 * 
	 * Renders the value
	 * 
	 * @access	public
	 * @param	string	Value
	 * @return	string	Rendered value
	 */
	public function render_cell_for_row_and_column($value, $row, $column)
	{
		return $value;
	}
}