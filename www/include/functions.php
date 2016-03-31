<?php
/* Copyright (c) 2011-2015 Ritho-web team (see AUTHORS)
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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with Foobar. If not, see <http://www.gnu.org/licenses/>.
 */

require_once $GLOBALS['configs']['exceptions_path'] . '/missingexception' .
$GLOBALS['configs']['class_ext'];

/* Class autoload function.
 *
 * @copyright Copyright (c) 2011-2015 Ritho-web team (see AUTHORS)
 * @parameter name (string): The class name.
 * @return void
 */
function __autoload($name) {
	$configs = $GLOBALS['configs'];
	$fileExists = false;
	$className = strtolower($name) . $configs['class_ext'];
	$subdirs = array($configs['class_path']);
	while (!$fileExists && $dir = each($subdirs)) {
		$handle = opendir($dir['value']);
		if ($handle === false)
			throw new MissingException('Impossible to load ' . $name . '.');

		while ($entry = readdir($handle)) {
			if (strpos($entry, $className) === 0) {
				$fileExists = true;
			    include_once $dir['value'] . '/' . $className;
				break;
			} else if (strpos($entry, '.') !== 0 &&
				is_dir($dir['value'] . '/' . $entry)) {
				$subdirs[] = $dir['value'] . '/' . $entry;
			}
		}

		closedir($handle);
	}

	if (!$fileExists)
		log_e('Couldn\'t load the class ' . $name . '.');
}

/** Function to log info messages into the logging file.
 *
 * @copyright Copyright (c) 2011-2015 Ritho-web team (see AUTHORS)
 * @param string $msg  Message to log.
 * @param string $file The log filename.
 * @return void
 */
function log_i($msg = '', $file = null) {
	Log::instance($file)->i($msg);
}

/** Function to log debug messages into the logging file.
 *
 * @copyright Copyright (c) 2011-2015 Ritho-web team (see AUTHORS)
 * @param string $msg  Message to log.
 * @param string $file The log filename.
 * @return void
 */
function log_d($msg = '', $file = null) {
	Log::instance($file)->d($msg);
}

/** Function to log error messages into the logging file.
 *
 * @copyright Copyright (c) 2011-2015 Ritho-web team (see AUTHORS)
 * @param string $msg  Message to log.
 * @param string $file The log filename.
 * @return void
 */
function log_e($msg = '', $file = null) {
	Log::instance($file)->e($msg);
}
