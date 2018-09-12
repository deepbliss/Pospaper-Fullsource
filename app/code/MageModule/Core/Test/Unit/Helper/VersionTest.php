<?php
/**
 * Copyright (c) 2018 MageModule: All rights reserved
 *
 * LICENSE: This source file is subject to our standard End User License
 * Agreeement (EULA) that is available through the world-wide-web at the
 * following URI: http://www.magemodule.com/magento2-ext-license.html.
 *
 * If you did not receive a copy of the EULA and are unable to obtain it through
 * the web, please send a note to admin@magemodule.com so that we can mail
 * you a copy immediately.
 *
 * @author        MageModule admin@magemodule.com
 * @copyright     2018 MageModule
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\Core\Test\Unit\Helper;

class VersionTest extends \Magento\Framework\TestFramework\Unit\BaseTestCase
{
    /**
     * @var \MageModule\Core\Helper\Version
     */
    private $helper;

    public function setUp()
    {
        parent::setUp();
        $this->helper = $this->objectManager->getObject(\MageModule\Core\Helper\Version::class);
    }

    public function testIsValidVersionNumber()
    {
        $version = '3.5.9';
        $this->assertTrue(
            $this->helper->isValidVersionNumber($version)
        );
    }

    public function testIsValidVersionNumberWithLetters()
    {
        $version = '3.5D.9';
        $this->assertTrue(
            $this->helper->isValidVersionNumber($version)
        );
    }

    public function testIsValidVersionNumberWithLeadingSpace()
    {
        $version = ' 3.5.9';
        $this->assertFalse(
            $this->helper->isValidVersionNumber($version)
        );
    }

    public function testIsValidVersionNumberWithTrailingSpace()
    {
        $version = '3.5.9 ';
        $this->assertTrue(
            $this->helper->isValidVersionNumber($version)
        );
    }

    public function testIsValidVersionNumberWithoutPatch()
    {
        $version = '3.5';
        $this->assertTrue(
            $this->helper->isValidVersionNumber($version)
        );
    }

    public function testIsValidVersionNumberWithoutMinor()
    {
        $version = '3';
        $this->assertTrue(
            $this->helper->isValidVersionNumber($version)
        );
    }
}
