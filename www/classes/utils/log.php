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

/** File log.php.
 *
 * @category  Utils
 * @package	  Ritho-web\Classes\Utils
 * @since	  0.1
 * @license	  http://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License
 * @version	  GIT: <git_id>
 * @link http://ritho.net
 */

/** Class to log messages. */
class Log extends Base {

	/** @var $_instances Array of Log instances (one per logging file). */
	private static $_instances = array();

	/** Get an instance of the Log class.
	 *
	 * @param string $file Logging file name.
	 * @return Log instance.
	 */
	public static function instance($file = null) {
		if ($file && is_string($file)) {
			/* If the instance of this file doesn't exists we create it. */
			if (!in_array($file, self::$instances))
				self::$_instances[$file] = new self($file);
			return self::$_instances[$file];
		} else {
			  /* If we don't have a default instance we create it. */
			if (count(self::$_instances) === 0)
				self::$_instances['default'] = new self();
			return self::$_instances['default'];
		}
	}

	/** Constructor of the class.
	 *
	 * @param string $file Logging file name.
	 * @throws Exception When there's a permission problem we throw an exception.
	 */
	public function __construct($file = null) {
		parent::__construct();

		if (!file_exists($this->configs['log_path']) &&
			!mkdir($this->configs['log_path'], 0755, true)) {
			throw new Exception('Sorry, I couldn\'t create the directory ' .
			    $this->configs['log_path'] . ' for logging.');
		}

		$this->logFilename = $this->configs['log_path'] . '/' .
			$this->configs['log_file'];
		if ($file && is_string($file)) {
			$this->logFilename = $this->configs['log_path'] . '/';
			if ($this->configs['log_file_prefix'] &&
				is_string($configs['log_file_prefix']))
				$this->logFilename .= $this->configs['log_file_prefix'];
			$this->logFilename .= $file . '.log';
		}

		if (file_exists($this->logFilename) && !is_writable($this->logFilename))
			throw new Exception('Sorry, the log file ' . $this->logFilename .
								' is not writable.');

		if (!($this->logFile = fopen($this->logFilename, 'a')))
			throw new Exception('Sorry, I could\'t open the log file ' .
								$this->logFilename . ' for write.');

		$this->logStatus = true;
	}

	/** Class destructor */
	public function __destruct() {
		if ($this->logFile)
			fclose($this->logFile);
	}

	/** Static method to print an info message to the Log file.
	 *
	 * @param string $msg Message to print.
	 * @return void
	 */
	public function i($msg = null) {
		if ($msg && is_string($msg))
			$this->_writeMsg('INFO', $msg);
	}

	/** Static method to print a debug message to the Log file.
	 *
	 * @param string $msg Message to print.
	 * @return void
	 */
	public function d($msg = null) {
		if ($msg && is_string($msg))
			$this->_writeMsg('DEBUG', $msg);
	}

	/** Static method to print an error message to the Log file.
	 *
	 * @param string $msg Message to print.
	 * @return void
	 */
	public function e($msg) {
		if ($msg && is_string($msg))
			$this->_writeMsg('ERROR', $msg);
	}

	/** Private function to write a message to the log file.
	 *
	 * @param string $logLevel Log level of the message.
	 * @param string $msg      Message to print.
	 * @throws Exception Throw an exception when the message can't be written.
	 * @return void
	 */
	private function _writeMsg($logLevel, $msg) {
		if ($this->logStatus) {
			$access = date('Y/m/d H:i:s');
			if (fwrite($this->logFile, '[' . $logLevel . '] ' . $access . ': ' .
					   $msg) === false)
				throw new Exception('Error writing ' . $msg . ' into ' .
									$this->logFilename);
		}
	}
}
