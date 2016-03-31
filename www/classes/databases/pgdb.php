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

/** File pgdb.php.
 *
 * @category  Databases
 * @package	  Ritho-web\Classes\Databases
 * @since	  0.1
 * @license http://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License
 * @version GIT: <git_id>
 * @link http://ritho.net
 */

/** Postgresql database engine. */
class PgDB extends Database {

	/** Constructor of the class.
	 *
	 * @param string  $user User to authenticate to the DB server.
	 * @param string  $pass Password to authenticate to the DB server.
	 * @param string  $db   Database name.
	 * @param string  $host Host where the db is listening.
	 * @param integer $port Port number where the DB server is listening.
	 */
	public function __construct($user = 'root', $pass = '', $db = 'db',
	                            $host = 'localhost', $port = 5432) {
		parent::__construct($user, $pass, $db, $host, $port);
	}

	/** Disconnect the database engine.
	 *
	 * @return TRUE on success, FALSE on failure.
	 */
	public function close() {
		if ($this->connection != null)
			return pg_close($this->connection);
		return pg_close();
	}

	/** Connect to the database engine.
	 *
	 * @return TRUE on success, FALSE on failure.
	 */
	public function connect() {
		/* Close previous persistent connection. */
		$this->persistent = false;
		if ($this->connection = null)
			$this->close();

		/* XXX: Get charset from config. */
		$connString = 'host=' . $this->host . ' port=' . $this->port .
			' dbname=' . $this->db . ' user=' . $this->user . ' password=' .
			$this->password . " options='--client_encoding=UTF8'";
		$this->connection = pg_connect($connString);

		return (pg_connection_status($this->connection) === PGSQL_CONNECTION_OK);
	}

	/** Deletes records from a table specified by the keys and values in
	 *   assoc_array.
	 *
	 * @param string $tableName Name of the table from which to delete rows.
	 * @param array	 $assoc     An array whose keys are field names in the table
	 * table_name, and whose values are the values of those fields that are to
	 * be deleted.
	 * @return TRUE on success, FALSE on failure.
	 */
	public function delete($tableName, array $assoc = array()) {
		if ($tableName && is_string($tableName)) {
			$query = 'DELETE FROM ' . $tableName;
			if ($assoc && is_array($assoc)) {
				$query .= ' WHERE ' .
					array_reduce(array_keys($assoc),
								 function($result, $key) {
									 $result = (!$result || !is_string($result)) ?
										 '$key = ' . $assoc[$key] :
										 $result . ' AND ' . $key . ' = ' . $assoc[$key];
									 return $result;
								 });
			}

			$query .= ';';
			Log::i($this->escapeString($query));
			return (pg_query($this->connection,
			                 $this->escapeString($query)) !== false);
		}

		Log::e('Error deleting the rows.');
		return false;
	}

	/** Escape a string for insertion into the database.
	 *
	 * @param string $str String to escape.
	 * @return String escaped.
	 */
	public function escapeString($str) {
		return ($str && is_string($str))
			? pg_escapeString($this->connection, $str) :
			'';
	}

	/** Execute a query.
	 *
	 * @param string $query Query to execute in the DB.
	 * @return TRUE on success, FALSE on failure.
	 */
	public function exec($query) {
		return ($query && is_string($query)) ?
			(pg_query($this->connection, $this->escapeString($query)) !== false) :
			false;
	}

	/** Close a prepared statement.
	 *
	 * @param string $stmtname The name of the prepared statement to execute.
	 * @return TRUE on success, FALSE on failure.
	 */
	public function execClose($stmtname = null) {
		if ($stmtname && is_string($stmtname)) {
			unset($this->stmts[$stmtname]);
			return true;
		}

		return false;
	}

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
	public function execPrepared($stmtname, $params) {
		if ($stmtname && is_string($stmtname) && isset($this->stmts[$stmtname]) &&
			$this->stmts[$stmtname] !== false && isset($params)) {
			$this->result = (is_array($params)) ?
				pg_execute($dbconn, $stmtname, $params) :
				pg_execute($dbconn, $stmtname, array($params));

			return $this->result;
		}

		return false;
	}

