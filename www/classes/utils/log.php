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

/* Class to log messages.

   @author Ritho-web team
   @copyright Copyright (c) 2011-2012 Ritho-web team (look at AUTHORS file)
*/
class Log {
    /* Array of Log instances (one per logging file). */
    private static $instances = array();

    /* Log file name. */
    private $logFilename = null;

    /* Log file handler. */
    private $logFile = null;

    /* Are we able to log? */
    private $logStatus = false;

    /* Get an instance of the Log class.

       @param file (string): Logging file name.
       @return Log instance.
    */
    public static function instance($file = null)
    {
        if($file && is_string($file)) {
            /* If the instance of this file doesn't exists we create it. */
            if(!in_array($file, self::$instances))
                self::$instances[$file] = new self($file);
            return self::$instances[$file];
        } else {
            /* If we don't have a default instance we create it. */
            if(count(self::$instances) === 0)
                self::$instances['default'] = new self();
            return self::$instances['default'];
        }
    }

    /* Constructor of the class.

       @param file (string): Logging file name.
    */
    private function __construct($file = null) {
        global $CONFIG;

        if(!file_exists($CONFIG['log_path']) && !mkdir($CONFIG['log_path'], 0755, true)) {
            throw new Exception("Sorry, I couldn't create the directory " . $CONFIG['log_path'] . " for logging.");
            return;
        }

        $this->logFilename = $CONFIG['log_path'] . '/' . $CONFIG['log_file'];
        if($file && is_string($file)) {
            $this->logFilename = $CONFIG['log_path'] . '/';
            if($CONFIG['log_file_prefix'] && is_string($CONFIG['log_file_prefix']))
                $this->logFilename .= $CONFIG['log_file_prefix'];
            $this->logFilename .= $file . '.log';
        }

        if(file_exists($this->logFilename) && !is_writable($this->logFilename)) {
            throw new Exception("Sorry, the log file " . $this->logFilename . " is not writable.");
            return;
        }

        if(!($this->logFile = fopen($this->logFilename, 'a'))) {
            throw new Exception("Sorry, I could't open the log file " . $this->logFilename . " for write.");
            return;
        }

        $this->logStatus = true;
    }

    /* Class destructor */
    public function __destruct() {
        if($this->logFile)
            fclose($this->logFile);
    }

    /* Static method to print an info message to the Log file.

       @param $msg (string): Message to print.
    */
    public function i($msg = null) {
        if($msg && is_string($msg))
            $this->writeMsg("INFO", $msg);
    }

    /* Static method to print a debug message to the Log file.

       @param $msg (string): Message to print.
    */
    public function d($msg = null) {
        if($msg && is_string($msg))
            $this->writeMsg("DEBUG", $msg);
    }

    /* Static method to print an error message to the Log file.

       @param $msg (string): Message to print.
    */
    public function e($msg) {
        if($msg && is_string($msg))
            $this->writeMsg("ERROR", $msg);
    }

    /* Private function to write a message to the log file.

       @param $logLevel (string): Log level of the message.
       @param $msg (string): Message to print.
    */
    private function writeMsg($logLevel, $msg) {
        if($this->logStatus) {
            $access = date("Y/m/d H:i:s");
            if(fwrite($this->logFile, "[$logLevel] $access: $msg") === false)
                throw new Exception("Error writing " . $msg . " into " . $this->logFilename);
        }
    }
}
