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

class Cron_Orm extends \Orm\Model
               implements \Cron_Driver
{
	/**
	 * The table name this model uses
	 * 
	 * @var string
	 */
	protected static $_table_name = 'cron';

	/**
	 * This model's properties
	 * 
	 * @var array
	 */
	protected static $_properties = array(
		'id' => array(
			'type'  => 'int',
			'label' => 'ID',
		),
		'key' => array(
			'type'  => 'varchar',
			'label' => 'Key',
		),
		'method' => array(
			'type'       => 'varchar',
			'label'      => 'Method',
			'validation' => array('required'),
		),
		'data' => array(
			'type'       => 'text',
			'label'      => 'Data',
		),
		'scheduled_for' => array(
			'type'       => 'int',
			'label'      => 'Scheduled For',
			'validation' => array('required'),
		),
		'executed_at' => array(
			'type'       => 'int',
			'label'      => 'Executed At',
		),
		'attempts' => array(
			'type'  => 'smallint',
			'label' => 'Attempts',
		),
		'completed' => array(
			'type'  => 'tinyint',
			'label' => 'Completed',
		),
		'created_at' => array(
			'type'       => 'int',
			'label'      => 'Created At',
		),
		'updated_at' => array(
			'type'       => 'int',
			'label'      => 'Updated At',
		),
	);

	/**
	 * This model's observers
	 * 
	 * @var array
	 */
	protected static $_observers = array(
		'Orm\\Observer_CreatedAt'  => array('events' => array('before_insert')),
		'Orm\\Observer_UpdatedAt'  => array('events' => array('before_save')),
		'Orm\\Observer_Validation' => array('events' => array('before_save')),
		'Orm\\Observer_Self'       => array('events' => array('before_insert')),
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
		// Override the table name
		static::$_table_name = \Config::get('cron.table_name', 'cron');
	}

	/**
	 * Event: Before insert
	 * 
	 * Called before a new record
	 * is inserted
	 * 
	 * @access  public
	 * @return  void
	 */
	public function _event_before_insert()
	{
		$this->attempts  = 0;
		$this->completed = 0;
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
	public function add($key, $method, $scheduled_for, $data = null)
	{
		$this->key    = $key;
		$this->method = $method;

		// Convert date instance to timestamp
		if ($scheduled_for instanceof \Date)
		{
			$scheduled_for = $scheduled_for->get_timestamp();
		}

		// Set the scheduled for
		$this->scheduled_for = $scheduled_for;

		// Set the data for the method
		if ($data !== null)
		{
			$this->data = serialize($data);
		}

		// Save the model
		$this->save();

		return $this;
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
		
	}
}