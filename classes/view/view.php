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
  Basic view.

  @author Ritho-web team
  @copyright Copyright (c) 2011 Ritho-web team (look at AUTHORS file)
*/
abstract class View extends Base {
  const RENDER_ACTION = 'render';
  const REDIRECT_ACTION = 'redirect';
 
  protected $name; /* Name of the template to render. */
  protected $url; /* URL to redirect. */
  protected $context; /* Context variables of the view. */
  protected $action; /* Action to do: render or redirect. */

  /*
    Constructor of View.

    @parameter url (string): Url of the view. It can be a local php file or an external url.
    @parameter context (array): Context variables of the view.
    @parameter action (const string): Action to do: render or redirect.
  */
  public function __construct($name, $context=array(), $action=View::RENDER_ACTION) {
    if($action == View::RENDER_ACTION)
      $this->name = $name;
    else
      $this->url = $name;
    $this->context = $context;
    $this->action = $action;
  }

  /*
    Method to generate the output the view.
  */
  abstract public function render();

  /*
    Method to get the action of the view.
  */
  public function get_action() {
    return $this->action;
  }

  /*
    Method to get the URL of the view.
  */
  public function get_url() {
    return $this->url;
  }
}
?>