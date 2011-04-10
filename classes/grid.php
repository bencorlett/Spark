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

class Grid extends \Object {
	
	/**
	 * Array of grid columns
	 * 
	 * @var	array
	 */
	protected $_columns = array();
	
	/**
	 * The model the grid uses
	 * 
	 * @var	mixed
	 */
	protected $_model;
	
	/**
	 * Available Grid Drivers
	 * 
	 * @var	array
	 */
	protected $_drivers = array(
		'Kohana\\Orm'			=> 'Grid_Driver_KohanaOrm',
		'Orm\\Model'			=> 'Grid_Driver_Orm',
	);
	
	/**
	 * The driver that is used
	 * 
	 * @var	Ignite\Grid_Driver_Abstract
	 */
	protected $_driver;
	
	/**
	 * The default limit for rows
	 * 
	 * @var	int
	 */
	protected $_default_limit = 20;
	
	/**
	 * The default sort
	 * 
	 * @var	string
	 */
	protected $_default_sort = false;
	
	/**
	 * The default sort direction
	 * 
	 * @var	string
	 */
	protected $_default_sort_direction = 'desc';
	
	/**
	 * The default filters
	 * 
	 * @var	string
	 */
	protected $_default_filters = array();
	
	/**
	 * Construct
	 * 
	 * Called when the class is constructed
	 * 
	 * @access	public
	 * @param	mixed
	 * @return	Ignite\Grid
	 */
	public function __construct($identifier, $model = null)
	{
		if ($_POST)
		{
			// print_r($_POST);
		}
		
		// Set the identifier and the model
		$this->set_identifier(\Str::lower(\Str::alphanumeric($identifier, '_')))
			 ->set_model($model);
	}
	
	/**
	 * Add Column
	 * 
	 * Adds a column to the grid
	 * 
	 * @access	public
	 * @param	string	Identifier
	 * @param	array	Attributes
	 * @return	Ignite\Grid
	 */
	public function add_column($column, array $attributes)
	{
		// Only add the column if it doesn't exist
		if ( ! isset($this->_columns[$column]))
		{
			$this->_columns[$column] = \Grid_Column::factory($attributes)
												   ->set_identifier($column)
												   ->set_grid($this);
		}
		
		return $this;
	}
	
	/**
	 * Render
	 * 
	 * Renders the grid object
	 * as a HTML table
	 * 
	 * @access	public
	 * @return	string	Html Table
	 */
	public function render()
	{
		$this->_prepare_grid();
		
		$grid = \View::factory('grid/grid')
					 ->set('grid', $this)
					 ->set('driver', $this->_driver);
		
		if (\Input::is_ajax())
		{
			return $grid;
		}
		
		$container = \View::factory('grid/container')
						  ->set('grid', $this)
						  ->set('table', $grid);
		
		return $container;
	}
	
	/**
	 * Prepare Grid
	 * 
	 * Prepares the grid for rendering
	 * 
	 * @access	protected
	 * @return	Ignite\Grid
	 */
	protected function _prepare_grid()
	{
		$this->_prepare_columns();
		$this->_prepare_massactions();
		$this->_prepare_model();
		
		return $this;
	}
	
	/**
	 * Prepare Columns
	 * 
	 * Prepares the columns for rendering
	 * 
	 * @access	protected
	 * @return	Ignite\Grid
	 */
	protected function _prepare_columns()
	{
		return $this;
	}
	
	/**
	 * Prepare Massactions
	 * 
	 * Prepares the massactions for rendering
	 * 
	 * @access	protected
	 * @return	Ignite\Grid
	 */
	protected function _prepare_massactions()
	{
		return $this;
	}
	
	/**
	 * Prepare Model
	 * 
	 * Prepares the model for rendering
	 * 
	 * @access	protected
	 * @return	Ignite\Grid
	 */
	protected function _prepare_model()
	{
		$this->_prepare_filters();
		$this->_prepare_sort();
		
		return $this;
	}
	
	/**
	 * Prepare Filters
	 * 
	 * Prepares the filters for the model
	 * 
	 * @access	protected
	 */
	protected function _prepare_filters()
	{
		if (($grid = \Input::post('grid')) !== null)
		{
			if (isset($grid[$this->get_identifier()]['filters']))
			{
				$this->_set_filters($grid[$this->get_identifier()]['filters']);
			}
		}
		
		$this->_apply_filters_to_model($this->get_filters());
	}
	
	/**
	 * Set Filters
	 * 
	 * Sets the grid filters
	 * in the session
	 * 
	 * @access	protected
	 * @param	array	Filters
	 */
	protected function _set_filters(array $filters)
	{
		// Get the session
		$session = \Session::get('grid');
		
		// Set the filters
		$session[$this->get_identifier()]['filters'] = $filters;
		
		// Set the session
		\Session::set('grid', $session);
	}
	
	/**
	 * Get Filters
	 * 
	 * Gets the grid filters
	 * from the session
	 * 
	 * @access	protected
	 * @return	array	Filters
	 */
	protected function _get_filters()
	{
		// Get the session
		$session = \Session::get('grid');
		
		return (isset($session[$this->get_identifier()]['filters'])) ? $session[$this->get_identifier()]['filters'] : array();
	}
	
