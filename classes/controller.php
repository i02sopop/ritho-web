<?php
/* This file is part of ritho-web.

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
  Basic controller engine.

  @package Controller
  @author Ritho-web team
  @copyright Copyright (c) 2011 Ritho-web team (look at AUTHORS file)
*/
class Controller{
  private $view; // View for the controller

  /*
    Constructor sets the view of the controller and checks it's valid.

    @parameter view (string): The view name.
  */
  public function __constructor($view) {
    if(!is_file(CLASS_PATH.strtolower($view).CLASS_EXT))
      die('Invalid view: '.$view); 
    
    $this->view = new $view();
  }

  /*
    Getter of the class.

    @parameter name (string): Name of the parameter to get.
  */
  public function __get($name)
  {
    if (method_exists($this, ($method = 'get_'.$name)))
      {
	return $this->$method();
      }
    else return;
  }
  
  /*
    Check if a parameter is set in the class.

    @parameter name (string): Name of the parameter to check.
  */
  public function __isset($name)
  {
    if (method_exists($this, ($method = 'isset_'.$name)))
      {
	return $this->$method();
      }
    else return;
  }
  
  /*
    Setter of the class.

    @parameter name (string): Name of the parameter to set.
    @parameter value (string): Value of the parameter to set.
  */
  public function __set($name, $value)
  {
    if (method_exists($this, ($method = 'set_'.$name)))
      {
	$this->$method($value);
      }
  }
  
  /*
    Unset a parameter of the class.

    @parameter name (string): Name of the parameter to unset..
  */
  public function __unset($name)
  {
    if (method_exists($this, ($method = 'unset_'.$name)))
      {
	$this->$method();
      }
  }
}
?>