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

class Grid_driver_Orm extends \Grid_Driver_Abstract {
	
	/**
	 * Get Rows
	 * 
	 * Gets the rows based
	 * off the model
	 * 
	 * @access	public
	 * @return	Spark\Object
	 */
	public function build_rows()
	{
		$this->get_rows()->set_identifier('rofl');
		$this->get_rows()->set_data('0', 'rofl');
	}
}