	/**
	 * Apply Filters to Model
	 * 
	 * Applies the filters to the
	 * model
	 * 
	 * @access	protected
	 */
	protected function _apply_filters_to_model()
	{
		$filters = $this->_get_filters();
		
		foreach ($this->get_columns() as $column)
		{
			if (isset($filters[(string) $column]))
			{
				$column->get_filter()->set_value($filters[(string) $column]);
			}
		}
		
		$this->get_driver()->apply_filters_to_model();
	}
	
	/**
	 * Prepare sort
	 * 
	 * Prepares the sort order
	 * for the grid
	 * 
	 * @access	protected
	 */
	protected function _prepare_sort()
	{
		if (($grid = \Input::post('grid')) !== null)
		{
			if (isset($grid[$this->get_identifier()]['sort']))
			{
				$this->_set_sort($grid[$this->get_identifier()]['sort']);
			}
		}
		
		$this->_apply_sort_to_model();
	}
	
	/**
	 * Set Sort
	 * 
	 * Sets the grid sort
	 * in the session
	 * 
	 * @access	protected
	 * @param	array	Column
	 */
	protected function _set_sort($column)
	{
		// Get the session
		$session = \Session::get('grid');
		
		// If the column is the same as the column in the session
		// Toggle ascending and descending
		if (isset($session[$this->get_identifier()]['sort']['column']) and $session[$this->get_identifier()]['sort']['column'] == $column)
		{
			switch ($session[$this->get_identifier()]['sort']['direction'])
			{
				case 'asc':
					$session[$this->get_identifier()]['sort']['direction'] = 'desc';
					break;
				case 'desc':
					$session[$this->get_identifier()]['sort']['direction'] = 'asc';
					break;
			}
		}
		
		// If the column is different, sort by that column ascending
		else
		{
			$session[$this->get_identifier()]['sort']['column']		= $column;
			$session[$this->get_identifier()]['sort']['direction']	= 'asc';
		}
		
		\Session::set('grid', $session);
	}
	
	/**
	 * Get Sort
	 * 
	 * Gets the grid sort
	 * from the session
	 * 
	 * @access	public
	 * @return	string	Sort
	 */
	public function get_sort()
	{
		// Get the session
		$session = \Session::get('grid');
		
		return (isset($session[$this->get_identifier()]['sort'])) ? $session[$this->get_identifier()]['sort'] : null;
	}
	
	/**
	 * Apply Sort to Model
	 * 
	 * Applys the sort to the
	 * model
	 * 
	 * @access	protected
	 */
	protected function _apply_sort_to_model()
	{
		// Get the sort from the session
		$sort = $this->get_sort();
		
		// Get the columns
		$columns = $this->get_columns();
		
		// Check the column exists
		if (isset($columns[$sort['column']]))
		{
			// Apply the sort
			$this->get_driver()->apply_sort_to_model($sort['column'], $sort['direction']);
		}
	}
	
	/**
	 * Set Model
	 * 
	 * Sets the model object
	 * property
	 * 
	 * @access	public
	 * @param	mixed	Model
	 * @return	Ignite\Grid
	 */
	public function set_model($model)
	{
		$this->_model = $model;
		
		return $this;
	}
	
	/**
	 * Get Model
	 * 
	 * Gets the model object
	 * property
	 * 
	 * @access	public
	 * @return	mixed	Model
	 */
	public function get_model()
	{
		return $this->_model;
	}
	
	/**
	 * Get Driver
	 * 
	 * Gets the driver object
	 * property
	 * 
	 * @access	public
	 * @return	mixed	Driver
	 */
	public function get_driver()
	{
		if ( ! $this->_driver)
		{
			// Check to see we have a driver available
			if ( ! isset($this->_drivers[get_parent_class($this->get_model())]))
			{
				throw new Exception('The model is an child class of \'%s\', for which we don\'t have a driver available.', get_parent_class($this->get_model()));
			}

			// Assign the driver
			$driver = $this->_drivers[get_parent_class($this->get_model())];
			
			
			$this->_driver = $driver::factory()
									->set_grid($this);
		}
		
		return $this->_driver;
	}
	
	/**
	 * Get Columns
	 * 
	 * Gets the columns object
	 * property
	 * 
	 * @access	public
	 * @return	array	Column
	 */
	public function get_columns()
	{
		return $this->_columns;
	}
	
	/**
	 * Get Column Count
	 * 
	 * Returns the column count
	 * with an optional offset
	 * 
	 * @access	public
	 * @param	int		Offset
	 * @return	int		Column count
	 */
	public function get_column_count($offset = 0)
	{
		return ($offset + count($this->get_columns()));
	}
	
	/**
	 * Get Rows
	 * 
	 * Gets the grid rows
	 *
	 * @access	public
	 * @return	array	Rows
	 */
	public function get_rows()
	{
		return $this->get_driver()->get_rows();
	}
}