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

class Grid_driver_Orm extends \Grid_Driver_Abstract {
	
	/**
	 * Prepare Model
	 * 
	 * Prepares the model
	 * based off parameters
	 * such as filters, sort
	 * and page
	 * 
	 * @access	public
	 * @return	Spark\Grid_Driver_Abstract
	 */
	public function prepare_model()
	{
		// Loop through columns, if they have
		// a real value we need to apply that
		// to the model
		foreach ($this->get_columns() as $column)
		{
			// Only process a value if there is one
			if (($value = $column->get_filter()->get_real_value()) !== false)
			{
				// If the value is a string we need
				// to filter where the index contains
				// that string
				if ( ! is_array($value) and ! is_object($value))
				{
					$this->get_model()->where($column->get_index(), 'LIKE', '%' . $value . '%');
				}
				
				// If the value is an instance of object
				// then it must be a range, which contains
				// a "min" property and a "max" property
				// which the column must be between. Used
				// commonly for dates, numbers and timestamps
				elseif ($value instanceof \Object and ($value->get_min() !== null or $value->get_max() !== null))
				{
					// If we've got a min and a max
					if ($value->get_min() !== null and $value->get_max() !== null)
					{
						$this->get_model()->where($column->get_index(), 'BETWEEN', array($value->get_min(), $value->get_max()));
					}
					
					// If we've only got a min
					if ($value->get_min() !== null)
					{
						$this->get_model()->where($column->get_index(), '>=', $value->get_min());
					}
					
					// If we've only got a max
					elseif ($value->get_max() !== null)
					{
						$this->get_model()->where($column->get_index(), '<=', $value->get_max());
					}
				}
				
				// Else the value is not valid
				else
				{
					throw new Exception('The value given to the driver to render is not a valid string or an instance of Spark\\Object');
				}
			}
		}
		
		return $this;
	}
	
	/**
	 * Get Rows
	 * 
	 * Gets the rows based
	 * off the model
	 * 
	 * @access	public
	 * @return	Spark\Object
	 */
	public function build_rows()
	{
		// Loop through the results and add them
		// to the rows
		foreach ($this->get_model()->get() as $result)
		{
			// Create a row
			$row = \Object::factory();
			
			// Loop through columns and add a cell
			// to the row for each column
			foreach ($this->get_columns() as $column)
			{
				// Create a column cell
				$cell = \Grid_Column_Cell::factory()
										 ->set_grid($this->get_grid())
										 ->set_column($column)
										 ->set_original_value($result->{$column->get_index()});
				
				// Add the cell to the row
				$row->set_data($column->get_identifier(), $cell);
			}
			
			// We've now built our row,
			// add it to the rows object
			$this->get_rows()->add_data($row);
		}
	}
}