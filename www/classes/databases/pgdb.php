<?php
/* Copyright (c) 2011-2012 Ritho-web team (look at AUTHORS file)

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

/* Basic database engine.

   @author Ritho-web team
   @copyright Copyright (c) 2011-2012 Ritho-web team (look at AUTHORS file)
*/
class PgDB extends DB {
    private $stmt_params = array();

    /* Constructor of the class.

       @param $user (string): User to authenticate to the DB server.
       @param $pass (string): Password to authenticate to the DB server.
       @param $host (string): Host where the db is listening.
       @param $db (string): Database name.
       @param $port (int): Port number where the DB server is listening.
    */
    public function __construct($user = 'root', $pass = '', $host = 'localhost',
                                $db = 'db', $port = 5432) {
        parent::__construct($user, $pass, $host, $db, $port);
    }

    /* Disconnect the database engine.

       @return TRUE on success, FALSE on failure.
    */
    public function close() {
        if($this->getConnection() != null)
            return pg_close($this->getConnection());
        return pg_close();
    }

    /* Connect to the database engine.

       @return TRUE on success, FALSE on failure.
    */
    public function connect() {
        /* Close previous persistent connection. */
        $this->setPersistent(false);
        if($this->setConnection(null))
            $this->close();

        // Get charset from config.
        $connection_string = "host=" . $this->getHost() . " port=" . $this->getPort() .
		" dbname=" . $this->getDB() . " user=" . $this->getUser() . " password=" .
		$this->getPassword() . " options='--client_encoding=UTF8'";
        $this->setConnection(pg_connect($connection_string));

        return (pg_connection_status($this->getConnection()) === PGSQL_CONNECTION_OK);
    }

    /* Deletes records from a table specified by the keys and values in assoc_array.

       @param $table_name (string): Name of the table from which to delete rows.
       @param $assoc (array): An array whose keys are field names in the table table_name,
	   and whose values are the values of those fields that are to
	   be deleted.
       @return TRUE on success, FALSE on failure.
    */
    public function delete($table_name, $assoc = array()) {
        if($table_name && is_string($table_name)) {
            $query = "DELETE FROM $table_name";
            if($assoc && is_array($assoc)) {
                $query .= " WHERE " . array_reduce(array_keys($assoc), function($result, $key) {
                        $result = (!$result || !is_string($result)) ?
						"$key = " . $assoc[$key] :
						$result . " AND $key = " . $assoc[$key];
                        return $result;
                    });
            }
            $query .= ";";

            Log::i($this->escape_string($query));
            return (pg_query($this->getConnection(), $this->escape_string($query)) !== false);
        }

        Log::e("Error deleting the rows.");
        return false;
    }

    /* Escape a string for insertion into the database.

       @param $str (string): String to escape.
       @return String escaped.
    */
    public function escape_string($str) {
        return ($str && is_string($str)) ? pg_escape_string($this->getConnection(), $str) : "";
    }

    /* Execute a query.

       @param $query (string): Query to execute in the DB.
       @return TRUE on success, FALSE on failure.
    */
    public function exec($query) {
        return ($query && is_string($query)) ? (pg_query($this->getConnection(), $this->escape_string($query)) !== false) : false;
    }

    /* Close a prepared statement.

       @param $stmtname (string): The name of the prepared statement to execute.
       @return TRUE on success, FALSE on failure.
    */
    public function exec_close($stmtname = null) {
		if($stmtname && is_string($stmtname)) {
			unset($this->stmts[$stmtname]);
			return true;
		}

		return false;
    }

    /* Sends a request to execute a prepared statement without waiting for the result(s).

       @param $stmtname (string): The name of the prepared statement to execute.
       @param $params (string | int | double | array): Array of parameter values
	   to substitute for the placeholders in the original prepared query
	   string. The number of elements in the array must match the number of
	   placeholders.
       @return Query result resource on success, FALSE on failure.
    */
    public function exec_prepared($stmtname, $params) {
        if($stmtname && is_string($stmtname) && isset($this->stmts[$stmtname]) && $this->stmts[$stmtname] !== false && isset($params)) {
			$this->result = (is_array($params)) ? pg_execute($dbconn, $stmtname, $params) : pg_execute($dbconn, $stmtname, array($params));

			return $this->result;
		}

		return false;
    }

