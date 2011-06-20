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

class View extends \Fuel\Core\View {
	
	/**
	 * Call
	 * 
	 * Magic method used as a
	 * getter / setter wrapper
	 * 
	 * @param	string	Method
	 * @param	array	Arguments
	 * @return	mixed
	 */
	public function __call($method, $arguments)
	{
		// Get the key
		$key = substr($method, 4);
		
		// Check different setters / getters
		// and return results if applicable
		switch (substr($method, 0, 3))
		{
			case 'set':
				return $this->set($key, isset($arguments[0]) ? $arguments[0] : null);
		}
		
		throw new Exception('Call to undefined method %s::%s()', get_called_class(), $method);
	}
}

/* End of file classes/view.php */