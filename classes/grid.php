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

class Grid_InvalidConfigurationException extends Exception {}
class Grid_InvalidQueryException extends Exception {}
class Grid_InvalidFormatException extends Exception {}

class Grid extends \Object
{
	
	/**
	 * Object containing all available drivers
	 * 
	 * @var	Spark\Object
	 */
	protected $_drivers;
	
	/**
	 * Object containing driver for the grid
	 * 
	 * @var	Spark\Grid_Driver_Abstract
	 */
	protected $_driver;
	
	/**
	 * Query object
	 * 
	 * @var	mixed
	 */
	protected $_query;
	
	/**
	 * Primary key of query object
	 * 
	 * @var	string
	 */
	protected $_primary_key;
	
	/**
	 * Object containing columns for the grid
	 * 
	 * @var	Spark\Object
	 */
	protected $_columns;
	
	/**
	 * Object containing rows for the grid
	 * 
	 * @var	Spark\Object
	 */
	protected $_rows;
	
	/**
	 * Page and sorting property var names,
	 * used in GET urls when manipulating the
	 * grid. These can be overriden if they conflict
	 * 
	 * @var	mixed
	 */
	protected $_var_name_limit		= 'limit';
	protected $_var_name_page		= 'page';
	protected $_var_name_sort		= 'sort';
	protected $_var_name_direction	= 'direction';
	protected $_var_name_filters	= 'filters';
	
	/**
	 * Default limit, sort and direction
	 * 
	 * @var	mixed
	 */
	protected $_default_limit		= 20;
	protected $_default_page		= 1;
	protected $_default_sort;
	protected $_default_direction	= 'desc';
	protected $_default_filters		= array();
	
	/**
	 * Limit options for the grid
	 * 
	 * @var	array
	 */
	protected $_limit_options = array(
		10		=> 10,
		20		=> 20,
		30		=> 30,
		50		=> 50,
		100		=> 100,
		200		=> 200,
	);
	
	/**
	 * An object storing the get
	 * parameters for page, sorting
	 * etc, outlined above
	 * 
	 * @var	bool
	 */
	protected $_params;
	
	/**
	 * The grid container object
	 * 
	 * @var	Spark\Grid_Container
	 */
	protected $_container;
	
	/**
	 * Flag to determine if the
	 * grid uses a container
	 * 
	 * @var	bool
	 */
	protected $_uses_container = false;
	
	/**
	 * The url of the grid, used for
	 * reloading, and ajax requests
	 * 
	 * @var	string
	 */
	protected $_url;
	
	/**
	 * Flat to determine if the grid
	 * uses ajax to update or not
	 * 
	 * @var	bool
	 */
	protected $_uses_ajax = true;
	
	/**
	 * A user defined string that
	 * represents each row action
	 * 
	 * @var	string
	 */
	protected $_row_action;
	
	/**
	 * The object containing
	 * all massactions for the grid
	 * 
	 * @var	Spark\Object
	 */
	protected $_massactions;
	
	/**
	 * The index used for the
	 * massactions
	 * 
	 * @var	string
	 */
	protected $_massactions_index;
	
	/**
	 * The name used for the
	 * massactions
	 * 
	 * @var	string
	 */
	protected $_massactions_name;

	/**
	 * Whether or not the grid
	 * uses filters
	 * 
	 * @var bool
	 */
	protected $_uses_filters = true;
	
	/**
	 * Construct
	 * 
	 * Called when the class is constructed
	 * 
	 * @access	public
	 * @param	mixed
	 * @return	Spark\Grid
	 */
	public function __construct($identifier = null, $query = null)
	{
		// Check we've got an identifier
		if ( ! $identifier) throw new Grid_InvalidConfigurationException('An identifier must be provided when initialising the grid');
		
		// Check we've got a query
		if ( ! is_object($query)) throw new Grid_InvalidQueryException('You must provide a query when initialising the grid');
		
		// Set properties
		$this->set_identifier($identifier)
			 ->set_query($query);
		
		// Load config
		\Config::load('grid', true);
	}
	
