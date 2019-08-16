<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Test\Unit\Observer;

use Amasty\Base\Observer\GenerateInformationTab;
use Amasty\Base\Test\Unit\Traits;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class GenerateInformationTabTest
 *
 * @see GenerateInformationTab
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @codingStandardsIgnoreFile
 */
class GenerateInformationTabTest extends \PHPUnit\Framework\TestCase
{
    use Traits\ObjectManagerTrait;
    use Traits\ReflectionTrait;

    /**
     * @var GenerateInformationTab
     */
    private $observer;

    protected function setUp()
    {
        $block = $this->createPartialMock(
            \Magento\Config\Block\System\Config\Form\Fieldset::class,
            ['getAdditionalModuleContent']
        );

        $this->observer = $this->getObjectManager()->getObject(
            GenerateInformationTab::class,
            []
        );

        $block->expects($this->any())->method('getAdditionalModuleContent')->willReturn('test');

        $this->setProperty($this->observer, 'block', $block, GenerateInformationTab::class);
    }

    /**
     * @covers Info::additionalContent
     */
    public function testAdditionalContent()
    {
        $this->assertEquals(
            '<div class="amasty-additional-content"><span class="message success">test</span></div>',
            $this->invokeMethod($this->observer, 'additionalContent')
        );
    }

    /**
     * @covers Info::showVersionInfo
     */
    public function testShowVersionInfo()
    {
        $this->observer = $this->createPartialMock(
            GenerateInformationTab::class,
            ['getCurrentVersion', 'isLastVersion', 'getModuleName', 'getLogoHtml', 'getChangeLogLink']
        );
        $this->observer->expects($this->any())->method('getCurrentVersion')->willReturn('2.2.2');
        $this->observer->expects($this->any())->method('isLastVersion')->willReturn(false);
        $this->observer->expects($this->any())->method('getModuleName')->willReturn('test');
        $this->observer->expects($this->any())->method('getLogoHtml')->willReturn('test');
        $this->observer->expects($this->any())->method('getChangeLogLink')->willReturn('test');
        $this->assertContains(
            'upgrade-error',
            $this->invokeMethod($this->observer, 'showVersionInfo')
        );
        $this->assertNotContains(
            'last-version',
            $this->invokeMethod($this->observer, 'showVersionInfo')
        );
    }

    /**
     * @covers Info::getModuleCode
     */
    public function testGetModuleCode()
    {
        $block = $this->getObjectManager()->getObject(\Magento\Config\Block\System\Config\Form\Fieldset::class);
        $this->setProperty($this->observer, 'block', $block, GenerateInformationTab::class);
        $this->assertEquals('Magento_Config', $this->invokeMethod($this->observer, 'getModuleCode'));
    }

    /**
     * @covers Info::getUserGuideLink
     */
    public function testGetUserGuideLink()
    {
        $block = $this->getObjectManager()->getObject(\Magento\Config\Block\System\Config\Form\Fieldset::class);
        $block->setUserGuide('test');
        $this->setProperty($this->observer, 'block', $block, GenerateInformationTab::class);
        $this->assertEquals(
            'test?utm_source=extension&utm_medium=backend&utm_campaign=userguide_Magento_Config',
            $this->invokeMethod($this->observer, 'getUserGuideLink')
        );
    }

    /**
     * @covers Info::getModuleName
     */
    public function testGetModuleName()
    {
        $this->observer = $this->createPartialMock(
            GenerateInformationTab::class,
            ['findResourceName']
        );
        $configStructure = $this->createMock(\Magento\Config\Model\Config\Structure::class);

        $this->observer->expects($this->any())->method('findResourceName')->willReturnOnConsecutiveCalls('test', '', '');

        $this->setProperty($this->observer, 'configStructure', $configStructure, GenerateInformationTab::class);

        $this->assertEquals('test', $this->invokeMethod($this->observer, 'getModuleName'));
        $this->setProperty($this->observer, 'moduleData', 'test', GenerateInformationTab::class);
        $this->assertEquals('Extension', $this->invokeMethod($this->observer, 'getModuleName')->getText());
        $this->setProperty(
            $this->observer,
            'moduleData',
            ['name' => 'test for Magento 2'],
            GenerateInformationTab::class
        );
        $this->assertEquals('test', $this->invokeMethod($this->observer, 'getModuleName'));
    }
}