    /* Get an array that contains all rows (records) in the result resource.

       @param $result (resource): Query result resource.
       @return Array with all rows in the result. Each row is an array of field
       values indexed by field name and by field number. FALSE if there are no
       rows in the result, or on any other error.
    */
    public function fetch_all($result = null) {
		if($result !== null)
			return pg_fetch_all($result);
		return pg_fetch_all($this->result);
    }

    /* Fetch a row into a numbered array from a query result.

       @param $result (resource): Result to get the row.
       @param $result_type (int): Parameter to control how the returned array is
       indexed. result_type is a constant and can take the following values:
       PGSQL_ASSOC, PGSQL_NUM and PGSQL_BOTH.
       @param $row (int): Row to fetch.
       @return Array indexed numerically, associatively or both, FALSE on error.
	   Each value in the array is represented as a string. Database NULL
	   values are returned as NULL. Returns NULL if there are no more rows
	   in resultset.
    */
    public function fetch_array($result = null, $result_type = PGSQL_BOTH, $row = -1) {
		$res = $this->result;
		if($result !== null)
			$res = $result;

		if($res !== null)
			return ($row > -1) ? pg_fetch_array($res, $row, $result_type) : pg_fetch_array($res, NULL, $result_type);

		return false;
    }

    /* Fetch a row into an associative array from a query result.

       @param $result (resource): Result to get the row.
       @param $row (int): Row to fetch.
       @return Array indexed associatively, FALSE on error. Each value in the
       array is represented as a string. Database NULL values are returned as
       NULL. Returns NULL if there are no more rows in resultset.
    */
    public function fetch_assoc($result = null, $row = -1) {
		return $this->fetch_array($result, PGSQL_ASSOC, $row);
    }

    /* Fetch an object with properties that correspond to the fetched row's field
       names. It can optionally instantiate an object of a specific class, and
       pass parameters to that class's constructor.

       @param $result (resource): Result to get the row.
       @param $class_name (string): Class name to store the row.
       @param $params (array): Params to attach to the constructor of the object.
       @return Object fetched.
    */
    public function fetch_object($result = null, $class_name = 'StdClass',
                                 $params = array()) {
		$res = $this->result;
		if($result !== null)
			$res = $result;

		if($res !== null)
            return fetch_object($res, null, $class_name, $params);

        return null;
    }

    /* Fetch a row into a numbered array from a query result.

       @param $result (resource): Result to get the row.
       @param $row (int): Row to fetch.
       @return Array indexed numerically, FALSE on error. Each value in the array
       is represented as a string. Database NULL values are returned as NULL.
    */
    public function fetch_row($result = null, $row = -1) {
		return $this->fetch_array($result, PGSQL_NUM, $row);
    }

    /* Get the name of the field occupying the given field_number in the given
       result resource. Field numbering starts from 0.

       @param $result (resource): Result to get the name of the column.
       @param $field_number (integer): Number of field to check.
       @return An string with the name of the field or NULL on failure.
    */
    public function field_name($result = null, $field_number = -1) {
		$res = $this->result;
		if($result !== null)
			$res = $result;

		if($res !== null && is_integer($field_number) && $field_number > -1 && $field_number < pg_num_fields($res)) {
			$name = pg_field_name($res , $field_number);
			return ($name !== false) ? $name : null;
		}

		return null;
    }

    /* Get a string containing the base type name of the given field_number in the
       given result resource.

       @param $result (resource): Result resource to get the type of the field.
       @param $field_number (int): Number of field to check.
       @return String with the type of object of the given field.
    */
    public function field_type($result = null, $field_number = -1) {
		$res = $this->result;
		if($result !== null)
			$res = $result;

		if($res !== null && is_integer($field_number) && $field_number > -1 && $field_number < pg_num_fields($res)) {
			$name = pg_field_type($res , $field_number);
			return ($name !== false) ? $name : null;
		}

		return null;
    }

    /* Free a query result.

       @param $result (resource): Result to free.
       @return TRUE on success, FALSE on failure.
    */
    public function free($result = null) {
		return pg_free_result($result);
    }

