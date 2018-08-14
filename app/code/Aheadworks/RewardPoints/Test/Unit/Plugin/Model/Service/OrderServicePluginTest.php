<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Plugin\Model\Service;

use Aheadworks\RewardPoints\Plugin\Model\Service\OrderServicePlugin;
use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Magento\Sales\Model\Service\OrderService;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Sales\Api\Data\OrderInterface;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Plugin\Model\Service\OrderServicePluginTest
 */
class OrderServicePluginTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var OrderServicePlugin
     */
    private $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CustomerRewardPointsManagementInterface
     */
    private $customerRewardPointsManagementMock;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->customerRewardPointsManagementMock = $this->getMockBuilder(
            CustomerRewardPointsManagementInterface::class
        )
            ->disableOriginalConstructor()
            ->setMethods(['spendPointsOnCheckout', 'reimbursedSpentRewardPointsOrderCancel'])
            ->getMockForAbstractClass();

        $data = [
            'customerRewardPointsService' => $this->customerRewardPointsManagementMock
        ];

        $this->object = $objectManager->getObject(OrderServicePlugin::class, $data);
    }

    /**
     * Test afterPlace method
     */
    public function testAroundCancelMethod()
    {
        $orderId = 1;

        $orderServiceMock = $this->getMockBuilder(OrderService::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->customerRewardPointsManagementMock->expects($this->once())
            ->method('reimbursedSpentRewardPointsOrderCancel')
            ->with($orderId)
            ->willReturnSelf();

        $orderMock = $this->getMockBuilder(OrderInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $closure = function ($orderId) use ($orderMock) {
            return $orderMock;
        };
        $this->object->aroundCancel($orderServiceMock, $closure, $orderId);
    }

    /**
     * Test afterPlace method
     */
    public function testAfterPlaceMethod()
    {
        $entityId = 1;

        $orderServiceMock = $this->getMockBuilder(OrderService::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $orderMock = $this->getMockBuilder(OrderInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $orderMock->expects($this->once())
            ->method('getEntityId')
            ->willReturn($entityId);
        $this->customerRewardPointsManagementMock->expects($this->once())
            ->method('spendPointsOnCheckout')
            ->with($entityId)
            ->willReturnSelf();

        $this->object->afterPlace($orderServiceMock, $orderMock);
    }
}
