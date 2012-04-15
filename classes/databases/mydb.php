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
class MyDB extends DB {
    $mysqli_conn = null;
    $isPersistent = false;
    $stmts = array();

    /* Constructor of the class.

       @param $user (string): User to authenticate to the DB server.
       @param $pass (string): Password to authenticate to the DB server.
       @param $host (string): Host where the db is listening.
       @param $db (string): Database name.
       @param $port (int): Port number where the DB server is listening.
    */
    public function __construct($user = 'root', $pass = '', $host = 'localhost',
                                $db = 'db', $port = 3306) {
        parent::__construct($user, $pass, $host, $db, $port);
    }

    /* Disconnect the database engine.

       @return TRUE on success, FALSE on failure.
    */
    public function close() {
        if(!$isPersistent)
            return $mysqli_conn->close();
        return true;
    }

    /* Connect to the database engine.

       @return TRUE on success, FALSE on failure.
    */
    public function connect() {
        /* Close previous persistent connection. */
        $isPersistent = false;
        if($mysqli_conn !== null)
            $this->close();

        /* Connect to the MySQL database. */
        $mysqli_conn = new mysqli($host,  $user, $pass, $db, $port);
        // TODO: Throws an exception if there is an error.
        if($mysqli_conn->connect_errno)
            Log::e("Failed to connect to MySQL: " . $mysqli_conn->connect_errno);

        return $mysqli_conn->connect_errno;
    }

    /* Delete records from a table specified by the keys and values in assoc_array.

       @param $table_name (string): Name of the table from which to delete rows.
       @param $assoc (array): An array whose keys are field names in the table
              table_name, and whose values are the values of those fields that are
              to be deleted.
       @return TRUE on success, FALSE on failure.
    */
    public function delete($table_name, $assoc) {
        $query = "DELETE FROM " . $table_name;
        if(is_array($assoc) && $assoc) {
            $query = $query . " WHERE";
            foreach($assoc as $key => $value)
                $query = $query . " " . $key . " = " . $value . " AND ";
            $query = substr($query, 0, -5);
        }

        if($query) {
            Log::i($this->escape_string($query));
            return $mysqli_conn->real_query($this->escape_string($query));
        } else {
            Log::e("Error deleting the rows.");
            return false;
        }
    }

    /* Escape a string for insertion into the database.

       @param $str (string): String to escape.
       @return String escaped.
    */
    public function escape_string($str) {
        if(is_null($str))
            return "";
        return $mysqli_conn->real_escape_string($str);
    }

    /* Execute a query.

       @param $query (string): Query to execute in the DB.
       @return TRUE on success, FALSE on failure.
    */
    public function exec($query) {
        // TODO: Check if the query is an INSERT, UPDATE, DELETE, DROP, ... or
        // any query without results.
        return $mysqli_conn->real_query($this->escape_string($query));
    }


    /* Bind parameters to a prepared statement.

       @param $stmtname (string): The name of the prepared statement to execute.
       @param $params (string | int | double | array): Array of parameter values
              to substitute for the placeholders in the original prepared query
              string. The number of elements in the array must match the number of
              placeholders.
       @return TRUE on success, FALSE on failure.
    */
    public function exec_bind($stmtname, $params) {
        if($stmtname !== null && is_string($stmtname) && is_set($stmts[$stmtname])) {
            if(is_string($params))
                return $stmts[$stmtname]->bind_param("s", $params);
            else if(is_int($params))
                return $stmts[$stmtname]->bind_param("i", $params);
            else if(is_double($params))
                return $stmts[$stmtname]->bind_param("d", $params);
            else if(is_array($params)) {
                $types = "";
                foreach($param as $params) {
                    if(is_string($param))
                        $types .= "s";
                    else if(is_int($param))
                        $types .= "i";
                    else if(is_double($param))
                        $types .= "d";
                    else {
                        Log::e("Unexpected parameter type.");
                        return false;
                    }
                }

                return call_user_func_array(array($stmts[$stmtname], "bind_param"), array($types, $params));
            } else {
                Log::e("Unexpected parameter type.");
                return false;
            }
        }
    }

    /* Close a prepared statement.

       @param $stmtname (string): The name of the prepared statement to execute.
       @return TRUE on success, FALSE on failure.
    */
    public function exec_close($stmtname) {
        if($stmtname !== null && is_string($stmtname) && is_set($stmts[$stmtname]))
            return $stmts[$stmtname]->close();
        return false;
    }

    /* Sends a request to execute a prepared statement without waiting for the result(s).

       @param $stmtname (string): The name of the prepared statement to execute.
       @return TRUE on success, FALSE on failure.
    */
    public function exec_prepared($stmtname) {
        if($stmtname !== null && is_string($stmtname) && is_set($stmts[$stmtname]))
            return $stmts[$stmtname]->execute();
        return false;
    }

    /* Get an array that contains all rows (records) in the result resource.

       @param $result (resource): Query result resource.
       @return Array with all rows in the result. Each row is an array of field
               values indexed by field name. FALSE if there are no rows in the
               result, or on any other error.
    */
    public function fetch_all($result) {
    }

