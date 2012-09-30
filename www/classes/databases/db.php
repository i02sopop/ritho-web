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

/* Global constant definitions. */
define('SQL_ASSOC', 0);
define('SQL_NUM', 1);
define('SQL_BOTH', 2);

/* Basic database engine.

   @author Ritho-web team
   @copyright Copyright (c) 2011-2012 Ritho-web team (look at AUTHORS file)
*/
abstract class DB extends Base {
    private $user = 'root';
    private $password = '';
    private $host = 'localhost';
    private $db = 'ritho';
    private $port = -1;
    private $connection = null;
    private $isPersistent = false;
    private $result = null;
    private $stmts = array();

    /* Constructor of the class.

       @param $newUser (string): User to authenticate to the DB server.
       @param $newPassword (string): Password to authenticate to the DB server.
       @param $newHost (string): Host where the db is listening.
       @param $newDB (string): Database name.
       @param $newPort (int): Port number where the DB server is listening.
    */
    public function __construct($newUser = 'root', $newPassword = '', $newHost = 'localhost',
                                $newDB = 'ritho', $newPort = -1) {
        if($newUser !== null)
            $this->user = $newUser;

        if($newPassword !== null)
            $this->password = $newPassword;

        if($newHost !== null)
            $this->host = $newHost;

        if($newDB !== null)
            $this->db = $newDB;

        $this->port = $newPort;
    }

    /* Disconnect the database engine.

       @return TRUE on success, FALSE on failure.
    */
    abstract public function close();

    /* Connect to the database engine.

       @return TRUE on success, FALSE on failure.
    */
    abstract public function connect();

    /* Deletes records from a table specified by the keys and values in assoc_array.

       @param $table_name (string): Name of the table from which to delete rows.
       @param $assoc (array): An array whose keys are field names in the table table_name,
	   and whose values are the values of those fields that are to
	   be deleted.
       @return TRUE on success, FALSE on failure.
    */
    abstract public function delete($table_name, $assoc = array());

    /* Escape a string for insertion into the database.

       @param $str (string): String to escape.
       @return String escaped.
    */
    abstract public function escape_string($str);

    /* Execute a query.

       @param $query (string): Query to execute in the DB.
       @return TRUE on success, FALSE on failure.
    */
    abstract public function exec($query);

    /* Close a prepared statement.

       @param $stmtname (string): The name of the prepared statement to execute.
       @return TRUE on success, FALSE on failure.
    */
    abstract public function exec_close($stmtname = null);

    /* Sends a request to execute a prepared statement without waiting for the result(s).

       @param $stmtname (string): The name of the prepared statement to execute.
       @param $params (string | int | double | array): Array of parameter values
	   to substitute for the placeholders in the original prepared query
	   string. The number of elements in the array must match the number of
	   placeholders.
       @return Query result resource on success, FALSE on failure.
    */
    abstract public function exec_prepared($stmtname, $params);

    /* Get an array that contains all rows (records) in the result resource.

       @param $result (resource): Query result resource.
       @return Array with all rows in the result. Each row is an array of field
       values indexed by field name and by field number. FALSE if there are no
       rows in the result, or on any other error.
    */
    abstract public function fetch_all($result = null);

    /* Fetch a row into a numbered array from a query result.

       @param $result (resource): Result to get the row.
       @param $result_type (int): Parameter to control how the returned array is
       indexed. result_type is a constant and can take the following values:
       SQL_ASSOC, SQL_NUM and SQL_BOTH.
       @param $row (int): Row to fetch.
       @return Array indexed numerically, associatively or both, FALSE on error.
       Each value in the array is represented as a string. Database NULL values
       are returned as NULL.
    */
    abstract public function fetch_array($result = null, $result_type = 0, $row = -1);

    /* Fetch a row into an associative array from a query result.

       @param $result (resource): Result to get the row.
       @param $row (int): Row to fetch.
       @return Array indexed associatively, FALSE on error. Each value in the
       array is represented as a string. Database NULL values are returned as
       NULL. Returns NULL if there are no more rows in resultset.
    */
    abstract public function fetch_assoc($result = null, $row = -1);

    /* Fetch an object with properties that correspond to the fetched row's field
       names. It can optionally instantiate an object of a specific class, and
       pass parameters to that class's constructor.

       @param $result (resource): Result to get the row.
       @param $class_name (string): Class name to store the row.
       @param $params (array): Params to attach to the constructor of the object.
       @return Object fetched.
    */
    abstract public function fetch_object($result = null, $class_name = 'StdClass',
                                          $params = array());

