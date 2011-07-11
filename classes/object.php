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

class Object {
	
	/**
	 * Object instance for Singleton access
	 * 
	 * @var	Spark\Object
	 */
	protected static $_instance = null;
	
	/**
	 * Object attributes
	 * 
	 * @var	array
	 */
	protected $_data = array();
	
	/**
	 * Has data changes flag
	 * 
	 * @var	bool
	 */
	protected $_has_data_changes = false;
	
	/**
	 * Original data that was loaded
	 * 
	 * @var	array
	 */
	protected $_original_data;
	
	/**
	 * Name of object identifier
	 * 
	 * @var	string
	 */
	protected $_identifier = null;
	
	/**
	 * Construct
	 * 
	 * Called when the class is constructed
	 * 
	 * @access	public
	 * @param	mixed
	 * @return	Spark\Object
	 */
	public function __construct()
	{
		// Get the arguments
		$arguments = func_get_args();
		
		// Set a fallback if none
		if (empty($arguments[0]))
		{
			$arguments[0] = array();
		}
		
		// Set the data
		$this->_data			= $arguments[0];
		$this->_original_data	= ( ! empty($arguments[0])) ? $arguments[0] : null;
		
		// Call internal constructor
		$this->_construct();
	}
	
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
		
	}
	
	/**
	 * Factory
	 * 
	 * Static method used to initialise
	 * the class. Allows method chaining.
	 * Usage is the same as calling 'new'
	 * and you provide the same parameters
	 * as you would when calling 'new'.
	 * 
	 * For example:
	 * 
	 * 		$test = new Test('some_param', array('another'));
	 * 
	 * 		Is the same as
	 * 
	 * 		$test = Test::factory('some_param', array('another'))
	 * 					// But now we can chain methods straight away
	 * 					->render();
	 * 
	 * @access	public
	 * @param	mixed
	 * @return	Spark\Object
	 */
	public static function factory()
	{
		// Create a reflection class from the called class
		$reflection_class = new \ReflectionClass(get_called_class());
		
		// Create a new instance of the reflection class and
		// parse the arguments given to this function to the
		// new instance of that class
		$class_instance = $reflection_class->newInstanceArgs(func_get_args());
		
		// Return the reflection class
		return $class_instance;
	}
	
	/**
	 * Instance
	 * 
	 * Get the instance of the
	 * class using the Singleton
	 * pattern
	 * 
	 * @access	public
	 * @param	mixed
	 * @return	Spark\Object
	 */
	public static function instance()
	{
		// You can't get an instance
		// of this class, but rather
		// of all the child classes
		// that inherit this class
		if (get_called_class() === __CLASS__)
		{
			throw new Exception('static::%s() can only be called on a child class of %s, and not this class itself', __FUNCTION__, __CLASS__);
		}
		
		if (is_null(static::$_instance))
		{
			// Create a reflection class from the called class
			$reflection_class = new \ReflectionClass(get_called_class());

			// Create a new instance of the reflection class and
			// parse the arguments given to this function to the
			// new instance of that class
			static::$_instance = $reflection_class->newInstanceArgs(func_get_args());
		}
		
		return static::$_instance;
	}
	
	/**
	 * Has Data Changes
	 * 
	 * Determines if the class has had
	 * data changes since being initalised
	 * 
	 * @access	public
	 * @return 	bool	Has data changes
	 */
	public function has_data_changes()
	{
		return $this->_has_data_changes;
	}
	
	/**
	 * Set Identifier
	 * 
	 * Sets the identifier of the class
	 * 
	 * @access	public
	 * @param	string		Identifier
	 * @return	Spark\Object
	 */
	public function set_identifier($identifier)
	{
		$this->_identifier = $identifier;
		
		return $this;
	}
	
	/**
	 * Get Identifier
	 * 
	 * Gets the identifier of the class
	 * 
	 * @access	public
	 * @return	string	Identifier
	 */
	public function get_identifier()
	{
		return $this->_identifier;
	}
	
	/**
	 * Add Data
	 * 
	 * Adds data to the object and
	 * retains previous data
	 * 
	 * @access	public
	 * @param	array		Data
	 * @return	Spark\Object
	 */
	public function add_data(array $data)
	{
		// Loop through data and add it
		foreach ($data as $index => $value)
		{
			$this->set_data($index, $value);
		}
		
		// Return this
		return $this;
	}
	
	/**
	 * Set Data
	 * 
	 * Overwrite data in the object
	 * 
	 * If the first parameter is a string
	 * the attribute will be overwritten by
	 * the second parameter.
	 * 
	 * If the first parameter is an array
	 * the entire data wiil be overwritten
	 * by the contents of the first parameter
	 * alone.
	 * 
	 * @access	public
	 * @param	string|array	Key
	 * @param	mixed			Value
	 * @return	Spark\Object
	 */
	public function set_data($key, $value = null)
	{
		// We've changed data
		$this->_has_data_changes = true;
		
		// If the key is an array
		if (is_array($key))
		{
			// Set the data
			$this->_data = $key;
			
			// Loop through data and set as original
			// if the data is in fact original
			foreach ($key as $key => $value)
			{
				if ( ! isset($this->_original_data[$key]))
				{
					$this->_original_data[$key] = $value;
				}
			}
		}
		
		// If the key is a string
		else
		{
			$this->_data[$key] = $value;
			
			// Set original data
			if ( ! isset($this->_original_data[$key]))
			{
				$this->_original_data[$key] = $value;
			}
		}
		
		// Return this
		return $this;
	}
	
	/**
	 * Unset Data
	 * 
	 * Unset data from the object.
	 * 
	 * If a key is provided, it will
	 * unset only the corresponding
	 * data for that key.
	 * 
	 * If a key isn't provided, all
	 * of the data will be unsetted.
	 * 
	 * @access	public
	 * @param	string|null	Key
	 * @return	Spark\Object
	 */
	public function unset_data($key = null)
	{
		// We've changed data
		$this->_has_data_changes = true;
		
		// If no key provided, unset all
		if (is_null($key))
		{
			$this->_data = array();
		}
		
		// Key must be string, nothing else
		else if (is_string($key))
		{
			unset($this->_data[$key]);
		}
		
		// Return this
		return $this;
	}
	
	/**
	 * Has Data
	 * 
	 * Determines if the model has
	 * data for the given key
	 * 
	 * If key is empty it will determine
	 * if any data is set
	 * 
	 * @access	public
	 * @param	string|null	Key
	 * @return	bool		Has data
	 */
	public function has_data($key = null)
	{
		if (is_null($key))
		{
			return (bool) $this->_data;
		}
		
		return (bool) isset($this->_data[$key]);
	}
	
	/**
	 * Get Data
	 * 
	 * Gets data from the object
	 * 
	 * If key is empty it wil return
	 * all of the data as an array.
	 * The key will accept using a '.'
	 * to separate depths within an array
	 * (Like Fuel's config class).
	 * 
	 * For example:
	 * 
	 * 		$this->get_data('a.b.c');
	 * 
	 * Will look for $this->_data['a']['b']['c'].
	 * 
	 * The second parameter is the default value
	 * to return if the specified value isn't set
	 * 
	 * If the key is provided return the
	 * value of the attribute specified
	 * by the key.
	 * 
	 * @access	public
	 * @param	string|null	Key
	 * @param	mixed		Default
	 * @return	mixed		Data
	 */
	public function get_data($key = null, $default = null)
	{
		// If no key provided, return all
		if (is_null($key))
		{
			return $this->_data;
		}
		
		// // If the user has used shorthand
		// // notation to access array depth
		// if (strpos($key, '.'))
		// {
		// 	
		// }
		
		// Return the default data
		return (isset($this->_data[$key])) ? $this->_data[$key] : $default;
	}
	
	/**
	 * Get Original Data
	 * 
	 * Gets original data from the object
	 * 
	 * If key is empty it wil return
	 * all of the data as an array.
	 * The key will accept using a '.'
	 * to separate depths within an array
	 * (Like Fuel's config class).
	 * 
	 * For example:
	 * 
	 * 		$this->get_original_data('a.b.c');
	 * 
	 * Will look for $this->_original_data['a']['b']['c'].
	 * 
	 * The second parameter is the default value
	 * to return if the specified value isn't set
	 * 
	 * If the key is provided return the
	 * value of the attribute specified
	 * by the key.
	 * 
	 * @access	public
	 * @param	string|null	Key
	 * @param	mixed		Default
	 * @return	mixed		Data
	 */
	public function get_original_data($key = null, $default = null)
	{
		// If no key provided, return all
		if (is_null($key))
		{
			return $this->_original_data;
		}
		
		// // If the user has used shorthand
		// // notation to access array depth
		// if (strpos($key, '.'))
		// {
		// 	
		// }
		
		// Return the default data
		return (isset($this->_original_data[$key])) ? $this->_original_data[$key] : $default;
	}
	
	/**
	 * Set Data Using Method
	 * 
	 * Overwrite data in the object
	 * using setter / getter methods
	 * 
	 * @access	public
	 * @param	string	Key
	 * @param	mixed	Value
	 */
	public function set_data_using_method($key, $value)
	{
		return $this;
	}
	
	
	/**
	 * To Array
	 * 
	 * Convert Object Attributes
	 * to an array.
	 * 
	 * If the keys aren't provided
	 * it will return all of the data as
	 * an array
	 * 
	 * @access	public
	 * @param	array	Keys
	 * @return	array	Data
	 */
	public function to_array(array $keys = array())
	{
		// If no attributes were specified, return
		// all data
		if (empty($keys))
		{
			return $this->_data;
		}
		
		// To return fallback
		$to_return = array();
		
		// Loop through keys provided, get data
		// and add them to the array to return
		foreach ($keys as $key)
		{
			if (isset($this->_data[$key]))
			{
				$to_return[$key] = $this->_data[$key];
			}
			else
			{
				$to_return[$key] = null;
			}
		}
		
		// Return the array
		return $to_return;
	}
	
	/**
	 * Call
	 * 
	 * Magic method used as a
	 * getter / setter wrapper
	 * 
	 * @access	public
	 * @param	string	Method
	 * @param	array	Arguments
	 * @return	mixed
	 */
	public function __call($method, $arguments)
	{
		// Get the key
		$key = substr($method, 4);
		
		// Check different setters / getters
		// and return results if applicable
		switch (substr($method, 0, 3))
		{
			case 'get':
				return $this->get_data($key, isset($arguments[0]) ? $arguments[0] : null);
				
			case 'set':
				return $this->set_data($key, isset($arguments[0]) ? $arguments[0] : null);
				
			case 'uns':
				return $this->unset_data($key);
				
			case 'has':
				return isset($this->_data[$key]);
		}
		
		throw new Exception('Call to undefined method %s::%s()', get_called_class(), $method);
	}
	
	/** 
	 * Underscore
	 * 
	 * Gets the object property
	 * by first checking
	 * for a property that matches
	 * the given key, and then checking
	 * for a property name with an
	 * underscore prepended
	 * 
	 * @access	protected
	 * @param	string	Key
	 * @return	string	Property name
	 */
	protected function _underscore($key)
	{
		// The underscore property
		$underscore = sprintf('_%s', $key);
		
		// Return the property without an
		// underscore if it exists
		if (property_exists($this, $key))
		{
			return $key;
		}
		else if (property_exists($this, $underscore))
		{
			return $underscore;
		}
		
		return false;
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
		return ($this->get_identifier()) ? $this->get_identifier() : get_called_class();
	}
}

/* End of file classes/object.php */