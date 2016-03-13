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

/**
 * File missingexpeption.php.
 *
 * This file include the MissingException class.
 *
 * @category  Exceptions
 * @package   Ritho-web\Classes\Exceptions
 * @since     0.1
 * @license http://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License
 * @version GIT: <git_id>
 * @link http://ritho.net
 */

/** Raise an exception when a class is missing. */
class MissingException extends Exception {

	/** @var $msg Error message. */
	protected $msg = '';

	/** @var $code Error code. */
	protected $code = 0;

	/** @var $previous Previous exception. */
	protected $previous = null;

	/** Class constructor.
	 *
	 * @param string    $msg      The error message.
	 * @param integer   $code     The error code.
	 * @param Exception $previous The previous exception.
	 */
	public function __construct($msg = '', $code = 0, Exception $previous = null) {
		$this->msg = 'Error loading the class: ' . $msg;
		$this->code = $code;
		$this->previous = $previous;
		parent::__construct($msg, $code, $previous);
	}
}
