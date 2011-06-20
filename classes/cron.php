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

class Cron extends \Kohana\Orm {
	
	/**
	 * The table name this model uses
	 * 
	 * @var	string
	 */
	protected $_table_name = 'cron';
	
	/**
	 * Default timezone
	 * 
	 * @var	string
	 */
	protected $_default_timezone;
	
	/**
	 * Schedule times
	 * 
	 * @var	array
	 */
	protected $_schedule_times = array(
		'now',
		'on_the_quarter_hour',
		'on_the_half_hour',
		'on_the_hour',
		'midnight',
	);
	
	/**
	 * Set Data
	 * 
	 * Sets data in the cron
	 * job
	 * 
	 * @access	public
	 * @param	mixed	Data
	 * @return	Cron\Model_Cron
	 */
	public function set_data()
	{
		return parent::set_data(serialize(func_get_args()));
	}
	
	/**
	 * Get Data
	 * 
	 * Gets the data from the
	 * cron job
	 * 
	 * @access	public
	 * @return	array	Data
	 */
	public function get_data($fallback = null)
	{
		// Try get the data
		if ($data = unserialize(parent::get_data())) return $data;
		
		// Otherwise return the fallback
		return $fallback;
	}
	
	/**
	 * Execute
	 * 
	 * Executes all cron jobs
	 * that are waiting
	 * 
	 * @access	public
	 * @return	int		Number of successful
	 * 					Jobs ran
	 */
	public static function execute()
	{
		// Get mysql datetime for right now
		$now = \Date::time()->format('mysql');
		
		// Load config
		\Config::load('cron', true);
		
		// Get jobs that were shcheduled
		// to run before now
		$jobs = static::factory()
					  ->where('scheduled_for', '<', $now)
					  ->where('completed', '=', 0)
					  ->where('attempts', '<=', \Config::get('cron.threshold'))
					  ->find_all();
		
		// Loop through jobs
		// and run method with
		// data. If the methods
		// Return true then
		// mark the job as completed
		foreach ($jobs as $job)
		{
			// Check attempts count
			if ($job->get_attempts() >= \Config::get('cron.threshold'))
			{
				// Send error email
				static::send_error_email($e);
				
				// Skip this cron job
				continue;
			}
			
			// Up the attempts
			$job->set_attempts($job->get_attempts() + 1)
				->save();
			
			// Catch any exceptions
			// because we don't want
			// one to break the cron
			try
			{
				// Get result, it must return true to be successful
				if (($result = call_user_func_array($job->get_method(), $job->get_data(array()))) === true)
				{
					// Update the cron
					$job->set_executed_at(\Date::time()->format('mysql'))
						->set_completed(1)
						->save();
				}
			}
			catch (\Exception $e)
			{
				// Send error email
				static::send_error_email($e);
			}
		}
	}
	
	/**
	 * Send Error Email
	 * 
	 * Sends an error email
	 * 
	 * @access	public
	 * @param	Exception
	 */
	public static function send_error_email($e)
	{
		// Send an email
		$email = \Email::factory()
					   ->to(\Config::get('cron.to_address'))
					   ->from(\Config::get('cron.from_address'), \Config::get('cron.from_name'))
					   ->subject(sprintf('Error: \'%s\' in method %s()', $e->getMessage(), $e->getMessage()))
					   ->html(nl2br($e->getTraceAsString()))
					   ->send();
	}
	
	/**
	 * Call
	 * 
	 * Magic method used as getters / setters
	 * 
	 * @access	public
	 * @param	string	Method
	 * @param	array	Arguments
	 * @return	mixed
	 */
	public function __call($method, array $arguments)
	{
		// Used to schedule times using convenience methods defined
		// in the $_schedule_times array. $cron->schedule_for_now();
		// or $scron->schedule_for_on_the_hour();
		if ((strpos($method, 'schedule_for') === 0) and ($schedule_time = substr($method, 13)) and in_array($schedule_time, $this->_schedule_times)) return $this->set_scheduled_for($this->_determine_schedule_time($schedule_time, (isset($arguments[0])) ? $arguments[0] : null));
		
		return parent::__call($method, $arguments);
	}
	
	public function get_timezone_offset($remote_tz, $origin_tz = null) {
	    if($origin_tz === null) {
	        if(!is_string($origin_tz = date_default_timezone_get())) {
	            return false; // A UTC timestamp was returned -- bail out!
	        }
	    }
	    $origin_dtz = new \DateTimeZone($origin_tz);
	    $remote_dtz = new \DateTimeZone($remote_tz);
	    $origin_dt = new \DateTime("now", $origin_dtz);
	    $remote_dt = new \DateTime("now", $remote_dtz);
	    $offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
	    return $offset;
	}
	
