<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Test\Unit\Block;

use Amasty\Base\Block\Info;
use Amasty\Base\Test\Unit\Traits;
use Magento\Framework\App\ProductMetadataInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class InfoTest
 *
 * @see Info
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @codingStandardsIgnoreFile
 */
class InfoTest extends \PHPUnit\Framework\TestCase
{
    use Traits\ObjectManagerTrait;
    use Traits\ReflectionTrait;

    /**
     * @covers Info::getSystemTime
     */
    public function testGetSystemTime()
    {
        $productMetadata = $this->createMock(ProductMetadataInterface::class);
        $resourceConnection = $this->createPartialMock(
            \Magento\Framework\App\ResourceConnection::class,
            ['getConnection', 'fetchOne']
        );
        $localeDate = $this->getMockBuilder(
            \Magento\Framework\Stdlib\DateTime\TimezoneInterface::class)
            ->setMethods(['date', 'format'])
            ->disableOriginalConstructor()
        ->getMockForAbstractClass();

        $block = $this->createPartialMock(
            Info::class,
            ['getFieldHtml']
        );

        $this->setProperty($block, 'productMetadata' , $productMetadata, Info::class);
        $this->setProperty($block, 'resourceConnection' , $resourceConnection, Info::class);
        $this->setProperty($block, '_localeDate' , $localeDate);

        $productMetadata->expects($this->any())->method('getVersion')->willReturnOnConsecutiveCalls('2.3.3', '2.1.1');
        $resourceConnection->expects($this->once())->method('getConnection')->willReturn($resourceConnection);
        $resourceConnection->expects($this->once())->method('fetchOne');
        $localeDate->expects($this->once())->method('date')->willReturn($localeDate);
        $localeDate->expects($this->once())->method('format');

        $this->invokeMethod($block, 'getSystemTime', ['']);
        $this->invokeMethod($block, 'getSystemTime', ['']);
    }
}