    /* Inserts the values of assoc_array into the table specified by table_name.

       @param $table_name (string): Name of the table into which to insert rows.
       @param $assoc (array): Array whose keys are field names in the table table_name,
       and whose values are the values of those fields that are to be inserted.
       @return TRUE on success, FALSE on failure.
    */
    public function insert($table_name, $assoc = array()) {
		if(!$table_name || !is_string($table_name) || !$assoc || !is_array($assoc))
			return false;
		return pg_insert($this->getConnection(), $table_name, $assoc);
    }

    /* Get the number of fields (columns) in a result resource.

       @param $result (resource): Result to check.
       @return Number of columns of the result.
    */
    public function num_fields($result = null) {
		$res = $this->result;
		if($result !== null)
			$res = $result;

		if($res !== null)
			return pg_num_fields($res);
		return 0;
    }

    /* Get the number of rows in a result resource..

       @param $result (resource): Result to check.
       @return Number of rows of the result.
    */
    public function num_rows($result = null) {
		$res = $this->result;
		if($result !== null)
			$res = $result;

		if($res !== null)
			return pg_num_rows($res);
		return 0;
    }

    /* Persistent connection to the database engine.

       @return TRUE on success, FALSE on failure.
    */
    public function pconnect() {
        $this->setPersistent(true);
        // XXX: Get charset from config.
        $connection_string = "host=" . $this->getHost() . " port=" . $this->getPort() .
		" dbname=" . $this->getDB() . " user=" . $this->getUser() . " password=" .
		$this->getPassword() . " options='--client_encoding=UTF8'";
        $this->setConnection(pg_pconnect($connection_string, PGSQL_CONNECT_FORCE_NEW));

        return (pg_connection_status($this->getConnection()) === PGSQL_CONNECTION_OK);
    }

    /* Pings a database connection and tries to reconnect it if it is broken.

       @return TRUE on success, FALSE on failure.
    */
    public function ping() {
		return pg_ping($this->getConnection());
    }

    /* Creates a prepared statement for later execution.

       @param $stmtname (string): The name to give the prepared statement. Must
       be unique per-connection.
       @param $query (string): The parameterized SQL statement. Must contain only
       a single statement. If any parameters are used, they are referred to as $1,
       $2, etc.
       @return TRUE on success, FALSE on failure.
    */
    public function prepare($stmtname, $query) {
		// Prepare a query for execution
		if($stmtname && is_string($stmtname) && $query && is_string($query)) {
			$this->stmts[$stmtname] = pg_prepare($dbconn, $stmtname, $query);
			return $this->stmts[$stmtname] !== false;
		}

		return false;
    }

    /* Execute a query.

       @param $query (string): Query to execute in the DB.
       @return Query result resource on success, FALSE on failure.
    */
    public function query($query) {
        if($query && is_string($query)) {
            $this->result = pg_query($this->getConnection(), $query);

            return $this->result;
        }

        return false;
    }

    /* Select records specified by assoc_array which has field=>value.

       @param $table_name (string): Name of the table from which to select rows.
       @param $cols (array): Array with the names of the columns to return by the query.
       @param $where (array): Array whose keys are columns in the table table_name, and
	   whose values are the conditions that a row must meet to be retrieved.
       @return Query result resource on success, FALSE on failure.
    */
    public function select($table_name, $cols = array(), $where = array()) {
        if($table_name && is_string($table_name)) {
            /* We build the query. */
            $query = "SELECT ";
            if($cols && is_array($cols)) {
                foreach($cols as $key => $value)
				$query .= $value . ", ";
                $query = substr($query, 0, -2);
			} else if($cols && is_string($cols)) {
                $query .= $cols;
            } else {
                $query .= "*";
            }

            $query .= " FROM " . $table_name;
            if($where && is_array($where)) {
                $query .= " WHERE";
                foreach($where as $key => $value)
				$query .= " " . $key . " = " . $value . " AND ";
                $query = substr($query, 0, -5);
            }
        }

        if($query) {
            /* We make the query and return the result. */
            $this->result = pg_query($this->getConnection(), $query);

            return $result;
        }

        return false;
    }
}
