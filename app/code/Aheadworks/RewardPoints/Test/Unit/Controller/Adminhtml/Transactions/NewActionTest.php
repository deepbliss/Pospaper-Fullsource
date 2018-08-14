<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Controller\Adminhtml\Transactions;

use Aheadworks\RewardPoints\Controller\Adminhtml\Transactions\NewAction;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Page\Title;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Controller\Adminhtml\Transactions\NewActionTest
 */
class NewActionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var NewAction
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

        $this->object = $objectManager->getObject(NewAction::class, $data);
    }

    /**
     * Test execute method
     */
    public function testExecuteMethod()
    {
        $this->resultPageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->resultPageMock);

        $this->resultPageMock->expects($this->once())
            ->method('setActiveMenu')
            ->with('Aheadworks_RewardPoints::aw_reward_points_transaction');

        $this->resultPageMock->expects($this->exactly(3))
            ->method('addBreadcrumb')
            ->withConsecutive(
                ['Aheadworks Reward Points', 'Aheadworks Reward Points'],
                ['Transactions', 'Transactions'],
                ['New Transaction', 'New Transaction']
            )
            ->willReturnSelf();

        $this->resultPageMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($this->pageConfigMock);

        $this->pageConfigMock->expects($this->once())
            ->method('getTitle')
            ->willReturn($this->pageTitleMock);

        $this->pageTitleMock->expects($this->exactly(2))
            ->method('prepend')
            ->withConsecutive(
                ['Transactions'],
                ['New Transaction']
            );

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
        $this->assertEquals('Aheadworks_RewardPoints::aw_reward_points_transaction_save', NewAction::ADMIN_RESOURCE);
    }
}
