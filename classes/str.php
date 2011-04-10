<?php
/**
 * Spark Fuel Package By Ben Corlett
 * 
 * Spark - Set your fuel on fire!
 * 
 * The Spark Fuel Package is an open-source
 * fuel package constisting of 'widgets'
 * engineered to make developing
 * administration systems easier and quicker.
 * 
 * @package    Fuel
 * @subpackage Spark
 * @author     Ben Corlett (http://www.bencorlett.com)
 * @license    MIT License
 * @copyright  (c) 2011 Ben Corlett
 * @link       http://www.github.com/bencorlett/bc
 */

namespace Spark;

class Str extends \Fuel\Core\Str {
	
	/**
	 * Alphanumeric
	 * 
	 * Converts the given string
	 * to alphanumeric characters
	 * only, removing all bad ones.
	 * 
	 * @access	public
	 * @param	string	Dirty string
	 * @param	string	Separator
	 * @return	string	Alphanumeric string
	 */
	public static function alphanumeric($string, $separator = null)
	{
		return preg_replace('#\W#', $separator, $string);
	}
}