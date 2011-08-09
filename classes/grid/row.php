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

class Grid_Row extends \Grid_Component {
	
	/**
	 * An object containing
	 * cells for this row
	 * 
	 * @var	Spark\Object
	 */
	protected $_cells;
	
	/**
	 * Add Cell
	 * 
	 * Adds a cell to the row
	 * 
	 * @access	public
	 * @param	string					Column identifier
	 * @param	Spark\Grid_Column_Cell	Cell
	 * @return	Spark\Grid_Row
	 */
	public function add_cell($identifier, \Grid_Column_Cell $cell)
	{
		// Lazy load cells
		if ( ! $this->_cells)
		{
			$this->_cells = \Object::factory();
		}
		
		// Add the data
		$this->_cells->add_data(array($identifier => $cell));
		return $this;
	}
	
	/**
	 * Get Cells
	 * 
	 * Gets the cells from the
	 * row
	 * 
	 * @access	public
	 * @return	Spark\Object	Cells
	 */
	public function get_cells()
	{
		// Lazy load cells
		if ( ! $this->_cells)
		{
			$this->_cells = \Object::factory();
		}
		
		// Return the cells
		return $this->_cells;
	}
}