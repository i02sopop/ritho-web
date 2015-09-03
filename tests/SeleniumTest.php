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

/** First generic selenium test. */
class SeleniumTest extends PHPUnit_Extensions_SeleniumTestCase {

	/** @var Set to true when we want a screenshot on a test failure. */
	protected $captureScreenshotOnFailure = true;

	/** @var Path of the screenshots taken on failure. */
	protected $screenshotPath = '%BUILD_DIR%/www/img';

	/** @var Url to show the screenshot. */
	protected $screenshotUrl = 'https://%HOST%:%HTTPS_PORT%/img';

	/** Setup the test environment.
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->setBrowser('*firefox /home/i02sopop/firefox/firefox-bin');
		$this->setBrowserUrl('https://%HOST%:%HTTPS_PORT%');
		$this->setHost('%HOST%');
		$this->setPort(%SELENIUM_PORT%);
	}

	/** Basic test to check the homepage title.
	 *
	 * @return void
	 */
	public function testTitle() {
		$this->open('https://%HOST%:%HTTPS_PORT%');
		$this->assertTitle('Inicio');
	}
}
