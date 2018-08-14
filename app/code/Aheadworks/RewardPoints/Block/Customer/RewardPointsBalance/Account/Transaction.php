<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Block\Customer\RewardPointsBalance\Account;

use Aheadworks\RewardPoints\Api\Data\TransactionSearchResultsInterface;
use Aheadworks\RewardPoints\Api\Data\TransactionInterface;
use Aheadworks\RewardPoints\Api\TransactionRepositoryInterface;
use Aheadworks\RewardPoints\Block\Html\Pager;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Block\Account\Dashboard;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\Template\Context;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Framework\Api\SortOrderBuilder;
use Aheadworks\RewardPoints\Model\Comment\CommentPoolInterface;

/**
 * Class Aheadworks\RewardPoints\Block\Customer\RewardPointsBalance\Account\Transaction
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 */
class Transaction extends Dashboard
{
    /**
     * @var TransactionRepositoryInterface
     */
    private $transactionRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;

    /**
     * @var TransactionSearchResultsInterface
     */
    private $transactions;

    /**
     * @var CommentPoolInterface
     */
    private $commentPool;

    /**
     * @param Context $context
     * @param CustomerSession $customerSession
     * @param SubscriberFactory $subscriberFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param AccountManagementInterface $customerAccountManagement
     * @param TransactionRepositoryInterface $transactionRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @param CommentPoolInterface $commentPool
     * @param array $data
     */
    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        SubscriberFactory $subscriberFactory,
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $customerAccountManagement,
        TransactionRepositoryInterface $transactionRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        CommentPoolInterface $commentPool,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $customerSession,
            $subscriberFactory,
            $customerRepository,
            $customerAccountManagement,
            $data
        );
        $this->transactionRepository = $transactionRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->commentPool = $commentPool;
    }

    /**
     *  {@inheritDoc}
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        /** @var Pager $pager */
        $pager = $this->getLayout()->createBlock(
            Pager::class,
            'aw_rp_transaction.pager'
        );

        $this->searchCriteriaBuilder->setCurrentPage($pager->getCurrentPage());
        $this->searchCriteriaBuilder->setPageSize($pager->getLimit());

        if ($this->getTransactions()) {
            $pager->setSearchResults($this->getTransactions());
            $this->setChild('pager', $pager);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * Retrieve transaction list
     *
     * @return TransactionSearchResultsInterface
     */
    public function getTransactions()
    {
        if (empty($this->transactions)) {
            $customerId = $this->customerSession->getCustomerId();
            if ($customerId != null) {
                $this->searchCriteriaBuilder->addFilter(TransactionInterface::CUSTOMER_ID, $customerId);
                $this->sortOrderBuilder->setField(TransactionInterface::TRANSACTION_ID)->setDescendingDirection();
                $this->searchCriteriaBuilder->addSortOrder($this->sortOrderBuilder->create());
                $this->transactions = $this->transactionRepository->getList(
                    $this->searchCriteriaBuilder->create()
                );
            }
        }
        return $this->transactions;
    }

    /**
     * Retrieve renderer comment
     *
     * @param TransactionInterface $transaction
     * @return string
     */
    public function renderComment($transaction)
    {
        if ($commentInstance = $this->commentPool->get($transaction->getType())) {
            $commentLabel = $commentInstance->renderTranslatedComment(
                $transaction->getEntities(),
                null,
                $transaction->getCommentToCustomerPlaceholder()
                    ? $transaction->getCommentToCustomerPlaceholder()
                    : $transaction->getCommentToCustomer(),
                true,
                true
            );
        }
        if (empty($commentLabel)) {
            $commentLabel = $transaction->getCommentToCustomer();
        }
        return $commentLabel;
    }

    /**
     * Format date in short format
     *
     * @param string $date
     * @return string
     */
    public function dateFormat($date)
    {
        return $this->formatDate($date, \IntlDateFormatter::MEDIUM);
    }
}
