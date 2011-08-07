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
	 * Container
	 * 
	 * The grid container object
	 * 
	 * @var	Spark\Grid_Container
	 */
	protected $_container;
	
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
		
		$this->set_identifier($identifier);
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
		// Only add the column if it doesn't exist
		if ( ! $this->get_columns()->has_data($identifier))
		{
			$this->get_columns()->set_data($identifier, \Grid_Column::factory($identifier, $attributes)
																	->set_grid($this));
		}
		
		return $this;
	}
	
	/**
	 * Build
	 * 
	 * Builds the grid and renders
	 * it as HTML
	 * 
	 * @access	public
	 * @return	View
	 */
	public function build()
	{
		$this->_prepare_grid();
		
		$container = $this->get_container()
						  ->build();
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
		$this->get_container()
			 ->set_add_button($attributes);
		
		return $this;
	}
}