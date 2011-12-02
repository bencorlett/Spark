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

class Grid_Button extends \Object
{
	
	/**
	 * Build
	 * 
	 * Builds a button for the grid
	 * container and returns it as
	 * either a view or html
	 * 
	 * @access	public
	 * @return	string|View
	 */
	public function build()
	{
		// We need a label and an action at the very least
		if ( ! $this->has_label() or ( ! $this->has_action() and ! $this->has_onclick()))
		{
			throw new Grid_InvalidConfigurationException('You must provide at least a label and an action (or substitute an onclick for an action) when adding a button to the grid');
		}
		
		// If we haven't been given
		// something to do onclick,
		// default to the action uri
		// given
		if ( ! $this->has_onclick())
		{
			$this->set_onclick('window.location.href=\''. \Uri::create($this->get_action()) . '\'')
				 ->uns_action();
		}
		
		// Return a form button
		return \Form::button(null,
							 $this->get_label(),
							 $this->get_data()
		);
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