<?php 
/** 
 * Basic template engine. 
 * 
 * @package Template
 * @author Pablo Alvarez de Sotomayor Posadillo <palvarez@ritho.net> 
 * @copyright Copyright (c) Pablo Alvarez de Sotomayor Posadillo, 2011
 */ 
  
class Template { 
  protected $name; // Template name
  protected $data = array(); // Local and global data.
  
  /** 
   * Constructor sets the template name, and makes sure 
   * it exists. 
   * 
   * @param name (string): The template name 
   */ 
  public function __construct($name) {
    if(!is_file(TEMPLATE_PATH.$name.TEMPLATE_EXT)) 
      die('Invalid template: '.$name); 
    
    $this->name = $name; 
  }
  
  /** 
   * Set some template data. 
   * 
   * @param key (string): Key of the data 
   * @param value (string): Value of the data 
   */ 
  public function __set($key, $value) 
  {
    $this->data[$key] = $value; 
  }
  
  /** 
   * Render a template.
   * 
   * @return string
   */
  public function __toString() 
  { 
    return $this->render()."\n";
  } 
     
  /** 
   * Renders a template. 
   * 
   * @param print (bool): Check if the template has to be printed out
   * @return string 
   */ 
  public function render($print=FALSE) 
  { 
    ob_start(); 
    
    // Extract data to local namespace. Don't worry extract isn't great 
    // but this is only in local scope so nothing to worry about :) 
    extract($this->data, EXTR_SKIP); 
    require_once(TEMPLATE_PATH.$this->name.TEMPLATE_EXT);
    $output = ob_get_clean();
    
    if($print === TRUE) { 
      echo $output;
      return true;
    }
    
    return $output;
  }
}
?>