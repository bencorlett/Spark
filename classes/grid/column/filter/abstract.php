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

abstract class Grid_Column_Filter_Abstract extends \Object {
	
	/**
	 * The grid this column belongs to
	 * 
	 * @var	Ignite\Grid_Column
	 */
	protected $_column;
	
	/**
	 * The view name this filter uses
	 * 
	 * @var	string
	 */
	protected $_view_name;
	
	/**
	 * The user value of the filter
	 * 
	 * @var	string
	 */
	protected $_user_value;
	
	/**
	 * The value of the filter
	 * 
	 * @var	string
	 */
	protected $_value;
	
	/**
	 * Set Column
	 * 
	 * Sets the column object
	 * property
	 * 
	 * @access	public
	 * @param	Ignite\Grid_Column
	 * @return	Ignite\Grid_Column_Filter_Abstract
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
	 * Set Value
	 * 
	 * Sets the value of the filter
	 * 
	 * @access	public
	 * @param	string|array	Value
	 * @return	Ignite\Grid_Column_Filter_Abstract
	 */
	public function set_value($value)
	{
		if (isset($value['value']))
		{
			$this->_value = $value['value'];
		}
		
		return $this;
	}
	
	/**
	 * Get Value
	 * 
	 * Gets the value of the filter
	 * 
	 * @access	public
	 * @return	string	Value
	 */
	public function get_value()
	{
		return $this->_value;
	}
	
	/**
	 * Set User Value
	 * 
	 * Sets the user value of the
	 * filter
	 * 
	 * @access	public
	 * @return	Ignite\Grid_Column_Filter_Abstract
	 */
	public function set_user_value($value = null)
	{
		$this->_user_value = $value;
	}
	
	/**
	 * Get User Value
	 * 
	 * Gets the user value of the
	 * filter
	 * 
	 * @access	public
	 * @return	string	User value
	 */
	public function get_user_value()
	{
		return $this->_user_value;
	}
}