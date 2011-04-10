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

class Grid_Column extends \Object {
	
	/**
	 * The default renderer class name
	 * 
	 * @var	string
	 */
	protected $_default_renderer_class = '\\Grid_Column_Renderer_Text';
	
	/**
	 * The default filter class name
	 * 
	 * @var	string
	 */
	protected $_default_filter_class = '\\Grid_Column_Filter_Text';
	
	/**
	 * The grid this column belongs to
	 * 
	 * @var	Ignite\Grid
	 */
	protected $_grid;
	
	/**
	 * The renderer object
	 * 
	 * @var	Ignite\Grid_Column_Renderer_Abstract
	 */
	protected $_renderer;
	
	/**
	 * The filter object
	 * 
	 * @var	Ignite\Grid_Column_Filter_Abstract
	 */
	protected $_filter;
	
	/**
	 * The column type
	 * 
	 * @var	string
	 */
	protected $_type;
	
	/**
	 * Construct
	 * 
	 * Internal constructor
	 * not dependent on parameters
	 * 
	 * @access	protected
	 */
	protected function _construct()
	{
		// If the renderer doesn't exist create it
		if ( ! $this->has_data('renderer'))
		{
			$this->set_data('renderer', $this->_default_renderer_class);
		}
		
		// If it does exist but the class doesn't, revert to default
		else if ($this->has_data('renderer') and ! class_exists($this->get_data('renderer')))
		{
			$this->set_renderer($this->_default_renderer);
		}
		
		// Set the renderer
		$renderer = $this->get_data('renderer');
		$this->set_renderer($renderer::factory())
			 ->unset_data('renderer');
		
		// Set the filter
		$filter = $this->_get_filter_class();
		$this->set_filter($filter::factory()
								 ->set_column($this));
	}
	
	/**
	 * Set Grid
	 * 
	 * Sets the grid object
	 * property
	 * 
	 * @access	public
	 * @param	Ignite\Grid
	 * @return	Ignite\Grid_Column
	 */
	public function set_grid(\Grid $grid)
	{
		$this->_grid = $grid;
		
		return $this;
	}
	
	/**
	 * Get Grid
	 * 
	 * Gets the grid object
	 * property
	 * 
	 * @access	public
	 * @return	Ignite\Grid
	 */
	public function get_grid()
	{
		return $this->_grid;
	}
	
	/**
	 * Set Renderer
	 * 
	 * Sets the renderer object
	 * property
	 * 
	 * @access	public
	 * @param	Ignite\Grid_Column_Renderer_Abstract
	 * @return	Ignite\Grid_Column
	 */
	public function set_renderer(\Grid_Column_Renderer_Abstract $renderer)
	{
		$this->_renderer = $renderer;
		
		return $this;
	}
	
	/**
	 * Get Renderer
	 * 
	 * Gets the renderer object
	 * property
	 * 
	 * @access	public
	 * @return	\Grid_Column_Renderer_Abstract
	 */
	public function get_renderer()
	{
		return $this->_renderer;
	}
	
	/**
	 * Get Filter Class
	 * 
	 * Gets the filter class
	 * based on the renderer
	 * 
	 * @access	public
	 * @return	string	Filter class
	 */
	protected function _get_filter_class()
	{
		$class = $this->_default_filter_class;
		
		switch (get_class($this->get_renderer()))
		{
			case 'Ignite\\Grid_Column_Renderer_Text':
				$class = '\\Grid_Column_Filter_Text';
				break;
		}
		
		return $class;
	}
	
	/**
	 * Set Filter
	 * 
	 * Sets the filter object
	 * property
	 * 
	 * @access	public
	 * @param	Ignite\Grid_Column_Filter_Abstract
	 * @return	Ignite\Grid_Column
	 */
	public function set_filter(\Grid_Column_Filter_Abstract $filter)
	{
		$this->_filter = $filter;
		
		return $this;
	}
	
	/**
	 * Get Filter
	 * 
	 * Gets the filter object
	 * property
	 * 
	 * @access	public
	 * @return	\Grid_Column_Filter_Abstract
	 */
	public function get_filter()
	{
		return $this->_filter;
	}
	
	/**
	 * Get Cell For Row
	 * 
	 * Gets the cell for the
	 * row
	 * 
	 * @access	public
	 * @param	mixed	Row
	 * @return	string	Cell Html
	 */
	public function get_cell_for_row($row)
	{
		return $this->get_renderer()->render($this->get_grid()->get_driver()->get_cell_for_row_and_column($row, $this));
	}
}