	/** Get an array that contains all rows (records) in the result resource.
	 *
	 * @param resource $result Query result resource.
	 * @return Array with all rows in the result. Each row is an array of field
	 * values indexed by field name and by field number. FALSE if there are no
	 * rows in the result, or on any other error.
	 */
	public function fetchAll($result = null) {
		return ($result !== null) ?
			pg_fetch_all($result) :
			pg_fetch_all($this->result);
	}

	/** Fetch a row into a numbered array from a query result.
	 *
	 * @param resource $result     Result to get the row.
	 * @param integer  $resultType Parameter to control how the returned array is
	 *        indexed. result_type is a constant and can take the following values:
	 *        PGSQL_ASSOC, PGSQL_NUM and PGSQL_BOTH.
	 * @param integer  $row        Row to fetch.
	 * @return Array indexed numerically, associatively or both, FALSE on error.
	 * Each value in the array is represented as a string. Database NULL
	 * values are returned as NULL. Returns NULL if there are no more rows
	 * in resultset.
	 */
	public function fetchArray($result = null, $resultType = PGSQL_BOTH,
	                           $row = -1) {
		$res = $this->result;
		if ($result !== null)
			$res = $result;

		if ($res !== null)
			return ($row > -1) ?
				pg_fetch_array($res, $row, $resultType) :
				pg_fetch_array($res, null, $resultType);

		return false;
	}

	/** Fetch a row into an associative array from a query result.
	 *
	 * @param resource $result Result to get the row.
	 * @param integer  $row	   Row to fetch.
	 * @return Array indexed associatively, FALSE on error. Each value in the
	 * array is represented as a string. Database NULL values are returned as
	 * NULL. Returns NULL if there are no more rows in resultset.
	 */
	public function fetchAssoc($result = null, $row = -1) {
		return $this->fetchArray($result, PGSQL_ASSOC, $row);
	}

	/** Fetch an object with properties that correspond to the fetched row's field
	 *	names. It can optionally instantiate an object of a specific class, and
	 *	pass parameters to that class's constructor.
	 *
	 * @param resource $result    Result to get the row.
	 * @param string   $className Class name to store the row.
	 * @param array	   $params    Params to attach to the constructor of the
	 *		  object.
	 * @return Object fetched.
	 */
	public function fetchObject($result = null, $className = 'StdClass',
	                            array $params = array()) {
		$res = $this->result;
		if ($result !== null)
			$res = $result;

		if ($res !== null)
			return pg_fetch_object($res, null, $className, $params);

		return null;
	}

	/** Fetch a row into a numbered array from a query result.
	 *
	 * @param resource $result Result to get the row.
	 * @param integer  $row	   Row to fetch.
	 * @return Array indexed numerically, FALSE on error. Each value in the array
	 * is represented as a string. Database NULL values are returned as NULL.
	 */
	public function fetchRow($result = null, $row = -1) {
		return $this->fetchArray($result, PGSQL_NUM, $row);
	}

	/** Get the name of the field occupying the given field_number in the given
	 * result resource. Field numbering starts from 0.
	 *
	 * @param resource $result      Result to get the name of the column.
	 * @param integer  $fieldNumber Number of field to check.
	 * @return An string with the name of the field or NULL on failure.
	 */
	public function fieldName($result = null, $fieldNumber = -1) {
		$res = $this->result;
		if ($result !== null)
			$res = $result;

		if ($res !== null && is_integer($fieldNumber) && $fieldNumber > -1 &&
			$fieldNumber < pg_num_fields($res)) {
			$name = pg_field_name($res, $fieldNumber);
			return ($name !== false) ? $name : null;
		}

		return null;
	}

	/** Get a string containing the base type name of the given field_number in the
	 * given result resource.
	 *
	 * @param resource $result      Result resource to get the type of the field.
	 * @param integer  $fieldNumber Number of field to check.
	 * @return String with the type of object of the given field.
	 */
	public function fieldType($result = null, $fieldNumber = -1) {
		$res = $this->result;
		if ($result !== null)
			$res = $result;

		if ($res !== null && is_integer($fieldNumber) && $fieldNumber > -1 &&
			$fieldNumber < pg_num_fields($res)) {
			$name = pg_field_type($res, $fieldNumber);
			return ($name !== false) ? $name : null;
		}

		return null;
	}

	/** Free a query result.
	 *
	 * @param resource $result Result to free.
	 * @return TRUE on success, FALSE on failure.
	 */
	public function free($result = null) {
		return pg_free_result($result);
	}

