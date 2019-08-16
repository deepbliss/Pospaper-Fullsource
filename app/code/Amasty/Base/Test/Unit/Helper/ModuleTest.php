<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Test\Unit\Helper;

use Amasty\Base\Helper\Module;
use Amasty\Base\Test\Unit\Traits;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class ModuleTest
 *
 * @see Module
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @codingStandardsIgnoreFile
 */
class UnsubscribeTest extends \PHPUnit\Framework\TestCase
{
    use Traits\ObjectManagerTrait;
    use Traits\ReflectionTrait;

    /**
     * @covers Module::getFeedModuleData
     */
    public function testGetFeedModuleData()
    {
        $helper = $this->createPartialMock(Module::class, ['getAllExtensions']);

        $helper->expects($this->any())->method('getAllExtensions')->willReturn([]);

        $this->assertEquals([], $helper->getFeedModuleData('test'));

        $this->setProperty($helper, 'modulesData', [['test1', 'test2']], Module::class);
        $this->assertEquals('test1', $helper->getFeedModuleData(0));
    }
}
