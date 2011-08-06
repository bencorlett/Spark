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

class Grid extends \Object {
	
	/**
	 * Model object
	 * 
	 * @var	mixed
	 */
	protected $_model;
	
	/**
	 * Array of columns for the grid
	 * 
	 * @var	Spark\Object
	 */
	protected $_columns;
	
	/**
	 * Default page and sorting properties
	 * 
	 * @var	mixed
	 */
	protected $_default_limit		= 20;
	protected $_default_page		= 1;
	protected $_default_sort		= false;
	protected $_default_direction	= 'desc';
	protected $_default_filter		= array();
	
	/**
	 * Construct
	 * 
	 * Called when the class is constructed
	 * 
	 * @access	public
	 * @param	mixed
	 * @return	Spark\Object
	 */
	public function __construct($identifier = null, $model = null)
	{
		// Check we've got an identifier
		if ( ! $identifier) throw new Exception('An identifier must be provided when initialising the grid');
		
		// Check we've got a model
		if ( ! is_object($model)) throw new Exception('You must provide a model when initialising the grid');
	}
}