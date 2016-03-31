<?php
/* Copyright (c) 2015-2016 Ritho-web team (look at AUTHORS file).

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

        $this->request = empty($_SERVER['REQUEST_URI']) ?
            false :
            $_SERVER['REQUEST_URI'];

        $this->pathRequested = (!empty($_SERVER['QUERY_STRING'])) ?
            substr($this->requested, 0, strpos($this->requested, $_SERVER['QUERY_STRING']) - 1) :
            $this->request;

        $this->controller = $this->getController();
        $this->controller->run();
    }

    /** Method to create the controller related with an entry path. */
    protected function getController() {
        $this->loadPaths();
        $controllerName = '';
        $firstParam = '';
        $params = null;
        $path = $this->cleanPath();
        if (empty($path)) {
            $controllerName = 'CIndex';
            $firstParam = '';
        } else if (array_key_exists($path, $this->paths)) {
            $controllerName = $this->paths[$path]['controller'];
            $firstParam = $this->paths[$path]['param'];
        } else {
            $pathKeys = array_keys($this->paths);
            $pathSelected = '';
            foreach ($pathKeys as $curPath) {
                if (strlen($curPath) > strlen($pathSelected)) {
                    $pattern = preg_replace('/\//', '\\/', preg_quote($curPath));
                    if (preg_match('/' . $pattern . '/', $path) === 1)
                        $pathSelected = $curPath;
                }
            }
            $controllerName = $this->paths[$pathSelected]['controller'];
            if ($controllerName === 'CIndex')
                $controllerName = '';
            $firstParam = $this->paths[$pathSelected]['param'];
            $params = preg_split('/\//', substr($path, strlen($pathSelected)));
            if (!empty($params[0]))
                $controllerName = '';
            array_shift($params);
        }

        if (empty($controllerName))
            return new C404($firstParam, array('path' => substr($path, 1)));

        return new $controllerName($firstParam, $params);
    }

    /** Method to load the relation between paths and controllers from db. */
    private function loadPaths() {
        if (!isset($this->paths))
            $this->paths = array();

        if (empty($this->paths)) {
            $res = $this->db->select('paths');
            if ($res === false)
                throw new Exception('Error loading the paths fromm the database.');

            $rows = $this->db->fetchAll($res);
            if ($rows && count($rows) > 0) {
                foreach ($rows as $row) {
                    $this->paths[$row['c_path']]['controller'] = $row['controller'];
                    $this->paths[$row['c_path']]['param'] = $row['param'];
                }
            }
        }
    }


    /** Method to clean the path of spaces (trim) and the final slash (/).
     *
     * @return void
     */
    private function cleanPath() {
        if (!is_string($this->pathRequested))
            $this->pathRequested = '';

        $this->pathRequested = trim(urldecode($this->pathRequested));
        if (!empty($this->pathRequested) &&
            strrpos($this->pathRequested, '/') == (strlen($this->pathRequested) - 1))
            $this->pathRequested = substr($this->pathRequested, 0, strlen($this->pathRequested) - 1);
    }
}