<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Test\Unit\Block\Customer;

use Aheadworks\RewardPoints\Block\Customer\RewardPointsBalance;
use Aheadworks\RewardPoints\Model\Config;
use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class Aheadworks\RewardPoints\Test\Unit\Block\Customer\RewardPointsBalanceTest
 */
class RewardPointsBalanceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Aheadworks\RewardPoints\Block\Customer\Toplink\RewardPointsBalance
     */
    private $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Config
     */
    private $configMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CustomerRewardPointsManagementInterface
     */
    private $customerRewardPointsServiceMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CurrentCustomer
     */
    private $currentCustomerMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|PriceHelper
     */
    private $priceHelperMock;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->configMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->setMethods(['isPointsBalanceTopLinkAtFrontend'])
            ->getMockForAbstractClass();

        $this->customerRewardPointsServiceMock = $this->getMockBuilder(
            CustomerRewardPointsManagementInterface::class
        )
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getCustomerRewardPointsBalance',
                    'getCustomerRewardPointsBalanceBaseCurrency',
                ]
            )
            ->getMockForAbstractClass();

        $this->currentCustomerMock = $this->getMockBuilder(CurrentCustomer::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCustomerId'])
            ->getMockForAbstractClass();

        $this->priceHelperMock = $this->getMockBuilder(PriceHelper::class)
            ->disableOriginalConstructor()
            ->setMethods(['currency'])
            ->getMock();

        $data = [
            'context' => $contextMock,
            'config' => $this->configMock,
            'customerRewardPointsService' => $this->customerRewardPointsServiceMock,
            'currentCustomer' => $this->currentCustomerMock,
            'priceHelper' => $this->priceHelperMock,
        ];

        $this->object = $objectManager->getObject(RewardPointsBalance::class, $data);
    }

    /**
     * Test getCustomerRewardPointsBalance method
     */
    public function testGetCustomerRewardPointsBalanceMethod()
    {
        $expectedValue = 88;
        $customerId = 3;

        $this->currentCustomerMock->expects($this->once())
            ->method('getCustomerId')
            ->willReturn($customerId);

        $this->customerRewardPointsServiceMock->expects($this->once())
            ->method('getCustomerRewardPointsBalance')
            ->willReturn($expectedValue);

        $this->assertEquals($expectedValue, $this->object->getCustomerRewardPointsBalance());
    }

    /**
     * Test getFormattedCustomerBalanceCurrency method
     */
    public function testGetFormattedCustomerBalanceCurrencyMethod()
    {
        $expectedValue = 88.00;
        $customerId = 3;

        $this->currentCustomerMock->expects($this->once())
            ->method('getCustomerId')
            ->willReturn($customerId);

        $this->customerRewardPointsServiceMock->expects($this->once())
            ->method('getCustomerRewardPointsBalanceBaseCurrency')
            ->willReturn($expectedValue);

        $this->priceHelperMock->expects($this->once())
            ->method('currency')
            ->with($expectedValue, true, false)
            ->willReturn($expectedValue);

        $this->assertEquals($expectedValue, $this->object->getFormattedCustomerBalanceCurrency());
    }
}
