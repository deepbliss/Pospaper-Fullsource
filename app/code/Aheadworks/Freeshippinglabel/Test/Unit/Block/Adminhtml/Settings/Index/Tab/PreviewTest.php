<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Test\Unit\Block\Adminhtml\Settings\Index\Tab;

use Aheadworks\Freeshippinglabel\Block\Adminhtml\Settings\Index\Tab\Preview;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Backend\Block\Template\Context;
use PHPUnit\Framework\TestCase;

/**
 * Test for \Aheadworks\Freeshippinglabel\Block\Adminhtml\Settings\Index\Tab\Preview
 */
class PreviewTest extends TestCase
{
    /**
     * @var Preview
     */
    private $block;

    /**
     * @var StoreManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $storeManagerMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->storeManagerMock = $this->getMockBuilder(StoreManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $contextMock = $objectManager->getObject(
            Context::class,
            [
                'storeManager' => $this->storeManagerMock
            ]
        );
        $this->block = $objectManager->getObject(
            Preview::class,
            [
                'context' => $contextMock
            ]
        );
    }

    /**
     * Testing of getScriptOptions method
     */
    public function testGetScriptOptions()
    {
        $this->storeManagerMock->expects($this->once())
            ->method('getStores')
            ->willReturn([]);
        $this->assertJson($this->block->getScriptOptions());
    }
}
