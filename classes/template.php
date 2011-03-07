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
  
  @author Ritho-web team
  @copyright Copyright (c) 2011 Ritho-web team (look at AUTHORS file)
*/ 
class Template extends Base {
  private $tName; // Template name
  
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