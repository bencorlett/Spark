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

class Grid_Column extends \Grid_Component {
	
	/**
	 * The renderer for this column
	 * 
	 * @var	Spark\Grid_Column_Renderer_Abstract
	 */
	protected $_renderer;
	
	/**
	 * Construct
	 * 
	 * Called when the class is constructed
	 * 
	 * @access	public
	 * @param	mixed
	 * @return	Spark\Grid
	 */
	public function __construct($identifier = null, array $attributes = array())
	{
		// Validate the identifier
		if ( ! $identifier) throw new Exception(\Str::f('An identifier must be provided when initialising a grid column'));
		
		$this->set_identifier($identifier)
			 ->set_data($attributes);
	}
	
	/**
	 * Get Index
	 * 
	 * Gets the index of the column
	 * 
	 * @access	public
	 * @return	string	Index
	 */
	public function get_index()
	{
		// Lazy set the index
		if ( ! $this->has_data('index')) $this->set_data('index', \Str::alphanumeric($this->get_identifier(), '_'));
		
		return parent::get_index();
	}
	
	/**
	 * Get Header
	 * 
	 * Gets the header of the column
	 * 
	 * @access	public
	 * @return	string	Header
	 */
	public function get_header()
	{
		// Lazy set the header
		if ( ! $this->has_data('header'))
		{
			// Make the header
			$header = \Str::ucwords(str_replace('_', ' ', $this->get_identifier()));
			
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
		
		return parent::get_header();
	}
	
	/**
	 * Get Type
	 * 
	 * Gets the type of the column
	 * 
	 * @access	public
	 * @return	string	Type
	 */
	public function get_type()
	{
		// Lazy set the type
		if ( ! $this->has_data('type')) $this->set_data('type', 'text');
		
		return parent::get_type();
	}
	
	/**
	 * Get Renderer
	 * 
	 * Gets the renderer for the
	 * column
	 * 
	 * @access	public
	 * @return	Spark\Grid_Column_Renderer_Abstract
	 */
	public function get_renderer()
	{
		// Lazy load the renderer
		if ( ! $this->_renderer)
		{
			// If we've specified when setting up
			// the column
			if ( ! $this->has_data('renderer'))
			{
				switch ($this->get_type())
				{
					default:
						$this->set_data('renderer', 'Grid_Column_Renderer_Text');
						break;
				}
			}
			
			// Now we've got a renderer class name
			// initiate the renderer
			$renderer = $this->get_data('renderer');
			$this->_renderer = $renderer::factory();
		}
		
		return $this->_renderer;
	}
}