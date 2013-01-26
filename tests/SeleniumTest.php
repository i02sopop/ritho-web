<?php
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class SeleniumTest extends PHPUnit_Extensions_SeleniumTestCase
{
    protected $captureScreenshotOnFailure = TRUE;
    protected $screenshotPath = '.';
    protected $screenshotUrl = 'https://karpov.ritho.net:20002/screenshots';

    protected function setUp()
	{
        $this->setBrowser('*firefox');
        $this->setBrowserUrl('https://karpov.ritho.net:20002');
		$this->setHost('karpov.ritho.net');
		$this->setPort(20030);
		$this->start();
    }

    public function testTitle()
	{
        $this->open('https://karpov.ritho.net:20002', '*firefox');
        $this->assertTitle('index - Ritho\'s Web Page');
    }
}