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

/** Raise an exception when a class is missing.
 *
 * @copyright Copyright (c) 2011-2014 Ritho-web team (see AUTHORS)
 * @category  Exceptions
 * @package   Ritho-web\Classes\Exceptions
 * @since     0.1
 */
class MissingException extends Exception
{
	/** Error message. */
    protected $msg = '';

	/** Error code. */
    protected $code = 0;

	/** Previous exception. */
    protected $previous = NULL;

    /** Class constructor.
	 *
	 * @access public
	 * @param string The error message.
	 * @param int The error code.
	 * @param Exception The previous exception.
    */
    public function __construct($msg = '', $code = 0, $previous = NULL)
	{
        $this->msg = 'Error loading the class: ' . $msg;
        $this->code = $code;
        $this->previous = $previous;
        parent::__construct($msg, $code, $previous);
    }
}
?>