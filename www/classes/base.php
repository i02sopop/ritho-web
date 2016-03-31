<?php
/* Copyright (c) 2011-2016 Ritho-web team (see AUTHORS)
 *
 * This file is part of ritho-web.
 *
 * ritho-web is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * ritho-web is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	 See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with ritho-web. If not, see <http://www.gnu.org/licenses/>.
 */

/** File base.php.
 *
 * @category  General
 * @package	  Ritho-web\Classes
 * @since	  0.1
 * @license	  http://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License
 * @version	  GIT: <git_id>
 * @link http://ritho.net
 */

/** Base class with some default methods and vars in common to all the app
 *  classes. */
abstract class Base {

	/** @var $data Local and global data. */
	private $_data = array();

	/** Constructor of the class. */
	public function __construct() {
		/* Load all configs. */
		$this->configs = array();
		foreach ($GLOBALS['configs'] as $key => $value)
		    $this->configs[$key] = $value;

		$this->db = Database::getDbConnection();
	}

	/** Getter of the class.
	 *
	 * @param string $name Name of the parameter to get.
	 * @return Value of the variable.
	 */
	public function &__get($name) {
		if (method_exists($this, ($method = 'get' . ucfirst($name))))
			return $this->$method();
		else if ($this->$name !== null)
			return $this->$name;
		else if (array_key_exists($name, $this->_data))
			return $this->_data[$name];

		$trace = debug_backtrace();
		trigger_error('Undefined property via __get(): ' . $name . ' in ' .
			$trace[0]['file'] . ' on line ' . $trace[0]['line'], E_USER_NOTICE);
		return $trace;
	}

	/** Check if a parameter is set in the class.
	 *
	 * @param string $name Name of the parameter to check.
	 * @return boolean True if the variable $name is set and false otherwise.
	 */
	public function __isset($name) {
		if (method_exists($this, ($method = 'isset' . ucfirst($name))))
			return $this->$method();
		return isset($this->_data[$name]) || isset($this->$name);
	}

	/** Setter of the class.
	 *
	 * @param string $name  Name of the parameter to set.
	 * @param string $value Value of the parameter to set.
	 * @return void
	 */
	public function __set($name, $value) {
		if (method_exists($this, ($method = 'set' . ucfirst($name))))
			$this->$method($value);
		else if (isset($this->$name))
			$this->$name = $value;
		else
			$this->_data[$name] = $value;
	}

	/** Unset a parameter of the class.
	 *
	 * @param string $name Name of the parameter to unset.
	 * @return void
	 */
	public function __unset($name) {
		if (method_exists($this, ($method = 'unset' . ucfirst($name))))
			$this->$method();
		else if (isset($this->$name))
			unset($this->$name);
		else
			unset($this->_data[$name]);
	}

	/** Open a connection with the database. */
	public function connectDB() {
		if (!isset($this->db)) {
			$engine = "PgDB";
			switch ($this->configs['db_engine']) {
				case "mysql":
					$engine = "MyDB";
					break;
				case "postgresql":
					$engine = "PgDB";
			}

			$this->db = new $engine(
				$this->configs['db_user'], $this->configs['db_password'],
				$this->configs['db_host'], $this->configs['db_name'],
				$this->configs['db_port']
			);
		}

		$this->db->pconnect();
	}
}
