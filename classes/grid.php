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

class Grid extends \Object {
	
	/**
	 * Array of grid columns
	 * 
	 * @var	array
	 */
	protected $_columns = array();
	
	/**
	 * Array of grid rows
	 * 
	 * @var	array
	 */
	protected $_rows = array();
	
	/**
	 * The model the grid uses
	 * 
	 * @var	mixed
	 */
	protected $_model;
	
	/**
	 * Does the grid use ajax?
	 *
	 * @var	bool
	 */
	protected $_use_ajax = true;
	
	/**
	 * Available Grid Drivers
	 * 
	 * @var	array
	 */
	protected $_drivers = array(
		'kohana_orm'	=> 'Grid_Driver_KohanaOrm',
		'orm'			=> 'Grid_Driver_Orm',
	);
	
	/**
	 * The driver that is used
	 * 
	 * @var	Spark\Grid_Driver_Abstract
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
	protected $_default_sort = 'id';
	
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
	 * Array of grid massactins
	 * 
	 * @var	array
	 */
	protected $_massactions = array();
	
	/**
	 * Add button
	 * 
	 * @var	Spark\Object
	 */
	protected $_add_button;
	
	/**
	 * Row action
	 * 
	 * @var	string
	 */
	protected $_row_action;
	
	/**
	 * Uses filters flag
	 * 
	 * @var	bool
	 */
	protected $_uses_filters = true;
	
	/**
	 * Uses bottom labels flag
	 * 
	 * @var	bool
	 */
	protected $_uses_bottom_labels = true;
	
	/**
	 * The default view used
	 * for the grid assets
	 * 
	 * @var	string
	 */
	protected $_assets_view = 'grid/assets';
	
	/**
	 * Uses pagination flag
	 * 
	 * @var	bool
	 */
	protected $_uses_pagination = false;
	
	/**
	 * Pagination size
	 * 
	 * @var	int
	 */
	protected $_pagination_size = 25;
	
	/**
	 * Pagination page
	 * 
	 * @var	string
	 */
	protected $_pagination_page;
	
	/**
	 * Paginated row count
	 * 
	 * @var	int
	 */
	protected $_paginated_row_count;
	
	/**
	 * Total row count
	 * 
	 * @var	int
	 */
	protected $_total_row_count;
	
	/**
	 * Pagination pages count
	 * 
	 * @var	int
	 */
	protected $_pagination_pages_count;
	
	/**
	 * Additonal CSS class that the
	 * container element has
	 * 
	 * @var	string
	 */
	protected $_additional_css_class;
	
	/**
	 * Construct
	 * 
	 * Called when the class is constructed
	 * 
	 * @access	public
	 * @param	mixed
	 * @return	Spark\Grid
	 */
	public function __construct($identifier, $model)
	{
		if ( ! is_string($identifier)) throw new Exception('The identifier \'%s\' provided to %s() must be a valid string', $identifier, __METHOD__);
		
		if ( ! is_object($model)) throw new Exception('You must provide a model to %s()', __METHOD__);
		
		// Set the identifier and the model
		$this->set_identifier(\Str::alphanumeric($identifier))
			 ->set_model($model);
		
		return parent::__construct();
	}
	
	/**
	 * Add Column
	 * 
	 * Adds a column to the grid
	 * 
	 * @access	public
	 * @param	string	Identifier
	 * @param	array	Attributes
	 * @return	Spark\Grid
	 */
	public function add_column($column, array $attributes = array())
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
	 * Add Massaction
	 * 
	 * Adds a massaction to the grid
	 * 
	 * @access	public
	 * @param	string	Identifier
	 * @param	array	Attributes
	 * @return	Spark\Grid
	 */
	public function add_massaction($massaction, array $attributes)
	{
		// Only add the column if it doesn't exist
		if ( ! isset($this->_massactions[$massaction]))
		{
			$this->_massactions[$massaction] = \Grid_Massaction::factory($attributes)
															   ->set_identifier($massaction)
															   ->set_grid($this);
		}
		
		return $this;
	}
	
	/**
	 * Set Add Button
	 * 
	 * Sets the add button
	 * for the grid
	 * 
	 * @access	public
	 * @param	array	Attributes
	 * @return	Spark\Grid
	 */
	public function set_add_button(array $attributes)
	{
		// Set the add button property
		$this->_add_button = Object::factory($attributes);
		
		return $this;
	}
	
