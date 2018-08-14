<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Model\ResourceModel;

use Aheadworks\RewardPoints\Model\ResourceModel\Order;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Model\ResourceModel\Db\TransactionManager;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\Pdo\Mysql;
use Magento\Framework\DB\Select;
use Magento\Sales\Model\Order as SalesOrder;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Model\ResourceModel\OrderTest
 */
class OrderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Order
     */
    private $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Context
     */
    private $contextMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|TransactionManager
     */
    private $transactionManagerMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ResourceConnection
     */
    private $resourcesMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Mysql
     */
    private $connectionMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Select
     */
    private $selectMock;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->transactionManagerMock = $this->getMockBuilder(TransactionManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->resourcesMock = $this->getMockBuilder(ResourceConnection::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->selectMock = $this->getMockBuilder(Select::class)
            ->setMethods(
                [
                    'joinInner',
                    'where',
                    'from',
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();

        $this->connectionMock = $this->getMockBuilder(Mysql::class)
            ->setMethods(
                [
                    'select',
                    'fetchOne',
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();

        $this->contextMock->expects($this->once())
            ->method('getTransactionManager')
            ->willReturn($this->transactionManagerMock);
        $this->contextMock->expects($this->once())
            ->method('getResources')
            ->willReturn($this->resourcesMock);

        $data = [
            'context' => $this->contextMock,
        ];

        $this->object = $objectManager->getObject(Order::class, $data);
    }

    /**
     * Test getCustomersOrdersByProductId
     */
    public function testIsCustomersOwnerOfProductId()
    {
        $customerId = 2;
        $productId = 1049;

        $this->resourcesMock->expects($this->exactly(1))
            ->method('getConnection')
            ->with('sales')
            ->willReturn($this->connectionMock);

        $this->resourcesMock->expects($this->exactly(2))
            ->method('getTableName')
            ->withConsecutive(['sales_order'], ['sales_order_item'])
            ->willReturnOnConsecutiveCalls('sales_order', 'sales_order_item');

        $this->connectionMock->expects($this->once())
            ->method('select')
            ->willReturn($this->selectMock);

        $this->selectMock->expects($this->once())
            ->method('from')
            ->with('sales_order', 'entity_id')
            ->willReturnSelf();
        
        $this->selectMock->expects($this->once())
            ->method('joinInner')
            ->with(
                ['items' => 'sales_order_item'],
                'entity_id = items.order_id AND items.product_id = ' . $productId,
                ['product_id' => 'product_id']
            )
            ->willReturnSelf();
        
        $this->selectMock->expects($this->at(2))
            ->method('where')
            ->with('customer_id = '. $customerId)
            ->willReturnSelf();
        $this->selectMock->expects($this->at(3))
            ->method('where')
            ->with('sales_order.state IN (?)', [SalesOrder::STATE_COMPLETE])
            ->willReturnSelf();
        
        $this->connectionMock->expects($this->once())
            ->method('fetchOne')
            ->with($this->selectMock)
            ->willReturn(null);
        
        $expectedValue = false;
        $actualValue = $this->object->isCustomersOwnerOfProductId($customerId, $productId);

        $this->assertEquals($expectedValue, $actualValue);
    }
}
