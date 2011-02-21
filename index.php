<?php
require_once('classes/template.php');

define('TEMPLATE_PATH', dirname(__FILE__) . '/templates/'); 
define('TEMPLATE_EXT', '.html');

$index = new Template('index'); 
$index->message = 'Hello world'; 

$index->head = new Template('head'); 
$index->head->charset = 'utf-8';
$index->head->author = 'Pablo Alvarez de Sotomayor Posadillo';
$index->head->description = 'Ritho\'s web page. It includes all the projects, blogs, new, ...';
$index->head->copy = 'Copyright Pablo Alvarez de Sotomayor Posadillo 2011. All Rights Reserved.';
$index->head->projName = 'Ritho';
$index->head->creator = 'Pablo Alvarez de Sotomayor Posadillo';
$index->head->subject = 'Ritho\'s web page. It includes all the projects, blogs, new, ...';
$index->head->favicon = '-/img/favicon.png'; //size: 16x16 or 32x32, transparency is OK, see wikipedia for info on broswer support: http://mky.be/favicon/
$index->head->appleicon = '-/img/custom_icon.png'; // size: 57x57 for older iPhones, 72x72 for iPads, 114x114 for iPhone4's retina display (IMHO, just go ahead and use the biggest one)
                                                   // To prevent iOS from applying its styles to the icon name it thusly: apple-touch-icon-precomposed.png
                                                   // Transparency is not recommended (iOS will put a black BG behind the icon)
$index->head->css = '/css/style.css';
$index->head->cssPrint = '/css/stylePrint.css';
$index->head->cssIE = '/css/styleIE.css';
$index->head->cssIE7 = '/css/styleIE7.css';
$index->head->cssIEOld = '/css/styleIEOld.css';
$index->head->jquery = '/javascript/jquery.js';
$index->head->title = 'Ritho\'s Web Page';
$index->head->modernizr = '/javascript/modernizr-1.7.min.js';
$index->head->gsVerification = 'Hr_OWj4SMe2RICyrXgKkj-rsIe-UqF15qtVk579MITk';

$index->header = new Template('header');
$index->footer = new Template('footer'); 

$index->render(TRUE);
?>
