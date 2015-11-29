<?php
/* Copyright (c) 2011-2015 Ritho-web team (look at AUTHORS file)

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

/** Ritho application class. */
class Ritho extends Base {
    /** Class constructor. */
    public function __construct() {
		parent::__construct();

        $this->controller = null;
        $this->request = empty($_SERVER['REQUEST_URI']) ?
            false :
            $_SERVER['REQUEST_URI'];

        $this->path = (!empty($_SERVER['QUERY_STRING'])) ?
            substr($this->requested, 0, strpos($this->requested, $_SERVER['QUERY_STRING']) -1) :
            $this->request;

        switch ($this->path) {
            case '/':
            case '/index':
            case '/index.php':
                $this->controller = new CIndex();
                break;
            default:
                $this->controller = new C404($this->path);
                break;
        }

        $this->controller->run();
    }
}