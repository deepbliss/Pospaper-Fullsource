<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Plugin\Model\Customer;

use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Magento\Framework\Registry;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Customer\Model\AccountManagement;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\Data\CustomerInterface;

/**
 * Class Aheadworks\RewardPoints\Plugin\Model\Customer\AccountManagementPlugin
 */
class AccountManagementPlugin
{
    /**
     * @var CustomerRewardPointsManagementInterface
     */
    private $customerRewardPointsService;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param CustomerRewardPointsManagementInterface $customerRewardPointsService
     * @param ScopeConfigInterface $scopeConfig
     * @param Registry $registry
     */
    public function __construct(
        CustomerRewardPointsManagementInterface $customerRewardPointsService,
        ScopeConfigInterface $scopeConfig,
        Registry $registry
    ) {
        $this->customerRewardPointsService = $customerRewardPointsService;
        $this->scopeConfig = $scopeConfig;
        $this->registry = $registry;
    }

    /**
     * Add reward points to customer after create account
     *
     * @param AccountManagementInterface $subject
     * @param CustomerInterface $result
     * @return CustomerInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterCreateAccountWithPasswordHash(
        AccountManagementInterface $subject,
        CustomerInterface $result
    ) {
        if ($result->getId() && !$this->isConfirmationRequired($result)) {
            $this->addPointsForRegistration($result->getId());
        }
        return $result;
    }

    /**
     * Add reward points to customer after activate his account
     *
     * @param AccountManagementInterface $subject
     * @param CustomerInterface $result
     * @return CustomerInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterActivate(AccountManagementInterface $subject, CustomerInterface $result)
    {
        if ($result->getId() && $this->isConfirmationRequired($result)) {
            $this->addPointsForRegistration($result->getId());
        }
        return $result;
    }

    /**
     * Add points to customer
     *
     * @param int $customerId
     * @return boolean
     */
    private function addPointsForRegistration($customerId)
    {
        return $this->customerRewardPointsService->addPointsForRegistration($customerId);
    }

    /**
     * Check if customer confirmation required
     *
     * @param CustomerInterface $customer
     * @return boolean
     */
    private function isConfirmationRequired($customer)
    {
        if ($this->canSkipConfirmation($customer)) {
            return false;
        }

        return (bool)$this->scopeConfig->getValue(
            AccountManagement::XML_PATH_IS_CONFIRM,
            ScopeInterface::SCOPE_WEBSITES,
            $customer->getWebsiteId()
        );
    }

    /**
     * Can skip confirmation
     *
     * @param CustomerInterface $customer
     * @return boolean
     */
    private function canSkipConfirmation($customer)
    {
        $skipConfirmationIfEmail = $this->registry->registry('skip_confirmation_if_email');
        if (!$skipConfirmationIfEmail) {
            return false;
        }

        return strtolower($skipConfirmationIfEmail) === strtolower($customer->getEmail());
    }
}
