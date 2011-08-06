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

class Str extends \Fuel\Core\Str {
	
	/**
	 * F
	 * 
	 * Format a string using
	 * sprintf()
	 * 
	 * @access	public
	 * @param	mixed
	 * @return	string
	 */
	public static function f()
	{
		return call_user_func_array('sprintf', func_get_args());
	}
}