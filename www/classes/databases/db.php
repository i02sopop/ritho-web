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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	 See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with ritho-web. If not, see <http://www.gnu.org/licenses/>.
 */

/** File db.php.
 *
 * @copyright 2011-2015 Ritho-web project (see AUTHORS).
 * @license	  http://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License
 * @version	  GIT: <git_id>
 * @link http://ritho.net
 */

/** Global constant definitions. */
define('SQL_ASSOC', 0);
define('SQL_NUM', 1);
define('SQL_BOTH', 2);

/** Basic database engine.
 *
 * @copyright Copyright (c) 2011-2015 Ritho-web team (see AUTHORS)
 * @category  Databases
 * @package	  Ritho-web\Classes\Databases
 * @since	  0.1
 */
abstract class DB extends Base {

	/** Function to get a database connection depending of the db engine used.
	 *
	 * @return Object Database connection.
	 */
	public static function getDbConnection() {
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


	/** Constructor of the class.
	 *
	 * @param string  $newUser     User to authenticate to the DB server.
	 * @param string  $newPassword Password to authenticate to the DB server.
	 * @param string  $newDB       Database name.
	 * @param string  $newHost     Host where the db is listening.
	 * @param integer $newPort     Port number where the DB server is listening.
	 */
	public function __construct($newUser = 'root', $newPassword = '',
								$newDB = 'ritho', $newHost = 'localhost',
								$newPort = -1) {
		$this->user = ($newUser !== null) ? $newUser : 'root';
		$this->password = ($newPassword !== null) ? $newPassword : '';
		$this->host = ($newHost !== null) ? $newHost : 'localhost';
		$this->db = ($newDB !== null) ? $newDB : 'ritho';
		$this->port = $newPort;
		$this->connection = null;
		$this->isPersistent = false;
		$this->result = null;
		$this->stmts = array();
	}

	/** Disconnect the database engine.
	 *
	 * @return TRUE on success, FALSE on failure.
	 */
	abstract public function close();

	/** Connect to the database engine.
	 *
	 * @return TRUE on success, FALSE on failure.
	 */
	abstract public function connect();

	/** Deletes records from a table specified by the keys and values in
	 * assoc_array.
	 *
	 * @param string $tableName Name of the table from which to delete rows.
	 * @param array  $assoc     An array whose keys are field names in the table
	 * table_name, and whose values are the values of those fields that are to
	 * be deleted.
	 * @return TRUE on success, FALSE on failure.
	 */
	abstract public function delete($tableName, array $assoc = array());

	/** Escape a string for insertion into the database.
	 *
	 * @param string $str String to escape.
	 * @return String escaped.
	 */
	abstract public function escapeString($str);

	/** Execute a query.
	 *
	 * @param string $query Query to execute in the DB.
	 * @return TRUE on success, FALSE on failure.
	 */
	abstract public function exec($query);

	/** Close a prepared statement.
	 *
	 * @param string $stmtname The name of the prepared statement to execute.
	 * @return TRUE on success, FALSE on failure.
	 */
	abstract public function execClose($stmtname = null);

	/** Sends a request to execute a prepared statement without waiting for the
	 *  result(s).
	 *
	 * @param string                     $stmtname The name of the prepared
	 *        statement to execute.
	 * @param string|integer|float|array $params   Array of parameter values
	 *        to substitute for the placeholders in the original prepared query
	 *        string. The number of elements in the array must match the number
	 *        of placeholders.
	 * @return Query result resource on success, FALSE on failure.
	 */
	abstract public function execPrepared($stmtname, $params);

	/** Get an array that contains all rows (records) in the result resource.
	 *
	 * @param resource $result Query result resource.
	 * @return Array with all rows in the result. Each row is an array of field
	 * values indexed by field name and by field number. FALSE if there are no
	 * rows in the result, or on any other error.
	 */
	abstract public function fetchAll($result = null);

	/** Fetch a row into a numbered array from a query result.
	 *
	 * @param resource $result     Result to get the row.
	 * @param integer  $resultType Parameter to control how the returned array is
	 *        indexed. result_type is a constant and can take the following values:
	 *        SQL_ASSOC, SQL_NUM and SQL_BOTH.
	 * @param integer  $row        Row to fetch.
	 * @return Array indexed numerically, associatively or both, FALSE on error.
	 * Each value in the array is represented as a string. Database NULL values
	 * are returned as NULL.
	 */
	abstract public function fetchArray($result = null, $resultType = 0,
	                                    $row = -1);

	/** Fetch a row into an associative array from a query result.
	 *
	 * @param resource $result Result to get the row.
	 * @param integer  $row    Row to fetch.
	 * @return Array indexed associatively, FALSE on error. Each value in the
	 * array is represented as a string. Database NULL values are returned as
	 * NULL. Returns NULL if there are no more rows in resultset.
	 */
	abstract public function fetchAssoc($result = null, $row = -1);

	/** Fetch an object with properties that correspond to the fetched row's field
	 * names. It can optionally instantiate an object of a specific class, and
	 * pass parameters to that class's constructor.
	 *
	 * @param resource $result    Result to get the row.
	 * @param string   $className Class name to store the row.
	 * @param array    $params    Params to attach to the constructor of the
	 *        object.
	 * @return Object fetched.
	 */
	abstract public function fetchObject($result = null, $className = 'StdClass',
										 array $params = array());

	/** Fetch a row into a numbered array from a query result.
	 *
	 * @param resource $result Result to get the row.
	 * @param integer  $row    Row to fetch.
	 * @return Array indexed numerically, FALSE on error. Each value in the array
	 * is represented as a string. Database NULL values are returned as NULL.
	 */
	abstract public function fetchRow($result = null, $row = -1);

	/** Get the name of the field occupying the given field_number in the given
	 * result resource. Field numbering starts from 0.
	 *
	 * @param resource $result      Result to get the name of the column.
	 * @param integer  $fieldNumber Number of field to check.
	 * @return An string with the name of the field.
	 */
	abstract public function fieldName($result = null, $fieldNumber = -1);

	/** Get a string containing the base type name of the given field_number in the
	 * given result resource.
	 *
	 * @param resource $result      Result resource to get the type of the field.
	 * @param integer  $fieldNumber Number of field to check.
	 * @return String with the type of object of the given field.
	 */
	abstract public function fieldType($result = null, $fieldNumber = -1);

	/** Free a query result.
	 *
	 * @param resource $result Result to free.
	 * @return TRUE on success, FALSE on failure.
	 */
	abstract public function free($result = null);

	/** Inserts the values of assoc_array into the table specified by table_name.
	 *
	 * @param string $tableName Name of the table into which to insert rows.
	 * @param array  $assoc     Array whose keys are field names in the table
	 *        tableName,
	 * and whose values are the values of those fields that are to be inserted.
	 * @return TRUE on success, FALSE on failure.
	 */
	abstract public function insert($tableName, array $assoc = array());

	/** Get the number of fields (columns) in a result resource.
	 *
	 * @param resource $result Result to check.
	 * @return Number of columns of the result.
	 */
	abstract public function numFields($result = null);

	/** Get the number of rows in a result resource..
	 *
	 * @param resource $result Result to check.
	 * @return Number of rows of the result.
	 */
	abstract public function numRows($result = null);

	/** Persistent connection to the database engine.
	 *
	 * @return TRUE on success, FALSE on failure.
	 */
	abstract public function pconnect();

	/** Pings a database connection and tries to reconnect it if it is broken.
	 *
	 * @return TRUE on success, FALSE on failure.
	 */
	abstract public function ping();

	/** Creates a prepared statement for later execution.
	 *
	 * @param string $stmtname The name to give the prepared statement. Must
	 * be unique per-connection.
	 * @param string $query    The parameterized SQL statement. Must contain only
	 * a single statement. If any parameters are used, they are referred to as $1,
	 * $2, etc.
	 * @return TRUE on success, FALSE on failure.
	 */
	abstract public function prepare($stmtname, $query);

	/** Execute a query.
	 *
	 * @param string $query Query to execute in the DB.
	 * @return Query result resource on success, FALSE on failure.
	 */
	abstract public function query($query);

	/** Select records specified by assoc_array which has field=>value.
	 *
	 * @param string $tableName Name of the table from which to select rows.
	 * @param array  $cols      Array with the names of the columns to return by
	 *        the query.
	 * @param array  $where     Array whose keys are columns in the table
	 *        tableName, and whose values are the conditions that a row must meet
	 *        to be retrieved.
	 * @return Query result resource on success, FALSE on failure.
	 */
	abstract public function select($tableName, array $cols = array(),
	                                array $where = array());
}
