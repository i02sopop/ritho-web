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

/*
  Base class with some default methods and vars in common to all the app classes..

  @author Ritho-web team
  @copyright Copyright (c) 2011-2012 Ritho-web team (look at AUTHORS file)
*/
abstract class Base {
    protected $data = array(); // Local and global data.

    /*
      Constructor of the class.
    */
    public function __construct() {
    }

    /*
      Getter of the class.

      @parameter name (string): Name of the parameter to get.
    */
    public function __get($name) {
        if(method_exists($this, ($method = 'get_'.$name)))
            return $this->$method();
        else if(array_key_exists($name, $this->data))
            return $this->data[$name];

        $trace = debug_backtrace();
        trigger_error('Undefined property via __get(): ' . $name . ' in ' . $trace[0]['file'] . ' on line ' . $trace[0]['line'], E_USER_NOTICE);
        return null;
    }

    /*
      Check if a parameter is set in the class.

      @parameter name (string): Name of the parameter to check.
    */
    public function __isset($name) {
        if(method_exists($this, ($method = 'isset_' . $name)))
            return $this->$method();
        else
            return isset($this->data[$name]);
    }

    /*
      Setter of the class.

      @parameter name (string): Name of the parameter to set.
      @parameter value (string): Value of the parameter to set.
    */
    public function __set($name, $value) {
        if(method_exists($this, ($method = 'set_' . $name)))
            $this->$method($value);
        else
            $this->data[$name] = $value;
    }

    /*
      Unset a parameter of the class.

      @parameter name (string): Name of the parameter to unset..
    */
    public function __unset($name) {
        if(method_exists($this, ($method='unset_' . $name)))
            $this->$method();
        else
            unset($this->data[$name]);
    }

}
?>
