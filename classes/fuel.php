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

class Fuel extends \Fuel\Core\Fuel {
	
	/**
	 * Items in the registry
	 * 
	 * @var	array
	 */
	protected static $_registry = array();
	
	/**
	 * Register
	 * 
	 * Register something in the registry.
	 * 
	 * If the first parameter is an
	 * associative array all data in the
	 * array will be added to the registry
	 * 
	 * If the data is a string, the value for
	 * the second parameter will be set as the
	 * value for that data
	 * 
	 * @access	public
	 * @param	string	Key
	 * @param	mixed	Value
	 */
	public static function register($key, $value = null)
	{
		if (is_array($key))
		{
			foreach ($key as $key => $value)
			{
				self::$_registry[$key] = $value;
			}
			
			return;
		}
		
		self::$_registry[$key] = $value;
	}
	
	/**
	 * Registry
	 * 
	 * Get something out of the registry
	 * 
	 * If the first parameter is empty, the
	 * entire registry will be returned
	 * 
	 * If the first parameter is provided
	 * and the item doesnt' exist, whatever
	 * is provided as the default in the second
	 * parameter will be returned
	 * 
	 * @access	public
	 * @param	string	Key
	 * @param	mixed	Default
	 */
	public static function registry($key = null, $default = null)
	{
		if (is_null($key))
		{
			return self::$_registry;
		}
		
		if ( ! isset(self::$_registry[$key]))
		{
			return $default;
		}
		
		return self::$_registry[$key];
	}
}