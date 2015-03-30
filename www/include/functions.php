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
		throw new MissingException('Impossible to load ' . $name . '.');
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

/** Function to get the unix time in microseconds.
 *
 * @copyright Copyright (c) 2011-2015 Ritho-web team (see AUTHORS)
 * @return int Time in microseconds.
 */
function getTime() {
	$time = explode(' ', microtime());
	$time = $time[1] + $time[0];

	return $time;
}

/** Function to load the relation between paths and controllers from db.
 *
 * @author Ritho-web team
 * @copyright 2015 Ritho-web team (see AUTHORS)
 * @throws Exception Throws an exception when it can't connect to the database.
 * @return void
 */
function loadPaths() {
	if (empty($GLOBALS['paths'])) {
		/* Open a connection with the database. */
		$dbConn = getDbConnection();
		$dbConn->pconnect();
		$res = $dbConn->select('paths');
		if ($res === false)
			throw new Exception('Error loading the paths fromm the database.');

		$rows = $dbConn->fetch_all($res);
		if ($rows && count($rows) > 0) {
			foreach ($rows as $row) {
				$GLOBALS['paths'][$row['c_path']]['controller'] = $row['controller'];
				$GLOBALS['paths'][$row['c_path']]['param'] = $row['param'];
			}
		}
	}
}

/** Function to clean the path of spaces (trim) and the final slash (/).
 *
 * @copyright 2015 Ritho-web team (see AUTHORS)
 * @param string $path Path to clean.
 * @return string Path cleared.
 */
function cleanPath($path) {
	if (!is_string($path))
		$path = '';

	$path = trim(urldecode($path));
	if (!empty($path) && strrpos($path, '/') == (strlen($path) - 1))
		$path = substr($path, 0, strlen($path) - 1);

	return $path;
}

/** Function to create the controller related with an entry path.
 *
 * @copyright 2015 Ritho-web team (see AUTHORS)
 * @param string $path Path requested.
 * @return Controller New object of the controller related with the path.
 */
function getController($path) {
	loadPaths();
	$controllerName = '';
	$firstParam = '';
	$params = null;
	$path = cleanPath($path);
	if (empty($path)) {
		$controllerName = 'CIndex';
		$firstParam = '';
	} else if (array_key_exists($path, $GLOBALS['paths'])) {
		$controllerName = $GLOBALS['paths'][$path]['controller'];
		$firstParam = $GLOBALS['paths'][$path]['param'];
	} else {
		$pathKeys = array_keys($GLOBALS['paths']);
	    $pathSelected = '';
		foreach ($pathKeys as $curPath) {
			if (strlen($curPath) > strlen($pathSelected)) {
				$pattern = preg_replace('/\//', '\\/', preg_quote($curPath));
				if (preg_match('/' . $pattern . '/', $path) === 1)
				    $pathSelected = $curPath;
			}
		}

		$controllerName = $GLOBALS['paths'][$pathSelected]['controller'];
		if ($controllerName === 'CIndex')
			$controllerName = '';
		$firstParam = $GLOBALS['paths'][$pathSelected]['param'];
		$params = preg_split('/\//', substr($path, strlen($pathSelected)));
		if (!empty($params[0]))
			$controllerName = '';
		array_shift($params);
	}

	if (empty($controllerName))
		return new C404($firstParam, array('path' => substr($path, 1)));
	return new $controllerName($firstParam, $params);
}

/** Function to get a database connection depending of the db engine used.
 *
 * @return Object Database connection.
 */
function getDbConnection() {
	$dbConn = null;
	switch ($GLOBALS['configs']['db_engine']) {
		case 'postgresql':
			$dbConn = new PgDB($GLOBALS['configs']['db_user'],
			                 $GLOBALS['configs']['db_password'],
			                 $GLOBALS['configs']['db_host'],
			                 $GLOBALS['configs']['db_name'],
			                 $GLOBALS['configs']['db_port']);
			break;
		case 'mysql':
			$dbConn = new MyDB($GLOBALS['configs']['db_user'],
			                 $GLOBALS['configs']['db_password'],
			                 $GLOBALS['configs']['db_host'],
			                 $GLOBALS['configs']['db_name'],
			                 $GLOBALS['configs']['db_port']);
			break;
		default:
			$dbConn = new DB($GLOBALS['configs']['db_user'],
			                 $GLOBALS['configs']['db_password'],
			                 $GLOBALS['configs']['db_host'],
			                 $GLOBALS['configs']['db_name'],
			                 $GLOBALS['configs']['db_port']);
			break;
	}

	return $dbConn;
}
