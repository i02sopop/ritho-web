<?php
/* Copyright (c) 2011-2015 Ritho-web team (look at AUTHORS file)

   This file is part of ritho-web.

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

/** File index.php.
 *
 * Landing page for the users.
 *
 * @category  Index
 * @version   GIT: <git_id>
 * @link      http://ritho.net
 */

require_once 'configuration.php';

/** TODO: This wil go to the main class. */
/** Controller to run. */
$controller = null;

/** URI requested. */
$request = empty($_SERVER['REQUEST_URI']) ?
    false :
    $_SERVER['REQUEST_URI'];

$path = (!empty($_SERVER['QUERY_STRING'])) ?
	substr($requested, 0, strpos($requested, $_SERVER['QUERY_STRING']) -1) :
	$request;

switch ($path) {
case '/':
case '/index':
case '/index.php':
    $controller = new CIndex();
    break;
default:
    $controller = new C404($path);
    break;
}

$controller->run();
