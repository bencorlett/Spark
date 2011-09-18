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
	 * The filter for this column
	 * 
	 * @var	Spark\Grid_Column_Filter_Abstract
	 */
	protected $_filter;
	
	/**
	 * The header for this column
	 * 
	 * @var	Spark\Grid_Column_Header
	 */
	protected $_header;
	
	/**
	 * The original header for this
	 * column
	 * 
	 * @var	string
	 */
	protected $_original_header;
	
	/**
	 * The rendered header for this
	 * column
	 * 
	 * @var	string
	 */
	protected $_rendered_header;

	/**
	 * Whether the current
	 * column is the active sort
	 * 
	 * @var bool
	 */
	protected $_active_sort = null;

	/**
	 * Active sort direction
	 * 
	 * @var string
	 */
	protected $_active_sort_direction;
	
	/**
	 * Construct
	 * 
	 * Called when the class is constructed
	 * 
	 * @access	public
	 * @param	mixed
	 * @return	Spark\Grid_Column
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
		
		return $this->get_data('index');
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
		// Lazy load the header
		if ( ! $this->_header)
		{
			$this->_header = \Grid_Column_Header::factory()
												->set_grid($this->get_grid())
												->set_column($this)
												->set_data('header', $this->get_data('header'));
		}
		
		return $this->_header;
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
		
		return $this->get_data('type');
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
			// If we've just set a type for the
			// grid, and not explicitly set the
			// renderer
			if ( ! $this->has_data('renderer'))
			{
				switch ($this->get_type())
				{
					case 'action':
						$this->set_data('renderer', 'Grid_Column_Renderer_Action');
						break;
					
					case 'checkbox':
						$this->set_data('renderer', 'Grid_Column_Renderer_Checkbox');
						break;
					
					case 'massaction':
						$this->set_data('renderer', 'Grid_Column_Renderer_Massaction');
						break;
					
					case 'number':
						$this->set_data('renderer', 'Grid_Column_Renderer_Number');
						break;
					
					case 'options':
						$this->set_data('renderer', 'Grid_Column_Renderer_Options');
						break;
					
					default:
						$this->set_data('renderer', 'Grid_Column_Renderer_Text');
						break;
				}
			}
			
			// Now we've got a renderer class name
			// initiate the renderer
			$renderer = $this->get_data('renderer');
			$this->_renderer = $renderer::factory();
			
			// Make sure the renderer is valid
			if ( ! $this->_renderer instanceof \Grid_Column_Renderer_Abstract or ! $this->_renderer instanceof \Grid_Column_Renderer_Interface)
			{
				throw new Exception('Grid renderers must extend Spark\\Grid_Column_Renderer_Abstract and inherit from Spark\\Grid_Column_Renderer_Interface');
			}
		}
		
		return $this->_renderer;
	}
	
	/**
	 * Get Filter
	 * 
	 * Gets the filter for the grid
	 * 
	 * @access	public
	 * @return	Spark\Grid_Column_Filter_Abstract
	 */
	public function get_filter()
	{
		// Lazy load the filter
		if ( ! $this->_filter)
		{
			// The filter is it's own object, that way it can store
			// the original values and rendered values etc all in
			// it's own little space
			$this->_filter = \Grid_Column_Filter::factory()
												->set_grid($this->get_grid())
												->set_column($this)
												->set_data('type', $this->get_data('type'))
												->set_data('filter', $this->get_data('filter'));
		}
		
		return $this->_filter;
	}
	
	/**
	 * Get Class
	 * 
	 * Gets the column's class
	 * built on various
	 * parameters
	 * 
	 * @access	public
	 * @return	string	Class
	 */
	public function get_class()
	{
		// If we don't have a class
		if ( ! $this->has_data('class'))
		{
			// Class fallback
			$class = '';
			
			// If we're the first column
			if ($this->get_first()) $class = 'first';
			
			// Add the column type
			if ($this->get_type()) $class .= ' type-' . $this->get_type();
			
			// If we're the last column
			if ($this->get_last()) $class .= ' last';

			// Sort class
			if ($sort_class = $this->get_sort_class()) $class .= ' ' . $sort_class;
			
			// Set the data
			$this->set_data('class', $class);
		}
		
		return $this->get_data('class');
	}

	/**
	 * Get Sort Class
	 * 
	 * Gets the sort class
	 * of the column
	 * 
	 * @access  public
	 * @return  string  Sort class
	 */
	public function get_sort_class()
	{
		$class = '';

		if (($direction = $this->is_active_sort()) !== false)
		{
			$class .= 'active ' . $direction;
		}

		return $class ? $class : false;
	}
	
	/**
	 * Get Style
	 * 
	 * Gets the column's style
	 * built on various parameters
	 * 
	 * @access	public
	 * @return	string	Style
	 */
	public function get_style()
	{
		// Process the width first
		if ($this->has_data('width'))
		{
			$width = $this->get_data('width');
			
			// If we've been given a width
			// that's a number, we need to
			// add "px" on the end
			if (is_numeric($width)) $width .= 'px';
			
			$this->set_data('width', $width);
			
			// If the user hasn't overwritten the width
			// in the style, add it to the style
			if (strpos($this->get_data('style'), 'width') === false)
			{
				$this->set_data('style', $this->get_data('style') . ' width: ' . $this->get_data('width') . ';');
			}
		}
		
		// Next process the align
		if ($this->has_data('align'))
		{
			// If the user hasn't overwritten the
			// text-align property in the style
			if (strpos($this->get_data('style'), 'text-align') === false)
			{
				$this->set_data('style', $this->get_data('style') . ' text-align: ' . $this->get_data('align') . ';');
			}
		}
		
		// Trim what we've created
		$this->set_data('style', trim($this->get_data('style')));
		
		return $this->get_data('style');
	}

	/**
	 * Is Active Sort
	 * 
	 * Determines if the
	 * column is the active
	 * sort or not
	 * 
	 * @access  public
	 * @return  string|false  Sort direction if active
	 *                        false if not active
	 */
	public function is_active_sort()
	{
		if ($this->_active_sort === null)
		{
			$params = $this->get_grid()->get_params();

			$this->_active_sort = ($this->get_identifier() == $params->{'get_'.$this->get_grid()->get_var_name_sort()}()) ? true : false;

			if ($this->_active_sort === true)
			{
				$this->_active_sort_direction = $params->{'get_'.$this->get_grid()->get_var_name_direction()}();
			}
		}

		if ($this->_active_sort === true)
		{
			return $this->_active_sort_direction;
		}

		return false;
	}
}