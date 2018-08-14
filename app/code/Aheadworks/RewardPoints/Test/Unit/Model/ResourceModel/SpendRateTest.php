<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Model\ResourceModel;

use Aheadworks\RewardPoints\Model\ResourceModel\SpendRate;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Model\ResourceModel\Db\TransactionManager;
use Magento\Framework\App\ResourceConnection;
use Magento\Customer\Api\Data\GroupInterface;
use Magento\Framework\DB\Adapter\Pdo\Mysql;
use Magento\Framework\DB\Select;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Model\ResourceModel\SpendRateTest
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SpendRateTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var SpendRate
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

        $this->connectionMock = $this->getMockBuilder(Mysql::class)
            ->setMethods(
                [
                    'prepareColumnValue',
                    'delete',
                    'describeTable',
                    'insert',
                    'select',
                    'quoteIdentifier',
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

        $this->object = $objectManager->getObject(SpendRate::class, $data);
    }

    /**
     * Test clear method
     */
    public function testClearMethod()
    {
        $this->resourcesMock->expects($this->once())
            ->method('getConnection')
            ->with('default')
            ->willReturn($this->connectionMock);
        $this->resourcesMock->expects($this->once())
            ->method('getTableName')
            ->with('aw_rp_spend_rate', 'default')
            ->will($this->returnArgument(0));

        $this->transactionManagerMock->expects($this->once())
            ->method('start')
            ->with($this->connectionMock)
            ->will($this->returnArgument(0));
        $this->transactionManagerMock->expects($this->once())
            ->method('commit')
            ->willReturnSelf();
        $this->transactionManagerMock->expects($this->never())
            ->method('rollBack')
            ->willReturnSelf();
        $this->connectionMock->expects($this->once())
            ->method('delete')
            ->with('aw_rp_spend_rate')
            ->willReturnSelf();

        $this->object->clear();
    }

    /**
     * Test clear method, throw exception
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Unable delete data
     */
    public function testClearException()
    {
        $this->resourcesMock->expects($this->once())
            ->method('getConnection')
            ->with('default')
            ->willReturn($this->connectionMock);
        $this->resourcesMock->expects($this->once())
            ->method('getTableName')
            ->with('aw_rp_spend_rate', 'default')
            ->will($this->returnArgument(0));

        $this->transactionManagerMock->expects($this->once())
            ->method('start')
            ->with($this->connectionMock)
            ->will($this->returnArgument(0));
        $this->transactionManagerMock->expects($this->never())
            ->method('commit')
            ->willReturnSelf();
        $this->transactionManagerMock->expects($this->once())
            ->method('rollBack')
            ->willReturnSelf();

        $this->connectionMock->expects($this->once())
            ->method('delete')
            ->with('aw_rp_spend_rate')
            ->willThrowException(new \Exception('Unable delete data'));

        $this->object->clear();
    }

    /**
     * Test saveConfigValue for not array data
     *
     * @dataProvider saveConfigNotArrayValueProvider
     */
    public function testSaveConfigValueNotArray($expectedValue)
    {
        $this->resourcesMock->expects($this->never())
            ->method('getConnection')
            ->with('default')
            ->willReturn($this->connectionMock);
        $this->resourcesMock->expects($this->never())
            ->method('getTableName')
            ->with('aw_rp_spend_rate', 'default')
            ->will($this->returnArgument(0));

        $this->transactionManagerMock->expects($this->never())
            ->method('start')
            ->with($this->connectionMock)
            ->will($this->returnArgument(0));
        $this->transactionManagerMock->expects($this->never())
            ->method('commit')
            ->willReturnSelf();

        $this->connectionMock->expects($this->never())
            ->method('delete')
            ->with('aw_rp_spend_rate')
            ->willReturnSelf();

        $this->object->saveConfigValue($expectedValue);
    }

    /**
     * Test saveConfigValue for empty array
     */
    public function testSaveConfigValueEmpty()
    {
        $expectedValue = [];

        $this->resourcesMock->expects($this->exactly(2))
            ->method('getConnection')
            ->with('default')
            ->willReturn($this->connectionMock);
        $this->resourcesMock->expects($this->exactly(1))
            ->method('getTableName')
            ->with('aw_rp_spend_rate', 'default')
            ->will($this->returnArgument(0));

        $this->transactionManagerMock->expects($this->exactly(2))
            ->method('start')
            ->with($this->connectionMock)
            ->will($this->returnArgument(0));
        $this->transactionManagerMock->expects($this->exactly(2))
            ->method('commit')
            ->willReturnSelf();

        $this->connectionMock->expects($this->once())
            ->method('delete')
            ->with('aw_rp_spend_rate')
            ->willReturnSelf();

        $this->object->saveConfigValue($expectedValue);
    }

    /**
     * Test saveConfigValue method
     */
    public function testSaveConfigValueMethod()
    {
        $data = [
            'website_id' => 2,
            'customer_group_id' => 1,
        ];

        $columns = [
            'website_id' => [
                'NULLABLE' => true,
                'DEFAULT' => false,
                'IDENTITY' => false,
                'COLUMN_NAME' => 'website_id',
                'DATA_TYPE' => 'int',
            ],
            'customer_group_id' => [
                'NULLABLE' => true,
                'DEFAULT' => false,
                'IDENTITY' => false,
                'COLUMN_NAME' => 'customer_group_id',
                'DATA_TYPE' => 'int',
            ],
        ];

        $this->resourcesMock->expects($this->exactly(3))
            ->method('getConnection')
            ->with('default')
            ->willReturn($this->connectionMock);
        $this->resourcesMock->expects($this->exactly(1))
            ->method('getTableName')
            ->with('aw_rp_spend_rate', 'default')
            ->will($this->returnArgument(0));

        $this->transactionManagerMock->expects($this->exactly(2))
            ->method('start')
            ->with($this->connectionMock)
            ->will($this->returnArgument(0));
        $this->transactionManagerMock->expects($this->exactly(2))
            ->method('commit')
            ->willReturnSelf();

        $this->connectionMock->expects($this->once())
            ->method('delete')
            ->with('aw_rp_spend_rate')
            ->willReturnSelf();

        $this->connectionMock->expects($this->exactly(1))
            ->method('describeTable')
            ->with('aw_rp_spend_rate')
            ->willReturn($columns);

        $this->connectionMock->expects($this->at(2))
            ->method('prepareColumnValue')
            ->with($columns['website_id'], $data['website_id'])
            ->willReturn($data['website_id']);

        $this->connectionMock->expects($this->at(3))
            ->method('prepareColumnValue')
            ->with($columns['customer_group_id'], $data['customer_group_id'])
            ->willReturn($data['customer_group_id']);

        $this->connectionMock->expects($this->exactly(1))
            ->method('insert')
            ->with('aw_rp_spend_rate', $data)
            ->willReturn(1);

        $this->object->saveConfigValue([$data]);
    }

    /**
     * Test saveConfigValue method for null value for data
     */
    public function testSaveConfigValueNullField()
    {
        $data = [
            'website_id' => null,
            'customer_group_id' => 1,
        ];

        $columns = [
            'website_id' => [
                'NULLABLE' => true,
                'DEFAULT' => false,
                'IDENTITY' => false,
                'COLUMN_NAME' => 'website_id',
                'DATA_TYPE' => 'int',
            ],
            'customer_group_id' => [
                'NULLABLE' => true,
                'DEFAULT' => false,
                'IDENTITY' => false,
                'COLUMN_NAME' => 'customer_group_id',
                'DATA_TYPE' => 'int',
            ],
        ];

        $this->resourcesMock->expects($this->exactly(3))
            ->method('getConnection')
            ->with('default')
            ->willReturn($this->connectionMock);
        $this->resourcesMock->expects($this->exactly(1))
            ->method('getTableName')
            ->with('aw_rp_spend_rate', 'default')
            ->will($this->returnArgument(0));

        $this->transactionManagerMock->expects($this->exactly(2))
            ->method('start')
            ->with($this->connectionMock)
            ->will($this->returnArgument(0));
        $this->transactionManagerMock->expects($this->exactly(2))
            ->method('commit')
            ->willReturnSelf();

        $this->connectionMock->expects($this->once())
            ->method('delete')
            ->with('aw_rp_spend_rate')
            ->willReturnSelf();

        $this->connectionMock->expects($this->exactly(1))
            ->method('describeTable')
            ->with('aw_rp_spend_rate')
            ->willReturn($columns);

        $this->connectionMock->expects($this->at(2))
            ->method('prepareColumnValue')
            ->with($columns['customer_group_id'], $data['customer_group_id'])
            ->willReturn($data['customer_group_id']);

        $this->connectionMock->expects($this->exactly(1))
            ->method('insert')
            ->with('aw_rp_spend_rate', $data)
            ->willReturn(1);

        $this->object->saveConfigValue([$data]);
    }

    /**
     * Test saveConfigValue method for Zend_Db_Expr value for data
     */
    public function testSaveConfigValueZendDbExprMethod()
    {
        $data = [
            'website_id' => 5,
            'customer_group_id' => new \Zend_Db_Expr(25),
        ];

        $columns = [
            'website_id' => [
                'NULLABLE' => true,
                'DEFAULT' => false,
                'IDENTITY' => false,
                'COLUMN_NAME' => 'website_id',
                'DATA_TYPE' => 'int',
            ],
            'customer_group_id' => [
                'NULLABLE' => true,
                'DEFAULT' => false,
                'IDENTITY' => false,
                'COLUMN_NAME' => 'customer_group_id',
                'DATA_TYPE' => 'int',
            ],
        ];

        $this->resourcesMock->expects($this->exactly(3))
            ->method('getConnection')
            ->with('default')
            ->willReturn($this->connectionMock);
        $this->resourcesMock->expects($this->exactly(1))
            ->method('getTableName')
            ->with('aw_rp_spend_rate', 'default')
            ->will($this->returnArgument(0));

        $this->transactionManagerMock->expects($this->exactly(2))
            ->method('start')
            ->with($this->connectionMock)
            ->will($this->returnArgument(0));
        $this->transactionManagerMock->expects($this->exactly(2))
            ->method('commit')
            ->willReturnSelf();

        $this->connectionMock->expects($this->once())
            ->method('delete')
            ->with('aw_rp_spend_rate')
            ->willReturnSelf();

        $this->connectionMock->expects($this->exactly(1))
            ->method('describeTable')
            ->with('aw_rp_spend_rate')
            ->willReturn($columns);

        $this->connectionMock->expects($this->at(2))
            ->method('prepareColumnValue')
            ->with($columns['website_id'], $data['website_id'])
            ->willReturn($data['website_id']);

        $this->connectionMock->expects($this->exactly(1))
            ->method('insert')
            ->with('aw_rp_spend_rate', $data)
            ->willReturn(1);

        $this->object->saveConfigValue([$data]);
    }

    /**
     * Test saveConfigValue method throw excpetion
     *
     * @expectedException \Exception
     */
    public function testSaveConfigThrowException()
    {
        $data = [
            'website_id' => 2,
            'customer_group_id' => 1,
        ];

        $columns = [
            'website_id' => [
                'NULLABLE' => true,
                'DEFAULT' => false,
                'IDENTITY' => false,
                'COLUMN_NAME' => 'website_id',
                'DATA_TYPE' => 'int',
            ],
            'customer_group_id' => [
                'NULLABLE' => true,
                'DEFAULT' => false,
                'IDENTITY' => false,
                'COLUMN_NAME' => 'customer_group_id',
                'DATA_TYPE' => 'int',
            ],
        ];

        $this->resourcesMock->expects($this->exactly(3))
            ->method('getConnection')
            ->with('default')
            ->willReturn($this->connectionMock);
        $this->resourcesMock->expects($this->exactly(1))
            ->method('getTableName')
            ->with('aw_rp_spend_rate', 'default')
            ->will($this->returnArgument(0));

        $this->transactionManagerMock->expects($this->exactly(2))
            ->method('start')
            ->with($this->connectionMock)
            ->will($this->returnArgument(0));
        $this->transactionManagerMock->expects($this->exactly(1))
            ->method('commit')
            ->willReturnSelf();

        $this->connectionMock->expects($this->once())
            ->method('delete')
            ->with('aw_rp_spend_rate')
            ->willReturnSelf();

        $this->connectionMock->expects($this->exactly(1))
            ->method('describeTable')
            ->with('aw_rp_spend_rate')
            ->willReturn($columns);

        $this->connectionMock->expects($this->at(2))
            ->method('prepareColumnValue')
            ->with($columns['website_id'], $data['website_id'])
            ->willReturn($data['website_id']);

        $this->connectionMock->expects($this->at(3))
            ->method('prepareColumnValue')
            ->with($columns['customer_group_id'], $data['customer_group_id'])
            ->willReturn($data['customer_group_id']);

        $this->connectionMock->expects($this->exactly(1))
            ->method('insert')
            ->with('aw_rp_spend_rate', $data)
            ->willThrowException(new \Exception());

        $this->object->saveConfigValue([$data]);
    }

    /**
     * Test getRateRowId method
     */
    public function testGetRateRowIdMethod()
    {
        $expectedValue = 5;
        $customerGroupId = 1;
        $customerGeneralGroupId = GroupInterface::CUST_GROUP_ALL;
        $lifetimeSalesAmount = 1000;
        $websiteId = 1;

        $this->resourcesMock->expects($this->exactly(2))
            ->method('getConnection')
            ->with('default')
            ->willReturn($this->connectionMock);
        $this->resourcesMock->expects($this->once())
            ->method('getTableName')
            ->with('aw_rp_spend_rate', 'default')
            ->will($this->returnArgument(0));

        $selectMock = $this->getMockBuilder(Select::class)
            ->setMethods(['from', 'where', 'order', 'orWhere'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->connectionMock->expects($this->once())
            ->method('select')
            ->willReturn($selectMock);

        $selectMock->expects($this->once())
            ->method('from')
            ->with('aw_rp_spend_rate')
            ->willReturnSelf();

        $this->connectionMock->expects($this->at(1))
            ->method('quoteIdentifier')
            ->with('aw_rp_spend_rate.customer_group_id')
            ->willReturn('`aw_rp_spend_rate`.`customer_group_id`');

        $this->connectionMock->expects($this->at(2))
            ->method('quoteIdentifier')
            ->with('aw_rp_spend_rate.website_id')
            ->willReturn('`aw_rp_spend_rate`.`website_id`');
        $this->connectionMock->expects($this->at(3))
            ->method('quoteIdentifier')
            ->with('aw_rp_spend_rate.lifetime_sales_amount')
            ->willReturn('`aw_rp_spend_rate`.`lifetime_sales_amount`');

        $selectMock->expects($this->at(1))
            ->method('where')
            ->with(
                '(`aw_rp_spend_rate`.`customer_group_id`=? '
                . 'OR `aw_rp_spend_rate`.`customer_group_id`='
                . $customerGeneralGroupId
                . ')',
                $customerGroupId
            )
            ->willReturnSelf();

        $selectMock->expects($this->at(2))
            ->method('where')
            ->with('`aw_rp_spend_rate`.`website_id`=?', $websiteId)
            ->willReturnSelf();
        $selectMock->expects($this->at(3))
            ->method('where')
            ->with('`aw_rp_spend_rate`.`lifetime_sales_amount`<=?', $lifetimeSalesAmount)
            ->willReturnSelf();
        $selectMock->expects($this->exactly(2))
            ->method('order')
            ->willReturnSelf();

        $this->connectionMock->expects($this->once())
            ->method('fetchOne')
            ->with($selectMock)
            ->willReturn($expectedValue);

        $actualValue = $this->object->getRateRowId(
            $customerGroupId,
            $lifetimeSalesAmount,
            $websiteId
        );
        $this->assertEquals($expectedValue, $actualValue);
    }

    /**
     * Test getRateRowId method for null connection
     */
    public function testGetRateRowIdMethodNullConnection()
    {
        $customerGroupId = 1;
        $lifetimeSalesAmount = 1000;
        $websiteId = 1;

        $this->resourcesMock->expects($this->once())
            ->method('getConnection')
            ->with('default')
            ->willReturn(null);

        $actualResult = $this->object->getRateRowId(
            $customerGroupId,
            $lifetimeSalesAmount,
            $websiteId
        );
        $this->assertNull($actualResult);
    }

    /**
     * Test getRateRowId method for null params
     */
    public function testGetRateRowIdMethodNullParams()
    {
        $customerGroupId = null;
        $lifetimeSalesAmount = 1000;
        $websiteId = 1;

        $this->resourcesMock->expects($this->once())
            ->method('getConnection')
            ->with('default')
            ->willReturn($this->connectionMock);

        $actualResult = $this->object->getRateRowId(
            $customerGroupId,
            $lifetimeSalesAmount,
            $websiteId
        );
        $this->assertNull($actualResult);
    }

    /**
     * Test not array data provider
     *
     * @return array
     */
    public function saveConfigNotArrayValueProvider()
    {
        return [
            ['setTestConfigValue'],
            [15],
            [null],
            [new \stdClass(11)],
        ];
    }
}
