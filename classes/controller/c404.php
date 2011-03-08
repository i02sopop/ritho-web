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
  Controller for the 404 page.

  @author Ritho-web team
  @copyright Copyright (c) 2011 Ritho-web team (look at AUTHORS file)
*/
class C404 extends Controller {
  $path; // Path that launch the error

  /*
    Constructor of C404.
  */
  public function __construct($path='') {
    $this->path = $path;
  }

  /*
    Method to initalize the controller before handling the request.
  */
  function init() {

  }

  /*
    GET request handler.
  */
  protected function get() {
    $view = new V404();
    if(!empty($this->path))
      $view->path = $this->path;
    return $view;
  }
}
?>