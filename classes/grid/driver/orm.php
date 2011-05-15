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

class Grid_Driver_Orm extends \Grid_Driver_Abstract {
	
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
	public function get_rows()
	{
		// Rows
		$rows = $this->get_grid()->get_model()->get();
		
		// Return fallback
		$return = array();
		
		foreach ($rows as $row_id => $row)
		{
			// // Row data
			// $row_data = array();
			// 
			// foreach ($row->properties() as $property_name => $property)
			// {
			// 	$row_data[$property_name] = $row->$property_name;
			// }
			
			$return[$row_id] = $row;
		}
		
		return $return;
	}
	
	/**
	 * Apply Filters to Model
	 * 
	 * Applies the filters to the
	 * model object
	 * 
	 * @access	public
	 */
	public function apply_filters_to_model()
	{
		foreach ($this->get_grid()->get_columns() as $column)
		{
			$filter_value = $column->get_filter()->get_value();
			
			if (is_string($filter_value))
			{
				if ($filter_value)
				{
					// Manipulate the model
					$this->get_grid()->get_model()->where($column->get_index(), 'LIKE', '%' . $filter_value . '%');
				}
			}
			else
			{
				// The user has a 'from' and 'to'
			}
		}
	}
	
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
	public function apply_sort_to_model($column, $direction = 'asc')
	{
		$columns = $this->get_grid()->get_columns();
		
		if (isset($columns[$column]))
		{
			$column = $columns[$column];
			
			// Manipulate the model
			$this->get_grid()->get_model()->order_by($column->get_index(), $direction);
		}
	}
	
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
	public function get_cell_for_row_and_column($row, $column)
	{
		// If we aren't dealing with relationships
		if (strpos($column->get_index(), ':') === false)
		{
			$property = $column->get_index();
			return $row->$property;
		}
		
		return sprintf('%s doesn\'t support joins yet', __CLASS__);
	}
	
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
	public function build_row_action($row)
	{
		if (($action = $this->get_grid()->get_row_action()) == null) return null;
		
		
		
		// Return the action
		return \Uri::create($action);
	}
}