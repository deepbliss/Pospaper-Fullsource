<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Api;

use Magento\Sales\Api\Data\CreditmemoInterface;
use \Magento\Sales\Model\Order as OrderModel;
use Magento\Sales\Api\Data\OrderInterface;

/**
 * @api
 */
interface CustomerRewardPointsManagementInterface
{
    /**
     * Add reward points to customer after purchases the order
     *
     * @param int $invoiceId
     * @param int|null $customerId
     * @return boolean
     */
    public function addPointsForPurchases($invoiceId, $customerId = null);

    /**
     * Add reward points to customer after registration
     *
     * @param int $customerId
     * @param int|null $websiteId
     * @return boolean
     */
    public function addPointsForRegistration($customerId, $websiteId = null);

    /**
     * Add reward points to customer after approve review
     *
     * @param int $customerId
     * @param boolean $isOwner
     * @param int|null $websiteId
     * @return boolean
     */
    public function addPointsForReviews($customerId, $isOwner, $websiteId = null);

    /**
     * Add reward points to customer after share social link(s)
     *
     * @param int $customerId
     * @param int $productId
     * @param string $shareNetwork
     * @param int|null $websiteId
     * @return boolean
     */
    public function addPointsForShares($customerId, $productId, $shareNetwork, $websiteId = null);

    /**
     * Add reward points to customer after newsletter signup
     *
     * @param int $customerId
     * @param int|null $websiteId
     * @return boolean
     */
    public function addPointsForNewsletterSignup($customerId, $websiteId = null);

    /**
     * Spend customer reward points on checkout
     *
     * @param int $orderId
     * @param int|null $customerId
     * @return boolean
     */
    public function spendPointsOnCheckout($orderId, $customerId = null);

    /**
     * Expired transaction points
     *
     * @param int $customerId
     * @param int $expiredPoints
     * @param int $websiteId
     * @param int $transactionId
     * @return boolean
     */
    public function expiredTransactionPoints($customerId, $expiredPoints, $websiteId, $transactionId);

    /**
     * Retrieve customer points balance
     *
     * @param int $customerId
     * @param int|null $websiteId
     * @return int
     */
    public function getCustomerRewardPointsBalance($customerId, $websiteId = null);

    /**
     * Retrieve customer points balance in currency
     *
     * @param int $customerId
     * @param int|null $websiteId
     * @return float
     */
    public function getCustomerRewardPointsBalanceCurrency($customerId, $websiteId = null);

    /**
     * Retrieve customer points balance in base currency
     *
     * @param int $customerId
     * @param int|null $websiteId
     * @return float
     */
    public function getCustomerRewardPointsBalanceBaseCurrency($customerId, $websiteId = null);

    /**
     * Get summary balance update notification status
     *
     * @param int $customerId
     * @param int|null $websiteId
     * @return int
     */
    public function getCustomerBalanceUpdateNotificationStatus($customerId, $websiteId = null);

    /**
     * Retrieve min balance for use at checkout
     *
     * @param int $customerId
     * @param int $websiteId
     * @return int
     */
    public function getCustomerRewardPointsOnceMinBalance($customerId, $websiteId = null);

    /**
     * Get summary expiration notification status
     *
     * @param int $customerId
     * @param int|null $websiteId
     * @return int
     */
    public function getCustomerExpirationNotificationStatus($customerId, $websiteId = null);

    /**
     * Is customer spend rate by group
     *
     * @param int $customerId
     * @param int|null $websiteId
     * @return bool
     */
    public function isCustomerRewardPointsSpendRateByGroup($customerId, $websiteId = null);

    /**
     * Is customer spend rate
     *
     * @param int $customerId
     * @param int|null $websiteId
     * @return bool
     */
    public function isCustomerRewardPointsSpendRate($customerId, $websiteId = null);

    /**
     * Is customer earn rate by group
     *
     * @param int $customerId
     * @param int|null $websiteId
     * @return bool
     */
    public function isCustomerRewardPointsEarnRateByGroup($customerId, $websiteId = null);

    /**
     * Is customer earn rate
     *
     * @param int $customerId
     * @param int|null $websiteId
     * @return bool
     */
    public function isCustomerRewardPointsEarnRate($customerId, $websiteId = null);

    /**
     * Retrieve customer reward points details
     *
     * @param int $customerId
     * @param int|null $websiteId
     * @return \Aheadworks\RewardPoints\Api\Data\CustomerRewardPointsDetailsInterface
     */
    public function getCustomerRewardPointsDetails($customerId, $websiteId = null);

    /**
     * Refund to reward points
     *
     * @param int $creditmemoId
     * @param int|null $customerId
     * @return boolean
     */
    public function refundToRewardPoints($creditmemoId, $customerId = null);

    /**
     * Reimbursed spent reward points
     *
     * @param int $creditmemoId
     * @param int|null $customerId
     * @return boolean
     */
    public function reimbursedSpentRewardPoints($creditmemoId, $customerId = null);

    /**
     * Reimbursed spent reward points on order cancel
     *
     * @param int $orderId
     * @param int|null $customerId
     * @return boolean
     */
    public function reimbursedSpentRewardPointsOrderCancel($orderId, $customerId = null);

    /**
     * Cancel transaction with earned reward points
     *
     * @param int $creditmemoId
     * @param int|null $customerId
     * @return boolean
     */
    public function cancelEarnedPointsRefundOrder($creditmemoId, $customerId = null);

    /**
     * Save transaction created by admin
     *
     * @param [] $transactionData
     * @return boolean
     */
    public function saveAdminTransaction($transactionData);

    /**
     * Reset customer
     *
     * @return void
     */
    public function resetCustomer();

    /**
     * Send notification
     *
     * @param int $customerId
     * @param string $notifiedType
     * @param array $data
     * @param int $websiteId
     * @return int
     */
    public function sendNotification($customerId, $notifiedType, $data, $websiteId = null);

    /**
     * Import points summary
     *
     * @param mixed $importRawData
     * @return mixed
     * @throws \Aheadworks\RewardPoints\Api\Exception\ImportValidatorExceptionInterface
     */
    public function importPointsSummary($importRawData);
}
