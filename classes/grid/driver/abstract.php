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

abstract class Grid_Driver_Abstract extends \Object {
	
	/**
	 * The grid this column belongs to
	 * 
	 * @var	Spark\Grid
	 */
	protected $_grid;
	
	/**
	 * Set Grid
	 * 
	 * Sets the grid object
	 * property
	 * 
	 * @access	public
	 * @param	Spark\Grid
	 * @return	Spark\Grid_Driver_Abstract
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
	 * @return	Spark\Grid
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
	 * of Spark\Object instances
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
	 * @param	Spark\Grid_Column
	 * @return	string	Cell Html
	 */
	abstract public function get_cell_for_row_and_column($row, $column);
	
	/**
	 * Build Row Action
	 * 
	 * Builds the row action by
	 * parsing property names according
	 * to the active row. The action
	 * is stored in the grid object
	 * 
	 * @access	public
	 * @param	mixed	Row
	 * @return	string	Action
	 */
	abstract public function build_row_action($row);
}