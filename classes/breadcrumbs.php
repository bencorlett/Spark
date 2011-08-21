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
	 * @param	string	Text
	 * @param	int		Level
	 * @param	string	Uri
	 */
	public static function add($text, $level = false, $uri = null)
	{
		// Add the breadcrumb
		static::instance()->_add($text, $level, $uri);
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
	 * @param	string	Text
	 * @param	int		Level
	 * @param	string	Uri
	 */
	protected function _add($text, $level = null, $uri = null)
	{
		// Create new breadcrumbs
		$old_breadcrumbs = $this->get_breadcrumbs();
		$new_breadcrumbs = \Object::factory();
		
		if ($level !== null)
		{
			// Loop through old breadcrumbs
			// and add all breadcrumbs to the new
			// breadcrumbs only if the level
			// is less than the new level
			foreach ($old_breadcrumbs as $old_breadcrumb)
			{
				// If we're adding a number 2, remove 3 and greater
				if ($old_breadcrumb->get_identifier() >= $level)
				{
					continue;
				}

				// Add data
				$new_breadcrumbs->add_data(array($old_breadcrumb->get_identifier() => $old_breadcrumb));
			}
		}
		else
		{
			$level = 1;
			foreach ($this->get_breadcrumbs() as $breadcrumb) $level = $breadcrumb->get_identifier();
		}
		
		// Create a new breadcrumb
		$new_breadcrumb = \Object::factory(array(
			'text'		=> $text,
			'uri'		=> $uri ? \Uri::create($uri) : ($uri === null ? \Uri::current() : false),
		))->set_identifier($level);
		
		// Set the data
		$new_breadcrumbs->add_data(array($new_breadcrumb->get_identifier() => $new_breadcrumb));
		
		// Set the breadcrumbs
		$this->set_breadcrumbs($new_breadcrumbs);
	}
	
	/**
	 * Get Breadcrumbs
	 * 
	 * Gets the breadcrumbs
	 * 
	 * @access	public
	 * @return	Spark\Object
	 */
	public function get_breadcrumbs()
	{
		// Lazy laod the breadcrumbs
		if ( ! $this->has_data('breadcrumbs'))
		{
			if ( ! ($breadcrumbs = \Session::get('breadcrumbs', false)) instanceof \Object)
			{
				$breadcrumbs = \Object::factory();
			}
			
			$this->set_data('breadcrumbs', $breadcrumbs);
		}
		
		return $this->get_data('breadcrumbs');
	}
	
	/**
	 * Set Breadcrumbs
	 * 
	 * Sets the breadcrumbs
	 * 
	 * @access	public
	 * @param	Spark\Object	Breadcrumbs
	 * @return	Spark\Breadcrumbs
	 */
	public function set_breadcrumbs(\Object $breadcrumbs)
	{
		$this->set_data('breadcrumbs', $breadcrumbs);
		\Session::set('breadcrumbs', $breadcrumbs);
		return $this;
	}
	
	/**
	 * Build
	 * 
	 * Builds the Breadcrumbs
	 * object as Html
	 * 
	 * @access	public
	 * @return	string	Html
	 */
	public static function build()
	{
		return static::instance()->_build();
	}
	
	/**
	 * Builds
	 * 
	 * Builds the Breadcrumbs
	 * object as Html
	 * 
	 * @access	protected
	 * @return	string	Html
	 */
	protected function _build()
	{
		// If there are no breadcrumbs
		// if ( ! $this->get_breadcrumbs()->count()) return null;
		
		// Load config
		\Config::load('breadcrumbs', true);
		
		$view = \View::factory(\Config::get('breadcrumbs.view'))
					 ->set('breadcrumbs', $this->get_breadcrumbs());
		
		return $view;
	}
}

/* End of file classes/breadcrumbs.php */