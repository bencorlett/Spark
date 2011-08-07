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

class Grid_Container extends \Grid_Component {
	
	/**
	 * The view name used for the container
	 * 
	 * @var	string
	 */
	protected $_view_name = 'grid/container';
	
	/**
	 * The add button for teh grid
	 * 
	 * @var	string
	 */
	protected $_add_button;
	
	/**
	 * Set Add Button
	 * 
	 * Sets the add button for the container
	 * 
	 * @access	public
	 * @param	array	Attributes
	 * @return	Spark\Grid_Container
	 */
	public function set_add_button(array $attributes = array())
	{
		$this->_add_button = \Grid_Button::factory($attributes);
		return $this;
	}
	
	/**
	 * Build
	 * 
	 * Builds the grid container and returns the
	 * view
	 * 
	 * @access	public
	 * @return	View
	 */
	public function build()
	{
		return \View::factory($this->_view_name);
	}
}