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

// TODO: Include sql configs.
$config = new array('path' => dirname(__FILE__),
		    'class_path' => dirname(__FILE__).'/classes/',
		    'model_path' => dirname(__FILE__).'/classes/model/',
		    'view_path' => dirname(__FILE__).'/classes/view/',
		    'controller_path' => dirname(__FILE__).'/classes/controller/',
		    'class_ext' => '.php',
		    'template_path' => dirname(__FILE__).'/templates/',
		    'template_ext' => '.php',
		    'include_path' => dirname(__FILE__).'/includes/'
		    );
require_once($config['include_path'].'functions.php');
?>