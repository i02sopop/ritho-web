<?php
require_once('classes/template.php');

define('TEMPLATE_PATH', dirname(__FILE__) . '/templates/'); 
define('TEMPLATE_EXT', '.html');

$index = new Template('index'); 
$index->message = 'Hello world'; 

//$home->header = new Template('header'); 

// This is how you set a variable in a sub template. Easy as pie :) 
//$home->header->title = 'Home Page'; 

//$home->footer = new Template('footer'); 

$index->render(TRUE);
?>
