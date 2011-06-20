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
			// Get the row id
			$row_id = (string) $row;
			
			$return[$row_id] = $row;
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
			if ( ! ($values = $column->get_filter()->get_values())) continue;
			
			// If there just a value
			if ($values->get_value())
			{
				// Manipulate the model
				$this->get_grid()->get_model()->where($this->_get_sql_column_name($column), 'LIKE', '%' . $values->get_value() . '%');
			}
			
			// If there is a range
			else if ($values->get_from() and $values->get_to())
			{
				// Manipulate the model
				$this->get_grid()->get_model()->where($this->_get_sql_column_name($column), 'BETWEEN', array($values->get_from(), $values->get_to()));
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
		// Get the columns
		$columns = $this->get_grid()->get_columns();
		
		// Check for a column
		if (isset($columns[$column]))
		{
			// Get the column
			$column = $columns[$column];
			
			// Sort
			$this->get_grid()->get_model()->order_by($this->_get_sql_column_name($column), $direction);
		}
		
		return $this;
	}
	
	/**
	 * Apply Pagination to Model
	 * 
	 * Applies the pagination to
	 * the model object
	 * 
	 * @access	public
	 */
	public function apply_pagination_to_model()
	{
		// For pagination purposes
		// we need to know the total
		// row count
		$clone = clone($this->get_grid()->get_model());
		$count = $clone->count_all();
		$this->get_grid()->set_total_row_count($count);
		unset($clone);
		
		// Add pagination if it's needed
		if ($this->get_grid()->get_uses_pagination()) $this->get_grid()->get_model()->limit($this->get_grid()->get_pagination_size())->offset($this->get_grid()->get_pagination_size() * ($this->get_grid()->get_pagination_page() - 1));
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
			$method = sprintf('get_%s', $column->get_index());
			return $row->$method();
		}
		
		// Create new row
		$rels = explode(':', $column->get_index());
		
		// Counter
		$i = 0;
		
		// Loop throug relationships
		foreach ($rels as $rel)
		{
			// Loop through relationships
			if (++$i < count($rels))
			{
				$row = $row->$rel;
			}
			
			// On the last relationship, be sure to
			// call the magic getter method, this way
			// if the person overwrites it in their
			// model we use their overwritten method
			else
			{
				$method = sprintf('get_%s', $rel);
				$row = $row->$method();
			}
		}
		
		// Return the row
		return $row;
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
		
		$action = $this->parse_value_for_row($action, $row);
		
		// Return the action
		return \Uri::create($action);
	}
	
	/**
	 * Get SQL Column Name
	 * 
	 * Gets the SQL query compatible
	 * column name
	 * 
	 * @access	public
	 * @param	string	Spark\Grid_Column
	 * @return	string	Name
	 */
	protected function _get_sql_column_name($column)
	{
		// Get table name
		$table_name = $this->get_grid()->get_model()->table_name();
		
		// Out of the main model
		if ( ! strpos($column->get_index(), ':'))
		{
			return $table_name . '.' . $column->get_index();
		}
		else // out of a relationship - don't need table name as ORM does selects a table AS a relationship - so we just use that relationship
		{
			$parts = explode(':', $column->get_index());

			return $parts[0] . '.' . $parts[1];
		}
	}
	
	/**
	 * Parse Value for Row
	 * 
	 * Parses a value for a row and a
	 * column
	 * 
	 * @access	public
	 * @param	string	Value
	 * @param	mixed	Row
	 * @return	string	Value
	 */
	public function parse_value_for_row($value, $row)
	{
		// Get the values
		preg_match('/\{\w+\}/', $value, $matches);
		
		// Loop through matches and get property values
		foreach ($matches as $match)
		{
			// Get property
			$property	= str_replace(array('{', '}'), array(null, null), $match);
			
			// Get method
			$method		= sprintf('get_%s', $property);
			
			// Get value
			$raw		= $row->$method();
			
			// Replace in string
			$value		= str_replace($match, $raw, $value);
		}
		
		return $value;
	}
}

/* End of file classes/grid/driver/kohanaorm.php */