	/**
	 * Set Row Action
	 * 
	 * Sets the action for when
	 * the user clicks on a row
	 * 
	 * @access	public
	 * @param	string	Action
	 * @return	Spark\Grid
	 */
	public function set_row_action($action)
	{
		// Set the row action property
		$this->_row_action = $action;
		
		return $this;
	}
	
	/**
	 * Set Uses Filters
	 * 
	 * Sets the uses filters
	 * class property
	 * 
	 * @access	public
	 * @param	bool	Uses filters
	 * @return	Spark\Grid
	 */
	public function set_uses_filters($uses_filters)
	{
		$this->_uses_filters = (bool) $uses_filters;
		
		return $this;
	}
	
	/**
	 * Set Uses Bottom Labels
	 * 
	 * Sets the uses bottom
	 * labels class property
	 * 
	 * @access	public
	 * @param	bool	Uses bottom labels
	 * @return	Spark\Grid
	 */
	public function set_uses_bottom_labels($uses_bottom_labels)
	{
		$this->_uses_bottom_labels = (bool) $uses_bottom_labels;
		
		return $this;
	}
	
	/**
	 * Build
	 * 
	 * Renders the grid object
	 * as a Html table
	 * 
	 * @access	public
	 * @return	string	Html Table
	 */
	public function build()
	{
		// Prepare the grid
		$this->_prepare_grid();
		
		// Get table
		$table = \View::factory('grid/table')
					  ->set('grid', $this, false);
		
		// If the request is AJAX, overwrite output so that
		// the grid is the only output (as the contents of the
		// output are what replaces the grid HTML)
		if (\Input::is_ajax() && $this->_post_is_for_this())
		{
			// We want to strip the entire response body
			// and replace it with our view
			$response = new \Response($table, 200);
			
			// Send the response
			$response->send(true);
			
			// Write the session data now
			\Session::write();
			
			// Exit and do no more
			exit;
		}
		
		// If we're not using ajax, create the container
		$container = \View::factory('grid/container')
						  ->set('grid', $this, false)
						  ->set('table', $table, false);
		
		// Return the container
		return $container;
	}
	
	/**
	 * Prepare Grid
	 * 
	 * Prepares the grid for rendering
	 * 
	 * @access	protected
	 * @return	Spark\Grid
	 */
	protected function _prepare_grid()
	{
		// Prepare grid
		$this->_prepare_columns()
			 ->_prepare_massactions()
			 ->_prepare_model();
		
		return $this;
	}
	
