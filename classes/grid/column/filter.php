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

class Grid_Column_Filter extends \Grid_Component {
	
	/**
	 * Reference to the column
	 * this cell is attached to
	 * 
	 * @var	Spark\Grid_Column
	 */
	protected $_column;
	
	/**
	 * The driver based filter object
	 * for this filter
	 * 
	 * @var	Spark\Grid_Column_Filter_Abstract
	 */
	protected $_filter;
	
	/**
	 * The html value of the filter
	 * 
	 * @var	string|View
	 */
	protected $_html;
	
	/**
	 * Set Column
	 * 
	 * Sets the column
	 * property that this
	 * cell belongs to
	 * 
	 * @access	public
	 * @param	Spark\Grid_Column	Column
	 * @return	Spark\Grid_Column_Filter
	 */
	public function set_column(\Grid_Column $column)
	{
		$this->_column = $column;
		return $this;
	}
	
	/**
	 * Get Column
	 * 
	 * Gets the column
	 * property that this
	 * cell belongs to
	 * 
	 * @access	public
	 * @return	Spark\Grid_Column_Filter
	 */
	public function get_column()
	{
		if ( ! $this->_column instanceof \Grid_Column) throw new \Exception(\Str::f('Cannot retrieve grid column instance for %s', get_class($this)));
		
		return $this->_column;
	}
	
	/**
	 * Set Html
	 * 
	 * Sets the html for the
	 * filter
	 * 
	 * @access	public
	 * @param	string|View	Html
	 * @return	Spark\Grid_Column_Filter
	 */
	public function set_html($html)
	{
		// We can either accept a string, html, or an instance of View
		if (is_string($html) or is_numeric($html) or $html instanceof \View)
		{
			$this->_html = $html;
			return $this;
		}

		// Let the person know
		throw new Exception('The rendered html for a grid filter must be either a string, a number or an instance of View');
	}
	
	/**
	 * Get Html
	 * 
	 * Get the html for the
	 * filter
	 * 
	 * @access	public
	 * @return	string|View
	 */
	public function get_html()
	{
		// Lazy render the html
		if ( ! $this->_html)
		{
			$this->get_filter()
				 ->render($this);
		}
		
		return $this->_html;
	}
	
	/**
	 * Get Filter
	 * 
	 * Gets the driver based filter
	 * for this filter object
	 * 
	 * @access	public
	 * @return	Spark\Grid_Column_Filter_Abstract
	 */
	public function get_filter()
	{
		// Lazy load the filter
		if ( ! $this->_filter)
		{
			// If we've just set a type for the
			// grid, and not explicitly set the
			// filter
			if ( ! $this->has_data('filter'))
			{
				switch ($this->get_type())
				{
					// case 'options':
					// 	$this->set_data('filter', 'Grid_Column_Filter_Options');
					// 	break;
					// 
					default:
						$this->set_data('filter', 'Grid_Column_Filter_Text');
						break;
				}
			}

			// Now we've got a filter class name
			// initiate the filter
			$filter = $this->get_data('filter');
			$this->_filter = $filter::factory();

			// Make sure the filter is valid
			if ( ! $this->_filter instanceof \Grid_Column_Filter_Abstract or ! $this->_filter instanceof \Grid_Column_Filter_Interface)
			{
				throw new Exception('Grid filters must extend Spark\\Grid_Column_Filter_Abstract and inherit from Spark\\Grid_Column_Filte_Interface');
			}
		}

		return $this->_filter;
	}
	
	/**
	 * Build
	 * 
	 * Builds the cell and
	 * returns a string or
	 * View of it's contents
	 * 
	 * @access	public
	 * @return	string|View
	 */
	public function build()
	{
		return $this->get_html();
	}
	
	/**
	 * To String
	 * 
	 * Represents the object
	 * as a string
	 * 
	 * @access	public
	 * @return	string
	 */
	public function __toString()
	{
		try
		{
			return (string) $this->build();
		}
		catch (\Exception $e)
		{
			\Error::show_php_error($e);
		}
	}
}