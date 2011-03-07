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

/*
  Class autoload function.

  @author Ritho-web team
  @copyright Copyright (c) 2011 Ritho-web team (look at AUTHORS file)
  @parameter name (string): The class name.
*/
function __autoload($name) {
  global $config;
  if(is_file($config['class_path'].strtolower($name).$config['class_ext']))
    require_once($config['class_path'].strtolower($name).$config['class_ext']);
  else if(is_file($config['model_path'].strtolower($name).$config['class_ext']))
    require_once($config['model_path'].strtolower($name).$config['class_ext']);
  else if(is_file($config['view_path'].strtolower($name).$config['class_ext']))
    require_once($config['view_path'].strtolower($name).$config['class_ext']);
  else if(is_file($config['controller_path'].strtolower($name).$config['class_ext']))
    require_once($config['controller_path'].strtolower($name).$config['class_ext']);
  else
    throw new MissingException("Imposible cargar $name.");
}
?>