    /* Fetch a row into a numbered array from a query result.

       @param $result (resource): Result to get the row.
       @param $result_type (int): Parameter to control how the returned array is
              indexed. result_type is a constant and can take the following values:
              SQL_ASSOC, SQL_NUM and SQL_BOTH.
       @param $row (int): Row to fetch.
       @return Array indexed numerically, associatively or both, FALSE on error.
               Each value in the array is represented as a string. Database NULL
               values are returned as NULL.
    */
    public function fetch_array($result, $result_type = 0, $row = -1) {
    }

    /* Fetch a row into an associative array from a query result.

       @param $result (resource): Result to get the row.
       @param $row (int): Row to fetch.
       @return Array indexed associatively, FALSE on error. Each value in the array
               is represented as a string. Database NULL values are returned as NULL.
    */
    public function fetch_assoc($result, $row = -1) {
    }

    /* Fetch an object with properties that correspond to the fetched row's field
       names. It can optionally instantiate an object of a specific class, and pass
       parameters to that class's constructor.

       @param $result (resource): Result to get the row.
       @param $row (int): Row to fetch.
       @param $class_name (string): Class name to store the row.
       @param $params (array): Params to attach to the constructor of the object.
       @return Object fetched.
    */
    public function fetch_object($result, $row = -1, $class_name = 'StdClass',
                                 $params = array()) {
    }

    /* Fetch a row into a numbered array from a query result.

       @param $result (resource): Result to get the row.
       @param $row (int): Row to fetch.
       @return Array indexed numerically, FALSE on error. Each value in the array is
               represented as a string. Database NULL values are returned as NULL.
    */
    public function fetch_row($result, $row = -1) {
    }

    /* Get the name of the field occupying the given field_number in the given
       result resource. Field numbering starts from 0.

       @param $result (resource): Result to get the name of the column.
       @param $field_number (integer): Number of field to check.
       @return An string with the name of the field.
    */
    public function field_name($result, $field_number = -1) {
    }

    /* Get a string containing the base type name of the given field_number in the
       given result resource.

       @param $result (resource): Result resource to get the type of the field.
       @param $field_number (int): Number of field to check.
       @return String with the type of object of the given field.
    */
    public function field_type($result, $field_number) {
    }

    /* Free a query result.

       @param $result (resource): Result to free.
       @return TRUE on success, FALSE on failure.
    */
    public function free($result) {
    }

    /* Inserts the values of assoc_array into the table specified by table_name.

       @param $table_name (string): Name of the table into which to insert rows.
       @param $assoc (array): Array whose keys are field names in the table
              table_name, and whose values are the values of those fields that are
              to be inserted.
       @return TRUE on success, FALSE on failure.
    */
    public function insert($table_name, $assoc) {
    }

    /* Get the number of fields (columns) in a result resource.

       @param $result (resource): Result to check.
       @return Number of columns of the result.
    */
    public function num_fields($result) {
    }

    /* Get the number of rows in a result resource..

       @param $result (resource): Result to check.
       @return Number of rows of the result.
    */
    public function num_rows($result) {
    }

    /* Persistent connection to the database engine.

       @return TRUE on success, FALSE on failure.
    */
    public function pconnect() {
        $isPersistent = true;
        if($mysqli_conn == null)
            return $this->connect();

        return $isPersistent;
    }

    /* Pings a database connection and tries to reconnect it if it is broken.

       @return TRUE on success, FALSE on failure.
    */
    public function ping() {
        return $mysqli_conn->ping();
    }

    /* Creates a prepared statement for later execution.

       @param $stmtname (string): The name to give the prepared statement. Must be
              unique per-connection.
       @param $query (string): The parameterized SQL statement. Must contain only a
              single statement. If any parameters are used, they are referred to as
              $1, $2, etc.
       @return TRUE on success, FALSE on failure.
    */
    public function prepare($stmtname, $query) {
        if($stmtname !== null && is_string($stmtname)) {
            $stmts[$stmtname] = $mysqli_conn->prepare($query);
            return ($stmts[$stmtname] !== false);
        }

        return false;
    }

    /* Execute a query.

       @param $query (string): Query to execute in the DB.
       @return Query result resource on success, FALSE on failure.
    */
    public function query($query) {
    }

    /* Query a prepared statement.

       @param $stmtname (string): The name of the prepared statement to execute.
       @param $params (array): Array of parameter values to substitute for the
              placeholders in the original prepared query string. The number of
              elements in the array must match the number of placeholders.
       @return Values that match the query.
    */
    public function query_prepared($stmtname, $params) {
    }

    /* Select records specified by assoc_array which has field=>value.

       @param $table_name (string): Name of the table from which to select rows.
       @param $assoc (array): Array whose keys are field names in the table
              table_name, and whose values are the conditions that a row must meet
              to be retrieved.
       @return Query result resource on success, FALSE on failure.
    */
    public function select($table_name, $assoc = array()) {
    }
}
?>
