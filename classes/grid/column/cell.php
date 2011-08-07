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

class Grid_Column_Cell extends \Grid_Component {
	
	/**
	 * Reference to the column
	 * this cell is attached to
	 * 
	 * @var	Spark\Grid_Column
	 */
	protected $_column;
	
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
	 * Get Renderer
	 * 
	 * Gets the renderer
	 * based off the cell type
	 * 
	 * @access	public
	 */
	
	/**
	 * Build
	 * 
	 * Builds the cell and
	 * returns a View of it's
	 * contents
	 * 
	 * @access	public
	 * @return	View
	 */
	public function build()
	{
		echo $this->get_column()->get_renderer();
	}
}