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

/* Setting the default configuration variables */
$GLOBALS['configs'] = $configs = array(
	/* Paths. */
	'class_path' => '%WWW_DIR%/classes',
	'controller_path' => '%WWW_DIR%/classes/controller',
	'css_path' => '/css', /* Include only relative path or external url to generate the html link. */
	'exceptions_path' => '%WWW_DIR%/classes/exceptions',
	'img_path' => '/img', /* Include only relative path or external url to generate the html link. */
	'include_path' => '%WWW_DIR%/include',
	'js_path' => '/js',  /* Include only relative path or external url to generate the html link. */
	'log_path' => '%LOG_DIR%',
	'model_path' => '%WWW_DIR%/classes/model',
	'path' => '%WWW_DIR%',
	'template_path' => '%WWW_DIR%/templates',
	'utils_path' => '%WWW_DIR%/classes/utils',
	'databases_path' => '%WWW_DIR%/classes/databases',
	'view_path' => '%WWW_DIR%/classes/view',
	/* File extensions. */
	'class_ext' => '.php',
	'template_ext' => '.html',
	/* css theme. */
	'css_theme' => 'blue-three-columns',
	/* Log parameters. */
	'log_file' => '%LOG_FILE%',
	'log_file_prefix' => '%LOG_PREFIX%',
	/* Database parameters. */
	'db_engine' => '%DB_ENGINE%',
	'db_host' => '%DB_HOST%',
	'db_port' => '%DB_PORT%',
	'db_user' => '%DB_USER%',
	'db_password' => '%DB_PASSWD%',
	'db_name' => '%DATABASE%',
	'timezone' => 'Europe/Madrid',
	);

/* Load functions. */
require_once($configs['include_path'] . '/functions.php');

/* Setting the timezone. */
date_default_timezone_set($configs['timezone']);
