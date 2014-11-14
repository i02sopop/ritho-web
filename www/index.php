<?php
/* Copyright (c) 2011-2014 Ritho-web team (see AUTHORS)
 *
 * This file is part of ritho-web.
 *
 * ritho-web is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * ritho-web is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	 See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with ritho-web. If not, see <http://www.gnu.org/licenses/>.
 */

require_once('configuration.php');

/* TODO: This wil go to the main class. */
/* Controller to run. */
$controller = null;

/* URI requested. */
$requested = empty($_SERVER['REQUEST_URI']) ?
	false :
	$_SERVER['REQUEST_URI'];

switch ($requested) {
case '/':
case '/index':
case '/index.php':
    $controller = new CIndex();
    break;
default:
    $controller = new C404(substr($requested, 1));
    break;
}

$controller->run();
