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

class Breadcrumbs extends \Object {
	
	/**
	 * Add
	 *
	 * Adds a new breadcrumb to
	 * the class instance
	 * 
	 * If you have previously added
	 * a level 3 breadcrumb and add a
	 * level 2 breadcrumb, it will
	 * remove any level 3 or greater
	 * breadcrumbs
	 * 
	 * @access	public
	 * @param	int		Level
	 * @param	string	Text
	 * @param	string	Uri
	 */
	public static function add($level, $text, $uri = null)
	{
		// We need a level
		if ( ! is_int($level))
		{
			throw new Exception('The level provided \'%s\' isn\'nt an integer', $level);
		}
		
		// Add the breadcrumb
		static::instance()->_add($level, $text, $uri);
	}
	
	/**
	 * Add
	 * 
	 * Adds a new breadcrumb to
	 * the session
	 * 
	 * If you have previously added
	 * a level 3 breadcrumb and add a
	 * level 2 breadcrumb, it will
	 * remove any level 3 or greater
	 * breadcrumbs
	 * 
	 * @access	public
	 * @param	int		Level
	 * @param	string	Text
	 * @param	string	Uri
	 */
	protected function _add($level, $text, $uri = null)
	{
		// \Session::delete('breadcrumbs');
		// Get or initialise breadcrumbs
		if (($old_breadcrumbs = \Session::get('breadcrumbs')) === null)
		{
			$old_breadcrumbs = \Object::factory();
		}
		
		$new_breadcrumbs = \Object::factory();
		
		// Loop through old breadcrumbs
		// and add all breadcrumbs to the new
		// breadcrumbs only if the level
		// is less than the new level
		foreach ($old_breadcrumbs->get_data() as $breadcrumb_level => $breadcrumb)
		{
			if ($breadcrumb_level >= $level)
			{
				continue;
			}
			
			// Add data
			$new_breadcrumbs->add_data(array(
				$breadcrumb_level => $breadcrumb
			));
		}
		
		// Set the data
		$new_breadcrumbs->add_data(array($level => array('text' => $text, 'uri' => $uri)));
		
		// Set the breadcrumbs
		\Session::set('breadcrumbs', $new_breadcrumbs);
	}
	
	/**
	 * Render
	 * 
	 * Renders the Breadcrumbs
	 * object as Html
	 * 
	 * @access	public
	 * @return	string	Html
	 */
	public static function render()
	{
		return static::instance()->_render();
	}
	
	/**
	 * Render
	 * 
	 * Renders the Breadcrumbs
	 * object as Html
	 * 
	 * @access	protected
	 * @return	string	Html
	 */
	protected function _render()
	{
		$view = \View::factory('breadcrumbs/default')
					 ->set('breadcrumbs', $this->get_breadcrumbs());
		
		return $view;
	}
}