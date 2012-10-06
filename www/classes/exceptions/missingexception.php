<?php
/* Copyright (c) 2011-2012 Ritho-web team (look at AUTHORS file)

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

/**
 * File missingexpeption.php.
 *
 * This file include the MissingException class.
 * 
 * @author Ritho-web team
 * @copyright Copyright (c) 2011-2012 Ritho-web team (look at AUTHORS file)
 * @license http://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License
 * @link http://ritho.net
 */

// namespace ritho-web;

/**
 * MissingException exception class.
 *
 * This class raises an exception when a class is missing (is not included
 * or required).
 * 
 * @package Exceptions
 * @since 0.1
 */
class MissingException extends Exception {
	/**
	 * Error message.
	 * @access protected.
	 */
    protected $msg = '';

	/**
	 * Error code.
	 * @access protected.
	 */
    protected $code = 0;

	/**
	 * Previous exception.
	 * @access protected.
	 */
    protected $previous = NULL; 

    /**
	 * Class constructor.
	 *
	 * This method sets the error message.
	 *
	 * @access public
	 * @param string The error message.
	 * @param int The error code.
	 * @param Exception The previous exception.
    */
    public function __construct($msg = '', $code = 0, $previous = NULL) {
        $this->msg = 'Error loading the class: ' . $msg;
        $this->code = $code;
        $this->previous = $previous;
        parent::__construct($msg, $code, $previous);
    }
}
?>