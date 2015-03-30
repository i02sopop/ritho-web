<?php
/* Copyright (c) 2011-2014 Ritho-web team (see AUTHORS)
 *
 * This file is part of ritho-web.
 *
 * ritho-web is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * ritho-web is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with ritho-web. If not, see <http://www.gnu.org/licenses/>.
 */

/** File cindex.php.
 *
 * @copyright 2011-2014 Ritho-web project (see AUTHORS).
 * @license   http://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License
 * @version   GIT: <git_id>
 * @link http://ritho.net
 */

/** Controller for the Index page.
 *
 * @copyright Copyright (c) 2011-2014 Ritho-web team (see AUTHORS)
 * @category  Controller
 * @package   Ritho-web\Classes\Controller
 * @since     0.1
 */
class CIndex extends Controller {
	/** Constructor of CIndex.
	 *
	 * @return void
	 */
	public function __construct() {
	}

	/** Method to initalize the controller before handling the request.
	 *
	 * @return void
	 */
	public function init() {
	}

	/** GET request handler.
	 *
	 * @return string Template name to render.
	 */
	protected function get() {
		$configs = $GLOBALS['configs'];

		$this->context['jquery'] = $configs['js_path'] . '/ritho.js';
		$this->context['charset'] = 'utf-8';
		$this->context['author'] = 'Pablo Alvarez de Sotomayor Posadillo';
		$this->context['description'] = 'www/classes/controller/c404.php';
		$this->context['copy'] = 'Copyright 2011-2015, Pablo Alvarez de Sotomayor Posadillo';
		$this->context['projName'] = 'Ritho';
		$this->context['creator'] = 'Pablo Alvarez de Sotomayor Posadillo';
		$this->context['subject'] = 'Ritho\'s web page.';

		/* size: 16x16 or 32x32, transparency is OK, see wikipedia for info on
		 * broswer support: http://mky.be/favicon/ */
		$this->context['favicon'] = '/img/favicon.png';

		/* size: 57x57 for older iPhones, 72x72 for iPads, 114x114 for iPhone4's
		 * retina display (IMHO, just go ahead and use the biggest one)
		 * To prevent iOS from applying its styles to the icon name it thusly:
		 * apple-touch-icon-precomposed.png
		 * Transparency is not recommended (iOS will put a black BG behind the
		 * icon). */
		$this->context['appleicon'] = $configs['img_path'] . '/custom_icon.png';
		$this->context['css'] = $configs['css_path'] . '/' . $configs['css_theme'] . '/style.css';
		$this->context['jquery'] = $configs['js_path'] . '/jquery.min.js';
		$this->context['title'] = $this->name.'index - Ritho\'s Web Page';
		$this->context['modernizr'] = $configs['js_path'] . '/modernizr.min.js';
		$this->context['lesscss'] = $configs['js_path'] . '/less.min.js';
		$this->context['gsVerification'] = 'Hr_OWj4SMe2RICyrXgKkj-rsIe-UqF15qtVk579MITk';

		return 'index';
	}
}
