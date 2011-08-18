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

class Grid_driver_Database extends \Grid_Driver_Abstract {
	
	/**
	 * The results object - we'll cache as we call
	 * it more than once
	 * 
	 * @var	Fuel\Core\Database_MySQL_Result
	 */
	protected $_results;
	
	/**
	 * Get Results
	 * 
	 * Gets the results object
	 * 
	 * @access	public
	 * @param	bool							Cached result
	 * @return	Fuel\Core\Database_MySQL_Result	Result
	 */
	public function get_results($cached = true)
	{
		if ( ! $this->_results or $cached === false)
		{
			$this->_results = $this->get_query()
								   ->as_object()
								   ->execute();
		}
		
		return $this->_results;
	}
	
	/**
	 * Prepare Query
	 * 
	 * Prepares the query
	 * based off parameters
	 * such as filters, sort
	 * and page
	 * 
	 * @access	public
	 * @return	Spark\Grid_Driver_Abstract
	 */
	public function prepare_query()
	{
		// Loop through columns, if they have
		// filters with a real (translated)
		// value and process them
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
					$this->get_query()->where($column->get_index(), 'LIKE', '%' . $value . '%');
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
						$this->get_query()->where($column->get_index(), 'BETWEEN', array($value->get_min(), $value->get_max()));
					}
					
					// If we've only got a min
					if ($value->get_min() !== null)
					{
						$this->get_query()->where($column->get_index(), '>=', $value->get_min());
					}
					
					// If we've only got a max
					elseif ($value->get_max() !== null)
					{
						$this->get_query()->where($column->get_index(), '<=', $value->get_max());
					}
				}
				
				// Else the value is not valid
				else
				{
					throw new Exception('The value given to the driver to render is not a valid string or an instance of Spark\\Object');
				}
			}
		}
		
		// Loop through sort and direction
		if ($sort = $this->get_params()->get_data($this->get_grid()->get_var_name_sort()) and $direction = $this->get_params()->get_data($this->get_grid()->get_var_name_direction()))
		{
			// Loop through columns and match
			// to sort
			foreach ($this->get_columns() as $column)
			{
				// Yay, we found one
				if ($sort === $column->get_identifier())
				{
					// Apply the sort and direction to the query
					$this->get_query()->order_by($column->get_index(), $direction);
					
					break;
				}
			}
		}
		
		// Process page, limit and offset
		if ($page = $this->get_params()->get_data($this->get_grid()->get_var_name_page()) and $limit = $this->get_params()->get_data($this->get_grid()->get_var_name_limit()))
		{
			// Get the count of rows to make sure that
			// the offset is valid
			$count = $this->get_results()->count();
			
			// Minus one because the first
			// page has an offset of 0
			$offset = ($page - 1) * $limit;
			
			// Reduce offset / page
			// to make sure it's under
			// the boundaries
			if ($offset > $count)
			{
				// Set to a new variable
				// as we're going to
				// reset it in the params
				// once we've calculated it
				$over_page = $page;
				
				while ($offset > $count)
				{
					$offset -= $limit;
					$over_page --;
				}
				
				// Reset in the params
				if ($over_page !== $page)
				{
					$this->get_params()->set_data($this->get_grid()->get_var_name_page(), $over_page);
					$page = $this->get_params()->get_data($this->get_grid()->get_var_name_page());
				}
			}
			
			// Set the total records and
			// pages parameters for display
			// on the grid
			$this->get_params()
				 ->set_total_records($count)
				 ->set_total_pages(($count > $limit) ? ceil($count / $limit) : 1);
			
			// Apply to query
			$this->get_query()
				 ->limit($limit)
				 ->offset($offset);
		}
		
		return $this;
	}
	
	/**
	 * Get Rows
	 * 
	 * Gets the rows based
	 * off the query
	 * 
	 * @access	public
	 * @return	Spark\Object
	 */
	public function build_rows()
	{
		// Counter
		$i = 0;
		
		// Loop through the results and add them
		// to the rows
		foreach ($this->get_results(false) as $result)
		{
			// Create a row
			$row = \Grid_Row::factory()
							->set_grid($this->get_grid());
			
			// Set the class of the row
			$class = '';
			if (++ $i == 1) $class = 'first';
			$class .= ($i % 2 == 0) ? ' even' : ' odd';
			if ($i == $this->get_results()->count()) $class .= ' last';
			$row->set_class($class);
			
			// Loop through columns and add a cell
			// to the row for each column
			foreach ($this->get_columns() as $column)
			{
				// Create a column cell
				$cell = \Grid_Column_Cell::factory()
										 ->set_grid($this->get_grid())
										 ->set_column($column)
										 ->set_row($row)
										 ->set_original_value($result->{$column->get_index()});
				
				// Add the cell to the row
				$row->add_cell($column->get_identifier(), $cell);
			}
			
			// We've now built our row,
			// add it to the rows object
			$this->get_rows()->add_data($row);
		}
	}
}