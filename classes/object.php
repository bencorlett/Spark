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

class Object implements \ArrayAccess, \Countable, \Iterator {
	
	/**
	 * Object identifier
	 * 
	 * @var	array
	 */
	protected $_identifier;
	
	/**
	 * Object data
	 * 
	 * @var	array
	 */
	protected $_data = array();
	
	/**
	 * Object original data
	 * 
	 * @var	array
	 */
	protected $_original_data = array();
	
	/**
	 * Flag to determine if the object
	 * has had data changes since it
	 * was initialised
	 * 
	 * @var	bool
	 */
	protected $_has_data_changes = false;
	
	/**
	 * Data keys for iterating
	 * 
	 * @var	array
	 */
	protected $_data_keys = array();
	
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
		if (empty($arguments[0])) $arguments[0] = array();
		
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
	 * Set Identifier
	 * 
	 * Sets the identifier for the
	 * object
	 * 
	 * @access	public
	 * @param	string	Identifier
	 * @return	Spark\Object
	 */
	public function set_identifier($identifier)
	{
		$this->_identifier = (string) $identifier;
		
		return $this;
	}
	
	/**
	 * Get Identifier
	 * 
	 * Gets the identifier for the
	 * object
	 * 
	 * @access	public
	 * @return	string	Identifier
	 */
	public function get_identifier()
	{
		// Lazy set the identifier
		if ( ! isset($this->_identifier)) $this->_identifier = get_class($this);
		
		return $this->_identifier;
	}
	
