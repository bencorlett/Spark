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

abstract class Grid_Column_Filter_Abstract extends \Object {
	
	/**
	 * The grid this column belongs to
	 * 
	 * @var	Spark\Grid_Column
	 */
	protected $_column;
	
	/**
	 * The view name this filter uses
	 * 
	 * @var	string
	 */
	protected $_view_name;
	
	/**
	 * The frontend value of the filter
	 * 
	 * @var	string
	 */
	protected $_frontend_values;
	
	/**
	 * The value of the filter
	 * 
	 * @var	string
	 */
	protected $_values;
	
	/**
	 * Set Column
	 * 
	 * Sets the column object
	 * property
	 * 
	 * @access	public
	 * @param	Spark\Grid_Column
	 * @return	Spark\Grid_Column_Filter_Abstract
	 */
	public function set_column(\Grid_Column $column)
	{
		$this->_column = $column;
		
		return $this;
	}
	
	/**
	 * Get Column
	 * 
	 * Gets the column object
	 * property
	 * 
	 * @access	public
	 * @return	\Grid_Column
	 */
	public function get_column()
	{
		return $this->_column;
	}
	
	/**
	 * Get Filter HTML
	 * 
	 * Gets the filter
	 * HTML by loading the view
	 * 
	 * @access	public
	 * @return	string	HTML
	 */
	public function get_filter_html()
	{
		$view = \View::factory($this->_view_name)
					 ->set('filter', $this);
		
		return $view;
	}
	
	/**
	 * Set Values
	 * 
	 * Sets the values of the filter
	 * 
	 * @access	public
	 * @param	array|Spark\Object	Values
	 * @return	Spark\Grid_Column_Filter_Abstract
	 */
	public function set_values($values)
	{
		if (is_array($values))
		{
			$values = \Object::factory($values);
		}
		
		if ( ! $values->get_value())
		{
			return $this;
		}
		
		// Set the frontend values
		$this->set_frontend_values(clone $values);
		
		// Set the values
		$this->_values = $values;
		
		return $this;
	}
	
	/**
	 * Get Values
	 * 
	 * Gets the values of the filter
	 * 
	 * @access	public
	 * @param	array|Spark\Object	Values
	 */
	public function get_values()
	{
		return $this->_values;
	}
	
	/**
	 * Set Frontend Values
	 * 
	 * Sets the frontend values of the
	 * filter
	 * 
	 * @access	public
	 * @param	array|Spark\Object	Values
	 * @return	Spark\Grid_Column_Filter_Abstract
	 */
	public function set_frontend_values($values = null)
	{
		if (is_array($values))
		{
			$values = \Object::factory($values);
		}
		
		$this->_frontend_values = $values;
	}
	
	/**
	 * Get Frontend Values
	 * 
	 * Gets the frontend values of the
	 * filter
	 * 
	 * @access	public
	 * @param	array|Spark\Object	Values
	 */
	public function get_frontend_values()
	{
		return $this->_frontend_values;
	}
}