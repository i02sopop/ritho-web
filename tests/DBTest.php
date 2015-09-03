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

/** File DBTest.php.
 *
 * @copyright 2011-2015 Ritho-web project (see AUTHORS).
 * @license http://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License
 * @version GIT: <git_id>
 * @link http://ritho.net
 */

/** Class to test the database classes. */
class DBTest extends PHPUnit_Framework_TestCase {

	/** First test to check the db connection.
	 *
	 * @return void
	 */
	public function testInitial() {
		$dbConn = DB::getDbConnection();
		$dbConn->connect();
		$dbConn->close();
	}
}
