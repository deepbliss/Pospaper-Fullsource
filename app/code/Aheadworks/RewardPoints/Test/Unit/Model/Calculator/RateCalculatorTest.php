<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Model\Calculator;

use Aheadworks\RewardPoints\Model\Calculator\RateCalculator;
use Aheadworks\RewardPoints\Api\Data\EarnRateInterface;
use Aheadworks\RewardPoints\Api\Data\SpendRateInterface;
use Aheadworks\RewardPoints\Api\Data\PointsSummaryInterface;
use Aheadworks\RewardPoints\Api\EarnRateRepositoryInterface;
use Aheadworks\RewardPoints\Api\SpendRateRepositoryInterface;
use Aheadworks\RewardPoints\Model\Service\PointsSummaryService;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\Data\GroupInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Sales\Model\ResourceModel\Sale\CollectionFactory as SaleCollectionFactory;
use Magento\Sales\Model\ResourceModel\Sale\Collection as SaleCollection;
use Magento\Sales\Model\Order;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\DB\Select;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Api\Data\WebsiteInterface;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Model\Calculator$RateCalculatorTest
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class RateCalculatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var RateCalculator
     */
    private $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|EarnRateRepositoryInterface
     */
    private $earnRateRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|SpendRateRepositoryInterface
     */
    private $spendRateRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|PointsSummaryService
     */
    private $pointsSummaryServiceMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|SaleCollectionFactory
     */
    private $saleCollectionFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CustomerRepositoryInterface
     */
    private $customerRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|GroupManagementInterface
     */
    private $groupServiceMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|GroupInterface
     */
    private $groupMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Select
     */
    private $selectMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|AdapterInterface
     */
    private $connectionMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|PriceCurrencyInterface
     */
    private $priceCurrencyMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|StoreManagerInterface
     */
    private $storeManagerMock;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->earnRateRepositoryMock = $this->getMockBuilder(EarnRateRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMockForAbstractClass();

        $this->priceCurrencyMock = $this->getMockBuilder(PriceCurrencyInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['convertAndRound'])
            ->getMockForAbstractClass();

        $this->spendRateRepositoryMock = $this->getMockBuilder(SpendRateRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMockForAbstractClass();

        $this->pointsSummaryServiceMock = $this->getMockBuilder(PointsSummaryService::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCustomerRewardPointsBalance'])
            ->getMock();

        $this->saleCollectionFactoryMock = $this->getMockBuilder(SaleCollectionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $this->selectMock = $this->getMockBuilder(Select::class)
            ->disableOriginalConstructor()
            ->setMethods(['from', 'where', 'group'])
            ->getMock();

        $this->selectMock->expects($this->any())
            ->method('from')
            ->willReturnSelf();

        $this->selectMock->expects($this->any())
            ->method('where')
            ->willReturnSelf();

        $this->selectMock->expects($this->any())
            ->method('group')
            ->willReturnSelf();

        $this->connectionMock = $this->getMockBuilder(AdapterInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['select', 'fetchOne'])
            ->getMockForAbstractClass();

        $this->connectionMock->expects($this->any())
            ->method('select')
            ->willReturn($this->selectMock);

        $this->customerRepositoryMock = $this->getMockBuilder(CustomerRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getById', 'getGroupId'])
            ->getMockForAbstractClass();

        $this->groupServiceMock = $this->getMockBuilder(GroupManagementInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getDefaultGroup'])
            ->getMockForAbstractClass();

        $this->groupMock = $this->getMockBuilder(GroupInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMockForAbstractClass();

        $this->storeManagerMock = $this->getMockForAbstractClass(StoreManagerInterface::class);

        $data = [
            'earnRateRepository' => $this->earnRateRepositoryMock,
            'spendRateRepository' => $this->spendRateRepositoryMock,
            'pointsSummaryService' => $this->pointsSummaryServiceMock,
            'saleCollectionFactory' => $this->saleCollectionFactoryMock,
            'customerRepository' => $this->customerRepositoryMock,
            'groupService' => $this->groupServiceMock,
            'priceCurrency' => $this->priceCurrencyMock,
            'storeManager' => $this->storeManagerMock
        ];

        $this->object = $objectManager->getObject(RateCalculator::class, $data);
    }

    /**
     * Test setCustomerId method
     */
    public function testSetCustomerIdMethod()
    {
        $expectedCustomerId = 5;

        $class = new \ReflectionClass(RateCalculator::class);
        $methodSetCustomerId = $class->getMethod('setCustomerId');
        $methodSetCustomerId->setAccessible(true);
        $methodSetCustomerId->invoke($this->object, $expectedCustomerId);

        $this->assertAttributeEquals($expectedCustomerId, 'customerId', $this->object);
    }

    /**
     * Test getCustomerId method
     */
    public function testGetCustomerIdMethod()
    {
        $expectedCustomerId = 5;

        $class = new \ReflectionClass(RateCalculator::class);
        $methodSetCustomerId = $class->getMethod('setCustomerId');
        $methodSetCustomerId->setAccessible(true);
        $methodSetCustomerId->invoke($this->object, $expectedCustomerId);

        $methodGetCustomerId = $class->getMethod('getCustomerId');
        $methodGetCustomerId->setAccessible(true);

        $this->assertEquals($expectedCustomerId, $methodGetCustomerId->invoke($this->object));
    }

    /**
     * * Test calculateEarnPoints method
     *
     * @dataProvider getCalculateEarnPointsDataProvider
     *
     * @param int $customerId
     * @param float $amount
     * @param int $customerGroupId
     * @param float $lifetimeAmount
     * @param int $baseAmount
     * @param int $basePoints
     * @param int $expectedValue
     * @param int $websiteId
     */
    public function testCalculateEarnPointsMethod(
        $customerId,
        $amount,
        $customerGroupId,
        $lifetimeAmount,
        $baseAmount,
        $basePoints,
        $expectedValue,
        $websiteId
    ) {
        $storeIds = [1, 2, 3];
        $customerMock = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getGroupId'])
            ->getMockForAbstractClass();
        $customerMock->expects($this->once())
            ->method('getGroupId')
            ->willReturn($customerGroupId);

        $this->customerRepositoryMock->expects($this->once())
            ->method('getById')
            ->with($customerId)
            ->willReturn($customerMock);

        $saleCollectionMock = $this->getMockBuilder(SaleCollection::class)
            ->disableOriginalConstructor()
            ->setMethods(['getConnection', 'getTable'])
            ->getMock();

        $saleCollectionMock->expects($this->exactly(2))
            ->method('getTable')
            ->with('sales_order')
            ->willReturn('sales_order');

        $this->connectionMock->expects($this->once())
            ->method('fetchOne')
            ->willReturn($lifetimeAmount);

        $saleCollectionMock->expects($this->once())
            ->method('getConnection')
            ->willReturn($this->connectionMock);

        $this->saleCollectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($saleCollectionMock);

        $earnRateMock = $this->getMockBuilder(EarnRateInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(
                ['getBaseAmount', 'getPoints']
            )
            ->getMockForAbstractClass();
        $earnRateMock->expects($this->once())
            ->method('getBaseAmount')
            ->willReturn($baseAmount);
        $earnRateMock->expects($this->once())
            ->method('getPoints')
            ->willReturn($basePoints);

        $this->earnRateRepositoryMock->expects($this->once())
            ->method('get')
            ->with($customerGroupId, $lifetimeAmount)
            ->willReturn($earnRateMock);

        $websiteInterfaceMock = $this->getMockForAbstractClass(
            WebsiteInterface::class,
            [],
            '',
            true,
            true,
            true,
            ['getStoreIds']
        );
        $websiteInterfaceMock->expects($this->once())
            ->method('getStoreIds')
            ->willReturn($storeIds);
        $this->storeManagerMock->expects($this->once())
            ->method('getWebsite')
            ->with($websiteId)
            ->willReturn($websiteInterfaceMock);

        $this->assertEquals($expectedValue, $this->object->calculateEarnPoints($customerId, $amount, $websiteId));
    }

    /**
     * Test calculateSpendPoints method
     *
     * @dataProvider getCalculateSpendPointsDataProvider
     * @param int $customerPointsSummary
     * @param int $basePoints
     * @param int $baseAmount
     * @param float $amount
     * @param int $spendPoints
     * @param int $websiteId
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function testCalculateSpendPoints(
        $customerPointsSummary,
        $basePoints,
        $baseAmount,
        $amount,
        $spendPoints,
        $rewardDiscount,
        $websiteId
    ) {
        $storeIds = [1, 2, 3];
        $customerId = 4;
        $customerGroupId = 1;
        $lifetimeAmount = 1000;

        $this->pointsSummaryServiceMock->expects($this->once())
            ->method('getCustomerRewardPointsBalance')
            ->with($customerId)
            ->willReturn($customerPointsSummary);

        $customerMock = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getGroupId'])
            ->getMockForAbstractClass();
        $customerMock->expects($this->once())
            ->method('getGroupId')
            ->willReturn($customerGroupId);

        $this->customerRepositoryMock->expects($this->once())
            ->method('getById')
            ->with($customerId)
            ->willReturn($customerMock);

        $saleCollectionMock = $this->getMockBuilder(SaleCollection::class)
            ->disableOriginalConstructor()
            ->setMethods(['getConnection', 'getTable'])
            ->getMock();

        $saleCollectionMock->expects($this->exactly(2))
            ->method('getTable')
            ->with('sales_order')
            ->willReturn('sales_order');

        $this->connectionMock->expects($this->once())
            ->method('fetchOne')
            ->willReturn($lifetimeAmount);

        $saleCollectionMock->expects($this->once())
            ->method('getConnection')
            ->willReturn($this->connectionMock);

        $this->saleCollectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($saleCollectionMock);

        $spendRateMock = $this->getMockBuilder(SpendRateInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(
                ['getBaseAmount', 'getPoints']
            )
            ->getMockForAbstractClass();
        $spendRateMock->expects($this->once())
            ->method('getBaseAmount')
            ->willReturn($baseAmount);
        $spendRateMock->expects($this->once())
            ->method('getPoints')
            ->willReturn($basePoints);

        $this->spendRateRepositoryMock->expects($this->once())
            ->method('get')
            ->with($customerGroupId, $lifetimeAmount)
            ->willReturn($spendRateMock);

        $websiteInterfaceMock = $this->getMockForAbstractClass(
            WebsiteInterface::class,
            [],
            '',
            true,
            true,
            true,
            ['getStoreIds']
        );
        $websiteInterfaceMock->expects($this->once())
            ->method('getStoreIds')
            ->willReturn($storeIds);
        $this->storeManagerMock->expects($this->once())
            ->method('getWebsite')
            ->with($websiteId)
            ->willReturn($websiteInterfaceMock);

        $this->assertEquals($spendPoints, $this->object->calculateSpendPoints($customerId, $amount, $websiteId));
    }

    /**
     * Test calculateSpendPoints method with null customer balance
     */
    public function testCalculateSpendPointsNullBalance()
    {
        $customerId = 11;
        $amount = 140.00;

        $this->pointsSummaryServiceMock->expects($this->once())
            ->method('getCustomerRewardPointsBalance')
            ->with($customerId)
            ->willReturn(0);

        $this->assertEquals(0, $this->object->calculateSpendPoints($customerId, $amount));
    }

    /**
     * Test calculateRewardDiscount method
     *
     * @dataProvider getCalculateSpendPointsDataProvider
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function testCalculateRewardDiscountMethod(
        $customerPointsSummary,
        $basePoints,
        $baseAmount,
        $amount,
        $spendPoints,
        $rewardDiscount,
        $websiteId
    ) {
        $storeIds = [1, 2, 3];
        $customerId = 4;
        $customerGroupId = 1;
        $lifetimeAmount = 1000;

        $customerMock = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getGroupId'])
            ->getMockForAbstractClass();
        $customerMock->expects($this->once())
            ->method('getGroupId')
            ->willReturn($customerGroupId);

        $this->customerRepositoryMock->expects($this->once())
            ->method('getById')
            ->with($customerId)
            ->willReturn($customerMock);

        $saleCollectionMock = $this->getMockBuilder(SaleCollection::class)
            ->disableOriginalConstructor()
            ->setMethods(['getConnection', 'getTable'])
            ->getMock();

        $saleCollectionMock->expects($this->exactly(2))
            ->method('getTable')
            ->with('sales_order')
            ->willReturn('sales_order');

        $this->connectionMock->expects($this->once())
            ->method('fetchOne')
            ->willReturn($lifetimeAmount);

        $saleCollectionMock->expects($this->once())
            ->method('getConnection')
            ->willReturn($this->connectionMock);

        $this->saleCollectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($saleCollectionMock);

        $spendRateMock = $this->getMockBuilder(SpendRateInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(
                ['getBaseAmount', 'getPoints']
            )
            ->getMockForAbstractClass();
        $spendRateMock->expects($this->once())
            ->method('getBaseAmount')
            ->willReturn($baseAmount);
        $spendRateMock->expects($this->once())
            ->method('getPoints')
            ->willReturn($basePoints);

        $this->spendRateRepositoryMock->expects($this->once())
            ->method('get')
            ->with($customerGroupId, $lifetimeAmount)
            ->willReturn($spendRateMock);

        $websiteInterfaceMock = $this->getMockForAbstractClass(
            WebsiteInterface::class,
            [],
            '',
            true,
            true,
            true,
            ['getStoreIds']
        );
        $websiteInterfaceMock->expects($this->once())
            ->method('getStoreIds')
            ->willReturn($storeIds);
        $this->storeManagerMock->expects($this->once())
            ->method('getWebsite')
            ->with($websiteId)
            ->willReturn($websiteInterfaceMock);

        $this->assertEquals(
            $rewardDiscount,
            $this->object->calculateRewardDiscount($customerId, $spendPoints, $websiteId)
        );
    }

    /**
     * Test getCustomerGroupId for guest, should return default group id
     */
    public function testGetCustomerGroupIdForGuest()
    {
        $defaultGroupId = 4;

        $class = new \ReflectionClass(RateCalculator::class);
        $methodGetCustomerGroupId = $class->getMethod('getCustomerGroupId');
        $methodGetCustomerGroupId->setAccessible(true);

        $this->groupServiceMock->expects($this->once())
            ->method('getDefaultGroup')
            ->willReturn($this->groupMock);

        $this->groupMock->expects($this->once())
            ->method('getId')
            ->willReturn($defaultGroupId);

        $this->assertEquals($defaultGroupId, $methodGetCustomerGroupId->invoke($this->object));
    }

    /**
     * Test getLifetimeSalesAmount for guest
     */
    public function testLifetimeSalesAmountForGuest()
    {
        $expectedLifetimeSalesAmount = 0;
        $websiteId = 1;

        $class = new \ReflectionClass(RateCalculator::class);

        $methodGetLifetimeSalesAmount = $class->getMethod('getLifetimeSalesAmount');
        $methodGetLifetimeSalesAmount->setAccessible(true);

        $this->assertEquals(
            $expectedLifetimeSalesAmount,
            $methodGetLifetimeSalesAmount->invoke($this->object, [$websiteId])
        );
    }

    /**
     * Test CustomerCacheData
     */
    public function testCustomerCacheData()
    {
        $class = new \ReflectionClass(RateCalculator::class);

        $methodGetCustomerCacheData = $class->getMethod('getCustomerCacheData');
        $methodGetCustomerCacheData->setAccessible(true);

        $methodSetCustomerCacheData = $class->getMethod('setCustomerCacheData');
        $methodSetCustomerCacheData->setAccessible(true);

        $customerId = 5;
        $keyData = 'test_key_data';
        $valueData = 'test_value_data';

        $this->assertNull(
            $methodGetCustomerCacheData->invokeArgs(
                $this->object,
                ['customerId' => $customerId, 'keyData' => $keyData]
            )
        );

        $methodSetCustomerCacheData->invokeArgs(
            $this->object,
            [
                'customerId' => $customerId,
                'keyData' => $keyData,
                'valueData' => $valueData,
            ]
        );

        $this->assertEquals(
            $valueData,
            $methodGetCustomerCacheData->invokeArgs(
                $this->object,
                ['customerId' => $customerId, 'keyData' => $keyData]
            )
        );
    }

    /**
     * Data provider for calculateEarnPointsMethod
     *
     * Values: [
     *    $customerId, $amount, $customerGroupId, $lifetimeAmount, $baseAmount, $basePoints, $expectedValue, $websiteId
     * ]
     * Should be:
     * $expectedValue = ($basePoints * $amount) / $baseAmount;
     *
     * @return array
     */
    public function getCalculateEarnPointsDataProvider()
    {
        return [
            [4, 100.00, 1, 1000, 20, 5, 25, 1],
            [5, 120.00, 2, 200, 10, 1, 12, 1],
            [6, 37.00, 2, 200, 10, 1, 3, 1],
            [7, 21.00, 2, 200, 10, 1, 2, 1],
            [7, 21.00, 2, 200, 5, 3, 12, 1],
            [10, 45.00, 3, 0, 0, 0, 0, 1],
            [10, 45.00, 1, 0, 0, 0, 0, 1],
            [10, 45.00, 1, 0, 10, 0, 0, 1],
            [10, 350.00, 1, 0, 10, 1, 35, 1],
            [10, 45.00, 1, 0, 0, 10, 0, 1],

        ];
    }

    /**
     * Data provider for testCalculateSpendPoints
     * Values: [$customerPointsSummary, $basePoints, $baseAmount, $amount, $spendPoints, $rewardDiscount, $websiteId]
     *
     * @return array
     */
    public function getCalculateSpendPointsDataProvider()
    {
        return [
            [10, 1, 1, 77, 10, 10, 1],
            [10, 2, 1, 77, 10, 5, 1],
            [10, 3, 1, 77, 10, 3.33, 1],
            [10, 4, 1, 77, 10, 2.5, 1],
            [10, 5, 1, 77, 10, 2, 1],
            [10, 6, 1, 77, 10, 1.67, 1],
            [10, 7, 1, 77, 10, 1.43, 1],
            [10, 8, 1, 77, 10, 1.25, 1],
            [10, 9, 1, 77, 10, 1.11, 1],
            [10, 10, 1, 77, 10, 1, 1],
            [10, 11, 1, 77, 10, 0.91, 1],
            [14, 5, 1, 77, 14, 2.8, 1],
            [140, 5, 1, 77, 140, 28, 1],
            [1400, 5, 1, 77, 385, 77, 1],
            [14, 5, 3, 77, 14, 8.4, 1],
            [140, 5, 3, 77, 129, 77.4, 1],
            [14, 4, 2, 77, 14, 7, 1],
            [140, 3, 1, 77, 140, 46.67, 1],
            [14, 0, 0, 77, 0, 0, 1],
            [14, 5, 1, 0,  0, 0, 1],
            [15, 1, 1, 9.99, 10, 10, 1],
            [15, 1, 1, 9.5, 10, 10, 1]
        ];
    }
}