	/**
	 * Set Identifier
	 * 
	 * Sets the identifier for the
	 * object
	 * 
	 * @access	public
	 * @param	string	Identifier
	 * @return	Spark\Grid
	 */
	public function set_identifier($identifier)
	{
		// Make sure the identifier is CSS and jQuery
		// friendly, as they both use the identifier
		// extensively
		$identifier = str_replace('--', '-', \Str::lower(\Str::alphanumeric($identifier, '-')));
		
		return parent::set_identifier($identifier);
	}
	
	/**
	 * Get Columns
	 * 
	 * Gets the columns object
	 * and lazy loads if it's
	 * not set
	 * 
	 * @access	public
	 * @return	Spark\Object
	 */
	public function get_columns()
	{
		// Get the columns
		if ( ! $this->_columns) $this->_columns = \Object::forge();
		
		return $this->_columns;
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
	public function add_column($identifier = null, Array $attributes = array())
	{
		// Sanitise the identifier
		$identifier = \Str::alphanumeric($identifier, '_');
		
		// Only add the column if it doesn't exist
		if ( ! $this->get_columns()->has_data($identifier))
		{
			// Determine if we're using the first column
			if ( ! $this->get_columns()->count()) $attributes['first'] = true;
			
			// Set the column
			$this->get_columns()->set_data($identifier, \Grid_Column::forge($identifier, $attributes)
																	->set_grid($this)
																	->make_recursive());
			
			// Set the last column
			$i = 0;
			foreach ($this->get_columns() as $column)
			{
				if (++$i < $this->get_columns()->count()) $column->uns_last();
				else $column->set_last(true);
			}
		}
		
		return $this;
	}
	
	/**
	 * Set Row Action
	 * 
	 * Sets the action of
	 * each row in the grid
	 * 
	 * @access	public
	 * @param	string	Row action
	 * @return	Spark\Grid
	 */
	public function set_row_action($row_action)
	{
		$this->_row_action = (string) $row_action;
		return $this;
	}
	
	/**
	 * Get Row Action
	 * 
	 * Gets the action of
	 * each row in the grid
	 * 
	 * @access	public
	 * @return	string	Row action
	 */
	public function get_row_action()
	{
		return $this->_row_action;
	}
	
	/**
	 * To String
	 * 
	 * Represents the object
	 * as a string
	 * 
	 * @access	public
	 * @return	string
	 */
	public function __toString()
	{
		// Create a new error
		$e = new Exception(\Str::f('Object of class %s could not be converted to string. This has to do with overriding the response when ajax is used with the grid', get_class($this)));
		
		// Show it
		\Error::show_php_error($e);
	}
	
	/**
	 * Build
	 * 
	 * Builds the grid and renders
	 * it as a View object
	 * 
	 * @access	public
	 * @return	View
	 */
	public function build()
	{
		// Make sure we have columns
		if ( ! $this->get_columns()->count()) throw new Exception('You must add a column to the grid before building it');
		
		$this->_prepare_grid();
		
		// Build the grid
		$grid = \View::forge(\Config::get('grid.view.grid', 'grid'))
					 ->set('grid', $this, false);
		
		// If we can't display a container just return
		// the grid
		if ( ! $this->can_display_container())
		{
			// If we're dealing with an
			// AJAX request, we want to
			// stream a new response
			if (\Input::is_ajax() and $this->should_override_response())
			{
				// This is needed because of the
				// way all template output
				// is added to the output buffer
				ob_clean();

				// Create and send a response
				// with the grid as the contents
				$response = new \Response($grid);
				
				$response->send(true);
				
				// Close down fuel
				\Event::shutdown();
				\Fuel::finish();
				
				exit;
			}
			
			// Return the grid
			return $grid;
		}
		
		// If we can display a container, add it
		// and return both of them
		return $this->get_container()
					->build()
					->set('grid', $grid, false);
	}
	
	/**
	 * Should Override Response
	 * 
	 * Determine if this grid
	 * should override the response
	 * 
	 * @access	public
	 * @return	bool	Should override response
	 */
	public function should_override_response()
	{
		return (bool) (\Input::get('grid_identifier') == $this->get_identifier());
	}
	
	/**
	 * Set Query
	 * 
	 * Sets the query for the grid
	 * 
	 * @access	public
	 * @param	mixed	Query
	 * @return	Spark\Grid
	 */
	public function set_query($query)
	{
		$this->_query = $query;
		return $this;
	}
	
	/**
	 * Get Query
	 * 
	 * Gets the query for the grid
	 * 
	 * @access	public
	 * @return	mixed
	 */
	public function get_query()
	{
		return $this->_query;
	}
	
	/**
	 * Register Driver
	 * 
	 * Registers a driver
	 * with the grid
	 * 
	 * @access	public
	 * @param	string	Class of query
	 * @param	string	Class of driver
	 * @return	Spark\Grid
	 */
	public function register_driver($query, $driver)
	{
		// Add the driver to the list
		if ( ! $this->get_drivers()->has_data($query))
		{
			$this->get_drivers()->set_data($query, $driver);
		}
		
		return $this;
	}
	
	/**
	 * Get Drivers
	 * 
	 * Gets the drivers
	 * for the grid
	 * 
	 * @access	public
	 * @return	Spark\Grid
	 */
	public function get_drivers()
	{
		// Lazy load the drivers
		// with the default drivers
		if ( ! $this->_drivers)
		{
			$this->_drivers = \Object::forge(array(
				'Orm\\Query'								=> 'Grid_Driver_Orm',
				'Fuel\\Core\\Database_Query_Builder_Select'	=> 'Grid_Driver_Database',
			));
		}
		
		return $this->_drivers;
	}
	
	/**
	 * Get Driver
	 * 
	 * Gets the driver for the
	 * grid, based off the query
	 * 
	 * @access	public
	 * @return	Spark\Grid_Driver_Abstract
	 */
	public function get_driver()
	{
		// Lazy load the driver
		if ( ! $this->_driver)
		{
			// Loop through drivers and determine the driver based off
			// the class the query has
			foreach ($this->get_drivers() as $query_class => $driver_class)
			{
				// We can either deal with a class or a subclass
				if (get_class($this->get_query()) === $query_class or is_subclass_of(get_class($this->get_query()), $query_class))
				{
					$this->_driver = $driver_class::forge()
												  ->set_grid($this);
					
					// Make sure the driver is valid
					if ( ! $this->_driver instanceof \Grid_Driver_Abstract) throw new Grid_InvalidConfigurationException('Grid drivers must extend Spark\\Grid_Driver_Abstract');
					
					break;
				}
			}
			
			// If we still haven't found a driver
			// throw an exception
			if ( ! $this->_driver) throw new Exception(Str::f('There is no driver for the a query which is an instance of %s', get_class($this->get_query())));
		}
		
		return $this->_driver;
	}
	
	/**
	 * Get Rows
	 * 
	 * Gets the rows for
	 * the grid
	 * 
	 * @access	public
	 * @return	Spark\Object
	 */
	public function get_rows()
	{
		// If our driver wants the rows to build them
		if ( ! $this->_rows)
		{
			$this->_rows = \Object::forge();
		}
		
		return $this->_rows;
	}
	
	/**
	 * Can Display Container
	 * 
	 * Determines if the grid can
	 * display a container or not
	 * 
	 * @access	public
	 * @return	bool	Can display container
	 */
	public function can_display_container()
	{
		// If we're using ajax we can never
		// use the container
		if (\Input::is_ajax()) return false;
		
		// If it's not ajax, determine based off
		// whether the grid is set to use the continaer
		return $this->get_uses_container();
	}
	
	/**
	 * Get Container
	 * 
	 * Gets the container object
	 * for the grid
	 * 
	 * @access	public
	 * @return	Spark\Grid_Container
	 */
	public function get_container()
	{
		if ( ! $this->_container)
		{
			$this->_container = \Grid_Container::forge()
											   ->set_grid($this);
		}
		
		return $this->_container;
	}
	
	/**
	 * Prepare Grid
	 * 
	 * Prepares the grid to be built
	 * 
	 * @access	protected
	 * @return	Spark\Grid
	 */
	protected function _prepare_grid()
	{
		// Prepare elements
		$this->_prepare_massactions()
			 ->_prepare_columns()
			 ->_prepare_query()
			 ->_prepare_rows();
		
		return $this;
	}
	
	/**
	 * Get Parameters
	 * 
	 * Gets the get parameters
	 * 
	 * @access	public
	 * @return	Spark\Object	Get
	 */
	public function get_params()
	{
		// Initialise the get
		// parameters
		if ( ! $this->_params)
		{
			// Get the cookie
			$cookie = \Object::forge($_COOKIE);
			
			// Params fallback with default
			// values
			$params = \Object::forge(array(
				$this->_var_name_limit		=> $this->_default_limit,
				$this->_var_name_page		=> $this->_default_page,
				$this->_var_name_sort		=> $this->_default_sort,
				$this->_var_name_direction	=> $this->_default_direction,
				$this->_var_name_filters	=> $this->_default_filters,
			));
			
			// Now override the values with the
			// user provided values
			if (isset($cookie['grid-' . $this->get_identifier()]) and $new_params = json_decode(stripslashes($cookie['grid-' . $this->get_identifier()]), true))
			{
				$params->add_data($new_params)
					   ->make_recursive();
			}
			
			$this->_params = $params;
		}
		
		return $this->_params;
	}
	
	/**
	 * Prepare Columns
	 * 
	 * Prepares the columns for the
	 * grid
	 * 
	 * @access	public
	 * @return	Spark\Grid
	 */
	protected function _prepare_columns()
	{
		// Loop through filters in the parameters and place appropriate
		// filters in appropriate columns
		if ($this->get_params()->get_data($this->get_var_name_filters()))
		{
			foreach ($this->get_params()->get_data($this->get_var_name_filters()) as $column_identifier => $filter)
			{
				if ($column = $this->get_columns()->{'get_'.$column_identifier}())
				{
					$column->get_filter()
						   ->set_user_value($filter);
				}
			}
		}
		
		return $this;
	}
	
	/**
	 * Prepare Query
	 * 
	 * Prepares the query for the grid
	 * 
	 * @access	protected
	 * @return	Spark\Grid
	 */
	protected function _prepare_query()
	{
		// Make sure that the page is valid
		if (($page = $this->get_params()->get_data($this->get_var_name_page())) !== null)
		{
			// Make an int
			$page = intval($page);
			if ($page < 1) $page = 1;
			$this->get_params()->set_data($this->get_var_name_page(), $page);
		}
		
		$this->get_driver()->prepare_query();
		
		return $this;
	}
	
	/**
	 * Prepare Rows
	 * 
	 * Prepares the rows for the
	 * grid
	 * 
	 * @access	public
	 * @return	Spark\Grid
	 */
	protected function _prepare_rows()
	{
		$this->get_driver()
			 ->build_rows();
		
		return $this;
	}
	
	/**
	 * Prepare Massactions
	 * 
	 * Prepares the massactions for the
	 * grid
	 * 
	 * @access	public
	 * @return	Spark\Grid
	 */
	protected function _prepare_massactions()
	{
		// Check for massactions
		if ($this->get_massactions()->count())
		{
			// Create a massaction column
			$massaction_column = \Grid_Column::forge('massactions', array(
													'index'		=> $this->get_massactions_index(),
													'type'		=> 'massaction',
													'header'	=> 'Massactions',
													'align'		=> 'center',
													'width'		=> 30,
											 ))
											 ->set_grid($this)
											 ->make_recursive();
			
			// Add the column
			$this->get_columns()->set_data('massactions', $massaction_column);
		}
		
		return $this;
	}
	
	/**
	 * Get Var Name Limit
	 * 
	 * Gets the var name limit
	 * class property
	 * 
	 * @access	public
	 * @return	string	Var name limit
	 */
	public function get_var_name_limit()
	{
		return $this->_var_name_limit;
	}
	
	/**
	 * Get Var Name Page
	 * 
	 * Gets the var name page
	 * class property
	 * 
	 * @access	public
	 * @return	string	Var name page
	 */
	public function get_var_name_page()
	{
		return $this->_var_name_page;
	}
	
	/**
	 * Get Var Name Sort
	 * 
	 * Gets the var name sort
	 * class property
	 * 
	 * @access	public
	 * @return	string	Var name sort
	 */
	public function get_var_name_sort()
	{
		return $this->_var_name_sort;
	}
	
	/**
	 * Get Var Name Direction
	 * 
	 * Gets the var name direction
	 * class property
	 * 
	 * @access	public
	 * @return	string	Var name direction
	 */
	public function get_var_name_direction()
	{
		return $this->_var_name_direction;
	}
	
	/**
	 * Get Var Name Filters
	 * 
	 * Gets the var name filters
	 * class property
	 * 
	 * @access	public
	 * @return	string	Var name filters
	 */
	public function get_var_name_filters()
	{
		return $this->_var_name_filters;
	}

	/**
	 * Set Var Name Limit
	 * 
	 * Sets the var name limit
	 * class property
	 * 
	 * @access	public
	 * @return	string	Var name limit
	 */
	public function set_var_name_limit($limit)
	{
		$this->_var_name_limit = $limit;
		return $this;
	}

	/**
	 * Set Var Name Page
	 * 
	 * Sets the var name page
	 * class property
	 * 
	 * @access	public
	 * @return	string	Var name page
	 */
	public function set_var_name_page($page)
	{
		$this->_var_name_page = $page;
		return $this;
	}

	/**
	 * Set Var Name Sort
	 * 
	 * Sets the var name sort
	 * class property
	 * 
	 * @access	public
	 * @return	string	Var name sort
	 */
	public function set_var_name_sort($sort)
	{
		$this->_var_name_sort = $sort;

		// Convenience method to set sort and
		// direction in one
		if ($direction !== null)
		{
			$this->set_var_name_direction($direction);
		}
		
		return $this;
	}

	/**
	 * Set Var Name Direction
	 * 
	 * Sets the var name direction
	 * class property
	 * 
	 * @access	public
	 * @return	string	Var name direction
	 */
	public function set_var_name_direction($direction)
	{
		$this->_var_name_direction = $direction;
		return $this;
	}

	/**
	 * Set Var Name Filters
	 * 
	 * Sets the var name filters
	 * class property
	 * 
	 * @access	public
	 * @return	string	Var name filters
	 */
	public function set_var_name_filters(Array $filters)
	{
		$this->_var_name_filters = $filters;
		return $this;
	}

	/**
	 * Get Default Limit
	 * 
	 * Gets the default limit
	 * class property
	 * 
	 * @access	public
	 * @return	string	Var name limit
	 */
	public function get_default_limit()
	{
		return $this->_default_limit;
	}
	
	/**
	 * Get Default Page
	 * 
	 * Gets the default page
	 * class property
	 * 
	 * @access	public
	 * @return	string	Var name page
	 */
	public function get_default_page()
	{
		return $this->_default_page;
	}
	
	/**
	 * Get Default Sort
	 * 
	 * Gets the default sort
	 * class property
	 * 
	 * @access	public
	 * @return	string	Var name sort
	 */
	public function get_default_sort()
	{
		return $this->_default_sort;
	}
	
	/**
	 * Get Default Direction
	 * 
	 * Gets the default direction
	 * class property
	 * 
	 * @access	public
	 * @return	string	Var name direction
	 */
	public function get_default_direction()
	{
		return $this->_default_direction;
	}
	
	/**
	 * Get Default Filters
	 * 
	 * Gets the default filters
	 * class property
	 * 
	 * @access	public
	 * @return	string	Var name filters
	 */
	public function get_default_filters()
	{
		return $this->_default_filters;
	}

	/**
	 * Set Default Limit
	 * 
	 * Sets the default limit
	 * class property
	 * 
	 * @access	public
	 * @return	string	Var name limit
	 */
	public function set_default_limit($limit)
	{
		$this->_default_limit = $limit;
		return $this;
	}

	/**
	 * Set Default Page
	 * 
	 * Sets the default page
	 * class property
	 * 
	 * @access	public
	 * @return	string	Var name page
	 */
	public function set_default_page($page)
	{
		$this->_default_page = $page;
		return $this;
	}

	/**
	 * Set Default Sort
	 * 
	 * Sets the default sort
	 * class property
	 * 
	 * @access	public
	 * @return	string	Var name sort
	 */
	public function set_default_sort($sort, $direction = null)
	{
		$this->_default_sort = $sort;

		// Convenience method to set sort and
		// direction in one
		if ($direction !== null)
		{
			$this->set_default_direction($direction);
		}

		return $this;
	}

	/**
	 * Set Default Direction
	 * 
	 * Sets the default direction
	 * class property
	 * 
	 * @access	public
	 * @return	string	Var name direction
	 */
	public function set_default_direction($direction)
	{
		$this->_default_direction = $direction;
		return $this;
	}

	/**
	 * Set Default Filters
	 * 
	 * Sets the default filters
	 * class property
	 * 
	 * @access	public
	 * @return	string	Var name filters
	 */
	public function set_default_filters(Array $filters)
	{
		$this->_default_filters = $filters;
		return $this;
	}
	
	/**
	 * Add Button
	 * 
	 * Adds a button to the grid
	 * 
	 * @access	public
	 * @param	array	Attributes
	 * @return	Spark\Grid
	 */
	public function add_button($identifier = null, Array $attributes = array())
	{
		// Sanitise the identifier
		$identifier = \Str::alphanumeric($identifier, '_');
		
		$this->set_uses_container(true)
			 ->get_container()
			 ->add_button($identifier, $attributes);
		
		return $this;
	}
	
	/**
	 * Get Massactions
	 * 
	 * Gets the object containing
	 * massactions for the grid
	 * 
	 * @access	public
	 * @return	Spark\Object
	 */
	public function get_massactions()
	{
		if ( ! $this->_massactions)
		{
			$this->_massactions = \Object::forge();
		}
		
		return $this->_massactions;
	}
	
	/**
	 * Set Massactions Index
	 * 
	 * Sets the index for the
	 * massactions to use
	 * 
	 * @access	public
	 * @param	string	Index
	 * @return	Spark\Grid
	 */
	public function set_massactions_index($index)
	{
		$this->_massactions_index = (string) $index;
		return $this;
	}
	
	/**
	 * Get Massactions Index
	 * 
	 * Gets the index used for the
	 * massactions to use
	 * 
	 * @access	public
	 * @return	string	Index
	 */
	public function get_massactions_index()
	{
		// Default the massactions index
		// to the primary key
		if ( ! $this->_massactions_index)
		{
			$this->_massactions_index = $this->get_primary_key();
		}
		
		return $this->_massactions_index;
	}
	
	/**
	 * Set Massactions Name
	 * 
	 * Sets the massactions name
	 * for the grid
	 * 
	 * @access	public
	 * @param	string	Name
	 * @return	Spark\Grid
	 */
	public function set_massactions_name($name)
	{
		$this->_massactions_name = (string) $name;
		return $this;
	}
	
	/**
	 * Get Massactions Name
	 * 
	 * Gets the massactions name
	 * for the grid
	 * 
	 * @access	public
	 * @return	string	Massactions name
	 */
	public function get_massactions_name()
	{
		if ( ! $this->_massactions_name)
		{
			// Default to the plural version of the massactions index
			$this->_massactions_name = \Inflector::pluralize($this->get_massactions_index()) . '[]';
		}
		
		return $this->_massactions_name;
	}
	
	/**
	 * Add Massaction
	 * 
	 * Adds a massaction to
	 * the grid
	 * 
	 * @access	public
	 * @param	string	Identifier
	 * @param	array	Attributes
	 * @return	Spark\Grid
	 */
	public function add_massaction($identifier = null, Array $attributes = array())
	{
		// Sanitise the identifier
		$identifier = \Str::alphanumeric($identifier, '_');
		
		// Only add the massaction if it doesn't exist
		if ( ! $this->get_massactions()->has_data($identifier))
		{
			// Set the massaction
			$this->get_massactions()->set_data($identifier, \Grid_Massaction::forge($identifier, $attributes)
																			->set_grid($this)
																			->make_recursive());
			
			// Set we're using a container
			if ( ! $this->get_uses_container()) $this->set_uses_container(true);
		}
		
		return $this;
	}
	
	/**
	 * Set Uses Container
	 * 
	 * Sets whether the grid
	 * uses a container
	 * 
	 * @access	public
	 * @param	bool	Uses container
	 * @return	Spark\Grid
	 */
	public function set_uses_container($uses)
	{
		$this->_uses_container = (bool) $uses;
		return $this;
	}
	
	/**
	 * Get Uses Container
	 * 
	 * Gets whether the grid
	 * uses a container
	 * 
	 * @access	public
	 * @return	bool	Uses container
	 */
	public function get_uses_container()
	{
		return $this->_uses_container;
	}
	
	/**
	 * Set Url
	 * 
	 * Sets the grid
	 * url
	 * 
	 * @access	public
	 * @param	string	Url
	 * @return	Spark\Grid
	 */
	public function set_url($url)
	{
		$this->_url = $url;
		return $this;
	}
	
	/**
	 * Get Url
	 * 
	 * Gets the grid
	 * url
	 * 
	 * @access	public
	 * @return	string	Grid url
	 */
	public function get_url()
	{
		// Lazy load url
		if ( ! $this->_url) $this->_url = \Uri::current();
		
		return $this->_url;
	}
	
	/**
	 * Set Uses Ajax
	 * 
	 * Sets whether the grid
	 * uses ajax
	 * 
	 * @access	public
	 * @param	bool	Uses ajax
	 * @return	Spark\Grid
	 */
	public function set_uses_ajax($uses)
	{
		$this->_uses_ajax = (bool) $uses;
		return $this;
	}
	
	/**
	 * Get Uses Ajax
	 * 
	 * Gets whether the grid
	 * uses ajax
	 * 
	 * @access	public
	 * @return	bool	Uses ajax
	 */
	public function get_uses_ajax()
	{
		return $this->_uses_ajax;
	}
	
	/**
	 * Get Current Params Json
	 * 
	 * Gets the current grid
	 * params for json
	 * 
	 * @access	public
	 * @return	string	Json
	 */
	public function get_current_params_json()
	{
		// Get cookie data
		$cookie = \Object::forge($_COOKIE);
		
		if (isset($cookie['grid-' . $this->get_identifier()]))
		{
			return stripslashes($cookie['grid-' . $this->get_identifier()]);
		}
		
		return false;
	}
	
	/**
	 * Get Page
	 * 
	 * Gets the page
	 * parameter
	 * 
	 * @access	public
	 * @return	int		Page
	 */
	public function get_page()
	{
		return $this->get_params()->get_data($this->get_var_name_page());
	}
	
	/**
	 * Get Total Pages
	 * 
	 * Gets the total
	 * pages
	 * 
	 * @access	public
	 * @return	int		Total pages
	 */
	public function get_total_pages()
	{
		return $this->get_params()->get_total_pages();
	}
	
	/**
	 * Get Total Records
	 * 
	 * Gets the total
	 * records found
	 * 
	 * @access	public
	 * @return	int		Total records
	 */
	public function get_total_records()
	{
		return $this->get_params()->get_total_records();
	}
	
	/**
	 * Get Records Shown
	 * 
	 * Gets the records shown
	 * on the current grid
	 * 
	 * @access	public
	 * @return	int		Records
	 */
	public function get_records_shown()
	{
		return $this->get_rows()->count();
	}
	
	/**
	 * Get Limit
	 * 
	 * Gets the current
	 * limit placed on the
	 * grid
	 * 
	 * @access	public
	 * @return	int		Limit
	 */
	public function get_limit()
	{
		return $this->get_params()->get_limit();
	}
	
	/**
	 * Set Limit Options
	 * 
	 * Sets the limit options
	 * for the grid
	 * 
	 * Note: You don't need to
	 *       include the default
	 *       limit if you're overridden
	 *       it, as it's added dynamically in
	 *       Spark\Grid::get_limit_options()
	 * 
	 * @access	public
	 * @param	array	Limit options
	 * @return	Spark\Grid
	 */
	public function set_limit_options(Array $options)
	{
		$this->_limit_options = $options;
	}
	
	/**
	 * Get Limit Options
	 * 
	 * Returns an associative
	 * array of limit options
	 * 
	 * @access	public
	 * @return	array	Limit Options
	 */
	public function get_limit_options()
	{
		// If the limit we have is not standard
		// we need to add it to the array
		if ( ! in_array($this->_default_limit, $this->_limit_options))
		{
			$this->_limit_options[$this->_default_limit] = $this->_default_limit;
		}
		
		// Sort the options
		// so they're ascending
		ksort($this->_limit_options);
		
		return $this->_limit_options;
	}
	
	/**
	 * Set Primary Key
	 * 
	 * Sets the primary key of
	 * the query object
	 * 
	 * @access	public
	 * @param	string	Primary key
	 * @return	Spark\Grid
	 */
	public function set_primary_key($key)
	{
		$this->_primary_key = (string) $key;
		return $this;
	}
	
	/**
	 * Get Primary Key
	 * 
	 * Gets the primary key of
	 * the grid
	 * 
	 * @access	public
	 * @return	string	Primary key
	 */
	public function get_primary_key()
	{
		// If the user hasn't set
		// the primary key
		if ( ! $this->_primary_key)
		{
			// Set the primary key
			$this->get_driver()
				 ->set_primary_key();
		}
		
		return $this->_primary_key;
	}

	/**
	 * Set Uses Filters
	 * 
	 * Sets the uses filters flag
	 * 
	 * @access  public
	 * @param   bool   Uses filters
	 * @return  Spark\Grid
	 */
	public function set_uses_filters($uses)
	{
		$this->_uses_filters = (bool) $uses;
		return $this;
	}

	/**
	 * Get Uses Filters
	 * 
	 * Gets the uses filters flag
	 * 
	 * @access  public
	 * @return  bool   Uses filters
	 */
	public function get_uses_filters()
	{
		return $this->_uses_filters;
	}

	/**
	 * Get Uses Controls
	 * 
	 * Gets the uses controls
	 * flag
	 * 
	 * @access  public
	 * @return  bool   Uses controls
	 */
	public function get_uses_controls()
	{
		return $this->_uses_controls;
	}

	/**
	 * Set Is Basic Mode
	 * 
	 * Sets the grid in
	 * basic mode, no filters / controls
	 * 
	 * @access  public
	 * @param   bool   Is basic mode
	 */
	public function set_is_basic_mode($is_basic = true)
	{
		if ($is_basic === true)
		{
			$this->set_uses_controls(false)
				 ->set_uses_filters(false);
			
			// Remove *all* pagination
			$this->set_default_limit(999999);
		}
		else
		{
			$this->set_uses_controls(true)
				 ->set_uses_filters(true)
				 ->set_default_limit(reset($this->get_limit_options()));
		}

		return $this;
	}
}