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

class Grid_driver_Orm extends \Grid_Driver_Abstract
{
	
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
					throw new Grid_InvalidFormatException('The value given to the driver to render is not a valid string or an instance of Spark\\Object');
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
			$count = $this->get_query()->count();
			$this->set_count($count);

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
	 * @return	Spark\Grid_Driver_Orm
	 */
	public function build_rows()
	{
		// Counter
		$i = 0;
		
		// Loop through the results and add them
		// to the rows
		foreach ($this->get_query()->get() as $result)
		{
			// Create a row
			$row = \Grid_Row::forge()
							->set_grid($this->get_grid());
			
			// Set the class of the row
			$class = '';
			if (++ $i == 1) $class = 'first';
			$class .= ($i % 2 == 0) ? ' even' : ' odd';
			if ($i == $this->get_count()) $class .= ' last';
			$row->set_class($class);
			
			// Process the row action of the grid
			if ($action = $this->get_row_action())
			{
				// Get dynamic parameters from the row
				// action
				preg_match('/\{\w+\}/', $action, $matches);
				
				// Loop through the matches and
				// update the row action string
				foreach ($matches as $match)
				{
					// Determine the actual property
					// the user is after
					$property = str_replace(array('{', '}'), null, $match);
					
					// Get the value of that proprty
					// in this result and replace it
					// in the string
					$value = $result->$property;
					$action = str_replace($match, $value, $action);
				}
				
				// Set the action of the row
				$row->set_action($action);
			}
			
			// Loop through columns and add a cell
			// to the row for each column
			foreach ($this->get_columns() as $column)
			{
				// Work out the value of the cell, considering
				// relationships
				if (strpos($column->get_index(), '.') !== false)
				{
					$parts = explode('.', $column->get_index());
					$value = $result;

					foreach ($parts as $part)
					{
						$value = $value->{'get_'.$part}();
					}
				}
				else
				{
					$value = $result->{'get_'.$column->get_index()}();
				}

				// Create a column cell
				$cell = \Grid_Column_Cell::forge()
										 ->set_grid($this->get_grid())
										 ->set_column($column)
										 ->set_row($row)
										 ->set_original_value($value);
				
				// Process cell actions
				if ($action = $column->get_action())
				{
					// Get dynamic parameters from the row
					// action
					preg_match('/\{\w+\}/', $action, $matches);

					// Loop through the matches and
					// update the row action string
					foreach ($matches as $match)
					{
						// Determine the actual property
						// the user is after
						$property = str_replace(array('{', '}'), null, $match);

						// Get the value of that proprty
						// in this result and replace it
						// in the string
						$value = $result->$property;
						$action = str_replace($match, $value, $action);
					}

					// Set the action of the cell
					$cell->set_action($action);
				}

				
				// Add the cell to the row
				$row->add_cell($column->get_identifier(), $cell);
			}
			
			// Process any actions on the column
			if ($actions = $column->get_actions() and $actions->count())
			{
				// Clone the actions object
				$actions = clone $actions;
				
				foreach ($actions as $action => $name)
				{
					// New action
					$new_action = false;
					
					// Get dynamic parameters from the row
					// uri
					preg_match('/\{\w+\}/', $action, $matches);
					
					// Loop through the matches and
					// update the row uri string
					foreach ($matches as $match)
					{
						// Determine the actual property
						// the user is after
						$property = str_replace(array('{', '}'), null, $match);
					
						// Get the value of that proprty
						// in this result and replace it
						// in the string
						$value = $result->$property;
						$new_action = \Uri::create(str_replace($match, $value, $action));
						
						// Update the actions
						$actions->unset_data($action)
								->set_data($new_action, $name);
					}
				}
				
				// Set the actions of the cell
				$cell->set_actions($actions);
			}
			
			// We've now built our row,
			// add it to the rows object
			$this->get_rows()->add_data($row);
		}
		
		return $this;
	}
	
	/**
	 * Set Primary Key
	 * 
	 * Sets the primary key
	 * of the query object
	 * 
	 * @access	public
	 * @return	Spark\Grid_Driver_Orm
	 */
	public function set_primary_key()
	{
		// Try and get the key, if we can't get a good
		// one, then just default to id
		try
		{
			$class = get_class($this->get_query()->get_one());
			$keys  = $class::primary_key();
			$key   = reset($keys);
		}
		catch (\Exception $e)
		{
			$key = 'id';
		}
		
		// Set the property
		$this->get_grid()
			 ->set_primary_key($key);
		return $this;
	}
}