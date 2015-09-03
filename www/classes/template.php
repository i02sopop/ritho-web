<?php
/* Copyright (c) 2011-2015 Ritho-web team (see AUTHORS)
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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	 See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with ritho-web. If not, see <http://www.gnu.org/licenses/>.
 */

/** File template.php.
 *
 * @copyright 2011-2015 Ritho-web project (see AUTHORS).
 * @license	  http://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License
 * @version	  GIT: <git_id>
 * @link http://ritho.net
 */

/** Basic template engine.
 *
 * @copyright Copyright (c) 2011-2015 Ritho-web team (see AUTHORS)
 * @category  General
 * @package	  Ritho-web\Classes
 * @since	  0.1
 */
class Template extends Base {

	/** @var string Template path. */
	private $_path;

	/** Constructor sets the template name, and makes sure
	 *  it exists.
	 *
	 * @param string $name The template name.
	 */
	public function __construct($name) {
		parent::__construct();

		/* Configs of the site. */
	    $configs = $GLOBALS['configs'];

		if (!is_file($configs['template_path'] . '/' . $name .
		             $configs['template_ext']))
			die('Invalid template: ' . $configs['template_path'] . '/' . $name .
				$configs['template_ext']);

		$this->path = $configs['template_path'] . '/' . $name .
		    $configs['template_ext'];
	}

	/** Render a template.
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->render();
	}

	/** Renders a template.
	 *
	 * @param boolean $print Check if the template has to be printed out.
	 * @return string
	 */
	public function render($print = false) {
		ob_start();

		/* Extract data to local namespace. */
		extract($this->_data, EXTR_SKIP);
		include_once $this->path;
		$output = ob_get_clean();

		if ($print === true) {
			echo $output;
			return true;
		}

		return $output;
	}
}