    /* Fetch a row into a numbered array from a query result.

       @param $result (resource): Result to get the row.
       @param $row (int): Row to fetch.
       @return Array indexed numerically, FALSE on error. Each value in the array
       is represented as a string. Database NULL values are returned as NULL.
    */
    abstract public function fetch_row($result = null, $row = -1);

    /* Get the name of the field occupying the given field_number in the given
       result resource. Field numbering starts from 0.

       @param $result (resource): Result to get the name of the column.
       @param $field_number (integer): Number of field to check.
       @return An string with the name of the field.
    */
    abstract public function field_name($result = null, $field_number = -1);

    /* Get a string containing the base type name of the given field_number in the
       given result resource.

       @param $result (resource): Result resource to get the type of the field.
       @param $field_number (int): Number of field to check.
       @return String with the type of object of the given field.
    */
    abstract public function field_type($result = null, $field_number = -1);

    /* Free a query result.

       @param $result (resource): Result to free.
       @return TRUE on success, FALSE on failure.
    */
    abstract public function free($result = null);

    /* Inserts the values of assoc_array into the table specified by table_name.

       @param $table_name (string): Name of the table into which to insert rows.
       @param $assoc (array): Array whose keys are field names in the table table_name,
       and whose values are the values of those fields that are to be inserted.
       @return TRUE on success, FALSE on failure.
    */
    abstract public function insert($table_name, $assoc = array());

    /* Get the number of fields (columns) in a result resource.

       @param $result (resource): Result to check.
       @return Number of columns of the result.
    */
    abstract public function num_fields($result = null);

    /* Get the number of rows in a result resource..

       @param $result (resource): Result to check.
       @return Number of rows of the result.
    */
    abstract public function num_rows($result = null);

    /* Persistent connection to the database engine.

       @return TRUE on success, FALSE on failure.
    */
    abstract public function pconnect();

    /* Pings a database connection and tries to reconnect it if it is broken.

       @return TRUE on success, FALSE on failure.
    */
    abstract public function ping();

    /* Creates a prepared statement for later execution.

       @param $stmtname (string): The name to give the prepared statement. Must
       be unique per-connection.
       @param $query (string): The parameterized SQL statement. Must contain only
       a single statement. If any parameters are used, they are referred to as $1,
       $2, etc.
       @return TRUE on success, FALSE on failure.
    */
    abstract public function prepare($stmtname, $query);

    /* Execute a query.

       @param $query (string): Query to execute in the DB.
       @return Query result resource on success, FALSE on failure.
    */
    abstract public function query($query);

    /* Select records specified by assoc_array which has field=>value.

       @param $table_name (string): Name of the table from which to select rows.
       @param $cols (array): Array with the names of the columns to return by the query.
       @param $where (array): Array whose keys are columns in the table table_name, and
       whose values are the conditions that a row must meet to be retrieved.
       @return Query result resource on success, FALSE on failure.
    */
    abstract public function select($table_name, $cols = array(), $where = array());

    /* Getters. */
    protected function getUser() {
        return $this->user;
    }

    protected function getPassword() {
        return $this->password;
    }

    protected function getHost() {
        return $this->host;
    }

    protected function getDB() {
        return $this->db;
    }

    protected function getPort() {
        return $this->port;
    }

    protected function getConnection() {
        return $this->connection;
    }

    protected function isPersistent() {
        return $this->isPersistent;
    }

    /* Setters. */
    protected function setUser($newUser = 'root') {
        if($newUser !== null)
            $this->user = $newUser;
    }

    protected function setPassword($newPassword = '') {
        if($newPassword !== null)
            $this->password = $newPassword;
    }

    protected function setHost($newHost = 'localhost') {
        if($newHost !== null)
            $this->host = $newHost;
    }

    protected function setDB($newDB = 'ritho') {
        if($newDB !== null)
            $this->db = $newDB;
    }

    protected function setPort($newPort = 3306) {
        $this->port = $newPort;
    }

    protected function setConnection($newConnection = null) {
        if($newConnection !== null)
            $this->connection = $newConnection;
    }

    protected function setPersistent($newPersistent = true) {
        $this->isPersistent = $newPersistent;
    }
}
