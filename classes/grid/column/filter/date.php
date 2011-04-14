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

class Grid_Column_Filter_Date extends \Grid_Column_Filter_Abstract {
	
	/**
	 * The view name this filter uses
	 * 
	 * @var	string
	 */
	protected $_view_name = 'grid/column/filter/date';
	
	/**
	 * Set Value
	 * 
	 * Sets the value of the filter
	 * 
	 * @access	public
	 * @param	string|array	Value
	 * @return	Spark\Grid_Column_Filter_Abstract
	 */
	public function set_value($value)
	{
		// Set the user value
		$this->set_user_from($value['from'])
			 ->set_user_to($value['to']);
		
		// Set the actual value
		if (isset($value['from']) and $value['from'])
		{
			$this->_value['from']	= \Date::create_from_string($value['from'], 'eu')->format('mysql');
		}
		if (isset($value['to']) and $value['to'])
		{
			$this->_value['to']		= \Date::create_from_string($value['to'], 'eu')->format('mysql');
		}
		
		return $this;
	}
}