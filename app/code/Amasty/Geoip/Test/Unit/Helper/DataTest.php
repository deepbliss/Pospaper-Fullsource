<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Geoip
 */


namespace Amasty\Geoip\Test\Unit\Helper;

use Amasty\Geoip\Helper\Data;
use Amasty\Geoip\Test\Unit\Traits;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class DataTest
 *
 * @see Data
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @codingStandardsIgnoreFile
 */
class DataTest extends \PHPUnit\Framework\TestCase
{
    use Traits\ObjectManagerTrait;
    use Traits\ReflectionTrait;

    const EXIST_FILE = BP . '/' . 'index.php';
    const NOT_EXIST_FILE = BP . '/' . 'missingIndexFile.php';

    /**
     * @covers Data::isDone
     *
     * @dataProvider isDoneDataProvider
     *
     * @throws \ReflectionException
     */
    public function testIsDone($flushCache, $amount, $value, $expectedResult)
    {
        /** @var Data|MockObject $helper */
        $helper = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->setMethods(['flushConfigCache'])
            ->getMock();
        $helper->expects($this->exactly($amount))->method('flushConfigCache');

        /** @var \Magento\Framework\App\Config\ScopeConfigInterface|MockObject $scopeConfig */
        $scopeConfig = $this->createMock(\Magento\Framework\App\Config\ScopeConfigInterface::class);
        $scopeConfig->expects($this->any())->method('getValue')->willReturn($value);

        $this->setProperty($helper, 'scopeConfig', $scopeConfig, Data::class);

        $this->assertEquals($expectedResult, $helper->isDone($flushCache));
    }

    /**
     * @covers Data::isFileExist
     *
     * @dataProvider isFileExistDataProvider
     *
     * @throws \ReflectionException
     */
    public function testIsFileExist($file, $expectedResult)
    {
        /** @var \Magento\Framework\Filesystem\Driver\File $fileDriver */
        $fileDriver = $this->getObjectManager()->getObject(\Magento\Framework\Filesystem\Driver\File::class);
        /** @var Data $helper */
        $helper = $this->getObjectManager()->getObject(Data::class,
            [
                'fileDriver' => $fileDriver
            ]
        );

        $this->assertEquals($expectedResult, $helper->isFileExist($file));
    }

    /**
     * @covers Data::isCacheEnabled
     *
     * @dataProvider isCacheEnabledDataProvider
     *
     * @throws \ReflectionException
     */
    public function testIsCacheEnabled($expectedResult, $value = null)
    {
        /** @var \Magento\Framework\App\Cache\StateInterface|MockObject $state */
        $state = $this->createMock(\Magento\Framework\App\Cache\StateInterface::class);
        $state->expects($this->any())->method('isEnabled')->willReturn(true);

        /** @var Data $helper */
        $helper = $this->getObjectManager()->getObject(Data::class,
            [
                '_cacheEnabled' => $value,
                '_state' => $state
            ]
        );

        $this->assertEquals($expectedResult, $helper->isCacheEnabled('redis'));
    }

    /**
     * Data provider for isCacheEnabled test
     * @return array
     */
    public function isCacheEnabledDataProvider()
    {
        return [
            [true, true],
            [false, false],
            [true],
        ];
    }

    /**
     * Data provider for isFileExist test
     * @return array
     */
    public function isFileExistDataProvider()
    {
        return [
            [self::EXIST_FILE, true],
            [self::NOT_EXIST_FILE, false],
        ];
    }

    /**
     * Data provider for isDone test
     * @return array
     */
    public function isDoneDataProvider()
    {
        return [
            [true, 1, true, true],
            [false, 0, false, false],
            [false, 0, true, true],
            [true, 1, false, false],
            [1, 1, false, false],
            [0, 0, false, false],
            ['test', 1, false, false],
            [-1, 1, false, false],
        ];
    }
}
