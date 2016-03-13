<?php
/* Copyright (c) 2011-2016 Ritho-web team (see AUTHORS)
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

/** File controller.php.
 *
 * @category  Controller
 * @package   Ritho-web\Classes\Controller
 * @since     0.1
 * @license   http://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License
 * @version   GIT: <git_id>
 * @link http://ritho.net
 */

/** Basic controller engine. */
abstract class Controller extends Base {
	const ACTION_RENDER = 'render';
	const ACTION_REDIRECT = 'redirect';

	/** @var $action Action to do (include a template, redirect, file, ...). */
	private $_action = self::ACTION_RENDER;

	/** @var $destination Destination of the controller (template, url, ...).  */
	protected $destination = 'index';

	/** @var $context Context variables of the view. */
	protected $context;

	/** Constructor of the class.
	 *
	 * @param string $action      Action to take (different from the default one).
	 * @param array  $extraParams Extra parameters to do the action.
	 * @return void
	 */
	public function __construct($action = '', array $extraParams = null) {
		parent::__construct();

		$this->action = $action;
		$this->extraParams = $extraParams;

		if ($_SERVER['REQUEST_METHOD'] == 'POST')
			$this->_populatePost();
		else if ($_SERVER['REQUEST_METHOD'] == 'GET')
			$this->_populateGet();
	}

	/** Method to set the common header (context) variables of every page.
	 *
	 * @return void
	 */
	public function setHeaders() {
		$this->context['configs'] = $this->configs;
		$this->context['js'][] = array('name' => 'jquery',
		                               'src' => 'jquery.min.js');
		$this->context['js'][] = array('name' => 'modernizr',
		                               'src' => 'modernizr.min.js');
		$this->context['js'][] = array('name' => 'lesscss',
		                               'src' => 'less.min.js');
		$this->context['js'][] = array('name' => 'ritho',
		                               'src' => 'ritho.min.js');
		$this->context['css'][] = array('name' => 'theme',
		                                'src' => $this->configs['css_theme'] .
										         '/style.css');
		$this->context['charset'] = 'utf-8';
		$this->context['author'] = 'Pablo Alvarez de Sotomayor Posadillo';
		$this->context['description'] = 'Ritho\'s Web Page';
		$this->context['copy'] =
			'Copyright 2011-2016, Pablo Alvarez de Sotomayor Posadillo';
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
		$this->context['appleicon'] = $this->configs['img_path'] . '/custom_icon.png';
		$this->context['title'] = $this->name . ' - Ritho\'s Web Page';
		$this->context['gsVerification'] =
		    'Hr_OWj4SMe2RICyrXgKkj-rsIe-UqF15qtVk579MITk';
	}

	/** Controller execution.
	 *
	 * @return void
	 */
	public function run() {
		$this->init();
		$this->destination = ($_SERVER['REQUEST_METHOD'] == 'POST') ?
			$this->post() :
			$this->get();
		$this->context['page'] = $this->destination;
		$this->_display();
	}

	/** Method to initalize the controller before handling the request.
	 *
	 * @return void
	 */
	abstract protected function init();

	/** GET request handler.
	 *
	 * @throws Exception Throws an exception when the subclass doesn't define the
	 *         get method.
	 * @return void
	 */
	protected function get() {
		throw new Exception($_SERVER['REQUEST_METHOD'] . ' request not handled');
	}

	/** POST request handler.
	 *
	 * @throws Exception Throws an exception when the subclass doesn't define the
	 *         post method.
	 * @return void
	 */
	protected function post() {
		throw new Exception($_SERVER['REQUEST_METHOD'] . ' request not handled');
	}

	/** Populates the controller object with POST data.
	 *
	 * @return void
	 */
	private function _populatePost() {
		foreach ($_POST as $var => $value) {
			$this->$var = trim($value);
			$this->context[$var] = trim($value);
		}
	}

	/** Populates the controller object with GET data.
	 *
	 * @return void
	 */
	private function _populateGet() {
		foreach ($_GET as $var => $value) {
			$this->$var = trim($value);
			$this->context[$var] = trim($value);
		}
	}

	/** Displays the view.
	 *
	 * @throws Exception Throws an exception when the action is not defined.
	 * @return void
	 */
	private function _display() {
		if ($this->_action === self::ACTION_RENDER)
			$this->render($this->destination);
		else if ($this->_action === self::ACTION_REDIRECT)
			header('Location: ' . $this->destination);
		else
			throw new Exception('Unknown view action: ' . $this->view->action);
	}

	/** Method to generate the output the view.
	 *
	 * @param string $templateName Name of the template to render.
	 * @return void
	 */
	public function render($templateName) {
		$output = new Template($templateName);
		foreach ($this->context as $key => $value)
			$output->$key = $value;
		$output->render(true);
	}
}
