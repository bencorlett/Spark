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

class BackFace extends \Object {
	
	/**
	 * Front face property
	 * 
	 * @var	mixed
	 */
	protected $_front_face;
	
	/**
	 * Back face property
	 * 
	 * @var	mixed
	 */
	protected $_back_face;
	
	/**
	 * Construct
	 * 
	 * Called when the class is constructed
	 * 
	 * @access	public
	 * @param	mixed
	 * @return	Spark\Grid
	 */
	public function __construct($front_face, $back_face)
	{
		$this->set_front_face($front_face)
			 ->set_back_face($back_face);
	}
	
	/**
	 * Set Front Face
	 * 
	 * Sets the front face object
	 * property
	 * 
	 * @access	public
	 * @param	mixed	Front Face
	 * @return	Spark\BackFace
	 */
	public function set_front_face($front_face)
	{
		$this->_front_face = $front_face;
		return $this;
	}
	
	/**
	 * Get Front Face
	 * 
	 * Gets the front face object
	 * property
	 * 
	 * @access	public
	 * @return	mixed	Back Face
	 */
	public function get_front_face()
	{
		return $this->_front_face;
	}
	
	/**
	 * Set Back Face
	 * 
	 * Sets the back face object
	 * property
	 * 
	 * @access	public
	 * @param	mixed	Back Face
	 * @return	Spark\BackFace
	 */
	public function set_back_face($back_face)
	{
		$this->_back_face = $back_face;
		return $this;
	}
	
	/**
	 * Get Back Face
	 * 
	 * Gets the back face object
	 * property
	 * 
	 * @access	public
	 * @return	mixed	Back Face
	 */
	public function get_back_face()
	{
		return $this->_back_face;
	}
	
	/**
	 * Build
	 * 
	 * Builds the BackFace object
	 * as Html
	 * 
	 * @access	public
	 * @return	string	Html
	 */
	public function build()
	{
		// Safari has nice 3d transitions
		// so it will be done natively in
		// CSS
		if (\Agent::is_browser('Safari'))
		{
			$view = \View::factory('backface/safari');
		}
		else
		{
			$view = \View::factory('backface/default');
		}
		
		$view->set('front_face', $this->get_front_face())
			 ->set('back_face', $this->get_back_face());
		
		// Return the view
		return (string) $view;
	}
	
	/**
	 * To String
	 * 
	 * Called when the class
	 * is treated as a string
	 * 
	 * @access	public
	 * @return	string	String
	 */
	public function __toString()
	{
		return $this->render();
	}
}