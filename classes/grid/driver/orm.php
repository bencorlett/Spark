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