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
	 * The buttons for the grid
	 * 
	 * @var	Spark\Object
	 */
	protected $_buttons;
	
	/**
	 * The massactions view
	 * 
	 * @var	View
	 */
	protected $_massactions_view;
	
	/**
	 * Add Button
	 * 
	 * Adds a button
	 * 
	 * @access	public
	 * @param	array	Attributes
	 * @return	Spark\Grid_Container
	 */
	public function add_button($identifier, array $attributes = array())
	{
		// Only add the button if it doesn't
		// exist
		if ( ! $this->get_buttons()->has_data($identifier))
		{
			// Add a button to the buttons collection
			$this->get_buttons()->set_data($identifier, \Grid_Button::factory($attributes)
																	->make_recursive());
		}
		
		return $this;
	}
	
	/**
	 * Get Add Button
	 * 
	 * Gets the add button for the container
	 * 
	 * @access	public
	 * @return	Spark\Grid_button
	 */
	public function get_buttons()
	{
		// Lazy load the buttons
		if ( ! $this->_buttons)
		{
			$this->_buttons = \Object::factory();
		}
		
		return $this->_buttons;
	}
	
	/**
	 * Get Massactions View
	 * 
	 * Gets the massactions view.
	 * Returns false if there are
	 * no massactions to generate the
	 * view for
	 * 
	 * @access	public
	 * @return	View	Massactions view
	 */
	public function get_massactions_view()
	{
		if ( ! $this->get_grid()->get_massactions()->count()) return false;
		
		if ( ! $this->_massactions_view)
		{
			$this->_massactions_view = \View::factory(\Config::get('grid.view.massactions', 'grid/massactions'))
											->set_grid($this->get_grid(), false)
											->set_massactions($this->get_grid()->get_massactions(), false);
		}
		
		return $this->_massactions_view;
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
		return \View::factory(\Config::get('grid.view.container', 'grid/container'))
					->set_container($this, false);
	}
}