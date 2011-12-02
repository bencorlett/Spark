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

class Grid_Column_Header extends \Grid_Component
{
	
	/**
	 * Reference to the column
	 * this cell is attached to
	 * 
	 * @var	Spark\Grid_Column
	 */
	protected $_column;
	
	/**
	 * The original value of the
	 * header
	 * 
	 * @var	string
	 */
	protected $_original_value;
	
	/**
	 * The rendered value
	 * of the header
	 * 
	 * @var	string|view
	 */
	protected $_rendered_value;
	
	/**
	 * Get Renderer
	 * 
	 * Gets the renderer
	 * for the header
	 * 
	 * @access	public
	 * @return	Spark\Grid_Column_Renderer_Abstract	Renderer
	 */
	public function get_renderer()
	{
		return $this->get_column()->get_renderer();
	}
	
	/**
	 * Get Original Value
	 * 
	 * Gets the original value of
	 * the header, as specified by
	 * the user
	 * 
	 * @access	public
	 * @return	string	Original value
	 */
	public function get_original_value()
	{
		// Lazy load the original value
		if ( ! $this->_original_value)
		{
			// Lazy set the header
			if ( ! $this->has_data('header'))
			{
				// Make the header
				$header = \Str::ucwords(str_replace('_', ' ', $this->get_column()->get_identifier()));
				
				// Change fields just to
				// look more English proper
				switch ($header)
				{
					case 'Id':
						$header = 'ID';
						break;
				}
				
				$this->set_data('header', $header);
			}
			
			// Set the modified header metadata to the orginal
			// value property
			$this->_original_value = $this->get_data('header');
		}
		
		return $this->_original_value;
	}
	
	/**
	 * Set Rendered Value
	 * 
	 * Sets the rendered value
	 * of the header
	 * 
	 * @access	public
	 * @param	string|View
	 * @return	Spark\Grid_Column_Header
	 */
	public function set_rendered_value($value)
	{
		// We can either accept a string, value, or an instance of View
		if (is_string($value) or is_numeric($value) or $value instanceof \View)
		{
			$this->_rendered_value = $value;
			return $this;
		}
		
		// Let the person know
		throw new Grid_InvalidFormatException('The rendered value for a grid column header must be either a string, a number or an instance of View');
	}
	
	/**
	 * Get Rendered Value
	 * 
	 * Gets the rendered value
	 * for the header
	 * 
	 * @access	public
	 * @return	string|View	Value
	 */
	public function get_rendered_value()
	{
		// Lazy load the rendered value
		if ( ! $this->_rendered_value)
		{
			$this->get_renderer()
				 ->render_header($this);
		}
		
		return $this->_rendered_value;
	}
	
	/**
	 * Build
	 * 
	 * Builds the header object
	 * and returns it as a string
	 * 
	 * @access	public
	 * @return	string	Html
	 */
	public function build()
	{
		return $this->get_rendered_value();
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