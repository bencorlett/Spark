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

abstract class Grid_Column_Renderer_Abstract extends \Object {
	
	/**
	 * Render
	 * 
	 * Renders the value
	 * 
	 * @access	public
	 * @param	string	Value
	 * @return	string	Rendered value
	 */
	public function render($value = null)
	{
		return $value;
	}
}