<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Service;

use Aheadworks\RewardPoints\Api\Data\CustomerRewardPointsDetailsInterfaceFactory;
use Aheadworks\RewardPoints\Api\Data\CustomerRewardPointsDetailsInterface;
use Aheadworks\RewardPoints\Api\Data\TransactionInterface;
use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Aheadworks\RewardPoints\Api\TransactionManagementInterface;
use Aheadworks\RewardPoints\Model\Calculator\RateCalculator;
use Aheadworks\RewardPoints\Model\Config;
use Aheadworks\RewardPoints\Model\Comment\CommentPoolInterface;
use Aheadworks\RewardPoints\Model\DateTime;
use Aheadworks\RewardPoints\Model\Source\NotifiedStatus;
use Aheadworks\RewardPoints\Model\Source\Transaction\Status;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Aheadworks\RewardPoints\Model\Source\Transaction\Type as TransactionType;
use Aheadworks\RewardPoints\Model\Source\Transaction\EntityType as TransactionEntityType;
use Magento\Framework\Exception\CouldNotSaveException;
use Aheadworks\RewardPoints\Model\ResourceModel\Transaction\Relation\Entity\SaveHandler as TransactionEntitySaveHandler;
use Aheadworks\RewardPoints\Model\ResourceModel\Transaction\Relation\AdjustedHistory\SaveHandler
    as TransactionAdjustedHistorySaveHandler;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Aheadworks\RewardPoints\Model\TransactionRepository;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\CreditmemoInterface;
use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Aheadworks\RewardPoints\Model\Sender;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Aheadworks\RewardPoints\Model\Source\SubscribeStatus;
use Aheadworks\RewardPoints\Model\Calculator\Earning as EarningCalculator;
use Magento\Sales\Api\CreditmemoRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Aheadworks\RewardPoints\Model\Import\PointsSummary as ImportPointsSummary;
use Aheadworks\RewardPoints\Api\Data\PointsSummaryInterface;
use Magento\Framework\DataObject;

