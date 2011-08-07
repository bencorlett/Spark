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

abstract class Grid_Component extends \Object {
	
	/**
	 * Reference to the grid this driver
	 * is attached to
	 * 
	 * @var	Spark\Grid
	 */
	protected $_grid;
	
	/**
	 * Set Grid
	 * 
	 * Sets the grid this component
	 * is part of
	 * 
	 * @access	public
	 * @param	Spark\Grid	Grid
	 * @return	Spark\Grid_Component
	 */
	final public function set_grid(\Grid $grid)
	{
		$this->_grid = $grid;
		return $this;
	}
	
	/**
	 * Get Grid
	 * 
	 * Gets the grid this component
	 * is part of
	 * 
	 * @access	public
	 * @return	Spark\Grid
	 */
	final public function get_grid()
	{
		// Make sure the grid has been set for this component
		if ( ! $this->_grid instanceof \Grid) throw new \Exception(\Str::f('Cannot retrieve grid instance for %s', get_class($this)));
		
		return $this->_grid;
	}
}