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

class Alert extends \Object
{
	
	/** 
	 * Type constants
	 * 
	 * @constant
	 */
	const TYPE_SUCCESS	= 'success';
	const TYPE_NOTICE	= 'notice';
	const TYPE_ERROR	= 'error';
	
	/**
	 * Allowable types
	 * 
	 * @var	array
	 */
	protected static $_allowable_types = array(
		self::TYPE_SUCCESS,
		self::TYPE_NOTICE,
		self::TYPE_ERROR,
	);
	
	/**
	 * Notification
	 * 
	 * @var	array
	 */
	protected $_notifications = array();
	
	/**
	 * Construct
	 * 
	 * Internal constructor
	 * not dependent on parameters
	 * 
	 * @access	protected
	 */
	protected function _construct()
	{
		// Load the notifications
		$this->_notifications = \Session::get('notifications');
	}
	
	/**
	 * Add
	 * 
	 * Adds a new notifiction
	 * 
	 * @access	public
	 * @param	string	Type
	 * @param	string	Message
	 */
	public static function add($type, $message)
	{
		// Set the notification
		static::instance()->_notifications[$type][] = (string) htmlentities($message);
		static::instance()->write();
	}
	
	/**
	 * Write
	 * 
	 * Writes the notifications
	 * to the session using the
	 * session class
	 * 
	 * @access	public
	 */
	public function write()
	{
		// Set the notifications in the session
		\Session::set('notifications', $this->_get_notifications());
	}
	
	/**
	 * Build
	 * 
	 * Builds the notifications
	 * 
	 * @access	public
	 * @return	View
	 */
	public static function build()
	{
		// We need notifications to continue
		if ( ! static::instance()->_get_notifications()) return null;
		
		// Load config
		\Config::load('alert', true);
		
		// Return view
		return \View::forge(\Config::get('alert.view'))
					->set('count', static::instance()->_get_total_count())
					->set('notifications', static::instance()->_get_notifications(true));
	}
	
	/**
	 * Get Notifications
	 * 
	 * Gets the notifications
	 * 
	 * @access	public
	 * @param	bool	Flash
	 * @return	array	Notifications
	 */
	protected function _get_notifications($flash = false)
	{
		// Get notifications
		$notifications = $this->_notifications;
		
		// If flash is true wipe
		// the notifications and the
		// session
		if ($flash === true)
		{
			// Reset the notifications
			$this->_notifications = array();
			\Session::delete('notifications');
		}
		
		// Return the notifications
		return $notifications;
	}
	
	/**
	 * Get Total Count
	 *
	 * Gets the total count
	 * of notifications
	 * 
	 * @access	public
	 * @return	int		Total count
	 */
	protected function _get_total_count()
	{
		// Get notifications
		$notifications = static::instance()->_notifications;
		
		// Counter fallback
		$i = 0;
		
		foreach ($notifications as $group)
		{
			foreach ($group as $item)
			{
				$i++;
			}
		}
		
		return $i;
	}
	
	/**
	 * Call Static
	 * 
	 * Magic method used for
	 * convenience adding
	 * 
	 * @access	public
	 * @param	string	Method
	 * @param	array	Arguments
	 */
	public static function __callStatic($method, array $arguments)
	{
		// If the method called is one of the allowable types call that method
		if (in_array($method, static::$_allowable_types)) return static::add($method, call_user_func_array('Str::f', $arguments));
		
		return parent::__callStatic($method, $arguments);
	}
}

/* End of file classes/notifications.php */