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