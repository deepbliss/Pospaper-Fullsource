<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Controller\Adminhtml\Customers;

use Aheadworks\RewardPoints\Controller\Adminhtml\Customers\Index as CustomersIndexController;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Page\Title;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Controller\Adminhtml\Customers\IndexTest
 */
class IndexTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CustomersIndexController
     */
    private $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|PageFactory
     */
    private $resultPageFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Page
     */
    private $resultPageMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Config
     */
    private $pageConfigMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Title
     */
    private $pageTitleMock;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->resultPageFactoryMock = $this->getMockBuilder(PageFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->resultPageMock = $this->getMockBuilder(Page::class)
            ->disableOriginalConstructor()
            ->setMethods(['setActiveMenu', 'getConfig', 'addBreadcrumb'])
            ->getMock();
        $this->pageConfigMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->pageTitleMock = $this->getMockBuilder(Title::class)
            ->disableOriginalConstructor()
            ->getMock();

        $data = [
            'resultPageFactory' => $this->resultPageFactoryMock,
        ];

        $this->object = $objectManager->getObject(CustomersIndexController::class, $data);
    }

    /**
     * Test execute method
     */
    public function testExecute()
    {
        $this->resultPageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->resultPageMock);

        $this->resultPageMock->expects($this->once())
            ->method('setActiveMenu')
            ->with('Aheadworks_RewardPoints::aw_reward_points_customers');

        $this->resultPageMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($this->pageConfigMock);

        $this->pageConfigMock->expects($this->once())
            ->method('getTitle')
            ->willReturn($this->pageTitleMock);

        $this->pageTitleMock->expects($this->once())
            ->method('prepend')
            ->with('Customers');

        $this->resultPageMock->expects($this->once())
            ->method('addBreadcrumb')
            ->with('Aheadworks Reward Points', 'Customers');

        $this->assertInstanceOf(
            Page::class,
            $this->object->execute()
        );
    }

    /**
     * Test ADMIN_RESOURCE attribute
     */
    public function testAdminResourceAttribute()
    {
        $this->assertEquals(
            'Aheadworks_RewardPoints::aw_reward_points_customers',
            CustomersIndexController::ADMIN_RESOURCE
        );
    }
}
