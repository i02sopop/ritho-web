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
  Basic controller engine.

  @author Ritho-web team
  @copyright Copyright (c) 2011 Ritho-web team (look at AUTHORS file)
*/
abstract class Controller extends Base {
  private $view; // View for the controller

  /*
    Controller execution.
  */
  public function run() {
    $this->init();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $this->view = $this->post();
    } else {
      $this->view = $this->get();
    }

    $this->display();
  }

  /*
    Method to initalize the controller before handling the request.
  */
  abstract protected function init();

  /*
    GET request handler.
  */
  protected function get() {
    $this->process();
  }

  /*
    POST request handler.
  */
  protected function post() {
    $this->process();
  }

  /**
     Request handler. This method will be called if no method specific handler
     is defined
  */
  protected function process() {
    throw new Exception($_SERVER['REQUEST_METHOD'] . ' request not handled');
  }

  /*
    Populates the given object with POST data.
    If not object is given a StdClass is created.

    @param $obj (StdClass): Object to add the POST values.
    @return Object populated
  */
  protected function populateWithPost($obj = null) {
    if(!is_object($obj)) {
      $obj = new StdClass();
    }

    foreach ($_POST as $var => $value) {
      $obj->$var = trim($value);
    }

    return $obj;
  }

  /*
    Displays the view.
  */
  private function display() {
    if ($this->view->action == View::RENDER_ACTION) {
      $this->view->render();
    } else if ($this->view->action == View::REDIRECT_ACTION) {
      header('Location: ' . $this->view->url);
    } else {
      throw Exception('Unknown view action: ' . $this->view->action);
    }
  }
}
?>
