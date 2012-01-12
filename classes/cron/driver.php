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

interface Cron_Driver
{
	/**
	 * Add
	 * 
	 * Adds an item to the cron system
	 * 
	 * @access  public
	 * @param   string   Key (to identify the cron job)
	 * @param   method   Method to call
	 * @param   int|Date Date to execute
	 * @param   mxed     Data to provide to method
	 * @return  mixed    Driver instance
	 */
	public function add($key, $method, $scheduled_for, $data = null);

	/**
	 * Execute
	 * 
	 * Executes the cron
	 * 
	 * @access  public
	 * @return  bool   Success
	 */
	public function execute();
}