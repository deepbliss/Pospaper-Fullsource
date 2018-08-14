<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Api;

use Aheadworks\RewardPoints\Api\Data\TransactionInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Aheadworks\RewardPoints\Model\Source\Transaction\Type as TransactionType;

/**
 * @api
 */
interface TransactionManagementInterface
{
    /**
     * Create empty transaction instance
     *
     * @return TransactionInterface
     */
    public function createEmptyTransaction();

    /**
     * Create transaction
     *
     * @param CustomerInterface $customer
     * @param int $balance
     * @param string $expirationDate
     * @param string $commentToCustomer
     * @param string $commentToCustomerPlaceholder
     * @param string $commentToAdmin
     * @param int $websiteId
     * @param int $transactionType
     * @param array $arguments
     * @return boolean
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function createTransaction(
        CustomerInterface $customer,
        $balance,
        $expirationDate = null,
        $commentToCustomer = null,
        $commentToCustomerPlaceholder = null,
        $commentToAdmin = null,
        $websiteId = null,
        $transactionType = TransactionType::BALANCE_ADJUSTED_BY_ADMIN,
        $arguments = []
    );

    /**
     * Save transaction
     *
     * @param TransactionInterface $transaction
     * @param array $arguments
     * @return boolean
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function saveTransaction(TransactionInterface $transaction, $arguments = []);
}
