<?php 
/** 
 * Basic template engine. 
 * 
 * @package Template
 * @author Pablo Alvarez de Sotomayor Posadillo <palvarez@ritho.net> 
 * @copyright Copyright (c) Pablo Alvarez de Sotomayor Posadillo, 2011
 */ 
  
class Template { 
  private $tName; // Template name
  private $data = array(); // Local and global data.
  
  /** 
   * Constructor sets the template name, and makes sure 
   * it exists. 
   * 
   * @param name (string): The template name 
   */ 
  public function __construct($name) {
    if(!is_file(TEMPLATE_PATH.$name.TEMPLATE_EXT)) 
      die('Invalid template: '.$name); 
    
    $this->tName = $name;
  }
  
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

  /** 
   * Set some template data. 
   * 
   * @param name (string): Name of the data 
   * @param value (string): Value of the data 
   */ 
  public function __set($name, $value) {
    $this->data[$name] = $value; 
  }
  
  public function __isset($name) {
    return isset($this->data[$name]);
  }

  public function __unset($name) {
    unset($this->data[$name]);
  }

  /** 
   * Render a template.
   * 
   * @return string
   */
  public function __toString() 
  { 
    return $this->render();
  } 
  
  /** 
   * Renders a template. 
   * 
   * @param print (bool): Check if the template has to be printed out
   * @return string 
   */ 
  public function render($print=false) { 
    ob_start(); 
    
    // Extract data to local namespace. Don't worry extract isn't great 
    // but this is only in local scope so nothing to worry about :) 
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