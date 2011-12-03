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

class Grid_Column_Cell extends \Grid_Component
{
	
	/**
	 * Reference to the column
	 * this cell is attached to
	 * 
	 * @var	Spark\Grid_Column
	 */
	protected $_column;
	
	/**
	 * Reference to the row
	 * this cell is attached to
	 * 
	 * @var	Spark\Grid_Row
	 */
	protected $_row;
	
	/**
	 * The original value the cell
	 * has
	 * 
	 * @var	string
	 */
	protected $_original_value = false;
	
	/**
	 * The rendered value the cell
	 * has. It can either be a string
	 * or a View object
	 * 
	 * @var	string|View
	 */
	protected $_rendered_value;
	
	/**
	 * Set Column
	 * 
	 * Sets the column
	 * property that this
	 * cell belongs to
	 * 
	 * @access	public
	 * @param	Spark\Grid_Column	Column
	 * @return	Spark\Grid_Column_Cell
	 */
	public function set_column(\Grid_Column $column)
	{
		$this->_column = $column;
		return $this;
	}
	
	/**
	 * Get Column
	 * 
	 * Gets the column
	 * property that this
	 * cell belongs to
	 * 
	 * @access	public
	 * @return	Spark\Grid_Column_Cell
	 */
	public function get_column()
	{
		if ( ! $this->_column instanceof \Grid_Column) throw new \Exception(\Str::f('Cannot retrieve grid column instance for %s', get_class($this)));
		
		return $this->_column;
	}
	
	/**
	 * Set Row
	 * 
	 * Sets the row property
	 * this cell belongs to
	 * 
	 * @access	public
	 * @param	Spark\Grid_Row	Row
	 * @return	Spark\Grid_Column_Cell
	 */
	public function set_row(\Grid_Row $row)
	{
		$this->_row = $row;
		return $this;
	}
	
	/**
	 * Get Row
	 * 
	 * Gets the row property
	 * this cell belongs to
	 * 
	 * @access	public
	 * @return	Spark\Grid_Row	Row
	 */
	public function get_row()
	{
		if ( ! $this->_row instanceof \Grid_Row) throw new \Exception(\Str::f('Cannot retrieve row instance for %s', get_class($this)));
		
		return $this->_row;
	}
	
	/**
	 * Get Renderer
	 * 
	 * Gets the renderer for
	 * the column that the
	 * cell belongs to
	 * 
	 * @access	public
	 * @return	Spark\Grid_Column_Renderer_Abstract
	 */
	public function get_renderer()
	{
		return $this->get_column()->get_renderer();
	}
	
	/**
	 * Get Driver
	 * 
	 * Gets the driver for the cell
	 * 
	 * @access	public
	 * @return	Spark\Grid_Driver_Abstract
	 */
	public function get_driver()
	{
		return $this->get_grid()->get_driver();
	}
	
	/**
	 * Set Original Value
	 * 
	 * Sets the original value
	 * for the cell
	 * 
	 * @access	public
	 * @param	string	Original value
	 * @return	Spark\Grid_Column_Cell
	 */
	public function set_original_value($value)
	{
		$this->_original_value = $value;
		return $this;
	}
	
	/**
	 * Get Original Value
	 * 
	 * Gets the original value
	 * for the cell
	 * 
	 * @access	public
	 * @return	string	Original value
	 */
	public function get_original_value()
	{
		// We must have an original value
		if ($this->_original_value === false) throw new Exception('An original value must be provided to every cell in the grid');
		
		return $this->_original_value;
	}
	
	/**
	 * Set Rendered Value
	 * 
	 * Sets the rendered value
	 * for the cell
	 * 
	 * @access	public
	 * @param	string|View
	 * @return	Spark\Grid_Column_Cell
	 */
	public function set_rendered_value($value)
	{
		// We can either accept a string, value, or an instance of View
		if (is_null($value) or is_string($value) or is_numeric($value) or $value instanceof \View)
		{
			$this->_rendered_value = $value;
			return $this;
		}
		
		// Let the person know
		throw new Exception('The rendered value for a grid cell must be either a string, a number or an instance of View');
	}
	
	/**
	 * Get Rendered Value
	 * 
	 * Gets the rendered value
	 * for the cell
	 * 
	 * @access	public
	 * @return	string|View	Rendered value
	 */
	public function get_rendered_value()
	{
		// Lazy render the value
		if ( ! $this->_rendered_value)
		{
			$this->get_renderer()
				 ->render($this);
		}
		
		return $this->_rendered_value;
	}
	
	/**
	 * Build
	 * 
	 * Builds the cell and
	 * returns a string or
	 * View of it's contents
	 * 
	 * @access	public
	 * @return	string|View
	 */
	public function build()
	{
		return $this->get_rendered_value();
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
	 * Get Class
	 * 
	 * Gets the class of
	 * the cell
	 * 
	 * @access	public
	 * @return	string	Class
	 */
	public function get_class()
	{
		return $this->get_column()->get_class();
	}
	
	/**
	 * Get Style
	 * 
	 * Gets the style
	 * of the cell
	 * 
	 * @access	public
	 * @return	string	Style
	 */
	public function get_style()
	{
		return $this->get_column()->get_style();
	}
	
	/**
	 * Allows Action
	 * 
	 * Determines if the current
	 * cell is allowed to have
	 * actions or not
	 * 
	 * @access	public
	 * @return	bool	Allows action
	 */
	public function allows_action()
	{
		/**
		 * Todo: Some cells won't allow you to set a location
		 *       based on their type, checkboxes for example
		 */
		
		// If the user has provided an action
		// to the column which has then been applied
		// to the cell by the driver
		if ($this->get_data('action')) return true;
		
		// Otherwise, ask the renderer
		// as it knows whether or not
		// actions should be allowed on cells
		return $this->get_renderer()->allows_action();
	}
	
	/**
	 * Get Action
	 * 
	 * Gets the action
	 * of the cell
	 * 
	 * @access	public
	 * @return	string	Action
	 */
	public function get_action()
	{
		// If the cell isn't allowed to have an action
		if ( ! $this->allows_action()) return false;
		
		// Action fallback
		$action = false;
		
		// Get the cell action but default to the row action
		if ($this->get_data('action')) $action = $this->get_data('action');
		elseif ($this->get_row()->get_action()) $action = $this->get_row()->get_action();
		
		// If we had no action, return false
		if ( ! $action) return false;
		
		// Otherwise return the action
		return \Uri::create($action);
	}
	
	/**
	 * Get Onclick
	 * 
	 * Gets the onlickc of
	 * the cell based off
	 * the action
	 * 
	 * @access	public
	 * @return	string	Onclick
	 */
	public function get_onclick()
	{
		// If there is no action
		if ( ! $action = $this->get_action()) return false;
		
		return 'window.location.href=\'' . $action . '\'';
	}
}