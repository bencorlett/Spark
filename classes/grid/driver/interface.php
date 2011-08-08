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

interface Grid_Driver_Interface {
	
	/**
	 * Prepare Model
	 * 
	 * Prepares the model
	 * based off parameters
	 * such as filters, sort
	 * and page
	 * 
	 * @access	public
	 * @return	Spark\Grid_Driver_Abstract
	 */
	public function prepare_model();
	
	/**
	 * Build Rows
	 * 
	 * Builds the rows based
	 * off the model and stores
	 * them in the grid
	 * 
	 * @access	public
	 * @return	Spark\Object
	 */
	public function build_rows();
}