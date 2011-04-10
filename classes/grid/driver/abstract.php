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

namespace Ignite;

abstract class Grid_Driver_Abstract extends \Object {
	
	/**
	 * The grid this column belongs to
	 * 
	 * @var	Ignite\Grid
	 */
	protected $_grid;
	
	/**
	 * Set Grid
	 * 
	 * Sets the grid object
	 * property
	 * 
	 * @access	public
	 * @param	Ignite\Grid
	 * @return	Ignite\Grid_Driver_Abstract
	 */
	public function set_grid(\Grid $grid)
	{
		$this->_grid = $grid;
		
		return $this;
	}
	
	/**
	 * Get Grid
	 * 
	 * Gets the grid object
	 * property
	 * 
	 * @access	public
	 * @return	Ignite\Grid
	 */
	public function get_grid()
	{
		return $this->_grid;
	}
	
	/**
	 * Apply Filters to Model
	 * 
	 * Applies the filters to the
	 * model object
	 * 
	 * @access	public
	 */
	abstract public function apply_filters_to_model();
	
	/**
	 * Apply Sort to Model
	 * 
	 * Applies the sort to the
	 * model object
	 * 
	 * @access	public
	 * @param	string	Column
	 * @param	string	Direction
	 */
	abstract public function apply_sort_to_model($column, $direction = 'asc');
	
	/**
	 * Get Rows
	 * 
	 * Gets the rows based
	 * on the model
	 * 
	 * This must return an array
	 * of Ignite\Object instances
	 * where the data is the data
	 * from the row
	 * 
	 * @access	public
	 * @return	array	Results
	 */
	abstract public function get_rows();
	
	/**
	 * Get Cell For Row
	 * 
	 * Gets the cell for the
	 * row
	 * 
	 * @access	public
	 * @param	mixed	Row
	 * @param	Ignite\Grid_Column
	 * @return	string	Cell Html
	 */
	public function get_cell_for_row_and_column($row, $column)
	{
		$method = sprintf('get_%s', $column->get_index());
		return $row->$method();
	}
}