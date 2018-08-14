<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Service;

use Aheadworks\RewardPoints\Api\Data\TransactionInterface;
use Aheadworks\RewardPoints\Api\TransactionRepositoryInterface;
use Aheadworks\RewardPoints\Api\TransactionManagementInterface;
use Aheadworks\RewardPoints\Model\Source\NotifiedStatus;
use Aheadworks\RewardPoints\Model\Source\Transaction\Status;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Store\Model\StoreManagerInterface;
use Aheadworks\RewardPoints\Model\Source\Transaction\Type as TransactionType;
use Magento\Backend\Model\Auth\Session as AuthSession;

/**
 * Class Aheadworks\RewardPoints\Model\Service\TransactionService
 */
class TransactionService implements TransactionManagementInterface
{
    /**
     * @var TransactionRepositoryInterface
     */
    private $transactionRepository;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var AuthSession
     */
    private $adminSession;

    /**
     * @param TransactionRepositoryInterface $transactionRepository
     * @param StoreManagerInterface $storeManager
     * @param AuthSession $adminSession
     */
    public function __construct(
        TransactionRepositoryInterface $transactionRepository,
        StoreManagerInterface $storeManager,
        AuthSession $adminSession
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->storeManager = $storeManager;
        $this->adminSession = $adminSession;
    }

    /**
     *  {@inheritDoc}
     */
    public function createEmptyTransaction()
    {
        return $this->transactionRepository->create();
    }

    /**
     * {@inheritDoc}
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
    ) {
        /** @var $transaction TransactionInterface **/
        $transaction = $this->createEmptyTransaction();

        $websiteId = $websiteId ? : $this->storeManager->getStore()->getWebsiteId();
        $status = Status::ACTIVE;
        $expirationNotified = NotifiedStatus::WAITING;
        $adminUserId = in_array($transactionType, $this->getTransactionsTypeCreatedByAdmin())
            ? $this->getAdminUserId()
            : null;
        if ((int)$balance <= 0) {
            $status = Status::USED;
            $expirationDate = null;
            $expirationNotified = NotifiedStatus::CANCELLED;
        }

        $transaction->setCustomerId($customer->getId());
        $transaction->setCustomerEmail($customer->getEmail());
        $transaction->setCustomerName($this->getCustomerName($customer));
        $transaction->setWebsiteId($websiteId);
        $transaction->setBalance($balance);
        $transaction->setExpirationDate($expirationDate);
        $transaction->setCommentToCustomer($commentToCustomer);
        $transaction->setCommentToCustomerPlaceholder($commentToCustomerPlaceholder);
        $transaction->setCommentToAdmin($commentToAdmin);
        $transaction->setType($transactionType);
        $transaction->setStatus($status);
        $transaction->setCreatedBy($adminUserId);
        $transaction->setExpirationNotified($expirationNotified);

        return $this->saveTransaction($transaction, $arguments);
    }

    /**
     *  {@inheritDoc}
     */
    public function saveTransaction(TransactionInterface $transaction, $arguments = [])
    {
        $result = false;
        try {
            $result = $this->transactionRepository->save($transaction, $arguments);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $result;
    }

    /**
     * Retrieve customer full name
     *
     * @param  CustomerInterface $customer
     * @return string
     */
    private function getCustomerName(CustomerInterface $customer)
    {
        return $customer->getFirstname() . ' ' . $customer->getLastname();
    }

    /**
     * Retrieve transactions type created by admin
     *
     * @return array
     */
    private function getTransactionsTypeCreatedByAdmin()
    {
        return [
            TransactionType::BALANCE_ADJUSTED_BY_ADMIN,
            TransactionType::ORDER_CANCELED,
            TransactionType::REFUND_BY_REWARD_POINTS,
            TransactionType::REIMBURSE_OF_SPENT_REWARD_POINTS,
            TransactionType::POINTS_REWARDED_FOR_ORDER,
            TransactionType::CANCEL_EARNED_POINTS_FOR_REFUND_ORDER
        ];
    }

    /**
     * Get current admin user id
     *
     * @return int
     */
    private function getAdminUserId()
    {
        $userId = null;
        if ($this->adminSession->getUser()) {
            $userId = $this->adminSession->getUser()->getUserId();
        }
        return $userId;
    }
}
