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

class Tabs extends \Object {
	
	/**
	 * An array of tab objects
	 * 
	 * @var	array
	 */
	protected $_tabs = array();
	
	/**
	 * A random ID for this set of tabs
	 * 
	 * @var	int
	 */
	protected $_rand_id;
	
	/**
	 * Construct
	 * 
	 * Called when the class is constructed
	 * 
	 * @access	public
	 * @param	mixed
	 * @return	Spark\Object
	 */
	public function __construct(array $tabs)
	{
		// Set the random id
		$this->_rand_id = mt_rand(0, 1000000);
		
		foreach ($tabs as $key => $attributes)
		{
			// Set some fallbacks
			if ( ! isset($attributes['view_path'])) $attributes['view_path'] = true;
			
			// Create a new tab
			$tab = \Object::factory($attributes)
						  ->set_identifier((string) $key);
			
			$tab->set_css_id($tab->get_identifier() . '-' . $this->_rand_id)
			    ->set_css_class('tab-' . $this->_rand_id);
			
			
			// If we're dealing with a string and the person hasn't said
			// they're not giving the path of a view
			if (is_string($tab->get_content()) and $tab->get_view_path() !== false) $tab->set_content(\View::factory($tab->get_content()));
			
			// Add to tabs
			$this->_tabs[] = $tab;
		}
	}
	
	/**
	 * Build
	 * 
	 * Builds the tabs
	 * 
	 * @access	public
	 * @return	string	Html
	 */
	public function build()
	{
		return \View::factory('tabs/default')
					->set_tabs($this->_tabs, false)
					->set_rand_id($this->_rand_id);
	}
}