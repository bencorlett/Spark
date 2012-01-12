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

class Cron
{
	/**
	 * The current driver instance
	 */
	protected static $instance = null;

	/**
	 * An array of driver instances
	 * 
	 * @var array
	 */
	protected static $instances = array();

	/**
	 * Default config values
	 */
	protected static $defaults = array(
		'driver' => 'orm',
	);

	/**
	 * Init
	 * 
	 * Initialises the calss by loading
	 * config
	 * 
	 * @access  public
	 * @return  void
	 */
	public static function _init()
	{
		\Config::load('cron', true);
	}

	/**
	 * Forge
	 * 
	 * Creates a new instance of the
	 * cron class
	 * 
	 * @access  public
	 * @return  mixed   Driver
	 */
	public static function forge($custom = array())
	{
		// Get config
		$config = \Config::get('cron', array());

		// If the only param was a driver then create
		// an array with it as a value
		if ( ! empty($custom) and ! is_array($custom))
		{
			$custom = array('driver' => $custom);
		}

		// Merge all configs together into one array
		$config = \Arr::merge(static::$defaults, $config, $custom);

		if (array_key_exists($config['driver'], static::$instances))
		{
			throw new \FuelException(\Str::f('You cannot instantiate two different cron drivers using the same driver \'%s\'', $driver));
		}

		// Determine the class to load
		$class = '\\Cron_'.\Str::ucfirst(\Arr::get($config, 'driver'));

		// Create a new instance
		$driver = new $class($config);

		static::$instances[$config['driver']] = $driver;

		return static::$instances[$config['driver']];
	}

	/**
	 * Instance
	 * 
	 * Creates or returns a driver instance
	 * 
	 * @access  public
	 * @param   string   Driver name
	 */
	public static function instance($driver = null)
	{
		if ($driver !== null)
		{
			if ( ! array_key_exists($driver, static::$instances))
			{
				return false;
			}

			return static::$instances[$driver];
		}

		if (static::$instance === null)
		{
			static::$instance = static::forge();
		}

		return static::$instance;
	}

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
	public static function add($key, $method, $scheduled_for, $data = null)
	{
		return static::instance()->add($key, $method, $scheduled_for, $data);
	}

	/**
	 * Execute
	 * 
	 * Executes the cron
	 * 
	 * @access  public
	 * @return  bool   Success
	 */
	public function execute()
	{
		return static::instance()->execute();
	}
}