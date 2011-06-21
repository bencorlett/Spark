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

class Grid_Column extends \Object {
	
	/**
	 * The default renderer class name
	 * 
	 * @var	string
	 */
	protected $_default_renderer_class = '\\Grid_Column_Renderer_Text';
	
	/**
	 * The default filter class name
	 * 
	 * @var	string
	 */
	protected $_default_filter_class = '\\Grid_Column_Filter_Text';
	
	/**
	 * The grid this column belongs to
	 * 
	 * @var	Spark\Grid
	 */
	protected $_grid;
	
	/**
	 * The renderer object
	 * 
	 * @var	Spark\Grid_Column_Renderer_Abstract
	 */
	protected $_renderer;
	
	/**
	 * The filter object
	 * 
	 * @var	Spark\Grid_Column_Filter_Abstract
	 */
	protected $_filter;
	
	/**
	 * The column type
	 * 
	 * @var	string
	 */
	protected $_type;
	
	/**
	 * Bunch of classes that are in fuel that will
	 * be picked up by other classes (such as date)
	 * 
	 * @var	array
	 */
	protected $_ignored_classes = array(
		'date',
	);
	
	/**
	 * Whether this column is sortable or not
	 * 
	 * @var	bool
	 */
	protected $_sortable = true;
	
	/**
	 * Construct
	 * 
	 * Internal constructor
	 * not dependent on parameters
	 * 
	 * @access	protected
	 */
	protected function _construct()
	{
		// Determine if the user wants
		// this column to be sortable or not
		if ($this->has_data('sortable')) $this->_sortable = (bool) $this->get_data('sortable');
		
		// If the renderer doesn't exist create it
		if ( ! $this->has_data('renderer'))
		{
			$this->set_data('renderer', $this->_default_renderer_class);
		}
		
		// If it does exist but the class doesn't, try find a possible renderer then revert to default
		else if ($this->has_data('renderer') and ! class_exists($this->get_data('renderer')))
		{
			// Guess a possible renderer if the person was lazy
			$possible_renderer = sprintf('\\Grid_Column_Renderer_%s', ucwords($this->get_data('renderer')));
			
			// If the possible renderer exists
			if (class_exists($possible_renderer))
			{
				$this->set_data('renderer', $possible_renderer);
			}
			else
			{
				$this->set_data('renderer', $this->_default_renderer_class);
			}
		}
		
		// If the class exists but it's an ignored class
		else if ($this->has_data('renderer') and class_exists($this->get_data('renderer')) and in_array($this->get_data('renderer'), $this->_ignored_classes))
		{
			// Guess a possible renderer if the person was lazy
			$possible_renderer = sprintf('\\Grid_Column_Renderer_%s', ucwords($this->get_data('renderer')));
			
			// If the possible renderer exists
			if (class_exists($possible_renderer))
			{
				$this->set_data('renderer', $possible_renderer);
			}
			else
			{
				$this->set_data('renderer', $this->_default_renderer_class);
			}
		}
		
		// Get the renderer we've found
		$renderer = $this->get_data('renderer');
		
		// Set the renderer
		$this->set_renderer($renderer::factory())
			 ->unset_data('renderer');
		
		// Set the filter
		$filter = $this->_get_filter_class();
		$this->set_filter($filter::factory()
								 ->set_column($this));
	}
	
	/**
	 * Set Identifier
	 * 
	 * Sets the identifier of the class
	 * 
	 * @access	public
	 * @param	string		Identifier
	 * @return	Spark\Grid_Column
	 */
	public function set_identifier($identifier)
	{
		// Call to parent
		parent::set_identifier($identifier);
		
		// Let the person lazily set
		// the index and label
		if ( ! $this->has_data('index')) $this->set_data('index', \Str::alphanumeric($identifier, '_'));
		if ( ! $this->has_data('label')) $this->set_data('label', \Str::ucwords(str_replace('_', ' ', $identifier)));
		
		return $this;
	}
	
	/**
	 * Set Grid
	 * 
	 * Sets the grid object
	 * property
	 * 
	 * @access	public
	 * @param	Spark\Grid
	 * @return	Spark\Grid_Column
	 */
	public function set_grid(\Grid $grid)
	{
		$this->_grid = $grid;
		
		return $this;
	}
	
	/**
	 * Get Grid
	 * 
	 * Gets the grid object
	 * property
	 * 
	 * @access	public
	 * @return	Spark\Grid
	 */
	public function get_grid()
	{
		return $this->_grid;
	}
	
	/**
	 * Set Renderer
	 * 
	 * Sets the renderer object
	 * property
	 * 
	 * @access	public
	 * @param	Spark\Grid_Column_Renderer_Abstract
	 * @return	Spark\Grid_Column
	 */
	public function set_renderer(\Grid_Column_Renderer_Abstract $renderer)
	{
		$this->_renderer = $renderer;
		
		return $this;
	}
	
	/**
	 * Get Renderer
	 * 
	 * Gets the renderer object
	 * property
	 * 
	 * @access	public
	 * @return	\Grid_Column_Renderer_Abstract
	 */
	public function get_renderer()
	{
		return $this->_renderer;
	}
	
	/**
	 * Get Filter Class
	 * 
	 * Gets the filter class
	 * based on the renderer
	 * 
	 * @access	public
	 * @return	string	Filter class
	 */
	protected function _get_filter_class()
	{
		// Filter class will start with default
		$class = $this->_default_filter_class;
		
		// Possible filter class based on renderer
		$possible_filter_class = str_replace('Renderer', 'Filter', get_class($this->get_renderer()));
		
		if (class_exists($possible_filter_class))
		{
			return $possible_filter_class;
		}
		
		return $class;
	}
	
	/**
	 * Set Filter
	 * 
	 * Sets the filter object
	 * property
	 * 
	 * @access	public
	 * @param	Spark\Grid_Column_Filter_Abstract
	 * @return	Spark\Grid_Column
	 */
	public function set_filter(\Grid_Column_Filter_Abstract $filter)
	{
		$this->_filter = $filter;
		
		return $this;
	}
	
	/**
	 * Get Filter
	 * 
	 * Gets the filter object
	 * property
	 * 
	 * @access	public
	 * @return	\Grid_Column_Filter_Abstract
	 */
	public function get_filter()
	{
		return $this->_filter;
	}
	
	/**
	 * Get Cell For Row
	 * 
	 * Gets the cell for the
	 * row
	 * 
	 * @access	public
	 * @param	mixed	Row
	 * @return	string	Cell Html
	 */
	public function get_cell_for_row($row)
	{
		return $this->get_renderer()->render_cell_for_row_and_column($this->get_grid()->get_driver()->get_cell_for_row_and_column($row, $this), $row, $this);
	}
	
	/**
	 * Is Sortable
	 * 
	 * Returns if the column
	 * is sortable or not
	 * 
	 * @access	public
	 * @return	bool	Is sortable
	 */
	public function is_sortable()
	{
		return $this->_sortable;
	}
}

/* End of file classes/grid/column.php */