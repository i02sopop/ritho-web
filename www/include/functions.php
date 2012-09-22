<?php
/* Copyright (c) 2011-2012 Ritho-web team (look at AUTHORS file)

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

require_once($CONFIG['exceptions_path'] . '/missingexception' . $CONFIG['class_ext']);

/*
  Class autoload function.

  @author Ritho-web team
  @copyright Copyright (c) 2011-2012 Ritho-web team (look at AUTHORS file)
  @parameter name (string): The class name.
*/
function __autoload($name) {
    global $CONFIG;

    if(is_file($CONFIG['class_path'] . '/' . strtolower($name) . $CONFIG['class_ext']))
        require_once($CONFIG['class_path'] . '/' . strtolower($name) . $CONFIG['class_ext']);
    else if(is_file($CONFIG['model_path'] . '/' . strtolower($name) . $CONFIG['class_ext']))
        require_once($CONFIG['model_path'] . '/' . strtolower($name) . $CONFIG['class_ext']);
    else if(is_file($CONFIG['view_path'] . '/' . strtolower($name) . $CONFIG['class_ext']))
        require_once($CONFIG['view_path'] . '/' . strtolower($name) . $CONFIG['class_ext']);
    else if(is_file($CONFIG['controller_path'] . '/' . strtolower($name) . $CONFIG['class_ext']))
        require_once($CONFIG['controller_path'] . '/' . strtolower($name) . $CONFIG['class_ext']);
    else if(is_file($CONFIG['utils_path'] . '/' . strtolower($name) . $CONFIG['class_ext']))
        require_once($CONFIG['utils_path'] . '/' . strtolower($name) . $CONFIG['class_ext']);
    else if(is_file($CONFIG['databases_path'] . '/' . strtolower($name) . $CONFIG['class_ext']))
        require_once($CONFIG['databases_path'] . '/' . strtolower($name) . $CONFIG['class_ext']);
    else
        throw new MissingException("Impossible to load $name.");
}

/*
  Function to log info messages into the logging file.

  @author Ritho-web team
  @copyright Copyright (c) 2011-2012 Ritho-web team (look at AUTHORS file)
  @parameter name (string): The class name.
  @parameter file (string): The log filename.
*/
function log_i($msg = "", $file = null) {
    Log::instance($file)->i($msg);
}

/*
  Function to log debug messages into the logging file.

  @author Ritho-web team
  @copyright Copyright (c) 2011-2012 Ritho-web team (look at AUTHORS file)
  @parameter name (string): The class name.
  @parameter file (string): The log filename.
*/
function log_d($msg = "", $file = null) {
    Log::instance($file)->d($msg);
}

/*
  Function to log error messages into the logging file.

  @author Ritho-web team
  @copyright Copyright (c) 2011-2012 Ritho-web team (look at AUTHORS file)
  @parameter name (string): The class name.
  @parameter file (string): The log filename.
*/
function log_e($msg = "", $file = null) {
    Log::instance($file)->e($msg);
}