	/**
	 * Prepare Columns
	 * 
	 * Prepares the columns for rendering
	 * 
	 * @access	protected
	 * @return	Spark\Grid
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
	 * @return	Spark\Grid
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
	 * @return	Spark\Grid
	 */
	protected function _prepare_model()
	{
		$this->_prepare_filters();
		$this->_prepare_sort();
		$this->_prepare_pagination();
		
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
		\Session::set('grid', $session, false);
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
				$column->get_filter()->set_values($filters[(string) $column]);
			}
		}
		
		$this->get_driver()->apply_filters_to_model();
	}
	
	/**
	 * Prepare Sort
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
			if (isset($grid[$this->get_identifier()]['sort'])) $this->_set_sort($grid[$this->get_identifier()]['sort']);
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
		
		return (isset($session[$this->get_identifier()]['sort'])) ? $session[$this->get_identifier()]['sort'] : array('column' => $this->get_default_sort(), 'direction' => $this->get_default_sort_direction());
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
	 * Prepare Pagination
	 * 
	 * Prepares Pagination
	 * for the grid
	 * 
	 * @access	protected
	 */
	protected function _prepare_pagination()
	{
		if (($grid = \Input::post('grid')) !== null)
		{
			if (isset($grid[$this->get_identifier()]['pagination'])) $this->_set_pagination($grid[$this->get_identifier()]['pagination']);
		}
		
		$this->_apply_pagination_to_model();
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
	protected function _set_pagination($pagination)
	{
		// Get the session
		$session = \Session::get('grid');
		
		// Set the pagination page
		$session[$this->get_identifier()]['pagination'] = $pagination;
		
		\Session::set('grid', $session);
	}
	
	/**
	 * Apply Pagination to Model
	 * 
	 * Applys pagination to the
	 * model
	 * 
	 * @access	protected
	 */
	protected function _apply_pagination_to_model()
	{
		$this->get_driver()->apply_pagination_to_model();
	}
	
	/**
	 * Set Model
	 * 
	 * Sets the model object
	 * property
	 * 
	 * @access	public
	 * @param	mixed	Model
	 * @return	Spark\Grid
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
			// Determine driver
			if ($this->get_model() instanceof \Kohana\Orm) $driver = $this->_drivers['kohana_orm'];
			else if (($this->get_model() instanceof \Orm\Model) or ($this->get_model() instanceof \Orm\Query)) $driver = $this->_drivers['orm'];
			else throw new Exception('There are no drivers available for a model that is a class of \'%s\'', get_class($this->get_model()));
			
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
	 * @return	array	Columns
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
		// Cache the rows
		if ( ! $this->_rows) $this->_rows = $this->get_driver()->get_rows();
		
		return $this->_rows;
	}
	
	/**
	 * Get Massactions
	 * 
	 * Gets the massactiosn
	 * object property
	 * 
	 * @access	public
	 * @return	array	Massactions
	 */
	public function get_massactions()
	{
		return $this->_massactions;
	}
	
	/**
	 * Needs Select
	 * 
	 * Determine if the grid
	 * needs to show the select
	 * functionality
	 * 
	 * @access	public
	 * @return	bool	Needs Select
	 */
	public function needs_select()
	{
		$needs = false;
		
		if (count($this->get_massactions()))
		{
			$needs = true;
		}
		
		return $needs;
	}
	
	/**
	 * Post is for this
	 * 
	 * Determines if the post
	 * that has come through from
	 * the browser was meant for this
	 * grid or not.
	 * 
	 * @access	protected
	 * @return	bool	Post is for this
	 */
	protected function _post_is_for_this()
	{
		if (($grid = \Input::post('grid')) !== null)
		{
			if (isset($grid[$this->get_identifier()]))
			{
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Get Add Button
	 * 
	 * Gets the add button class
	 * property
	 * 
	 * @access	public
	 * @return	Spark\Object
	 */
	public function get_add_button()
	{
		return $this->_add_button;
	}
	
	/**
	 * Get Row Action
	 * 
	 * Gets the row action class
	 * property
	 * 
	 * @access	public
	 * @return	Spark\Object
	 */
	public function get_row_action()
	{
		return $this->_row_action;
	}
	
	/**
	 * Get Uses Filters
	 * 
	 * Gets the uses filters
	 * class property
	 * 
	 * @access	public
	 * @return	bool	Uses filters
	 */
	public function get_uses_filters()
	{
		return $this->_uses_filters;
	}
	
	/**
	 * Get Uses Bottom Labels
	 * 
	 * Gets the uses bottom
	 * labels class property
	 * 
	 * @access	public
	 * @return	bool	Uses bottom label
	 */
	public function get_uses_bottom_labels()
	{
		return $this->_uses_bottom_labels;
	}
	
	/**
	 * Set Assets View
	 * 
	 * Sets the assets
	 * view class property
	 * 
	 * @access	public
	 * @param	string	View name
	 * @return	Spark\Grid
	 */
	public function set_assets_view($view_name)
	{
		$this->_assets_view = (string) $view_name;
		
		return $this;
	}
	
	/**
	 * Get Assets
	 * 
	 * Gets the assets
	 * by loading the view
	 * declared in the class
	 * or overridden by the user
	 * when constructing the grid
	 * 
	 * @access	public
	 * @return	string	Asset html
	 */
	public function get_assets()
	{
		return \View::factory($this->_assets_view);
	}
	
	/**
	 * Set Uses Pagination
	 * 
	 * Sets the uses pagination
	 * class property
	 * 
	 * For example:
	 * 
	 * 		// Set uses pagination
	 * 		$grid->set_uses_pagination(true);
	 * 
	 * 		// Set pagination size at same
	 * 		// time
	 * 		$grid->set_uses_pagination(25);
	 * 
	 * @access	public
	 * @param	bool	Uses pagination
	 * @return	Spark\Grid
	 */
	public function set_uses_pagination($pagination = true)
	{
		// Set the property
		$this->_uses_pagination = (bool) $pagination;
		
		// Set size
		if (is_int($pagination)) $this->set_pagination_size($pagination);
		
		return $this;
	}
	
	/**
	 * Get Uses Pagination
	 * 
	 * Gets the uses pagination
	 * class property
	 * 
	 * @access	public
	 * @return	bool
	 */
	public function get_uses_pagination()
	{
		return $this->_uses_pagination;
	}
	
	/**
	 * Set Pagination Size
	 * 
	 * Sets the pagination size
	 * class property
	 * 
	 * @access	public
	 * @param	int		Size
	 * @return	Spark\Grid
	 */
	public function set_pagination_size($size = 10)
	{
		$this->_pagination_size = $size;
		
		return $this;
	}
	
	/**
	 * Get Pagination Size
	 * 
	 * Gets the pagination size
	 * class property
	 * 
	 * @access	public
	 * @return	int		Pagination size
	 */
	public function get_pagination_size()
	{
		return $this->_pagination_size;
	}
	
	/**
	 * Set Pagination Row Count
	 * 
	 * Sets the paginated row
	 * count class property
	 * 
	 * @access	public
	 * @param	int		Count
	 * @return	Spark\Grid
	 */
	public function set_paginated_row_count($count)
	{
		$this->_paginated_row_count = $count;
		
		return $this;
	}
	
	/**
	 * Get Pagination Row Count
	 * 
	 * Gets the paginated row
	 * count class property
	 * 
	 * @access	public
	 * @return	int		Count
	 */
	public function get_paginated_row_count()
	{
		return $this->_paginated_row_count;
	}
	
	/**
	 * Set Total Row Count
	 * 
	 * Sets the total row
	 * count class property
	 * 
	 * @access	public
	 * @param	int		Count
	 * @return	Spark\Grid
	 */
	public function set_total_row_count($count)
	{
		$this->_total_row_count = $count;
		
		// Also set the number
		// of pages
		$this->_pagination_pages_count = ceil($count / $this->get_pagination_size());
		
		return $this;
	}
	
	/**
	 * Get Total Row Count
	 * 
	 * Gets the total row
	 * count class property
	 * 
	 * @access	public
	 * @return	int		Count
	 */
	public function get_total_row_count()
	{
		return $this->_total_row_count;
	}
	
	/**
	 * Get Pagination Pages Count
	 * 
	 * Gets the pagination pages
	 * count class property
	 * 
	 * @access	public
	 * @return	int		Count
	 */
	public function get_pagination_pages_count()
	{
		return $this->_pagination_pages_count;
	}
	
	/**
	 * Get Pagination Page
	 * 
	 * Gets the pagination page
	 * class property
	 * 
	 * @access	public
	 * @return	int		Page
	 */
	public function get_pagination_page()
	{
		if ( ! $this->_pagination_page)
		{
			// Get the session
			$session = \Session::get('grid');

			$this->_pagination_page = (isset($session[$this->get_identifier()]['pagination'])) ? $session[$this->get_identifier()]['pagination'] : 1;
		}
		
		return $this->_pagination_page;
	}
	
	/**
	 * Set Default Sort
	 * 
	 * Sets the default sort
	 * for the grid
	 * 
	 * Note:	You must provide
	 *			the column alias
	 * 			to the sort that
	 * 			you used to configure
	 * 			the grid
	 * 
	 * @access	public
	 * @param	string	Column
	 * @param	string	Direction
	 */
	public function set_default_sort($column, $direction = null)
	{
		$this->_default_sort = $column;
		
		return $this->set_default_sort_direction($direction);
	}
	
	/**
	 * Set Default Sort Direction
	 * 
	 * Sets the default sort direction
	 * for the grid
	 * 
	 * @access	public
	 * @param	string	Sort direction
	 * @return	Spark\Grid
	 */
	public function set_default_sort_direction($direction = null)
	{
		// Directions
		$directions = array('asc', 'desc');
		
		// Set the class property
		if ( ! is_null($direction) and in_array($direction, $directions)) $this->_default_sort_direction = $direction;
		
		return $this;
	}
	
	/**
	 * Get Default Sort
	 * 
	 * Gets the default sort
	 * for the grid
	 * 
	 * @access	public
	 * @return	string	Sort
	 */
	public function get_default_sort()
	{
		return $this->_default_sort;
	}
	
	/**
	 * Get Default Sort Direction
	 * 
	 * Gets the default sort direction
	 * for the grid
	 * 
	 * @access	public
	 * @return	string	Sort direction
	 */
	public function get_default_sort_direction()
	{
		return $this->_default_sort_direction;
	}
	
	/**
	 * Set Additional CSS Class
	 * 
	 * Sets the "additional CSS
	 * class" class property
	 * 
	 * @access	public
	 * @param	int		Additional CSS class
	 * @return	Spark\Grid
	 */
	public function set_additional_css_class($class)
	{
		$this->_additional_css_class = $class;
		
		return $this;
	}
	
	/**
	 * Get Additional CSS Class
	 * 
	 * Gets the "additional CSS
	 * class" class property
	 * 
	 * @access	public
	 * @return	int		Additional CSS class
	 */
	public function get_additional_css_class()
	{
		return $this->_additional_css_class;
	}
}

/* End of file classes/grid.php */