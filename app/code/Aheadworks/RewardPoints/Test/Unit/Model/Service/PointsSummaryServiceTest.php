<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Model\Service;

use Aheadworks\RewardPoints\Model\Service\PointsSummaryService;
use Aheadworks\RewardPoints\Api\Data\TransactionInterface;
use Aheadworks\RewardPoints\Api\Data\PointsSummaryInterface;
use Aheadworks\RewardPoints\Api\PointsSummaryRepositoryInterface;
use Aheadworks\RewardPoints\Model\DateTime;
use Aheadworks\RewardPoints\Model\Source\Transaction\Type;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Model\Service\PointsSummaryServiceTest
 */
class PointsSummaryServiceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var PointsSummaryService
     */
    private $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|TransactionInterface
     */
    private $transactionMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|PointsSummaryRepositoryInterface
     */
    private $pointsSummaryRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|PointsSummaryInterface
     */
    private $pointsSummaryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|StoreManagerInterface
     */
    private $storeManagerMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|StoreInterface
     */
    private $storeMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|DateTime
     */
    private $dateTimeMock;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->transactionMock = $this->getMockForAbstractClass(
            TransactionInterface::class,
            [],
            '',
            false,
            true,
            false,
            ['getBalanceUpdateNotificationStatus', 'getExpirationNotificationStatus']
        );

        $this->pointsSummaryRepositoryMock = $this->getMockBuilder(PointsSummaryRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['save', 'get', 'create'])
            ->getMockForAbstractClass();

        $this->pointsSummaryMock = $this->getMockBuilder(PointsSummaryInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getSummaryId',
                    'setWebsiteId',
                    'setCustomerId',
                    'setPoints',
                    'getPoints',
                    'setDailyReviewPoints',
                    'getDailyReviewPoints',
                    'setDailyReviewPointsDate',
                    'getDailyReviewPointsDate',
                    'getIsAwardedForNewsletterSignup',
                ]
            )
            ->getMockForAbstractClass();

        $this->storeManagerMock = $this->getMockBuilder(StoreManagerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getStore'])
            ->getMockForAbstractClass();

        $this->storeMock = $this->getMockBuilder(StoreInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId', 'getWebsiteId'])
            ->getMockForAbstractClass();

        $this->dateTimeMock = $this->getMockBuilder(DateTime::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'isTodayDate',
                    'getTodayDate',
                    'getExpirationDate',
                ]
            )
            ->getMock();

        $data = [
            'pointsSummaryRepository' => $this->pointsSummaryRepositoryMock,
            'storeManager' => $this->storeManagerMock,
            'dateTime' => $this->dateTimeMock,
        ];

        $this->object = $objectManager->getObject(PointsSummaryService::class, $data);
    }

    /**
     * test getPointsSymmary method
     */
    public function testGetPointsSummaryMethod()
    {
        $customerId = 6;

        $this->pointsSummaryRepositoryMock->expects($this->once())
            ->method('get')
            ->with($customerId)
            ->willReturn($this->pointsSummaryMock);

        $class = new \ReflectionClass(PointsSummaryService::class);
        $method = $class->getMethod('getPointsSymmary');
        $method->setAccessible(true);

        $this->assertEquals(
            $this->pointsSummaryMock,
            $method->invokeArgs($this->object, ['customerId' => $customerId])
        );
    }

    /**
     * test getPointsSymmary method, not found model
     */
    public function testGetPointsSummaryMethodNotFoundModel()
    {
        $customerId = 6;

        $this->pointsSummaryRepositoryMock->expects($this->once())
            ->method('get')
            ->with($customerId)
            ->willThrowException(new NoSuchEntityException());

        $this->pointsSummaryRepositoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->pointsSummaryMock);

        $class = new \ReflectionClass(PointsSummaryService::class);
        $method = $class->getMethod('getPointsSymmary');
        $method->setAccessible(true);

        $this->assertEquals(
            $this->pointsSummaryMock,
            $method->invokeArgs($this->object, ['customerId' => $customerId])
        );
    }

    /**
     * Test getCustomerRewardPointsBalance method
     */
    public function testGetCustomerRewardPointsBalanceMethod()
    {
        $customerId = 6;
        $points = 10;

        $this->pointsSummaryRepositoryMock->expects($this->once())
            ->method('get')
            ->with($customerId)
            ->willReturn($this->pointsSummaryMock);

        $this->pointsSummaryMock->expects($this->once())
            ->method('getPoints')
            ->willReturn($points);

        $this->assertEquals($points, $this->object->getCustomerRewardPointsBalance($customerId));
    }

    /**
     * Test getCustomerDailyReviewPoints method
     */
    public function testGetCustomerDailyReviewPointsMethod()
    {
        $customerId = 6;
        $points = 20;

        $this->pointsSummaryRepositoryMock->expects($this->once())
            ->method('get')
            ->with($customerId)
            ->willReturn($this->pointsSummaryMock);

        $this->pointsSummaryMock->expects($this->once())
            ->method('getDailyReviewPoints')
            ->willReturn($points);

        $this->assertEquals($points, $this->object->getCustomerDailyReviewPoints($customerId));
    }

    /**
     * Test getCustomerDailyReviewPointsDate method
     */
    public function testGetCustomerDailyReviewPointsDateMethod()
    {
        $customerId = 6;
        $date = '2016-10-18';

        $this->pointsSummaryRepositoryMock->expects($this->once())
            ->method('get')
            ->with($customerId)
            ->willReturn($this->pointsSummaryMock);

        $this->pointsSummaryMock->expects($this->once())
            ->method('getDailyReviewPointsDate')
            ->willReturn($date);

        $this->assertEquals($date, $this->object->getCustomerDailyReviewPointsDate($customerId));
    }

    /**
     * Test isAwardedForNewsletterSignup method
     */
    public function testIsAwardedForNewsletterSignupMethod()
    {
        $customerId = 6;

        $this->pointsSummaryRepositoryMock->expects($this->once())
            ->method('get')
            ->with($customerId)
            ->willReturn($this->pointsSummaryMock);

        $this->pointsSummaryMock->expects($this->once())
            ->method('getIsAwardedForNewsletterSignup')
            ->willReturn(true);

        $this->assertTrue($this->object->isAwardedForNewsletterSignup($customerId));
    }

    /**
     * Test resetPointsSummaryDailyReview method for new customer
     */
    public function testResetPointsSummaryDailyReviewMethodNewCustomer()
    {
        $customerId = 5;
        $websiteId = 1;
        $today = '2016-10-18';

        $this->expectedSetupPointsSummaryNewCustomer($customerId, $websiteId, $today);
        $this->expectedSavePointsSummary();

        $this->assertTrue($this->object->resetPointsSummaryDailyReview($customerId));
    }

    /**
     * Test resetPointsSummaryDailyReview method for exists customer
     */
    public function testResetPointsSummaryDailyReviewMethodExistsCustomer()
    {
        $customerId = 5;
        $summaryId = 1;
        $oldPoints = 10;
        $newPoints = 10;

        $this->expectedSetupPointsSummaryExistsCustomer($customerId, $summaryId, $oldPoints, $newPoints);
        $this->expectedSavePointsSummary();

        $this->assertTrue($this->object->resetPointsSummaryDailyReview($customerId));
    }

    /**
     * Test resetPointsSummaryDailyReview method for exists customer throw exception
     *
     * @expectedException \Magento\Framework\Exception\CouldNotSaveException
     * @expectedExceptionMessage Unable save data
     */
    public function testResetPointsSummaryDailyReviewMethodExistsCustomerThrowException()
    {
        $customerId = 5;
        $summaryId = 1;
        $oldPoints = 10;
        $newPoints = 10;

        $this->expectedSetupPointsSummaryExistsCustomer($customerId, $summaryId, $oldPoints, $newPoints);
        $this->expectedSavePointsSummaryThrowException();

        $this->object->resetPointsSummaryDailyReview($customerId);
    }

    /**
     * Test addPointsSummaryToCustomer method for new customer
     */
    public function testAddPointsSummaryToCustomerMethodNewCustomer()
    {
        $customerId = 5;
        $today = '2016-10-18';
        $balance = 10;
        $newPoints = 10;
        $websiteId = 1;

        $this->transactionMock->expects($this->once())
            ->method('getCustomerId')
            ->willReturn($customerId);
        $this->transactionMock->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn($websiteId);
        $this->transactionMock->expects($this->exactly(2))
            ->method('getBalance')
            ->willReturn($balance);

        $this->expectedSetupPointsSummaryNewCustomer($customerId, $websiteId, $today, $newPoints);
        $this->expectedSavePointsSummary();

        $this->assertTrue($this->object->addPointsSummaryToCustomer($this->transactionMock));
    }

    /**
     * Test addPointsSummaryToCustomer method for exists customer
     */
    public function testAddPointsSummaryToCustomerMethodExistsCustomer()
    {
        $customerId = 5;
        $summaryId = 1;
        $balance = 10;
        $oldPoints = 5;
        $newPoints = 15;
        $websiteId = 1;

        $this->transactionMock->expects($this->once())
            ->method('getCustomerId')
            ->willReturn($customerId);
        $this->transactionMock->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn($websiteId);
        $this->transactionMock->expects($this->exactly(2))
            ->method('getBalance')
            ->willReturn($balance);

        $this->expectedSetupPointsSummaryExistsCustomer($customerId, $summaryId, $oldPoints, $newPoints);
        $this->expectedSavePointsSummary();

        $this->assertTrue($this->object->addPointsSummaryToCustomer($this->transactionMock));
    }

    /**
     * Test addPointsSummaryToCustomer method for exists customer for review
     */
    public function testAddPointsSummaryToCustomerMethodExistsCustomerForReview()
    {
        $customerId = 5;
        $summaryId = 1;
        $balance = 10;
        $oldPoints = 5;
        $newPoints = 15;
        $dailyReviewPoints = 43;
        $websiteId = 1;

        $this->transactionMock->expects($this->once())
            ->method('getCustomerId')
            ->willReturn($customerId);
        $this->transactionMock->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn($websiteId);
        $this->transactionMock->expects($this->exactly(2))
            ->method('getBalance')
            ->willReturn($balance);
        $this->transactionMock->expects($this->any())
            ->method('getType')
            ->willReturn(Type::POINTS_REWARDED_FOR_REVIEW_APPROVED_BY_ADMIN);

        $this->pointsSummaryMock->expects($this->once())
            ->method('getDailyReviewPoints')
            ->willReturn($dailyReviewPoints);

        $this->pointsSummaryMock->expects($this->once())
            ->method('setDailyReviewPoints')
            ->with(53)
            ->willReturnSelf();

        $this->expectedSetupPointsSummaryExistsCustomer($customerId, $summaryId, $oldPoints, $newPoints);
        $this->expectedSavePointsSummary();

        $this->assertTrue($this->object->addPointsSummaryToCustomer($this->transactionMock));
    }

    /**
     * Test addPointsSummaryToCustomer method for exists customer for newsletter
     */
    public function testAddPointsSummaryToCustomerMethodExistsCustomerForNewsletter()
    {
        $customerId = 5;
        $summaryId = 1;
        $balance = 10;
        $oldPoints = 5;
        $newPoints = 15;
        $websiteId = 1;

        $this->transactionMock->expects($this->once())
            ->method('getCustomerId')
            ->willReturn($customerId);
        $this->transactionMock->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn($websiteId);
        $this->transactionMock->expects($this->exactly(2))
            ->method('getBalance')
            ->willReturn($balance);
        $this->transactionMock->expects($this->any())
            ->method('getType')
            ->willReturn(Type::POINTS_REWARDED_FOR_NEWSLETTER_SIGNUP);

        $this->pointsSummaryMock->expects($this->once())
            ->method('setIsAwardedForNewsletterSignup')
            ->with(true)
            ->willReturnSelf();

        $this->expectedSetupPointsSummaryExistsCustomer($customerId, $summaryId, $oldPoints, $newPoints);
        $this->expectedSavePointsSummary();

        $this->assertTrue($this->object->addPointsSummaryToCustomer($this->transactionMock));
    }

    /**
     * Test addPointsSummaryToCustomer method for exists customer throw exception
     *
     * @expectedException \Magento\Framework\Exception\CouldNotSaveException
     * @expectedExceptionMessage Unable save data
     */
    public function testAddPointsSummaryToCustomerMethodExistsCustomerThrowException()
    {
        $customerId = 5;
        $summaryId = 1;
        $balance = 10;
        $oldPoints = 5;
        $newPoints = 15;
        $websiteId = 1;

        $this->transactionMock->expects($this->once())
            ->method('getCustomerId')
            ->willReturn($customerId);
        $this->transactionMock->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn($websiteId);
        $this->transactionMock->expects($this->exactly(2))
            ->method('getBalance')
            ->willReturn($balance);
        $this->transactionMock->expects($this->any())
            ->method('getType')
            ->willReturn(Type::POINTS_REWARDED_FOR_NEWSLETTER_SIGNUP);

        $this->expectedSetupPointsSummaryExistsCustomer($customerId, $summaryId, $oldPoints, $newPoints);
        $this->expectedSavePointsSummaryThrowException();

        $this->object->addPointsSummaryToCustomer($this->transactionMock);
    }

    /**
     * Expected setupPointsSummary method for new customer
     *
     * @param int $customerId
     * @param int $websiteId
     * @param string $today
     * @param int $newPoints
     * @return void
     */
    private function expectedSetupPointsSummaryNewCustomer($customerId, $websiteId, $today, $newPoints = 0)
    {
        $this->pointsSummaryRepositoryMock->expects($this->once())
            ->method('get')
            ->with($customerId)
            ->willThrowException(new NoSuchEntityException());

        $this->pointsSummaryRepositoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->pointsSummaryMock);

        $this->pointsSummaryMock->expects($this->once())
            ->method('getSummaryId')
            ->willReturn(null);

        $this->storeManagerMock->expects($this->once())
            ->method('getStore')
            ->willReturn($this->storeMock);
        $this->storeMock->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn($websiteId);

        $this->pointsSummaryMock->expects($this->once())
            ->method('setWebsiteId')
            ->with($websiteId)
            ->willReturnSelf();

        $this->pointsSummaryMock->expects($this->once())
            ->method('setCustomerId')
            ->with($customerId)
            ->willReturnSelf();

        $this->dateTimeMock->expects($this->any())
            ->method('isTodayDate')
            ->with(null)
            ->willReturn(false);

        $this->dateTimeMock->expects($this->any())
            ->method('getTodayDate')
            ->willReturn($today);

        $this->pointsSummaryMock->expects($this->once())
            ->method('setDailyReviewPoints')
            ->with(0)
            ->willReturnSelf();

        $this->pointsSummaryMock->expects($this->once())
            ->method('setDailyReviewPointsDate')
            ->with($today)
            ->willReturnSelf();
    }

    /**
     * Expected setupPointsSummary method for exists customer
     *
     * @param int $customerId
     * @param int $summaryId
     * @param int $oldPoints
     * @param int $newPoints
     */
    private function expectedSetupPointsSummaryExistsCustomer($customerId, $summaryId, $oldPoints, $newPoints)
    {
        $storeMock = $this->getMockBuilder(StoreInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getWebsiteId'])
            ->getMockForAbstractClass();
        $storeMock->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn(1);

        $this->storeManagerMock->expects($this->once())
            ->method('getStore')
            ->willReturn($storeMock);

        $this->pointsSummaryRepositoryMock->expects($this->once())
            ->method('get')
            ->with($customerId)
            ->willReturn($this->pointsSummaryMock);

        $this->pointsSummaryMock->expects($this->once())
            ->method('getSummaryId')
            ->willReturn($summaryId);

        $this->dateTimeMock->expects($this->any())
            ->method('isTodayDate')
            ->with(null)
            ->willReturn(true);

        $this->dateTimeMock->expects($this->never())
            ->method('getTodayDate')
            ->willReturnSelf();
    }

    /**
     * Expected savePointsSummary method
     */
    private function expectedSavePointsSummary()
    {
        $this->pointsSummaryRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->pointsSummaryMock)
            ->willReturn(true);
    }

    /**
     * Expected savePointsSummary method
     */
    private function expectedSavePointsSummaryThrowException()
    {
        $this->pointsSummaryRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->pointsSummaryMock)
            ->willThrowException(new \Exception('Unable save data'));
    }
}
