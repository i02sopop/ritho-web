<?php
require_once('classes/template.php');

define('TEMPLATE_PATH', dirname(__FILE__) . '/templates/'); 
define('TEMPLATE_EXT', '.html');

$index = new Template('index'); 
$index->message = 'Hello world'; 

$index->head = new Template('head'); 
$index->header = new Template('header');

//$home->footer = new Template('footer'); 

$index->render(TRUE);
?>
