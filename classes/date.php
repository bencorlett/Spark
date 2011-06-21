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

class Date extends \Fuel\Core\Date {
	
	public function get_timezone_offset()
	{
		// Create two datetimezone objects
		$origin_datetimezone	= new \DateTimeZone(\Fuel::$timezone);
		$remote_datetimezone	= new \DateTimeZone($this->timezone);
		
		// Get datetime in both zones
		$origin_datetime		= new \DateTime('now', $origin_datetimezone);
		$remote_datetime		= new \DateTime('now', $remote_datetimezone);
		
		// Calculate the offset in seconds
		return ($remote_datetimezone->getOffset($remote_datetime) - $origin_datetimezone->getOffset($origin_datetime));
	}
}