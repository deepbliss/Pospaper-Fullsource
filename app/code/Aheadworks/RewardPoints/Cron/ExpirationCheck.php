<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Cron;

use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Aheadworks\RewardPoints\Model\Source\Transaction\Status;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Aheadworks\RewardPoints\Model\TransactionRepository;
use Aheadworks\RewardPoints\Api\Data\TransactionInterface;
use Aheadworks\RewardPoints\Model\Flag;

/**
 * Class Aheadworks\RewardPoints\Cron\ExpirationCheck
 */
class ExpirationCheck extends CronAbstract
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if ($this->isLocked(Flag::AW_RP_EXPIRATION_CHECK_LAST_EXEC_TIME)) {
            return $this;
        }
        $this->createExpiredTransactions();
        $this->setFlagData(Flag::AW_RP_EXPIRATION_CHECK_LAST_EXEC_TIME);
    }

    /**
     * Create expired transactions
     *
     * @return $this
     */
    private function createExpiredTransactions()
    {
        $this->searchCriteriaBuilder
            ->addFilter(TransactionInterface::STATUS, Status::ACTIVE)
            ->addFilter(TransactionInterface::EXPIRATION_DATE, 'expired');

        $expiredTransactions = $this->transactionRepository
            ->getList($this->searchCriteriaBuilder->create())
            ->getItems();

        foreach ($expiredTransactions as $expiredTransaction) {
            $this->customerRewardPointsService->expiredTransactionPoints(
                $expiredTransaction->getCustomerId(),
                $expiredTransaction->getBalance() + $expiredTransaction->getBalanceAdjusted(),
                $expiredTransaction->getWebsiteId(),
                $expiredTransaction->getTransactionId()
            );
        }
        return $this;
    }
}
