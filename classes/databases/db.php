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
    $$user = null;
    $pass = null;
    $host = null;
    $db = null;
    $port = null;
    $conn = null;
    $stmts = array();
    $isPersistent = false;

    /* Constructor of the class.

       @param $user (string): User to authenticate to the DB server.
       @param $pass (string): Password to authenticate to the DB server.
       @param $host (string): Host where the db is listening.
       @param $db (string): Database name.
       @param $port (int): Port number where the DB server is listening.
    */
    public function __construct($user = 'root', $pass = '', $host = 'localhost',
                                $db = 'db', $port = 3306) {
        $this->user = $user;
        $this->pass = $pass;
        $this->host = $host;
        $this->db = $db;
        $this->port = $port;
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
       @param $assoc (array): An array whose keys are field names in the table table_name, and whose values are the values of those fields that are to be deleted.
       @return TRUE on success, FALSE on failure.
    */
    abstract public function delete($table_name, $assoc);

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

    /* Bind parameters to a prepared statement.

       @param $stmtname (string): The name of the prepared statement to execute.
       @param $params (string | int | double | array): Array of parameter values
              to substitute for the placeholders in the original prepared query
              string. The number of elements in the array must match the number of
              placeholders.
       @return TRUE on success, FALSE on failure.
    */
    abstract public function exec_bind($stmtname, $params);

    /* Close a prepared statement.

       @param $stmtname (string): The name of the prepared statement to execute.
       @return TRUE on success, FALSE on failure.
    */
    abstract public function exec_close($stmtname);

    /* Sends a request to execute a prepared statement without waiting for the result(s).

       @param $stmtname (string): The name of the prepared statement to execute.
       @return TRUE on success, FALSE on failure.
    */
    abstract public function exec_prepared($stmtname);

    /* Get an array that contains all rows (records) in the result resource.

       @param $result (resource): Query result resource.
       @return Array with all rows in the result. Each row is an array of field values indexed by field name. FALSE if there are no rows in the result, or on any other error.
    */
    abstract public function fetch_all($result);

    /* Fetch a row into a numbered array from a query result.

       @param $result (resource): Result to get the row.
       @param $result_type (int): Parameter to control how the returned array is indexed. result_type is a constant and can take the following values: SQL_ASSOC, SQL_NUM and SQL_BOTH.
       @param $row (int): Row to fetch.
       @return Array indexed numerically, associatively or both, FALSE on error. Each value in the array is represented as a string. Database NULL values are returned as NULL.
    */
    abstract public function fetch_array($result, $result_type = 0, $row = -1);

    /* Fetch a row into an associative array from a query result.

       @param $result (resource): Result to get the row.
       @param $row (int): Row to fetch.
       @return Array indexed associatively, FALSE on error. Each value in the array is represented as a string. Database NULL values are returned as NULL.
    */
    abstract public function fetch_assoc($result, $row = -1);

    /* Fetch an object with properties that correspond to the fetched row's field names. It can optionally instantiate an object of a specific class, and pass parameters to that class's constructor.

       @param $result (resource): Result to get the row.
       @param $row (int): Row to fetch.
       @param $class_name (string): Class name to store the row.
       @param $params (array): Params to attach to the constructor of the object.
       @return Object fetched.
    */
    abstract public function fetch_object($result, $row = -1,
                                          $class_name = 'StdClass',
                                          $params = array());

    /* Fetch a row into a numbered array from a query result.

       @param $result (resource): Result to get the row.
       @param $row (int): Row to fetch.
       @return Array indexed numerically, FALSE on error. Each value in the array is represented as a string. Database NULL values are returned as NULL.
    */
    abstract public function fetch_row($result, $row = -1);

    /* Get the name of the field occupying the given field_number in the given result resource. Field numbering starts from 0.

       @param $result (resource): Result to get the name of the column.
       @param $field_number (integer): Number of field to check.
       @return An string with the name of the field.
    */
    abstract public function field_name($result, $field_number = -1);

    /* Get a string containing the base type name of the given field_number in the given result resource.

       @param $result (resource): Result resource to get the type of the field.
       @param $field_number (int): Number of field to check.
       @return String with the type of object of the given field.
    */
    abstract public function field_type($result, $field_number);

    /* Free a query result.

       @param $result (resource): Result to free.
       @return TRUE on success, FALSE on failure.
    */
    abstract public function free($result);

    /* Inserts the values of assoc_array into the table specified by table_name.

       @param $table_name (string): Name of the table into which to insert rows.
       @param $assoc (array): Array whose keys are field names in the table table_name, and whose values are the values of those fields that are to be inserted.
       @return TRUE on success, FALSE on failure.
    */
    abstract public function insert($table_name, $assoc);

    /* Get the number of fields (columns) in a result resource.

       @param $result (resource): Result to check.
       @return Number of columns of the result.
    */
    abstract public function num_fields($result);

    /* Get the number of rows in a result resource..

       @param $result (resource): Result to check.
       @return Number of rows of the result.
    */
    abstract public function num_rows($result);

    /* Persistent connection to the database engine.

       @return TRUE on success, FALSE on failure.
    */
    abstract public function pconnect();

    /* Pings a database connection and tries to reconnect it if it is broken.

       @return TRUE on success, FALSE on failure.
    */
    abstract public function ping();

    /* Creates a prepared statement for later execution.

       @param $stmtname (string): The name to give the prepared statement. Must be unique per-connection.
       @param $query (string): The parameterized SQL statement. Must contain only a single statement. If any parameters are used, they are referred to as $1, $2, etc.
       @return TRUE on success, FALSE on failure.
    */
    abstract public function prepare($stmtname, $query);

    /* Execute a query.

       @param $query (string): Query to execute in the DB.
       @return Query result resource on success, FALSE on failure.
    */
    abstract public function query($query);

    /* Query a prepared statement.

       @param $stmtname (string): The name of the prepared statement to execute.
       @param $params (array): Array of parameter values to substitute for the placeholders in the original prepared query string. The number of elements in the array must match the number of placeholders.
       @return Values that match the query.
    */
    abstract public function query_prepared($stmtname, $params);

    /* Select records specified by assoc_array which has field=>value.

       @param $table_name (string): Name of the table from which to select rows.
       @param $assoc (array): Array whose keys are field names in the table table_name, and whose values are the conditions that a row must meet to be retrieved.
       @return Query result resource on success, FALSE on failure.
    */
    abstract public function select($table_name, $assoc = array());
}
?>