	/** Inserts the values of assoc_array into the table specified by table_name.
	 *
	 * @param string $tableName Name of the table into which to insert rows.
	 * @param array	 $assoc     Array whose keys are field names in the table
	 *        tableName,
	 * and whose values are the values of those fields that are to be inserted.
	 * @return TRUE on success, FALSE on failure.
	 */
	public function insert($tableName, array $assoc = array()) {
		return (!$tableName || !is_string($tableName) ||
		        !$assoc || !is_array($assoc)) ?
		    false :
		    pg_insert($this->connection, $tableName, $assoc);
	}

	/** Get the number of fields (columns) in a result resource.
	 *
	 * @param resource $result Result to check.
	 * @return Number of columns of the result.
	 */
	public function numFields($result = null) {
		$res = $this->result;
		if ($result !== null)
			$res = $result;

		return ($res !== null) ? pg_num_fields($res) : 0;
	}

	/** Get the number of rows in a result resource..
	 *
	 * @param resource $result Result to check.
	 * @return Number of rows of the result.
	 */
	public function numRows($result = null) {
		$res = $this->result;
		if ($result !== null)
			$res = $result;

		return ($res !== null) ? pg_num_rows($res) : 0;
	}

	/** Persistent connection to the database engine.
	 *
	 * @return TRUE on success, FALSE on failure.
	 */
	public function pconnect() {
		$this->persistent = true;
		/* XXX: Get charset from config. */
		$connString = 'host=' . $this->host . ' port=' . $this->port .
			' dbname=' . $this->db . ' user=' . $this->user . ' password=' .
			$this->password . " options='--client_encoding=UTF8'";
		$this->connection = pg_pconnect($connString, PGSQL_CONNECT_FORCE_NEW);

		return (pg_connection_status($this->connection) === PGSQL_CONNECTION_OK);
	}

	/** Pings a database connection and tries to reconnect it if it is broken.
	 *
	 * @return TRUE on success, FALSE on failure.
	 */
	public function ping() {
		return pg_ping($this->connection);
	}

	/** Creates a prepared statement for later execution.
	 *
	 * @param string $stmtname The name to give the prepared statement. Must
	 * be unique per-connection.
	 * @param string $query	   The parameterized SQL statement. Must contain only
	 * a single statement. If any parameters are used, they are referred to as $1,
	 * $2, etc.
	 * @return TRUE on success, FALSE on failure.
	 */
	public function prepare($stmtname, $query) {
		/* Prepare a query for execution. */
		if ($stmtname && is_string($stmtname) && $query && is_string($query)) {
			$this->stmts[$stmtname] = pg_prepare($dbconn, $stmtname, $query);
			return $this->stmts[$stmtname] !== false;
		}

		return false;
	}

	/** Execute a query.
	 *
	 * @param string $query Query to execute in the DB.
	 * @return Query result resource on success, FALSE on failure.
	 */
	public function query($query) {
		if ($query && is_string($query)) {
			$this->result = pg_query($this->connection, $query);
			return $this->result;
		}

		return false;
	}

	/** Select records specified by assoc_array which has field=>value.
	 *
	 * @param string $tableName Name of the table from which to select rows.
	 * @param array	 $cols      Array with the names of the columns to return by
	 *        the query.
	 * @param array	 $where     Array whose keys are columns in the table
	 *        tableName, and whose values are the conditions that a row must meet
	 *        to be retrieved.
	 * @return Query result resource on success, FALSE on failure.
	 */
	public function select($tableName, array $cols = array(),
	                       array $where = array()) {
		if ($tableName && is_string($tableName)) {
			/* We build the query. */
			$query = 'SELECT ';
			if ($cols && is_array($cols)) {
				foreach($cols as $key => $value)
					$query .= $value . ', ';
				$query = substr($query, 0, -2);
			} else if ($cols && is_string($cols)) {
				$query .= $cols;
			} else {
				$query .= '*';
			}

			$query .= ' FROM ' . $tableName;
			if ($where && is_array($where)) {
				$query .= ' WHERE';
				foreach ($where as $key => $value)
					$query .= ' ' . $key . ' = ' . $value . ' AND ';
				$query = substr($query, 0, -5);
			}
		}

		if ($query) {
			/* We make the query and return the result. */
			$this->result = pg_query($this->connection, $query);
			return $this->result;
		}

		return false;
	}
}
