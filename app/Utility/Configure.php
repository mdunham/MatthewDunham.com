<?php

/**
 * Configure Class
 * 
 * Simple configuration holder.
 * 
 * @author Matthew Dunham <matt@matthewdunham.com> 
 */
class Configure {

	/**
	 * Array of values currently stored in Configure.
	 *
	 * @var array
	 */
	protected static $_values = array();

	/**
	 * Used to read information stored in Configure.  Its not
	 * possible to store `null` values in Configure.
	 *
	 * Usage:
	 * {{{
	 * Configure::read('Name'); will return all values for Name
	 * }}}
	 *
	 * @param string $var Variable to obtain.
	 * @return mixed value stored in configure, or null.
	 */
	public static function read($key = NULL) {
		if ($key == NULL) {
			return self::$_values;
		}

		return self::$_values[$key];
	}

	/**
	 * Used to store a dynamic variable in Configure.
	 *
	 * Usage:
	 * {{{
	 * Configure::write('key1', 'value of the key1');
	 * }}}
	 *
	 * @param string $key Name of var to write
	 * @param mixed $value Value to set for var
	 * @return boolean True if write was successful
	 */
	public static function write($key, $value = null) {
		self::$_values[$key] = $value;
		return true;
	}

	/**
	 * Used to delete a variable from Configure.
	 *
	 * Usage:
	 * {{{
	 * Configure::delete('Name'); will delete the entire Configure::Name
	 * }}}
	 *
	 * @param string $var the var to be deleted
	 * @return void
	 */
	public static function delete($key) {
		unset(self::$_values[$key]);
	}

}