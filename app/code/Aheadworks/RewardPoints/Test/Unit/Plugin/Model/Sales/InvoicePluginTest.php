<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Plugin\Model\Sales;

use Aheadworks\RewardPoints\Plugin\Model\Sales\InvoicePlugin;
use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Api\Data\StoreInterface;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Plugin\Model\Service\InvoicePluginTest
 */
class InvoicePluginTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var InvoicePlugin
     */
    private $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CustomerRewardPointsManagementInterface
     */
    private $customerRewardPointsManagementMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Invoice
     */
    private $invoiceMock;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->invoiceMock = $this->getMockBuilder(Invoice::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getEntityId'
                ]
            )->getMock();

        $this->customerRewardPointsManagementMock = $this->getMockBuilder(
            CustomerRewardPointsManagementInterface::class
        )
            ->disableOriginalConstructor()
            ->setMethods(['addPointsForPurchases'])
            ->getMockForAbstractClass();

        $data = [
            'customerRewardPointsService' => $this->customerRewardPointsManagementMock
        ];

        $this->object = $objectManager->getObject(InvoicePlugin::class, $data);
    }

    /**
     * Test afterAfterSave method
     */
    public function testAfterAfterSaveMethod()
    {
        $entityId = 1;

        $this->invoiceMock->expects($this->once())
            ->method('getEntityId')
            ->willReturn($entityId);
        $this->customerRewardPointsManagementMock->expects($this->once())
            ->method('addPointsForPurchases')
            ->with($entityId)
            ->willReturnSelf();

        $invoiceMock = $this->getMockBuilder(Invoice::class)
            ->setMethods([])
            ->disableOriginalConstructor()
            ->getMock();
        $this->object->afterPay($invoiceMock);

        $this->object->afterAfterSave($this->invoiceMock);
    }
}
