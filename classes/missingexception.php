<?php
/* This file is part of ritho-web.

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

/*
  MissingException exception.

  @package Exceptions
  @author Ritho-web team
  @copyright Copyright (c) 2011 Ritho-web team (look at AUTHORS file)
*/
class MissingException extends Exception {
  private $msg = ''; // Error message
  private $code = 0; // Error code
  private $previous = NULL; // Previous exception

  /*
    Constructor sets the error message.

    @parameter msg (string): The error message.
    @parameter code (int): The error code.
    @parameter previous (Exception): The previous exception.
   */
  public function __construct($msg, $code=0, $previous=NULL) {
    parent::__construct($msg, $code, $previous);
    $this->msg = $msg;
    $this->code = $code;
    $this->previous = $previous;
  }
}
?>