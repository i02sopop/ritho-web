<?php
/* Copyright (c) 2011-2013 Ritho-web team (look at AUTHORS file)

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
   License along with ritho-web. If not, see <http://www.gnu.org/licenses/>.
*/

/* Basic template engine.

  @author Ritho-web team
  @copyright Copyright (c) 2011-2013 Ritho-web team (look at AUTHORS file)
*/
class Template extends Base
{
    private $path; /* Template path */

    /* Constructor sets the template name, and makes sure
       it exists.

       @param name (string): The template name
    */
    public function __construct($name)
	{
        global $configs; /* Configs of the site. */

        if(!is_file($configs['template_path'] . '/' . $name . $configs['template_ext']))
            die('Invalid template: ' . $configs['template_path'] . '/' . $name .
                $configs['template_ext']);

        $this->path = $configs['template_path'] . '/' . $name . $configs['template_ext'];
    }

    /* Render a template.

       @return string
    */
    public function __toString()
	{
        return $this->render();
    }

    /* Renders a template.

       @param print (bool): Check if the template has to be printed out
       @return string
    */
    public function render($print = false)
	{
        ob_start();

        /* Extract data to local namespace. */
        extract($this->data, EXTR_SKIP);
        require_once($this->path);
        $output = ob_get_clean();

        if($print === true) {
            echo $output;
            return true;
        }

        return $output;
    }
}
?>
