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

require_once('configuration.php');

// TODO: This wil go to the main class.
$controller = null; /* Controller to run. */
$requested = empty($_SERVER['REQUEST_URI']) ? false : $_SERVER['REQUEST_URI']; /* URI requested. */
switch ($requested) {
case '/':
case '/index':
  $controller = new CIndex();
  break;
default:
  // include '404.php';
}

$controller->run();

?>
