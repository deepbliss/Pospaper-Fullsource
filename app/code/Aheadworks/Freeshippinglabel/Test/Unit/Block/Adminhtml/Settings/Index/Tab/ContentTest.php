<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Test\Unit\Block\Adminhtml\Settings\Index\Tab;

use Aheadworks\Freeshippinglabel\Block\Adminhtml\Settings\Index\Tab\Content;
use Magento\Framework\Registry;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Store\Model\System\Store as SystemStore;
use PHPUnit\Framework\TestCase;

/**
 * Test for \Aheadworks\Freeshippinglabel\Block\Adminhtml\Settings\Index\Tab\Content
 */
class ContentTest extends TestCase
{
    /**
     * @var Content
     */
    private $block;

    /**
     * @var StoreManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $storeManagerMock;

    /**
     * @var SystemStore|\PHPUnit_Framework_MockObject_MockObject
     */
    private $systemStoreMock;

    /**
     * @var Registry|\PHPUnit_Framework_MockObject_MockObject
     */
    private $coreRegistryMock;

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
        $this->systemStoreMock = $this->createPartialMock(
            SystemStore::class,
            ['getStoreValuesForForm']
        );
        $this->coreRegistryMock = $this->createPartialMock(
            Registry::class,
            ['registry']
        );
        $contextMock = $objectManager->getObject(
            Context::class,
            [
                'storeManager' => $this->storeManagerMock
            ]
        );
        $this->block = $objectManager->getObject(
            Content::class,
            [
                'context' => $contextMock,
                'systemStore' => $this->systemStoreMock,
                'coreRegistry' => $this->coreRegistryMock
            ]
        );
    }

    /**
     * Testing of isSingleStoreMode method
     */
    public function testIsSingleStoreMode()
    {
        $isSinglestoreMode = false;
        $this->storeManagerMock->expects($this->once())
            ->method('isSingleStoreMode')
            ->willReturn($isSinglestoreMode);
        $this->assertEquals($isSinglestoreMode, $this->block->isSingleStoreMode());
    }

    /**
     * Testing of getStoresOptions method
     */
    public function testGetStoresOptions()
    {
        $options = [['label' => __('All Store Views'), 'value' => 0]];
        $this->systemStoreMock->expects($this->once())
            ->method('getStoreValuesForForm')
            ->with(false, true)
            ->willReturn($options);
        $this->assertEquals($options, $this->block->getStoresOptions());
    }

    /**
     * Testing of getContentItems method
     */
    public function testGetContentItems()
    {
        $this->coreRegistryMock->expects($this->once())
            ->method('registry')
            ->with('aw_fslabel_label_content')
            ->willReturn([]);
        $this->assertTrue(is_array($this->block->getContentItems()));
    }
}
