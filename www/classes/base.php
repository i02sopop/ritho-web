<?php
/* Copyright (c) 2011-2015 Ritho-web team (look at AUTHORS file)

   This file is part of ritho-web.

   ritho-web is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as
   published by the Free Software Foundation, either version 3 of the
   License, or (at your option) any later version.

   ritho-web is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public
   License along with Foobar. If not, see <http://www.gnu.org/licenses/>.
*/

/* Base class with some default methods and vars in common to all the app classes. */
abstract class Base {
	/** Local and global data. */
	protected $_data = array();

	/** Constructor of the class. */
	public function __construct() {
		global $configs;

		$this->configs = $configs;
	}

	/** Getter of the class.
	 *
	 * @param string $name Name of the parameter to get.
	 */
	public function __get($name) {
		if (method_exists($this, ($method = 'get_' . ucfirst($name))))
			return $this->$method();
		else if (array_key_exists($name, $this->_data))
			return $this->_data[$name];
		else if (isset($this->$name))
			return $this->$name;

		$trace = debug_backtrace();
		trigger_error(
			'Undefined property via __get(): ' . $name .
			' in ' . $trace[0]['file'] . ' on line ' .
			$trace[0]['line'], E_USER_NOTICE);
		return null;
	}

	/** Check if a parameter is set in the class.
	 *
	 * @param string $name Name of the parameter to check.
	 */
	public function __isset($name) {
		if (method_exists($this, ($method = 'isset_' . ucfirst($name))))
			return $this->$method();
		return isset($this->_data[$name]) ||
			isset($this->$name);
	}

	/** Setter of the class.
	 *
	 * @param string $name Name of the parameter to set.
	 * @param string $value Value of the parameter to set.
	 */
	public function __set($name, $value) {
		if (method_exists($this, ($method = 'set_' . ucfirst($name))))
			$this->$method($value);
		else if (property_exists($this, $name))
			$this->$name = $value;
		else
			$this->_data[$name] = $value;
	}

	/** Unset a parameter of the class.
	 *
	 * @param string $name Name of the parameter to unset..
	 */
	public function __unset($name) {
		if (method_exists($this, ($method='unset_' . ucfirst($name))))
			$this->$method();
		else if (property_exists($this, $name))
			unset($this->$name);
		else
			unset($this->_data[$name]);
	}
}
