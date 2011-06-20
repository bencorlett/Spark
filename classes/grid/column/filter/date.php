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
	public function set_values($values)
	{
		if (is_array($values))
		{
			$values = \Object::factory($values);
		}
		
		// Set the frontend values
		$this->set_frontend_values(clone $values);
		
		if ($values->get_from() and $values->get_to())
		{
			$values->set_from(\Date::create_from_string($values->get_from(), 'eu')->format('mysql'))
				   ->set_to(\Date::create_from_string($values->get_to(), 'eu')->format('mysql'));
			
			$this->_values = $values;
		}
		
		return $this;
	}
}

/* End of file classes/grid/column/filter/date.php */