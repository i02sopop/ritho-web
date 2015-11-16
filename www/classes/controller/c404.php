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

/** Controller for the 404 page. */
class C404 extends Controller {
    /** Path that launch the error. */
    private $_path;

    /** Constructor of C404. */
    public function __construct($path='') {
		parent::__construct();

        $this->_path = $path;
    }

    /** Method to initalize the controller before handling the request. */
    function init() {
        $this->name = 'index';
    }

    /* GET request handler. */
    protected function get() {
        $this->context['charset'] = 'utf-8';
        $this->context['author'] = 'Pablo Alvarez de Sotomayor Posadillo';
        $this->context['description'] = 'Ritho\'s web page. It includes all the ' .
            'projects, blogs, new, ...';
        $this->context['copy'] = 'Copyright 2011-2013, Pablo Alvarez de Sotomayor ' .
            'Posadillo';
        $this->context['projName'] = 'Ritho';
        $this->context['creator'] = 'Pablo Alvarez de Sotomayor Posadillo';
        $this->context['subject'] = 'Ritho\'s web page. It includes all the projects,'
            . ' blogs, new, ...';

        //size: 16x16 or 32x32, transparency is OK, see wikipedia for info on broswer support: http://mky.be/favicon/
        $this->context['favicon'] = '/img/favicon.png';

        // size: 57x57 for older iPhones, 72x72 for iPads, 114x114 for iPhone4's retina display (IMHO, just go ahead and use the biggest one)
        // To prevent iOS from applying its styles to the icon name it thusly: apple-touch-icon-precomposed.png
        // Transparency is not recommended (iOS will put a black BG behind the icon)
        $this->context['appleicon'] = $this->configs['img_path'] . '/custom_icon.png';
        $this->context['css'] = $this->configs['css_path'] . '/' . $this->configs['css_theme'] . '/style.css';
        $this->context['cssPrint'] = $this->configs['css_path'] . '/' . $this->configs['css_theme'] . '/stylePrint.css';
        $this->context['cssIE'] = $this->configs['css_path'] . '/' . $this->configs['css_theme'] . '/styleIE.css';
        $this->context['cssIE7'] = $this->configs['css_path'] . '/' . $this->configs['css_theme'] . '/styleIE7.css';
        $this->context['cssIEOld'] = $this->configs['css_path'] . '/' . $this->configs['css_theme'] . '/styleIEOld.css';
        $this->context['jquery'] = $this->configs['js_path'] . '/jquery.js';
        $this->context['title'] = $this->name . ' - Ritho\'s Web Page';
        $this->context['modernizr'] = $this->configs['js_path'] . '/modernizr.js';
        $this->context['lesscss'] = $this->configs['js_path'] . '/less.js';
        $this->context['gsVerification'] = 'Hr_OWj4SMe2RICyrXgKkj-rsIe-UqF15qtVk579MITk';

        if($this->_path && is_string($this->_path))
            $this->context['path'] = $this->_path;

        return '404';
    }
}
