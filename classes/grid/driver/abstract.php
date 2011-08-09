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

abstract class Grid_Driver_Abstract extends \Grid_Component
implements \Grid_Driver_Interface {
	
	/**
	 * Get Model
	 * 
	 * Gets the model
	 * from the grid
	 * 
	 * @access	public
	 * @return	mixed	Model
	 */
	public function get_model()
	{
		return $this->get_grid()->get_model();
	}
	
	/**
	 * Get Rows
	 * 
	 * Gets the rows
	 * from the grid
	 * 
	 * @access	public
	 * @return	Spark\Object
	 */
	public function get_rows()
	{
		return $this->get_grid()->get_rows(false);
	}
	
	/**
	 * Get Columns
	 * 
	 * Gets the columns
	 * from the grid
	 * 
	 * @access	public
	 * @return	Spark\Object
	 */
	public function get_columns()
	{
		return $this->get_grid()->get_columns();
	}
	
	/**
	 * Get Params
	 * 
	 * Gets the params
	 * from the grid
	 * 
	 * @access	public
	 * @return	Spark\Object	Params
	 */
	public function get_params()
	{
		return $this->get_grid()->get_params();
	}
}