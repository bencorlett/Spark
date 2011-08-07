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

class Grid_Column_Filter_Options extends \Grid_Column_Filter_Abstract {
	
	/**
	 * Render
	 * 
	 * Renders a filter for
	 * a column
	 * 
	 * @access	public
	 * @param	Spark\Grid_Column_Filter	Filter
	 * @return	Spark\Grid_Column_Filter_Interface
	 */
	public function render(\Grid_Column_Filter $filter)
	{
		// Get options
		$options = $filter->get_column()->get_options();
		
		// Html fallback
		$html = '';
		
		// Make sure we have an array of options
		if ($options->get_data())
		{
			$html = \Form::select($filter->get_column()->get_identifier(),
								  null,
								  array(null => null) + $options->get_data(),
								  array(
								  	'class'		=> 'filter',
								  )
			);
		}
		
		$filter->set_html($html);
		
		return $this;
	}
}