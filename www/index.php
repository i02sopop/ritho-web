<?php
/* Copyright (c) 2011-2015 Ritho-web team (see AUTHORS)
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

require_once 'configuration.php';

/* TODO: This wil go to the main class. */
/* Controller to run. */
$controller = null;

/* URI requested. */
$requested = empty($_SERVER['REQUEST_URI']) ? false : $_SERVER['REQUEST_URI'];
$path = (!empty($_SERVER['QUERY_STRING'])) ?
	substr($requested, 0, strpos($requested, $_SERVER['QUERY_STRING']) - 1) :
	$requested;
$controller = getController($path);
$controller->run();
