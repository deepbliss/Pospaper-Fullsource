<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Unit\Test\Plugin\Model\Customer;

use Aheadworks\RewardPoints\Plugin\Model\Customer\AccountManagementPlugin;
use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Magento\Framework\Registry;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class Aheadworks\RewardPoints\Unit\Test\Plugin\Model\Customer\AccountManagementPluginTest
 */
class AccountManagementPluginTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var AccountManagementPlugin
     */
    private $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CustomerRewardPointsManagementInterface
     */
    private $customerRewardPointsServiceMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ScopeConfigInterface
     */
    private $scopeConfigMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Registry
     */
    private $registryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|AccountManagementInterface
     */
    private $accountManagementMock;

    protected function setUp()
    {
        $objectManager = new ObjectManager($this);

        $this->customerRewardPointsServiceMock = $this->getMockBuilder(
            CustomerRewardPointsManagementInterface::class
        )
            ->disableOriginalConstructor()
            ->setMethods(['addPointsForRegistration'])
            ->getMockForAbstractClass();

        $this->scopeConfigMock = $this->getMockBuilder(ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getValue'])
            ->getMockForAbstractClass();

        $this->registryMock = $this->getMockBuilder(Registry::class)
            ->disableOriginalConstructor()
            ->setMethods(['registry'])
            ->getMock();

        $this->accountManagementMock = $this->getMockBuilder(AccountManagementInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $data = [
            'customerRewardPointsService' => $this->customerRewardPointsServiceMock,
            'scopeConfig' => $this->scopeConfigMock,
            'registry' => $this->registryMock,
        ];

        $this->object = $objectManager->getObject(AccountManagementPlugin::class, $data);
    }

    /**
     * Test afterCreateAccountWithPasswordHash method if null customer id
     */
    public function testAfterCreateAccountWithPasswordHashMethodNullCustomerId()
    {
        $customerId = null;

        $customerMock = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId', 'getWebsiteId', 'getEmail'])
            ->getMockForAbstractClass();

        $customerMock->expects($this->once())
            ->method('getId')
            ->willReturn($customerId);

        $this->object->afterCreateAccountWithPasswordHash($this->accountManagementMock, $customerMock);
    }

    /**
     * Test afterCreateAccountWithPasswordHash method if skip email
     */
    public function testAfterCreateAccountWithPasswordHashMethodSkipEmail()
    {
        $customerId = 11;

        $customerMock = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId', 'getWebsiteId', 'getEmail'])
            ->getMockForAbstractClass();

        $customerMock->expects($this->exactly(2))
            ->method('getId')
            ->willReturn($customerId);

        $this->expectedCanSkipConfirmationTrue($customerMock, 'test@test.com', 'test@test.com');

        $this->customerRewardPointsServiceMock->expects($this->once())
            ->method('addPointsForRegistration')
            ->with($customerId)
            ->willReturnSelf();

        $this->object->afterCreateAccountWithPasswordHash($this->accountManagementMock, $customerMock);
    }

    /**
     * Test afterCreateAccountWithPasswordHash method if is required confirm
     */
    public function testAfterCreateAccountWithPasswordHashMethodIsRequiredConfirm()
    {
        $customerId = 11;

        $customerMock = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId', 'getWebsiteId', 'getEmail'])
            ->getMockForAbstractClass();

        $customerMock->expects($this->once())
            ->method('getId')
            ->willReturn($customerId);

        $this->expectedCanSkipConfirmationFalse();

        $this->expectedIsConfirmationRequired($customerMock, 1, true);

        $this->customerRewardPointsServiceMock->expects($this->never())
            ->method('addPointsForRegistration')
            ->with($customerId)
            ->willReturnSelf();

        $this->object->afterCreateAccountWithPasswordHash($this->accountManagementMock, $customerMock);
    }

    /**
     * Test afterCreateAccountWithPasswordHash method if is not required confirm
     */
    public function testAfterCreateAccountWithPasswordHashMethodIsNotRequiredConfirm()
    {
        $customerId = 11;

        $customerMock = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId', 'getWebsiteId', 'getEmail'])
            ->getMockForAbstractClass();

        $customerMock->expects($this->exactly(2))
            ->method('getId')
            ->willReturn($customerId);

        $this->expectedCanSkipConfirmationFalse();

        $this->expectedIsConfirmationRequired($customerMock, 1, false);

        $this->customerRewardPointsServiceMock->expects($this->once())
            ->method('addPointsForRegistration')
            ->with($customerId)
            ->willReturnSelf();

        $this->object->afterCreateAccountWithPasswordHash($this->accountManagementMock, $customerMock);
    }

    /**
     * Test afterActivate method if null customer id
     */
    public function testAfterActivateMethodNullCustomerId()
    {
        $customerId = null;

        $customerMock = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId', 'getWebsiteId', 'getEmail'])
            ->getMockForAbstractClass();

        $customerMock->expects($this->once())
            ->method('getId')
            ->willReturn($customerId);

        $this->object->afterActivate($this->accountManagementMock, $customerMock);
    }

    /**
     * Test afterActivate method if skip email
     */
    public function testActivateMethodSkipEmail()
    {
        $customerId = 9;

        $customerMock = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId', 'getWebsiteId', 'getEmail'])
            ->getMockForAbstractClass();

        $customerMock->expects($this->once())
            ->method('getId')
            ->willReturn($customerId);

        $this->expectedCanSkipConfirmationTrue($customerMock, 'test2@test.com', 'test2@test.com');

        $this->customerRewardPointsServiceMock->expects($this->never())
            ->method('addPointsForRegistration')
            ->with($customerId)
            ->willReturnSelf();

        $this->object->afterActivate($this->accountManagementMock, $customerMock);
    }

    /**
     * Test afterActivate method if is required confirm
     */
    public function testAfterActivateHashMethodIsRequiredConfirm()
    {
        $customerId = 10;

        $customerMock = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId', 'getWebsiteId', 'getEmail'])
            ->getMockForAbstractClass();

        $customerMock->expects($this->exactly(2))
            ->method('getId')
            ->willReturn($customerId);

        $this->expectedCanSkipConfirmationFalse();

        $this->expectedIsConfirmationRequired($customerMock, 1, true);

        $this->customerRewardPointsServiceMock->expects($this->once())
            ->method('addPointsForRegistration')
            ->with($customerId)
            ->willReturnSelf();

        $this->object->afterActivate($this->accountManagementMock, $customerMock);
    }

    /**
     * Test afterActivate method if is not required confirm
     */
    public function testAfterActivateMethodIsNotRequiredConfirm()
    {
        $customerId = 11;

        $customerMock = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId', 'getWebsiteId', 'getEmail'])
            ->getMockForAbstractClass();

        $customerMock->expects($this->once())
            ->method('getId')
            ->willReturn($customerId);

        $this->expectedCanSkipConfirmationFalse();

        $this->expectedIsConfirmationRequired($customerMock, 1, false);

        $this->customerRewardPointsServiceMock->expects($this->never())
            ->method('addPointsForRegistration')
            ->with($customerId)
            ->willReturnSelf();

        $this->object->afterActivate($this->accountManagementMock, $customerMock);
    }

    /**
     * Expected isConfirmationRequired method
     *
     * @param \PHPUnit_Framework_MockObject_MockObject|CustomerInterface $customerMock
     * @param int $websiteId
     * @param boolean $isConfirmationRequired
     */
    private function expectedIsConfirmationRequired($customerMock, $websiteId, $isConfirmationRequired)
    {
        $customerMock->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn($websiteId);

        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with('customer/create_account/confirm', 'websites', $websiteId)
            ->willReturn($isConfirmationRequired);
    }

    /**
     * Expected canSkipConfirmation method expect TRUE
     *
     * @param \PHPUnit_Framework_MockObject_MockObject|CustomerInterface $customerMock
     * @param string $email
     * @param string $emailSkip
     */
    private function expectedCanSkipConfirmationTrue($customerMock, $email, $emailSkip)
    {
        $customerMock->expects($this->once())
            ->method('getEmail')
            ->willReturn($email);

        $this->registryMock->expects($this->once())
            ->method('registry')
            ->with('skip_confirmation_if_email')
            ->willReturn($emailSkip);
    }

    /**
     * Expected canSkipConfirmation method expect FALSE
     */
    private function expectedCanSkipConfirmationFalse()
    {
        $this->registryMock->expects($this->once())
            ->method('registry')
            ->with('skip_confirmation_if_email')
            ->willReturn(false);
    }
}