	/**
	 * Determine Schedule Time
	 * 
	 * Determines schedule time
	 * for a cron job based on
	 * the description of when
	 * it should occur (found
	 * in $_schedule_times array)
	 * 
	 * @access	protected
	 * @param	string	Schedule description
	 * @param	string	Target timezone
	 * @return	string	Schedule datetime
	 */
	protected function _determine_schedule_time($time, $target_timezone = null)
	{
		// Set default timezone
		if ( ! $this->_default_timezone) $this->_default_timezone = date_default_timezone_get();
		
		// Return different mysql datetimes
		// based on the time requested
		switch ($time)
		{
			// Do the cron job right now
			case 'now':
				return \Date::time()->format('mysql');
			
			// On the quarter hour
			case 'on_the_quarter_hour':
				
				// Get timestamp
				$time = \Date::time()->get_timestamp();
				
				// Get time increments
				$first_quarter	= mktime(date('H'), 15, 0);
				$second_quarter	= mktime(date('H'), 30, 0);
				$third_quarter	= mktime(date('H'), 45, 0);
				$next_hour		= mktime(date('H') + 1, 0, 0);
				
				// 45 - 59
				if ($time >= $third_quarter) return \Date::factory($next_hour)->format('mysql');
				
				// 30 - 44
				if ($time >= $second_quarter) return \Date::factory($third_quarter)->format('mysql');
				
				// 15 - 29
				if ($time >= $first_quarter) return \Date::factory($second_quarter)->format('mysql');
				
				// 0 - 14
				return \Date::factory($first_quarter)->format('mysql');
			
			// On the half hour
			case 'on_the_half_hour':
				
				// Get timestamp
				$time = \Date::time()->get_timestamp();
				
				// Half hour
				$half_hour = mktime(date('H'), 30, 0);
				$next_hour = mktime(date('H') + 1, 0, 0);
				
				// If we're already more than half way into the hour
				// schedule it for on the next hour
				if ($time >= $half_hour) return \Date::factory($next_hour)->format('mysql');
				
				// Otherwise schedule it for half way through this hour
				return \Date::factory($half_hour)->format('mysql');
			
			// On the hour
			case 'on_the_hour':

				// Return the next hour up
				return \Date::factory(mktime(date('H') + 1, 0, 0))->format('mysql');
			
			// At midnight OUR time
			case 'midnight':
				
				// Get time to offset
				$timezone = date_create(date('Y/m/d'), timezone_open('Australia/Sydney'));
				$offset = $timezone->getOffset();
				$time_to_offset = 86400 - abs($this->get_timezone_offset($target_timezone, $this->_default_timezone));
				
				// Return the mysql timestamp for midnight in Australia / Sydney
				return \Date::factory(\Date::factory(strtotime(date('Y/m/d') . '+' . $time_to_offset . ' seconds'))->get_timestamp())->format('mysql');
		}
		
		// Return false
		return false;
	}
	
	/**
	 * Save
	 * 
	 * Save's the current object's
	 * context
	 * 
	 * @access	public
	 * @return	Cron\Model_Cron
	 */
	public function save(\Kohana\Validation $validation = null)
	{
		// The cron must be scheduled
		// and must be scheduled for a
		// time later than now
		if ( ! $this->get_scheduled_for()) throw new Exception('A time later than the current time must be provided for a cron job to be scheduled');
		
		// Get the datetime
		$mysql_datetime = \Date::time()->format('mysql');
		
		// If it's a new record set the created at
		if ( ! $this->loaded()) $this->set_created_at($mysql_datetime);
		
		// Always set the updated at
		$this->set_updated_at($mysql_datetime);
		
		// And now actually save the record
		return parent::save($validation);
	}
	
	/**
	 * Has Active By Slug
	 * 
	 * Determines if the cron
	 * system has an active
	 * job or a job that 
	 * is still waiting to be
	 * completed for the given
	 * slug
	 * 
	 * @access	public
	 * @param	string	Slug
	 * @return	bool	Has active by slug
	 */
	public static function has_active_by_slug($slug)
	{
		// Load config
		\Config::load('cron', true);
		
		// Get model
		$static = static::factory()
						->where('completed', '=', 0)
						->where('attempts', '<', \Config::get('cron.threshold'))
						->where('slug', '=', $slug)
						->find();
		
		// Determine if the model is loaded
		return (bool) $static->loaded();
	}
}

/* End of file classes/cron.php */