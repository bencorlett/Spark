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
 * @link       http://www.github.com/bencorlett/bc
 */

namespace Spark;

class Grid_Driver_KohanaOrm extends \Grid_Driver_Abstract {
	
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
		// Get the model results
		$rows = $this->get_grid()->get_model()->find_all();
		
		// Fallback
		$return = array();
		
		// Loop through results and build an object
		// containing data
		foreach ($rows as $row)
		{
			$row_id = (string) $row;
			
			$return[$row_id] = \Object::factory($row->as_array());
		}
		
		// Return the object
		return $return;
	}
	
	/**
	 * Apply Filters to Model
	 * 
	 * Applies the filters to the
	 * model object
	 * 
	 * @access	public
	 * @param	array	Filters
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
}