/**
 * Class Aheadworks\RewardPoints\Model\Service\CustomerRewardPointsManagement
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class CustomerRewardPointsService implements CustomerRewardPointsManagementInterface
{
    /**
     * @var CustomerRewardPointsDetailsInterfaceFactory
     */
    private $customerRewardPointsDetailsFactory;

    /**
     * @var CustomerRewardPointsDetailsInterface[]
     */
    private $customerRewardPointsDetailsCache = [];

    /**
     * @var TransactionManagementInterface
     */
    private $transactionService;

    /**
     * @var PointsSummaryService
     */
    private $pointsSummaryService;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var CommentPoolInterface
     */
    private $commentPool;

    /**
     * @var RateCalculator
     */
    private $rateCalculator;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var CustomerInterface
     */
    private $customer;

    /**
     * @var int
     */
    private $customerId;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var TransactionRepository
     */
    private $transactionRepository;

    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var CreditmemoRepositoryInterface
     */
    private $creditmemoRepository;

    /**
     * @var InvoiceRepositoryInterface
     */
    private $invoiceRepository;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var Sender
     */
    private $sender;

    /**
     * @var EarningCalculator
     */
    private $earningCalculator;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ImportPointsSummary
     */
    private $importPointsSummary;

    /**
     * @var DataObject
     */
    private $dataObject;

    /**
     * @param CustomerRewardPointsDetailsInterfaceFactory $customerRewardPointsDetailsFactory
     * @param TransactionManagementInterface $transactionService
     * @param PointsSummaryService $pointsSummaryService
     * @param CustomerRepositoryInterface $customerRepository
     * @param CommentPoolInterface $commentPool
     * @param RateCalculator $rateCalculator
     * @param Config $config
     * @param DateTime $dateTime
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param TransactionRepository $transactionRepository
     * @param SortOrderBuilder $sortOrderBuilder
     * @param OrderRepositoryInterface $orderRepository
     * @param CreditmemoRepositoryInterface $creditmemoRepository
     * @param InvoiceRepositoryInterface $invoiceRepository
     * @param PriceCurrencyInterface $priceCurrency
     * @param Sender $sender
     * @param EarningCalculator $earningCalculator
     * @param StoreManagerInterface $storeManager
     * @param ImportPointsSummary $importPointsSummary
     * @param DataObject $dataObject
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        CustomerRewardPointsDetailsInterfaceFactory $customerRewardPointsDetailsFactory,
        TransactionManagementInterface $transactionService,
        PointsSummaryService $pointsSummaryService,
        CustomerRepositoryInterface $customerRepository,
        CommentPoolInterface $commentPool,
        RateCalculator $rateCalculator,
        Config $config,
        DateTime $dateTime,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        TransactionRepository $transactionRepository,
        SortOrderBuilder $sortOrderBuilder,
        OrderRepositoryInterface $orderRepository,
        CreditmemoRepositoryInterface $creditmemoRepository,
        InvoiceRepositoryInterface $invoiceRepository,
        PriceCurrencyInterface $priceCurrency,
        Sender $sender,
        EarningCalculator $earningCalculator,
        StoreManagerInterface $storeManager,
        ImportPointsSummary $importPointsSummary,
        DataObject $dataObject
    ) {
        $this->customerRewardPointsDetailsFactory = $customerRewardPointsDetailsFactory;
        $this->transactionService = $transactionService;
        $this->pointsSummaryService = $pointsSummaryService;
        $this->customerRepository = $customerRepository;
        $this->commentPool = $commentPool;
        $this->rateCalculator = $rateCalculator;
        $this->config = $config;
        $this->dateTime = $dateTime;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->transactionRepository = $transactionRepository;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->orderRepository = $orderRepository;
        $this->creditmemoRepository = $creditmemoRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->priceCurrency = $priceCurrency;
        $this->sender = $sender;
        $this->earningCalculator = $earningCalculator;
        $this->storeManager = $storeManager;
        $this->importPointsSummary = $importPointsSummary;
        $this->dataObject = $dataObject;
    }

    /**
     * {@inheritDoc}
     */
    public function addPointsForPurchases($invoiceId, $customerId = null)
    {
        $invoice = $this->getInvoiceById($invoiceId);
        if (!$invoice) {
            return false;
        }
        $order = $this->getOrderById($invoice->getOrderId());
        if (!$order) {
            return false;
        }
        if (!$customerId) {
            $customerId = $order->getCustomerId();
        }
        $websiteId = $this->storeManager->getStore($order->getStoreId())->getWebsiteId();

        if ($customerId) {
            $this->setCustomerId($customerId);
            $rewardPointsForPurchase = $this->earningCalculator->calculation($invoice, $customerId, $websiteId);
            $transactionType = TransactionType::POINTS_REWARDED_FOR_ORDER;

            return $this->createTransaction(
                $rewardPointsForPurchase,
                $this->getExpirationDate($websiteId),
                $this->getCommentToCustomer($transactionType)->renderComment([
                    TransactionEntityType::ORDER_ID => [
                        'entity_id' => $order->getEntityId(),
                        'entity_label' => $order->getIncrementId()
                    ]
                ]),
                $this->getCommentToCustomer($transactionType)->getLabel(),
                null,
                $websiteId,
                $transactionType,
                [
                    TransactionEntitySaveHandler::TRANSACTION_ENTITY_TYPE => [
                        [
                            'entity_type' => TransactionEntityType::ORDER_ID,
                            'entity_id' => $order->getEntityId(),
                            'entity_label' => $order->getIncrementId()
                        ]
                    ]
                ],
                $order->getStoreId()
            );
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function addPointsForRegistration($customerId, $websiteId = null)
    {
        if (null != $customerId) {
            $this->setCustomerId($customerId);
            $rewardPointsForRegister = $this->config->getAwardedPointsForRegistration();
            if ($rewardPointsForRegister > 0) {
                $transactionType = TransactionType::POINTS_REWARDED_FOR_REGISTRATION;
                return $this->createTransaction(
                    $rewardPointsForRegister,
                    $this->getExpirationDate($websiteId),
                    $this->getCommentToCustomer($transactionType)->renderComment(),
                    $this->getCommentToCustomer($transactionType)->getLabel(),
                    null,
                    $websiteId,
                    $transactionType
                );
            }
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function addPointsForReviews($customerId, $isOwner, $websiteId = null)
    {
        if (null != $customerId) {
            $this->setCustomerId($customerId);
            if (null == $websiteId) {
                $websiteId = $this->getCustomer()->getWebsiteId();
            }
            $rewardPointsForReview = $this->getRewardPointsForReview($customerId, $websiteId);
            if ($rewardPointsForReview > 0 && $this->isReviewForOwnerAllowed($isOwner)) {
                $transactionType = TransactionType::POINTS_REWARDED_FOR_REVIEW_APPROVED_BY_ADMIN;
                return $this->createTransaction(
                    $rewardPointsForReview,
                    $this->getExpirationDate($websiteId),
                    $this->getCommentToCustomer($transactionType)->renderComment(),
                    $this->getCommentToCustomer($transactionType)->getLabel(),
                    null,
                    $websiteId,
                    $transactionType
                );
            }
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function addPointsForShares($customerId, $productId, $shareNetwork, $websiteId = null)
    {
        if (null != $customerId && null != $productId && null != $shareNetwork) {
            $this->setCustomerId($customerId);
            $rewardPointsForShare = $this->getRewardPointsForShare($customerId);
            if ($rewardPointsForShare > 0) {
                $transactionType = TransactionType::POINTS_REWARDED_FOR_SHARES;
                return $this->createTransaction(
                    $rewardPointsForShare,
                    $this->getExpirationDate($websiteId),
                    $this->getCommentToCustomer($transactionType)->renderComment(),
                    $this->getCommentToCustomer($transactionType)->getLabel(),
                    null,
                    $websiteId,
                    $transactionType
                );
            }
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function addPointsForNewsletterSignup($customerId, $websiteId = null)
    {
        if (null != $customerId) {
            $this->setCustomerId($customerId);
            $rewardPointsForNewsletter = $this->config->getAwardedPointsForNewsletterSignup();
            if ($rewardPointsForNewsletter > 0 && !$this->isAwardedForNewsletterSignup($customerId)) {
                $transactionType = TransactionType::POINTS_REWARDED_FOR_NEWSLETTER_SIGNUP;
                return $this->createTransaction(
                    $rewardPointsForNewsletter,
                    $this->getExpirationDate($websiteId),
                    $this->getCommentToCustomer($transactionType)->renderComment(),
                    $this->getCommentToCustomer($transactionType)->getLabel(),
                    null,
                    $websiteId,
                    $transactionType
                );
            }
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function spendPointsOnCheckout($orderId, $customerId = null)
    {
        /** @var $order OrderInterface */
        $order = $this->getOrderById($orderId);
        if (!$order) {
            return false;
        }
        if (!$customerId) {
            $customerId = $order->getCustomerId();
        }
        $websiteId = $this->storeManager->getStore($order->getStoreId())->getWebsiteId();
        $spendPoints = $order->getAwRewardPoints();

        if ($customerId && $order->getAwUseRewardPoints() && $spendPoints > 0) {
            $this->setCustomerId($customerId);
            $transactionType = TransactionType::POINTS_SPENT_ON_ORDER;

            $result = $this->createTransaction(
                -(int)$spendPoints,
                null,
                $this->getCommentToCustomer($transactionType)->renderComment([
                    TransactionEntityType::ORDER_ID => [
                        'entity_id' => $order->getEntityId(),
                        'entity_label' => $order->getIncrementId()
                    ]
                ]),
                $this->getCommentToCustomer($transactionType)->getLabel(),
                null,
                $websiteId,
                $transactionType,
                [
                    TransactionEntitySaveHandler::TRANSACTION_ENTITY_TYPE => [
                        [
                            'entity_type' => TransactionEntityType::ORDER_ID,
                            'entity_id' => $order->getEntityId(),
                            'entity_label' => $order->getIncrementId()
                        ]
                    ]
                ],
                $order->getStoreId()
            );
            return $result;
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function expiredTransactionPoints($customerId, $expiredPoints, $websiteId, $transactionId)
    {
        $result = false;
        if ($customerId && $expiredPoints > 0) {
            $this->customer = null;
            $this->setCustomerId($customerId);
            $transactionType = TransactionType::POINTS_EXPIRED;

            try {
                $result = $this->createTransaction(
                    -(int)$expiredPoints,
                    null,
                    $this->getCommentToCustomer($transactionType)->renderComment([
                        TransactionEntityType::TRANSACTION_ID => [
                            'entity_id' => $transactionId,
                            'entity_label' => ''
                        ]
                    ]),
                    $this->getCommentToCustomer($transactionType)->getLabel(),
                    null,
                    $websiteId,
                    $transactionType,
                    [
                        TransactionEntitySaveHandler::TRANSACTION_ENTITY_TYPE => [
                            [
                                'entity_type' => TransactionEntityType::TRANSACTION_ID,
                                'entity_id' => $transactionId
                            ]
                        ]
                    ]
                );
            } catch (\Exception $exception) {
                //todo: save exception to log
            }
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function refundToRewardPoints($creditmemoId, $customerId = null)
    {
        /** @var $creditmemo CreditmemoInterface */
        $creditmemo = $this->getCreditmemoById($creditmemoId);
        if (!$creditmemo) {
            return false;
        }
        if (!$customerId) {
            $customerId = $creditmemo->getCustomerId();
        }
        $websiteId = $this->storeManager->getStore($creditmemo->getStoreId())->getWebsiteId();
        $points = $creditmemo->getAwRewardPointsBlnceRefund();

        /** @var $order OrderInterface */
        $order = $this->getOrderById($creditmemo->getOrderId());

        if ($customerId && $order && abs($points) > 0) {
            $this->setCustomerId($customerId);
            $transactionType = TransactionType::REFUND_BY_REWARD_POINTS;

            return $this->createTransaction(
                $points,
                $this->getExpirationDate($websiteId),
                $this->getCommentToCustomer($transactionType)->renderComment([
                    TransactionEntityType::ORDER_ID => [
                        'entity_id' => $order->getEntityId(),
                        'entity_label' => $order->getIncrementId()
                    ],
                    TransactionEntityType::CREDIT_MEMO_ID => [
                        'entity_id' => $creditmemo->getEntityId(),
                        'entity_label' => $creditmemo->getIncrementId()
                    ]
                ]),
                $this->getCommentToCustomer($transactionType)->getLabel(),
                null,
                $websiteId,
                $transactionType,
                [
                    TransactionEntitySaveHandler::TRANSACTION_ENTITY_TYPE => [
                        [
                            'entity_type' => TransactionEntityType::ORDER_ID,
                            'entity_id' => $order->getEntityId(),
                            'entity_label' => $order->getIncrementId()
                        ],
                        [
                            'entity_type' => TransactionEntityType::CREDIT_MEMO_ID,
                            'entity_id' => $creditmemo->getEntityId(),
                            'entity_label' => $creditmemo->getIncrementId()
                        ]
                    ]
                ],
                $order->getStoreId()
            );
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function reimbursedSpentRewardPoints($creditmemoId, $customerId = null)
    {
        /** @var $creditmemo CreditmemoInterface */
        $creditmemo = $this->getCreditmemoById($creditmemoId);
        if (!$creditmemo) {
            return false;
        }
        if (!$customerId) {
            $customerId = $creditmemo->getCustomerId();
        }
        $websiteId = $this->storeManager->getStore($creditmemo->getStoreId())->getWebsiteId();
        $points = $creditmemo->getAwRewardPointsBlnceReimbursed();

        /** @var $order OrderInterface */
        $order = $this->getOrderById($creditmemo->getOrderId());

        if ($this->config->isReimburseRefundPoints($websiteId) && $customerId
            && $creditmemo->getAwUseRewardPoints() && abs($points) > 0
        ) {
            $this->setCustomerId($customerId);
            $transactionType = TransactionType::REIMBURSE_OF_SPENT_REWARD_POINTS;

            return $this->createTransaction(
                $points,
                $this->getExpirationDate($websiteId),
                $this->getCommentToCustomer($transactionType)->renderComment([
                    TransactionEntityType::ORDER_ID => [
                        'entity_id' => $order->getEntityId(),
                        'entity_label' => $order->getIncrementId()
                    ],
                    TransactionEntityType::CREDIT_MEMO_ID => [
                        'entity_id' => $creditmemo->getEntityId(),
                        'entity_label' => $creditmemo->getIncrementId()
                    ]
                ]),
                $this->getCommentToCustomer($transactionType)->getLabel(),
                null,
                $websiteId,
                $transactionType,
                [
                    TransactionEntitySaveHandler::TRANSACTION_ENTITY_TYPE => [
                        [
                            'entity_type' => TransactionEntityType::ORDER_ID,
                            'entity_id' => $order->getEntityId(),
                            'entity_label' => $order->getIncrementId()
                        ],
                        [
                            'entity_type' => TransactionEntityType::CREDIT_MEMO_ID,
                            'entity_id' => $creditmemo->getEntityId(),
                            'entity_label' => $creditmemo->getIncrementId()
                        ]
                    ]
                ],
                $order->getStoreId()
            );
        }
        return false;
    }

    /**
     *  {@inheritDoc}
     */
    public function reimbursedSpentRewardPointsOrderCancel($orderId, $customerId = null)
    {
        /** @var $order OrderInterface */
        $order = $this->getOrderById($orderId);
        if (!$order) {
            return false;
        }
        if (!$customerId) {
            $customerId = $order->getCustomerId();
        }
        $websiteId = $this->storeManager->getStore($order->getStoreId())->getWebsiteId();
        $points = $order->getAwRewardPoints();

        if ($this->config->isReimburseRefundPoints($websiteId) && $customerId
            && $order->getAwUseRewardPoints() && abs($points) > 0
        ) {
            $this->setCustomerId($customerId);
            $transactionType = TransactionType::ORDER_CANCELED;

            return $this->createTransaction(
                $points,
                $this->getExpirationDate($websiteId),
                $this->getCommentToCustomer($transactionType)->renderComment([
                    TransactionEntityType::ORDER_ID => [
                        'entity_id' => $order->getEntityId(),
                        'entity_label' => $order->getIncrementId()
                    ]
                ]),
                $this->getCommentToCustomer($transactionType)->getLabel(),
                null,
                $websiteId,
                $transactionType,
                [
                    TransactionEntitySaveHandler::TRANSACTION_ENTITY_TYPE => [
                        [
                            'entity_type' => TransactionEntityType::ORDER_ID,
                            'entity_id' => $order->getEntityId(),
                            'entity_label' => $order->getIncrementId()
                        ]
                    ]
                ],
                $order->getStoreId()
            );
        }
        return false;
    }

    /**
     * {@inheritDoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function cancelEarnedPointsRefundOrder($creditmemoId, $customerId = null)
    {
        /** @var $creditmemo CreditmemoInterface */
        $creditmemo = $this->getCreditmemoById($creditmemoId);
        if (!$creditmemo) {
            return false;
        }
        if (!$customerId) {
            $customerId = $creditmemo->getCustomerId();
        }
        $websiteId = $this->storeManager->getStore($creditmemo->getStoreId())->getWebsiteId();

        /** @var $order OrderInterface */
        $order = $this->getOrderById($creditmemo->getOrderId());

        if ($customerId && $order && $this->config->isCancelEarnedPointsRefundOrder($websiteId)) {
            $this->setCustomerId($customerId);
            $points = -$this->earningCalculator->calculation($creditmemo, $customerId, $websiteId);
            $transactionType = TransactionType::CANCEL_EARNED_POINTS_FOR_REFUND_ORDER;

            return $this->createTransaction(
                $points,
                $this->getExpirationDate($websiteId),
                $this->getCommentToCustomer($transactionType)->renderComment([
                    TransactionEntityType::ORDER_ID => [
                        'entity_id' => $order->getEntityId(),
                        'entity_label' => $order->getIncrementId()
                    ],
                    TransactionEntityType::CREDIT_MEMO_ID => [
                        'entity_id' => $creditmemo->getEntityId(),
                        'entity_label' => $creditmemo->getIncrementId()
                    ]
                ]),
                $this->getCommentToCustomer($transactionType)->getLabel(),
                null,
                $websiteId,
                $transactionType,
                [
                    TransactionEntitySaveHandler::TRANSACTION_ENTITY_TYPE => [
                        [
                            'entity_type' => TransactionEntityType::ORDER_ID,
                            'entity_id' => $order->getEntityId(),
                            'entity_label' => $order->getIncrementId()
                        ],
                        [
                            'entity_type' => TransactionEntityType::CREDIT_MEMO_ID,
                            'entity_id' => $creditmemo->getEntityId(),
                            'entity_label' => $creditmemo->getIncrementId()
                        ]
                    ]
                ],
                $creditmemo->getStoreId()
            );
        }
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function saveAdminTransaction($transactionData)
    {
        $customerId = $transactionData[TransactionInterface::CUSTOMER_ID];
        $balance = $transactionData[TransactionInterface::BALANCE];

        if (null != $customerId && abs($balance) > 0) {
            $this->setCustomerId($customerId);
            return $this->createTransaction(
                $balance,
                $transactionData[TransactionInterface::EXPIRATION_DATE],
                $transactionData[TransactionInterface::COMMENT_TO_CUSTOMER],
                null,
                $transactionData[TransactionInterface::COMMENT_TO_ADMIN],
                $transactionData[TransactionInterface::WEBSITE_ID],
                TransactionType::BALANCE_ADJUSTED_BY_ADMIN
            );
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function getCustomerRewardPointsBalance($customerId, $websiteId = null)
    {
        $customerRewardPointsDetails = $this->getCustomerRewardPointsDetails($customerId, $websiteId);
        return $customerRewardPointsDetails->getCustomerRewardPointsBalance();
    }

    /**
     * {@inheritDoc}
     */
    public function getCustomerRewardPointsBalanceCurrency($customerId, $websiteId = null)
    {
        $customerRewardPointsDetails = $this->getCustomerRewardPointsDetails($customerId, $websiteId);
        return $customerRewardPointsDetails->getCustomerRewardPointsBalanceCurrency();
    }

    /**
     * {@inheritDoc}
     */
    public function getCustomerRewardPointsBalanceBaseCurrency($customerId, $websiteId = null)
    {
        $customerRewardPointsDetails = $this->getCustomerRewardPointsDetails($customerId, $websiteId);
        return $customerRewardPointsDetails->getCustomerRewardPointsBalanceBaseCurrency();
    }

    /**
     * {@inheritDoc}
     */
    public function getCustomerBalanceUpdateNotificationStatus($customerId, $websiteId = null)
    {
        $customerRewardPointsDetails = $this->getCustomerRewardPointsDetails($customerId, $websiteId);
        return $customerRewardPointsDetails->getCustomerBalanceUpdateNotificationStatus();
    }

    /**
     * {@inheritDoc}
     */
    public function getCustomerExpirationNotificationStatus($customerId, $websiteId = null)
    {
        $customerRewardPointsDetails = $this->getCustomerRewardPointsDetails($customerId, $websiteId);
        return $customerRewardPointsDetails->getCustomerExpirationNotificationStatus();
    }

    /**
     * {@inheritDoc}
     */
    public function getCustomerRewardPointsOnceMinBalance($customerId, $websiteId = null)
    {
        $customerRewardPointsDetails = $this->getCustomerRewardPointsDetails($customerId, $websiteId);
        return $customerRewardPointsDetails->getCustomerRewardPointsOnceMinBalance();
    }

    /**
     * {@inheritDoc}
     */
    public function isCustomerRewardPointsSpendRateByGroup($customerId, $websiteId = null)
    {
        $customerRewardPointsDetails = $this->getCustomerRewardPointsDetails($customerId, $websiteId);
        return $customerRewardPointsDetails->isCustomerRewardPointsSpendRateByGroup();
    }

    /**
     * {@inheritDoc}
     */
    public function isCustomerRewardPointsSpendRate($customerId, $websiteId = null)
    {
        $customerRewardPointsDetails = $this->getCustomerRewardPointsDetails($customerId, $websiteId);
        return $customerRewardPointsDetails->isCustomerRewardPointsSpendRate();
    }

    /**
     * {@inheritDoc}
     */
    public function isCustomerRewardPointsEarnRateByGroup($customerId, $websiteId = null)
    {
        $customerRewardPointsDetails = $this->getCustomerRewardPointsDetails($customerId, $websiteId);
        return $customerRewardPointsDetails->isCustomerRewardPointsEarnRateByGroup();
    }

    /**
     * {@inheritDoc}
     */
    public function isCustomerRewardPointsEarnRate($customerId, $websiteId = null)
    {
        $customerRewardPointsDetails = $this->getCustomerRewardPointsDetails($customerId, $websiteId);
        return $customerRewardPointsDetails->isCustomerRewardPointsEarnRate();
    }

    /**
     * {@inheritDoc}
     */
    public function getCustomerRewardPointsDetails($customerId, $websiteId = null)
    {
        if (isset($this->customerRewardPointsDetailsCache[$customerId])) {
            return $this->customerRewardPointsDetailsCache[$customerId];
        }

        /** @var CustomerRewardPointsDetailsInterface $customerRewardPointsDetails **/
        $customerRewardPointsDetails = $this->customerRewardPointsDetailsFactory->create();

        $balance = $this->pointsSummaryService->getCustomerRewardPointsBalance($customerId);

        $spendRateByCustomerGroup = (bool)$this->rateCalculator
            ->getSpendRate($websiteId, $customerId, false, false)
            ->getRateId();
        $spendRateByCustomer = (bool)$this->rateCalculator
            ->getSpendRate($websiteId, $customerId, true, false)
            ->getRateId();
        $earnRateByCustomerGroup = (bool)$this->rateCalculator
            ->getEarnRate($websiteId, $customerId, false, false)
            ->getRateId();
        $earnRateByCustomer = (bool)$this->rateCalculator
            ->getEarnRate($websiteId, $customerId, true, false)
            ->getRateId();

        $balanceBaseCurrency = $this->rateCalculator->calculateRewardDiscount($customerId, $balance, $websiteId);
        $balanceUpdateNotificationStatus = $this->pointsSummaryService
            ->getCustomerBalanceUpdateNotificationStatus($customerId);
        $expirationNotificationStatus = $this->pointsSummaryService
            ->getCustomerExpirationNotificationStatus($customerId);

        $customerRewardPointsDetails->setCustomerRewardPointsBalance($balance)
            ->setCustomerRewardPointsBalanceBaseCurrency($balanceBaseCurrency)
            ->setCustomerRewardPointsBalanceCurrency(
                $this->rateCalculator->convertCurrency($balanceBaseCurrency)
            )->setCustomerBalanceUpdateNotificationStatus($balanceUpdateNotificationStatus)
            ->setCustomerExpirationNotificationStatus($expirationNotificationStatus)
            ->setCustomerRewardPointsOnceMinBalance($this->getOnceMinBalance($balance, $websiteId))
            ->setCustomerRewardPointsSpendRateByGroup($spendRateByCustomerGroup)
            ->setCustomerRewardPointsSpendRate($spendRateByCustomer)
            ->setCustomerRewardPointsEarnRateByGroup($earnRateByCustomerGroup)
            ->setCustomerRewardPointsEarnRate($earnRateByCustomer);

        $this->customerRewardPointsDetailsCache[$customerId] = $customerRewardPointsDetails;

        return $customerRewardPointsDetails;
    }

    /**
     * {@inheritDoc}
     */
    public function resetCustomer()
    {
        $this->customer = null;
    }

    /**
     * {@inheritDoc}
     */
    public function sendNotification($customerId, $notifiedType, $data, $websiteId = null)
    {
        if (null == $this->getCustomerId()) {
            $this->setCustomerId($customerId);
        }
        $this->resetRewardPointsDetailsCache($customerId);
        $storeId = $data['store_id'] ?: $this->getCustomer()->getStoreId();
        $websiteId = $websiteId ?: $this->getCustomer()->getWebsiteId();
        $pointsBalance = $this->getCustomerRewardPointsBalance($customerId, $websiteId);

        $notifiedStatus = SubscribeStatus::NOT_SUBSCRIBED;
        $template = '';
        switch ($notifiedType) {
            case TransactionInterface::BALANCE_UPDATE_NOTIFIED:
                $notifiedStatus = $this->getCustomerBalanceUpdateNotificationStatus($customerId, $websiteId);
                $template = $this->config->getBalanceUpdateEmailTemplate($storeId);
                break;
            case TransactionInterface::EXPIRATION_NOTIFIED:
                $notifiedStatus = $this->getCustomerExpirationNotificationStatus($customerId, $websiteId);
                $template = $this->config->getExpirationReminderEmailTemplate($storeId);
                break;
        }

        $result = NotifiedStatus::NOT_SUBSCRIBED;
        if ($notifiedStatus == SubscribeStatus::SUBSCRIBED) {
            $moneyBalance = $this->priceCurrency->format(
                $this->getCustomerRewardPointsBalanceBaseCurrency($customerId, $websiteId),
                false,
                PriceCurrencyInterface::DEFAULT_PRECISION,
                $storeId
            );
            $result = $this->sender->sendNotification(
                $this->getCustomer(),
                $data['comment'],
                $data['balance'],
                $pointsBalance,
                $moneyBalance,
                $data['expiration_date'],
                $storeId,
                $template
            );
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function importPointsSummary($importRawData)
    {
        $importedRecords = [];
        if (empty($importRawData)) {
            return $importedRecords;
        }
        $pointsSummaryToImport = $this->importPointsSummary->process($importRawData);
        $importedRecords = $this->adjustPointsSummary($pointsSummaryToImport);
        return $importedRecords;
    }

    /**
     * Adjust points summary
     *
     * @param array $pointsSummaryToImport
     * @return array
     */
    private function adjustPointsSummary($pointsSummaryToImport)
    {
        $importedRecords = [];
        foreach ($pointsSummaryToImport as $pointsSummaryData) {
            $importedRecords[] = $this->adjustPointsBalance($pointsSummaryData);
            $this->adjustPointsSummaryNotificationStatuses($pointsSummaryData);
        }
        return $importedRecords;
    }

    /**
     * Adjust points balance by corresponding transactions
     *
     * @param array $pointsSummaryData
     * @return TransactionInterface
     */
    private function adjustPointsBalance($pointsSummaryData)
    {
        $transactionData = $this->getTransactionDataForImport($pointsSummaryData);
        $this->resetCustomer();
        $this->setCustomerId($transactionData[TransactionInterface::CUSTOMER_ID]);
        return $this->createTransaction(
            $transactionData[TransactionInterface::BALANCE],
            $transactionData[TransactionInterface::EXPIRATION_DATE],
            $transactionData[TransactionInterface::COMMENT_TO_CUSTOMER],
            $transactionData[TransactionInterface::COMMENT_TO_CUSTOMER_PLACEHOLDER],
            $transactionData[TransactionInterface::COMMENT_TO_ADMIN],
            $transactionData[TransactionInterface::WEBSITE_ID],
            $transactionData[TransactionInterface::TYPE]
        );
    }

    /**
     * Retrieves data for creating new transaction
     *
     * @param array $pointsSummaryData
     * @return array
     */
    private function getTransactionDataForImport($pointsSummaryData)
    {
        $transactionData = [];
        $customerId = $pointsSummaryData[PointsSummaryInterface::CUSTOMER_ID];
        $websiteId = $pointsSummaryData[PointsSummaryInterface::WEBSITE_ID];
        $points = $pointsSummaryData[PointsSummaryInterface::POINTS];
        $currentBalance = $this->getCustomerRewardPointsBalance($customerId, $websiteId);
        $transactionData[TransactionInterface::CUSTOMER_ID] = $customerId;
        $transactionData[TransactionInterface::BALANCE] = $points - $currentBalance;
        $transactionData[TransactionInterface::EXPIRATION_DATE] = $this->getExpirationDate($websiteId);
        $transactionData[TransactionInterface::COMMENT_TO_CUSTOMER] = null;
        $transactionData[TransactionInterface::COMMENT_TO_CUSTOMER_PLACEHOLDER] = null;
        $transactionData[TransactionInterface::COMMENT_TO_ADMIN] = __("IMPORT: Balance adjusted");
        $transactionData[TransactionInterface::WEBSITE_ID] = $websiteId;
        $transactionData[TransactionInterface::TYPE] = TransactionType::BALANCE_IMPORTED_BY_ADMIN;

        return $transactionData;
    }

    /**
     * Adjust points summary notification statuses
     *
     * @param array $pointsSummaryData
     * @return bool
     */
    private function adjustPointsSummaryNotificationStatuses($pointsSummaryData)
    {
        $summaryDataToAdjust = [
            PointsSummaryInterface::CUSTOMER_ID => $pointsSummaryData[PointsSummaryInterface::CUSTOMER_ID],
            PointsSummaryInterface::WEBSITE_ID => $pointsSummaryData[PointsSummaryInterface::WEBSITE_ID]
        ];
        if (isset($pointsSummaryData[PointsSummaryInterface::BALANCE_UPDATE_NOTIFICATION_STATUS])) {
            $summaryDataToAdjust[PointsSummaryInterface::BALANCE_UPDATE_NOTIFICATION_STATUS] =
                $pointsSummaryData[PointsSummaryInterface::BALANCE_UPDATE_NOTIFICATION_STATUS];
        }
        if (isset($pointsSummaryData[PointsSummaryInterface::EXPIRATION_NOTIFICATION_STATUS])) {
            $summaryDataToAdjust[PointsSummaryInterface::EXPIRATION_NOTIFICATION_STATUS] =
                $pointsSummaryData[PointsSummaryInterface::EXPIRATION_NOTIFICATION_STATUS];
        }
        $summaryData = $this->dataObject->setData($summaryDataToAdjust);
        return $this->pointsSummaryService->updateCustomerSummary($summaryData);
    }

    /**
     * Retrieve once min balance
     *
     * @param int $balance
     * @param int|null $websiteId
     * @return int
     */
    private function getOnceMinBalance($balance, $websiteId = null)
    {
        if ($onceMinBalance = $this->config->getOnceMinBalance($websiteId)) {
            $onceMinBalance = max(0, intval($onceMinBalance));
            if ($balance >= $onceMinBalance) {
                $onceMinBalance = 0;
            }
        }
        return $onceMinBalance;
    }

    /**
     * Return Daily Reward Points for Current Date
     *
     * @param int $customerId
     * @return int
     */
    private function getCustomerDailyReviewPoints($customerId)
    {
        if (!$this->dateTime->isTodayDate(
            $this->pointsSummaryService->getCustomerDailyReviewPointsDate($customerId)
        )
        ) {
            $this->pointsSummaryService->resetPointsSummaryDailyReview($customerId);
        }
        return $this->pointsSummaryService->getCustomerDailyReviewPoints($customerId);
    }

    /**
     * Calculate and retrieve reward points for review
     *
     * @param int|null $websiteId
     * @param int $customerId
     * @return int
     */
    private function getRewardPointsForReview($customerId, $websiteId)
    {
        $rewardPointsForReview = $this->config->getAwardedPointsForReview($websiteId);
        if ($rewardPointsForReview > 0) {
            $dailyLimitPointsForReview = $this->config->getDailyLimitPointsForReview($websiteId);
            if (!empty($dailyLimitPointsForReview)) {
                $customerDailyReviewPoints = $this->getCustomerDailyReviewPoints($customerId);
                if ($customerDailyReviewPoints < $dailyLimitPointsForReview) {
                    $deltaReviewLimit = ($dailyLimitPointsForReview - $customerDailyReviewPoints);
                    if ($rewardPointsForReview > $deltaReviewLimit) {
                        $rewardPointsForReview = $deltaReviewLimit;
                    }
                } else {
                    $rewardPointsForReview = 0;
                }
            }
        }
        return $rewardPointsForReview;
    }

    /**
     * Is allowed review for owner
     *
     * @param boolean $isOwner
     * @return boolean
     */
    private function isReviewForOwnerAllowed($isOwner)
    {
        $isProductOwnerForReview = $this->config->isProductReviewOwner();
        return (!$isProductOwnerForReview || ($isOwner && $isProductOwnerForReview));
    }

    /**
     * Return Daily Reward Points for Current Date Share
     *
     * @param int $customerId
     * @return int
     */
    private function getCustomerDailySharePoints($customerId)
    {
        if (!$this->dateTime->isTodayDate(
            $this->pointsSummaryService->getCustomerDailySharePointsDate($customerId)
        )
        ) {
            $this->pointsSummaryService->resetPointsSummaryDailyShare($customerId);
        }
        return $this->pointsSummaryService->getCustomerDailySharePoints($customerId);
    }

    /**
     * Return Daily Reward Points for Current Month Share
     *
     * @param int $customerId
     * @return int
     */
    private function getCustomerMonthlySharePoints($customerId)
    {
        if (!$this->dateTime->isCurrentMonthDate(
            $this->pointsSummaryService->getCustomerDailySharePointsDate($customerId)
        )) {
            $this->pointsSummaryService->resetPointsSummaryDailyShare($customerId);
        }
        return $this->pointsSummaryService->getCustomerMonthlySharePoints($customerId);
    }

    /**
     * Calculate and retrieve reward points for share
     *
     * @param int $customerId
     * @return int
     */
    private function getRewardPointsForShare($customerId)
    {
        $rewardPointsForShare = 0;
        if ($this->isCustomerCanGetRewardPointsForShare($customerId)) {
            $rewardPointsForShare = $this->getRewardPointsForShareForCustomer($customerId);
        }
        return $rewardPointsForShare;
    }

    private function isCustomerCanGetRewardPointsForShare($customerId)
    {
        $flag = true;
        $dailyLimitPointsForShare = $this->config->getDailyLimitPointsForShare();
        $monthlyLimitPointsForShare = $this->config->getMonthlyLimitPointsForShare();
        $customerDailySharePoints = $this->getCustomerDailySharePoints($customerId);
        $customerMonthlySharePoints = $this->getCustomerMonthlySharePoints($customerId);
        if ((!empty($dailyLimitPointsForShare)) && ($customerDailySharePoints >= $dailyLimitPointsForShare)) {
            $flag = false;
        }
        if ((!empty($monthlyLimitPointsForShare)) && ($customerMonthlySharePoints >= $monthlyLimitPointsForShare)) {
            $flag = false;
        }
        return $flag;
    }

    private function getRewardPointsForShareForCustomer($customerId)
    {
        $rewardPointsForShare = 0;
        $rewardPointsForShare = $this->config->getAwardedPointsForShare();
        $dailyLimitPointsForShare = $this->config->getDailyLimitPointsForShare();
        $monthlyLimitPointsForShare = $this->config->getMonthlyLimitPointsForShare();
        $customerDailySharePoints = $this->getCustomerDailySharePoints($customerId);
        $customerMonthlySharePoints = $this->getCustomerMonthlySharePoints($customerId);
        $deltaDailyShareLimit = (empty($dailyLimitPointsForShare)) ?
            ($rewardPointsForShare) :
            ($dailyLimitPointsForShare - $customerDailySharePoints);
        $deltaMonthlyShareLimit = (empty($monthlyLimitPointsForShare)) ?
            ($rewardPointsForShare) :
            ($monthlyLimitPointsForShare - $customerMonthlySharePoints);
        if ($deltaMonthlyShareLimit > $deltaDailyShareLimit) {
            if ($rewardPointsForShare > $deltaDailyShareLimit) {
                $rewardPointsForShare = $deltaDailyShareLimit;
            }
        } else {
            if ($rewardPointsForShare > $deltaMonthlyShareLimit) {
                $rewardPointsForShare = $deltaMonthlyShareLimit;
            }
        }
        return $rewardPointsForShare;
    }

    /**
     * Return if Customer is already Awarded for Newsletter Signup
     *
     * @param int $customerId
     * @return boolean
     */
    private function isAwardedForNewsletterSignup($customerId)
    {
        return $this->pointsSummaryService->isAwardedForNewsletterSignup($customerId);
    }

    /**
     * Set customer id
     *
     * @param  int $customerId
     * @return CustomerRewardPointsService
     */
    private function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
        return $this;
    }

    /**
     * Get customer id
     *
     * @return int
     */
    private function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * Create transaction model
     *
     * @param  int $balance
     * @param  string $expirationDate
     * @param  string $commentToCustomer
     * @param  string $commentToCustomerPlaceholder
     * @param  string $commentToAdmin
     * @param  int $transactionType
     * @param  int $websiteId
     * @param  int $transactionType
     * @param  array $arguments
     * @param  int $storeId
     * @return TransactionInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    private function createTransaction(
        $balance,
        $expirationDate = null,
        $commentToCustomer = null,
        $commentToCustomerPlaceholder = null,
        $commentToAdmin = null,
        $websiteId = null,
        $transactionType = TransactionType::BALANCE_ADJUSTED_BY_ADMIN,
        $arguments = [],
        $storeId = null
    ) {
        $result = false;
        try {
            $customerId = $this->getCustomer()->getId();

            $correctBalanceTransactionsType = [
                TransactionType::BALANCE_ADJUSTED_BY_ADMIN,
                TransactionType::CANCEL_EARNED_POINTS_FOR_REFUND_ORDER,
                TransactionType::BALANCE_IMPORTED_BY_ADMIN
            ];
            // Correct the transaction balance if the admin has adjusted the balance > balance of customer points
            if ((int)$balance < 0 && in_array($transactionType, $correctBalanceTransactionsType)) {
                $this->resetRewardPointsDetailsCache($customerId);
                $pointsBalance = $this->getCustomerRewardPointsBalance($customerId);
                if (abs($balance) > $pointsBalance) {
                    $balance = -$pointsBalance;
                }
            }
            // Don't create empty transactions, except import
            if (((int)$balance == 0)
                && ($transactionType != TransactionType::BALANCE_IMPORTED_BY_ADMIN)
            ) {
                return false;
            }

            $result = $this->transactionService->createTransaction(
                $this->getCustomer(),
                $balance,
                $expirationDate,
                $commentToCustomer,
                $commentToCustomerPlaceholder,
                $commentToAdmin,
                $websiteId,
                $transactionType,
                $arguments
            );
            $customerId = $this->getCustomer()->getId();
            if ((int)$balance < 0) {
                $this->spendPointsFromCustomerTransactions(
                    $customerId,
                    $balance,
                    $result->getTransactionId(),
                    $transactionType,
                    $websiteId,
                    $arguments
                );
            }

            $this->resetRewardPointsDetailsCache($customerId);
            $pointsBalance = $this->getCustomerRewardPointsBalance($customerId);

            $transaction = $this->transactionRepository->getById($result->getTransactionId());
            $transaction->setCurrentBalance($pointsBalance);

            $balanceUpdateActions = explode(',', $this->config->getBalanceUpdateActions());
            if ($result && in_array($transactionType, $balanceUpdateActions)) {
                $data = [
                    'store_id' => $storeId,
                    'comment' => $commentToCustomer,
                    'balance' => $balance,
                    'expiration_date' => $expirationDate
                ];
                $notifiedStatus = $this->sendNotification(
                    $this->getCustomer()->getId(),
                    TransactionInterface::BALANCE_UPDATE_NOTIFIED,
                    $data,
                    $websiteId
                );
                $transaction->setBalanceUpdateNotified($notifiedStatus);
            }
            $this->transactionRepository->save($transaction);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $result;
    }

    /**
     * Spend points from customer transactions
     *
     * @param int $customerId
     * @param int $pointsToSpend
     * @param int $fromTransactionId
     * @param int $transactionType
     * @param int|null $websiteId
     * @param array $arguments
     *
     * @return bool
     */
    private function spendPointsFromCustomerTransactions(
        $customerId,
        $pointsToSpend,
        $fromTransactionId,
        $transactionType,
        $websiteId = null,
        $arguments = []
    ) {
        $transactionsToSpend = [];
        $pointsToSpend = abs($pointsToSpend);
        $additionalFilters = $this->getAdditionalFiltersByTransactionType($transactionType, $arguments);

        $transactionUsedIds = [];
        $transactionsWithFilter = [
            TransactionType::POINTS_EXPIRED
        ];
        while ($pointsToSpend > 0) {
            $transactions = $this->getCustomerTransactions($customerId, $websiteId, $additionalFilters);
            if (count($additionalFilters) > 0 && !count($transactions)
                && !in_array($transactionType, $transactionsWithFilter) // This type can be used without filters
            ) {
                $additionalFilters = [];
                continue;
            }
            if (count($additionalFilters) > 0 && !count($transactions)
                && in_array($transactionType, $transactionsWithFilter) // This type can't be used without filters
            ) {
                break;
            }
            if (!in_array($transactionType, $transactionsWithFilter)) {
                // Reset additional filters
                $additionalFilters = [];
            }

            // Remove duplicate transactions
            foreach ($transactions as $key => $transaction) {
                if (in_array($transaction->getTransactionId(), $transactionUsedIds)) {
                    unset($transactions[$key]);
                }
            }
            if (!count($transactions)) {
                break;
            }
            foreach ($transactions as $transaction) {
                $transactionBalance = $transaction->getBalance() + $transaction->getBalanceAdjusted();
                if ($transactionBalance >= $pointsToSpend) {
                    $transactionsToSpend[] = [
                        'transaction_id' => $transaction->getTransactionId(),
                        'balance'        => -$pointsToSpend
                    ];
                    $pointsToSpend = 0;
                    break;
                } else {
                    $pointsToSpend -= $transactionBalance;
                    $transactionsToSpend[] = [
                        'transaction_id' => $transaction->getTransactionId(),
                        'balance'        => -$transactionBalance
                    ];
                    $transactionUsedIds[] = $transaction->getTransactionId();
                }
            }
        }

        if (!count($transactionsToSpend)) {
            return false;
        }
        foreach ($transactionsToSpend as $transactionToSpend) {
            $transaction = $this->transactionRepository->getById($transactionToSpend['transaction_id'], false);
            $arguments = [
                TransactionAdjustedHistorySaveHandler::TRANSACTION_ADJUSTED_HISTORY => [
                    'balance' => $transactionToSpend['balance'],
                    'from_transaction_id' => $fromTransactionId
                ]
            ];

            $transaction = $this->transactionRepository->save($transaction, $arguments);
            if (((int)$transaction->getBalance() - abs($transaction->getBalanceAdjusted())) <= 0) {
                switch ($transactionType) {
                    case TransactionType::POINTS_EXPIRED:
                        $newTransactionStatus = Status::EXPIRED;
                        break;
                    default:
                        $newTransactionStatus = Status::USED;
                }
                $transaction->setStatus($newTransactionStatus);
                if ($transaction->getExpirationNotified() == NotifiedStatus::WAITING) {
                    $transaction->setExpirationNotified(NotifiedStatus::CANCELLED);
                }
                $this->transactionRepository->save($transaction);
            }
        }
        return true;
    }

    /**
     * Retrieve additional filters by transaction type
     *
     * @param int $transactionType
     * @param array $arguments
     *
     * @return array
     */
    private function getAdditionalFiltersByTransactionType($transactionType, $arguments)
    {
        switch ($transactionType) {
            case TransactionType::CANCEL_EARNED_POINTS_FOR_REFUND_ORDER:
                $orderId = null;
                foreach ($arguments[TransactionEntitySaveHandler::TRANSACTION_ENTITY_TYPE] as $entity) {
                    if ($entity['entity_type'] == TransactionEntityType::ORDER_ID) {
                        $orderId = $entity['entity_id'];
                        break;
                    }
                }
                $additionalFilters = [
                    [
                        'field' => TransactionInterface::TYPE,
                        'value' => TransactionType::POINTS_REWARDED_FOR_ORDER
                    ],
                    [
                        'field' => TransactionEntityType::ORDER_ID,
                        'value' => $orderId
                    ]
                ];
                break;
            case TransactionType::POINTS_EXPIRED:
                $transactionId = null;
                foreach ($arguments[TransactionEntitySaveHandler::TRANSACTION_ENTITY_TYPE] as $entity) {
                    if ($entity['entity_type'] == TransactionEntityType::TRANSACTION_ID) {
                        $transactionId = $entity['entity_id'];
                        break;
                    }
                }
                $additionalFilters = [
                    [
                        'field' => TransactionInterface::TRANSACTION_ID,
                        'value' => $transactionId
                    ]
                ];
                break;
            default:
                $additionalFilters = [];
        }
        return $additionalFilters;
    }

    /**
     * Retrieve customer transactions
     *
     * @param int $customerId
     * @param int|null $websiteId
     * @param array $additionalFilters
     *
     * @return array
     */
    private function getCustomerTransactions($customerId, $websiteId = null, $additionalFilters = [])
    {
        $expirationDateSort = $this->sortOrderBuilder
            ->setField('ISNULL(expiration_date), expiration_date, transaction_id')
            ->setDirection(SortOrder::SORT_ASC)
            ->create();
        $balanceSort = $this->sortOrderBuilder
            ->setField('balance')
            ->setDirection(SortOrder::SORT_DESC)
            ->create();
        $this->searchCriteriaBuilder
            ->addFilter(TransactionInterface::STATUS, Status::ACTIVE)
            ->addFilter(TransactionInterface::BALANCE, 'positive')
            ->addFilter(TransactionInterface::CUSTOMER_ID, $customerId)
            ->addFilter(TransactionInterface::WEBSITE_ID, $websiteId)
            ->addSortOrder($expirationDateSort)
            ->addSortOrder($balanceSort);

        foreach ($additionalFilters as $filter) {
            $this->searchCriteriaBuilder->addFilter($filter['field'], $filter['value']);
        }

        $transactions = $this->transactionRepository
            ->getList($this->searchCriteriaBuilder->create())
            ->getItems();

        return $transactions;
    }

    /**
     * Reset Reward Points details cache
     *
     * @param int $customerId
     * @return void
     */
    private function resetRewardPointsDetailsCache($customerId)
    {
        if (isset($this->customerRewardPointsDetailsCache[$customerId])) {
            unset($this->customerRewardPointsDetailsCache[$customerId]);
        }
    }

    /**
     * Retrieve customer model
     *
     * @return CustomerInterface
     */
    private function getCustomer()
    {
        if (null == $this->customer) {
            $this->customer = $this->customerRepository->getById($this->getCustomerId());
        }
        return $this->customer;
    }

    /**
     * Retrieve comment to customer
     *
     * @param int $type
     * @return string
     */
    private function getCommentToCustomer($type)
    {
        return $this->commentPool->get($type);
    }

    /**
     * Retrieve expiration date
     *
     * @param  int|null $websiteId
     * @return string
     */
    private function getExpirationDate($websiteId = null)
    {
        $expireInDays = $this->config->getCalculationExpireRewardPoints($websiteId);

        if ($expireInDays == 0) {
            return null;
        }
        return $this->dateTime->getExpirationDate($expireInDays, false);
    }

    /**
     * Retrieve creditmemo object by id
     *
     * @param int $creditmemoId
     * @return CreditmemoInterface|bool
     */
    private function getCreditmemoById($creditmemoId)
    {
        try {
            return $this->creditmemoRepository->get($creditmemoId);
        } catch (NoSuchEntityException $e) {
            return false;
        }
    }

    /**
     * Retrieve order object by id
     *
     * @param int $orderId
     * @return OrderInterface|bool
     */
    private function getOrderById($orderId)
    {
        try {
            return $this->orderRepository->get($orderId);
        } catch (NoSuchEntityException $e) {
            return false;
        }
    }

    /**
     * Retrieve invoice object by id
     *
     * @param int $invoiceId
     * @return InvoiceInterface|bool
     */
    private function getInvoiceById($invoiceId)
    {
        try {
            return $this->invoiceRepository->get($invoiceId);
        } catch (NoSuchEntityException $e) {
            return false;
        }
    }
}
