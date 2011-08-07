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

class Grid extends \Object {
	
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
	 * Model object
	 * 
	 * @var	mixed
	 */
	protected $_model;
	
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
	 * The view name used for the grid
	 * 
	 * @var	string
	 */
	protected $_view_name = 'grid';
	
	/**
	 * Default page and sorting properties
	 * 
	 * @var	mixed
	 */
	protected $_default_limit		= 20;
	protected $_default_page		= 1;
	protected $_default_sort		= false;
	protected $_default_direction	= 'desc';
	protected $_default_filters		= array();
	
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
	 * The grid container object
	 * 
	 * @var	Spark\Grid_Container
	 */
	protected $_container;
	
	/**
	 * Flag to determine if the
	 * grid uses a container
	 * 
	 * @var	Spark\Grid_Container
	 */
	protected $_uses_container = false;
	
	/**
	 * Construct
	 * 
	 * Called when the class is constructed
	 * 
	 * @access	public
	 * @param	mixed
	 * @return	Spark\Grid
	 */
	public function __construct($identifier = null, $model = null)
	{
		// Check we've got an identifier
		if ( ! $identifier) throw new Exception('An identifier must be provided when initialising the grid');
		
		// Check we've got a model
		if ( ! is_object($model)) throw new Exception('You must provide a model when initialising the grid');
		
		$this->set_identifier($identifier)
			 ->set_model($model);
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
		if ( ! $this->_columns) $this->_columns = \Object::factory();
		
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
	public function add_column($identifier = null, array $attributes = array())
	{
		// Sanitise the identifier
		$identifier = \Str::alphanumeric($identifier, '_');
		
		// Only add the column if it doesn't exist
		if ( ! $this->get_columns()->has_data($identifier))
		{
			$this->get_columns()->set_data($identifier, \Grid_Column::factory($identifier, $attributes)
																	->set_grid($this)
																	->make_recursive());
		}
		
		return $this;
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
		// Make sure we handle errors nicely
		try
		{
			return (string) $this->build();
		}
		catch (\Exception $e)
		{
			\Error::show_php_error($e);
		}
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
		$grid = \View::factory($this->_view_name)
					 ->set('grid', $this, false);
		
		// If we can't display a container just return
		// the grid
		if ( ! $this->can_display_container()) return $grid;
		
		// If we can display a container, add it
		// and return both of them
		return $this->get_container()
					->build()
					->set('grid', $grid, false);
	}
	
	/**
	 * Set Model
	 * 
	 * Sets the model for the grid
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
	 * Gets the model for the grid
	 * 
	 * @access	public
	 * @return	mixed
	 */
	public function get_model()
	{
		return $this->_model;
	}
	
	/**
	 * Register Driver
	 * 
	 * Registers a driver
	 * with the grid
	 * 
	 * @access	public
	 * @param	string	Class of model
	 * @param	string	Class of driver
	 * @return	Spark\Grid
	 */
	public function register_driver($model, $driver)
	{
		// Add the driver to the list
		if ( ! $this->get_drivers()->has_data($model))
		{
			$this->get_drivers()->set_data($model, $driver);
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
			$this->_drivers = \Object::factory(array(
				'Orm\\Query'		=> 'Grid_Driver_Orm',
				'type'				=> 'class',
			));
		}
		
		return $this->_drivers;
	}
	
	/**
	 * Get Driver
	 * 
	 * Gets the driver for the
	 * grid, based off the model
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
			// the class the model has
			foreach ($this->get_drivers() as $model_class => $driver_class)
			{
				// We can either deal with a class or a subclass
				if (get_class($this->get_model()) === $model_class or is_subclass_of(get_class($this->get_model()), $model_class))
				{
					$this->_driver = $driver_class::factory()
												  ->set_grid($this);
					
					// Make sure the driver is valid
					if ( ! $this->_driver instanceof \Grid_Driver_Abstract) throw new Exception('Grid drivers must extend Spark\\Grid_Driver_Abstract');
					
					break;
				}
			}
			
			// If we still haven't found a driver
			// throw an exception
			if ( ! $this->_driver) throw new Exception(Str::f('There is no driver for the a model which is an instance of %s', get_class($this->get_model())));
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
			$this->_rows = \Object::factory();
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
			$this->_container = \Grid_Container::factory()
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
		$this->_prepare_columns()
			 ->_prepare_rows()
			 ->_prepare_massactions();
		
		return $this;
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
	public function set_var_name_filters($filters)
	{
		$this->_var_name_filters = $filters;
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
	public function set_add_button(array $attributes = array())
	{
		$this->set_uses_container(true)
			 ->get_container()
			 ->set_add_button($attributes);
		
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
	public function uses_container($uses)
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
}