<?php
class SeleniumTest extends PHPUnit_Extensions_SeleniumTestCase {
	protected $captureScreenshotOnFailure = TRUE;
	protected $screenshotPath = '%BUILD_DIR%/www/img';
	protected $screenshotUrl = 'https://%HOST%:%HTTPS_PORT%/img';

	protected function setUp() {
		$this->setBrowser('*firefox');
		$this->setBrowserUrl('https://%HOST%:%HTTPS_PORT%');
		$this->setHost('%HOST%');
		$this->setPort(%SELENIUM_PORT%);
	}

	public function testTitle() {
		$this->open('https://%HOST%:%HTTPS_PORT%');
		$this->assertTitle('index - Ritho\'s Web Page');
	}
}
