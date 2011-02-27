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
  Basic template engine. 
  
  @package Template
  @author Ritho-web team
  @copyright Copyright (c) 2011 Ritho-web team (look at AUTHORS file)
*/ 
class Template { 
  private $tName; // Template name
  private $data = array(); // Local and global data.
  
  /*
    Constructor sets the template name, and makes sure 
    it exists. 
    
    @param name (string): The template name 
  */ 
  public function __construct($name) {
    if(!is_file(TEMPLATE_PATH.$name.TEMPLATE_EXT)) 
      die('Invalid template: '.$name); 
    
    $this->tName = $name;
  }
  
  /*
    Getter for the data. 
    
    @param name (string): Name of the data.
  */
  public function __get($name) {
    if (array_key_exists($name, $this->data)) {
      return $this->data[$name];
    }
    
    $trace = debug_backtrace();
    trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
    return null;
  }

  /*
    Set some template data. 
    
    @param name (string): Name of the data.
    @param value (string): Value of the data.
  */
  public function __set($name, $value) {
    $this->data[$name] = $value; 
  }
  
  /*
    Check if a data is set.
    
    @param name (string): Name of the data.
  */
  public function __isset($name) {
    return isset($this->data[$name]);
  }

  /*
    Unset a data value.
    
    @param name (string): Name of the data.
  */
  public function __unset($name) {
    unset($this->data[$name]);
  }

  /*
    Render a template.
    
    @return string
  */
  public function __toString() 
  { 
    return $this->render();
  } 
  
  /*
    Renders a template. 
    
    @param print (bool): Check if the template has to be printed out
    @return string 
  */
  public function render($print=false) { 
    ob_start(); 
    
    // Extract data to local namespace.
    extract($this->data, EXTR_SKIP); 
    require_once(TEMPLATE_PATH.$this->tName.TEMPLATE_EXT);
    $output = ob_get_clean();
    
    if($print) { 
      echo $output;
      return true;
    }
    
    return $output;
  }
}
?>