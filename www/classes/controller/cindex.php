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

/** File cindex.php.
 *
 * @category  Controller
 * @package   Ritho-web\Classes\Controller
 * @since     0.1
 * @license http://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License
 * @version GIT: <git_id>
 * @link http://ritho.net
 */

/** Controller for the Index page. */
class CIndex extends Controller {
	/** Constructor of CIndex.
	 *
	 * @param string $action      Action to take (different from the default one).
	 * @param array  $extraParams Extra parameters to do the action.
	 * @return void
	 */
	public function __construct($action = '', array $extraParams = null) {
		parent::__construct($action, $extraParams);
	}

	/** Method to initalize the controller before handling the request.
	 *
	 * @return void
	 */
	public function init() {
		$this->name = 'index';
	}

	/** GET request handler.
	 *
	 * @return string Template name to render.
	 */
	protected function get() {
		$this->setHeaders();
		$this->context['title'] = 'Inicio';

		return 'index';
	}
}
