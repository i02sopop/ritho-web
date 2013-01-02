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

/*
  Basic controller engine.

  @author Ritho-web team
  @copyright Copyright (c) 2011-2013 Ritho-web team (look at AUTHORS file)
*/
abstract class Controller extends Base {
    const ACTION_RENDER = 'render';
    const ACTION_REDIRECT = 'redirect';

    /* Action to do (include a template, redirect, file, ...). */
    protected $action = Controller::ACTION_RENDER;

    /* Destination of the controller (template, url, ...).  */
    protected $destination = 'index.html';

    /* Context variables of the view. */
    protected $context;

    /* Controller execution. */
    public function run() {
        $this->init();
        $this->destination = ($_SERVER['REQUEST_METHOD'] == 'POST') ?
            $this->post() :
            $this->get();
        $this->display();
    }

    /* Method to initalize the controller before handling the request. */
    abstract protected function init();

    /* GET request handler. */
    protected function get() {
        throw new Exception($_SERVER['REQUEST_METHOD'] . ' request not handled');
    }

    /* POST request handler. */
    protected function post() {
        throw new Exception($_SERVER['REQUEST_METHOD'] . ' request not handled');
    }

    /*
      Populates the given object with POST data.
      If not object is given a StdClass is created.

      @param $obj (StdClass): Object to add the POST values.
      @return Object populated
    */
    protected function populatePost($obj = null) {
        if($obj && !is_object($obj))
            $obj = new StdClass();

        foreach($_POST as $var => $value)
            $obj->$var = trim($value);

        return $obj;
    }

    /*
      Displays the view.
    */
    private function display() {
        if($this->action === Controller::ACTION_RENDER)
            $this->render($this->destination);
        else if($this->action === Controller::ACTION_REDIRECT)
            header('Location: ' . $this->destination);
        else
            throw new Exception('Unknown view action: ' . $this->view->action);
    }

    /* Method to generate the output the view. */
    public function render($templateName) {
        $output = new Template($templateName);
        foreach($this->context as $key => $value) {
            $output->$key = $value;
        }
        $output->render(true);
    }
}
?>
