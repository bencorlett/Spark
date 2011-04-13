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

class Exception extends \Fuel\Core\Fuel_Exception {
	
	/**
	 * Construct
	 * 
	 * Called when the class is constructed
	 * 
	 * @access	public
	 * @param	mixed
	 * @return	Spark\Exception
	 */
	public function __construct()
	{
		// Call sprintf() on the arguments
		parent::__construct(call_user_func_array('sprintf', func_get_args()));
	}
}