	/**
	 * Add Data
	 * 
	 * Adds data to the object and
	 * retains previous data
	 * 
	 * @access	public
	 * @param	mixed Data
	 * @return	Spark\Object
	 */
	public function add_data($data = array())
	{
		// If we've been given a string we just
		// want to push it to the array
		if ( ! is_array($data)) $this->_data[] = $data;
		
		// Loop through data and add it to the array
		else foreach ($data as $index => $value) $this->set_data($index, $value);
		
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
			
			// Set original data if it's not been set
			if ( ! isset($this->_original_data[$key])) $this->_original_data[$key] = $value;
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
		if ($key === null) $this->_data = array();
		
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
		if ($key === null) return (bool) $this->_data;
		
		return isset($this->_data[$key]);
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
		return $this->_get_data($key, $default);
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
	public function get_original_data($key = null, $default = null)
	{
		return $this->_get_data($key, $default, 'original_data');
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
	 * @access	protected
	 * @param	string|null	Key
	 * @param	mixed		Default
	 * @param	string		Source
	 * @return	mixed		Data
	 */
	protected function _get_data($key = null, $default = null, $source = 'data')
	{
		
		// If no key provided, return all
		if ($key === null) return $this->{'_' . $source};
		
		// If the user has used dot
		// notation to access array depth
		if (strpos($key, '.') !== false)
		{
			// Extract keys and data
			$key_array = explode('.', $key);
			$data = $this->{'_' . $source};
			
			// Loop through keys and try to match to data
			foreach ($key_array as $index => $key)
			{
				// If we've been given an incomplete
				// implementation of the dot notation
				if ($key == null) return $default;
				
				// If we've been given an array
				if (is_array($data))
				{
					if ( ! isset($data[$key])) return $default;
					
					// Step a level deeper
					$data = $data[$key];
				}
				
				// If we've nested objects within objects
				elseif ($data instanceof Object) $data = $data->get_data($key);
				
				else return $default;
			}
			
			// Return whatever we've found
			return $data;
		}
		
		// Return the default data
		return isset($this->{'_' . $source}[$key]) ? $this->{'_' . $source}[$key] : $default;
	}
	
	/**
	 * Make Recursive
	 * 
	 * Makes the data in a spark
	 * object a recursive set of
	 * spark objects
	 * 
	 * @access	public
	 * @return	Spark\Object
	 */
	public function make_recursive()
	{
		// Loop through data and convert
		// to objects
		foreach ($this->_data as $key => $value)
		{
			if (is_array($value))
			{
				$this->_data[$key] = \Object::factory($value)
											->make_recursive();
			}
		}
		
		return $this;
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
	public function __call($method, array $arguments)
	{
		// Get the key
		$key = substr($method, 4);
		
		// Bunch of more setters and getters
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
		
		throw new Exception(\Str::f('Call to undefined method %s::%s()', get_called_class(), $method));
	}
	
	/**
	 * Call Static
	 * 
	 * Magic method used to create
	 * an instance of the object
	 * 
	 * @access	public
	 * @param	string	Method
	 * @param	array	Arguments
	 * @return	mixed
	 */
	public static function __callStatic($method, array $arguments)
	{
		switch (substr($method, 12))
		{
			case 'post':
				return static::factory($_POST)
							 ->make_recursive();
			
			case 'get':
				
				return static::factory($_GET)
							 ->make_recursive();
		}
		
		throw new Exception(\Str::f('Call to undefined method %s::%s()', get_class($this), $method));
	}
	
	/**
	 * Offset Get
	 * 
	 * Implementation of ArrayAccess::offsetGet()
	 * 
	 * @access	public
	 * @link	http://www.php.net/manual/en/arrayaccess.offsetget.php
	 * @param	string	Offset
	 * @return	mixed
	 */
	public function offsetGet($offset)
	{
		return isset($this->_data[$offset]) ? $this->_data[$offset] : null;
	}
	
	/**
	 * Offset Set
	 * 
	 * Implementation of ArrayAccess::offsetSet()
	 * 
	 * @access	public
	 * @link	http://www.php.net/manual/en/arrayaccess.offsetset.php
	 * @param	string $offset
	 * @param	mixed $value
	 */
	public function offsetSet($offset, $value)
	{
		$this->_data[$offset] = $value;
	}
	
	/**
	 * Offset Unset
	 * 
	 * Implementation of ArrayAccess::offsetUnset()
	 * 
	 * @access	public
	 * @link	http://www.php.net/manual/en/arrayaccess.offsetunset.php
	 * @param	string	Offset
	 */
	public function offsetUnset($offset)
	{
		unset($this->_data[$offset]);
	}

	/**
	 * Offset Exists
	 * 
	 * Implementation of ArrayAccess::offsetExists()
	 * 
	 * @access	public
	 * @link	http://www.php.net/manual/en/arrayaccess.offsetexists.php
	 * @param	string	Offset
	 * @return	bool
	 */
	public function offsetExists($offset)
	{
		if ($offset or $offset === 0) return isset($this->_data[$offset]);
		return false;
	}
	
	/**
	 * Count
	 * 
	 * Implementation of Countable::count()
	 * 
	 * @access	public
	 * @link	http://www.php.net/manual/en/countable.count.php
	 * @return	int		Count
	 */
	public function count()
	{
		return count($this->_data);
	}
	
	/**
	 * Key
	 * 
	 * Implementation of Iterator::key()
	 * 
	 * @access	public
	 * @link	http://www.php.net/manual/en/iterator.key.php
	 * @return	int		Current key
	 */
	public function key()
	{
		return current($this->_data_keys);
	}

	/**
	 * Next
	 * 
	 * Implementation of Iterator::next()
	 * 
	 * @access	public
	 * @link	http://www.php.net/manual/en/iterator.next.php
	 * @return	Spark\Object
	 */
	public function next()
	{
		next($this->_data_keys);
		return $this;
	}

	/**
	 * Rewind
	 * 
	 * Implementation of Iterator::rewind()
	 * 
	 * @access	public
	 * @link	http://www.php.net/manual/en/iterator.rewind.php
	 * @return	Spark\Object
	 */
	public function rewind()
	{
		$this->_data_keys = array_keys($this->_data);
		
		return $this;
	}
	
	/**
	 * Valid
	 * 
	 * Implementation of Iterator::valid()
	 * 
	 * @access	public
	 * @link	http://www.php.net/manual/en/iterator.valid.php
	 * @return	bool
	 */
	public function valid()
	{
		return $this->offsetExists(current($this->_data_keys));
	}
	
	/**
	 * Current
	 * 
	 * Implementation of Iterator::current()
	 * 
	 * @access	public
	 * @link	http://www.php.net/manual/en/iterator.current.php
	 * @return	bool
	 */
	public function current()
	{
		return $this->_data[current($this->_data_keys)];
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
		return (string) $this->get_identifier();
	}
}