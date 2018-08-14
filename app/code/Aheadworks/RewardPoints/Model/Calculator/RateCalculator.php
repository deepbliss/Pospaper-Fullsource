<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Calculator;

use Aheadworks\RewardPoints\Api\Data\EarnRateInterface;
use Aheadworks\RewardPoints\Api\Data\SpendRateInterface;
use Aheadworks\RewardPoints\Api\EarnRateRepositoryInterface;
use Aheadworks\RewardPoints\Api\SpendRateRepositoryInterface;
use Aheadworks\RewardPoints\Model\Service\PointsSummaryService;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Sales\Model\ResourceModel\Sale\CollectionFactory as SaleCollectionFactory;
use Magento\Sales\Model\Order;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Aheadworks\RewardPoints\Model\Calculator\RateCalculator
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class RateCalculator
{
    /**
     * @var int
     */
    private $customerId;

    /**
     * @var array
     */
    private $customerCacheData = [];

    /**
     * @var EarnRateRepositoryInterface
     */
    private $earnRateRepository;

    /**
     * @var EarnRateInterface
     */
    private $earnRate;

    /**
     * @var SpendRateRepositoryInterface
     */
    private $spendRateRepository;

    /**
     * @var SpendRateInterface
     */
    private $spendRate;

    /**
     * @var PointsSummaryService
     */
    private $pointsSummaryService;

    /**
     * @var SaleCollectionFactory
     */
    private $saleCollectionFactory;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var GroupManagementInterface
     */
    private $groupService;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param EarnRateRepositoryInterface $earnRateRepository
     * @param SpendRateRepositoryInterface $spendRateRepository
     * @param PointsSummaryService $pointsSummaryService
     * @param SaleCollectionFactory $saleCollectionFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param GroupManagementInterface $groupService
     * @param PriceCurrencyInterface $priceCurrency
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        EarnRateRepositoryInterface $earnRateRepository,
        SpendRateRepositoryInterface $spendRateRepository,
        PointsSummaryService $pointsSummaryService,
        SaleCollectionFactory $saleCollectionFactory,
        CustomerRepositoryInterface $customerRepository,
        GroupManagementInterface $groupService,
        PriceCurrencyInterface $priceCurrency,
        StoreManagerInterface $storeManager
    ) {
        $this->earnRateRepository = $earnRateRepository;
        $this->spendRateRepository = $spendRateRepository;
        $this->pointsSummaryService = $pointsSummaryService;
        $this->saleCollectionFactory = $saleCollectionFactory;
        $this->customerRepository = $customerRepository;
        $this->groupService = $groupService;
        $this->priceCurrency = $priceCurrency;
        $this->storeManager = $storeManager;
    }

    /**
     * Set customer id
     *
     * @param int $customerId
     * @return RateCalculator
     */
    private function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
        return $this;
    }

    /**
     * Retrieve customer id
     *
     * @return int
     */
    private function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * Calculate earn points
     *
     * @param int $customerId
     * @param float $amount
     * @param int|null $websiteId
     * @return int
     */
    public function calculateEarnPoints($customerId, $amount, $websiteId = null)
    {
        $this->setCustomerId($customerId);
        $rate = $this->getEarnRate($websiteId);
        return (int)$this->calculateRate($rate->getBaseAmount(), $rate->getPoints(), $amount);
    }

    /**
     * Calculate spend points
     *
     * @param int $customerId
     * @param float $amount
     * @param int|null $websiteId
     * @param int|null $points
     * @return int
     */
    public function calculateSpendPoints($customerId, $amount, $websiteId = null, $points = null)
    {
        $customerSpendPoints = 0;
        $this->setCustomerId($customerId);
        $customerPointsBalance = $points == null ? $this->getCustomerPointsBalance() : $points;

        if ($customerPointsBalance > 0) {
            $spendRate = $this->getSpendRate($websiteId);
            $spendRatePoints = $spendRate->getPoints();
            $spendRateAmount = $spendRate->getBaseAmount();

            $customerSpendPoints = $this->calculateUsePoints($customerPointsBalance, $spendRatePoints);

            $customerDiscountAmount = $this->calculateRate($spendRatePoints, $spendRateAmount, $customerSpendPoints);
            if ($customerDiscountAmount > $amount) {
                $customerSpendPoints = (int)ceil($this->calculateRate($spendRateAmount, $spendRatePoints, $amount));
            }
        }
        return $customerSpendPoints;
    }

    /**
     * Calculate spend points
     *
     * @param int $customerId
     * @param float $amount
     * @param int|null $websiteId
     * @return int
     */
    public function calculatePointsRefundToCustomer($customerId, $amount, $websiteId = null)
    {
        $this->setCustomerId($customerId);

        $spendRate = $this->getSpendRate($websiteId);
        $spendRatePoints = $spendRate->getPoints();
        $spendRateAmount = $spendRate->getBaseAmount();
        $customerSpendPoints = (int)ceil($this->calculateRate($spendRateAmount, $spendRatePoints, $amount));

        return $customerSpendPoints;
    }

    /**
     * Calculate reward discount
     *
     * @param int $customerId
     * @param int $points
     * @param int|null $websiteId
     * @param SpendRateInterface $rate
     * @return float
     */
    public function calculateRewardDiscount($customerId, $points, $websiteId = null, $rate = null)
    {
        $this->setCustomerId($customerId);
        if (null == $rate) {
            $rate = $this->getSpendRate($websiteId);
        }
        return round($this->calculateRate($rate->getPoints(), $rate->getBaseAmount(), $points), 2);
    }

    /**
     * Convert to store currency
     *
     * @param float $baseAmount
     * @return float
     */
    public function convertCurrency($baseAmount)
    {
        return $this->priceCurrency->convertAndRound($baseAmount);
    }

    /**
     * Retrieve earn rate model
     *
     * @param  int|null $websiteId
     * @param  int|null $customerId
     * @param  bool $useLifetimeSales
     * @param  bool $cached
     * @return EarnRateInterface
     */
    public function getEarnRate($websiteId = null, $customerId = null, $useLifetimeSales = true, $cached = true)
    {
        if ((!$cached && null !=$this->earnRate) || null == $this->earnRate) {
            if (null != $customerId) {
                $this->setCustomerId($customerId);
            }
            $lifetimeSalesAmount = $useLifetimeSales
                ? $this->getLifetimeSalesAmount($websiteId)
                : null;
            $this->earnRate = $this->earnRateRepository->get(
                $this->getCustomerGroupId(),
                $lifetimeSalesAmount,
                $websiteId
            );
        }
        return $this->earnRate;
    }

    /**
     * Retrieve spend rate model
     *
     * @param  int|null $websiteId
     * @param  int|null $customerId
     * @param  bool $useLifetimeSales
     * @param  bool $cached
     * @return SpendRateInterface
     */
    public function getSpendRate($websiteId = null, $customerId = null, $useLifetimeSales = true, $cached = true)
    {
        if ((!$cached && null != $this->spendRate) || null == $this->spendRate) {
            if (null != $customerId) {
                $this->setCustomerId($customerId);
            }
            $lifetimeSalesAmount = $useLifetimeSales
                ? $this->getLifetimeSalesAmount($websiteId)
                : null;
            $this->spendRate = $this->spendRateRepository->get(
                $this->getCustomerGroupId(),
                $lifetimeSalesAmount,
                $websiteId
            );
        }
        return $this->spendRate;
    }

    /**
     * Retrieve min spend rate is customer needed
     *
     * @param  int $customerId
     * @param  int|null $websiteId
     * @return []
     */
    public function getMinRateIsNeeded($customerId, $websiteId = null)
    {
        $this->setCustomerId($customerId);
        $lifetimeSalesAmount = $this->getLifetimeSalesAmount($websiteId);

        $spendRate = $this->spendRateRepository->get(
            $this->getCustomerGroupId(),
            null,
            $websiteId,
            true
        );

        return ['spend_rate' => $spendRate, 'lifetime_sales' => $lifetimeSalesAmount];
    }

    /**
     * Calculate rate
     *  $result = ($baseY * $targetX) / $baseX
     *
     * @param int $baseX
     * @param int $baseY
     * @param int $targetX
     * @return float
     */
    private function calculateRate($baseX, $baseY, $targetX)
    {
        $result = 0;
        if ($baseX > 0) {
            $result = ($baseY * $targetX) / $baseX;
        }
        return $result;
    }

    /**
     * Calculate use spend points
     *
     * @param int $customerPoints
     * @param int $ratePoints
     * @return int
     */
    private function calculateUsePoints($customerPoints, $ratePoints)
    {
        $usePoints = 0;
        if ($ratePoints > 0) {
            $usePoints = $customerPoints;
        }
        return $usePoints;
    }

    /**
     * Retrieve customer group id
     *
     * @return int
     */
    private function getCustomerGroupId()
    {
        $customerId = (int) $this->getCustomerId();
        $customerGroupId = $this->getCustomerCacheData($customerId, 'customer_group');

        if ($customerGroupId == null) {
            if ($customerId == 0) {
                $customerGroupId = $this->getDefaultGroupId();
            } else {
                /** @var $customer CustomerInterface **/
                $customer = $this->customerRepository->getById($customerId);
                $customerGroupId = $customer->getGroupId();
            }
            $this->setCustomerCacheData($customerId, 'customer_group', $customerGroupId);
        }
        return $customerGroupId;
    }

    /**
     * Retrieve default group id
     *
     * @return int
     */
    private function getDefaultGroupId()
    {
        $defaultGroupId = $this->getCustomerCacheData(0, 'default_group_id');

        if ($defaultGroupId == null) {
            $defaultGroup = $this->groupService->getDefaultGroup();
            $defaultGroupId = $defaultGroup->getId();
            $this->setCustomerCacheData(0, 'default_group_id', $defaultGroupId);
        }
        return $defaultGroupId;
    }

    /**
     * Retrieve customer lifetime sales amount
     *
     * @param  int|null $websiteId
     * @return float
     */
    private function getLifetimeSalesAmount($websiteId = null)
    {
        $customerId = (int) $this->getCustomerId();
        $lifetimeSalesAmount = $this->getCustomerCacheData($customerId, 'lifetime_sales_amount');

        if ($lifetimeSalesAmount == null) {
            if ($customerId == 0) {
                $lifetimeSalesAmount = 0;
            } else {
                if (null == $websiteId) {
                    $websiteId = $this->storeManager->getStore()->getWebsiteId();
                }
                $storeIds = $this->storeManager->getWebsite($websiteId)->getStoreIds();
                $lifetimeSalesAmount = $this->getLifetimeSalesValue($customerId, $storeIds);
            }
            $this->setCustomerCacheData($customerId, 'lifetime_sales_amount', $lifetimeSalesAmount);
        }
        return $lifetimeSalesAmount;
    }

    /**
     * Retrieve lifetime sales value
     *
     * @param int $customerId
     * @param [] $storeIds
     * @return float
     */
    private function getLifetimeSalesValue($customerId, $storeIds)
    {
        /** @var $salesCollection \Magento\Sales\Model\ResourceModel\Sale\Collection */
        $salesCollection = $this->saleCollectionFactory->create();
        $connection = $salesCollection->getConnection();
        $columns = $connection->describeTable($salesCollection->getTable('sales_order'));
        $lifetimeSumQuery = 'SUM(IFNULL(base_total_invoiced, 0)) - SUM(IFNULL(base_total_refunded, 0))';
        $allowedExternalColumns = [
            'base_aw_store_credit_refunded' => '+',
            'base_aw_reward_points_refund' => '+'
        ];

        foreach ($allowedExternalColumns as $allowedExternalColumn => $operation) {
            if (isset($columns[$allowedExternalColumn])) {
                $lifetimeSumQuery .= $operation . 'SUM(IFNULL(' . $allowedExternalColumn . ', 0))';
            }
        }

        $select = $connection->select()
            ->from(
                $salesCollection->getTable('sales_order'),
                ['lifetime_sales' => new \Zend_Db_Expr('(' . $lifetimeSumQuery . ')')]
            )
            ->where('customer_id=?', $customerId)
            ->where(
                'state IN (?)',
                [Order::STATE_COMPLETE, Order::STATE_CLOSED, Order::STATE_PROCESSING]
            )->where(
                'store_id IN (?)',
                implode(',', $storeIds)
            )->group('store_id');

        return floatval($connection->fetchOne($select));
    }

    /**
     * Retrieve customer points summary balance
     *
     * @return int
     */
    private function getCustomerPointsBalance()
    {
        $customerId = $this->getCustomerId();

        $customerPointsBalance = $this->getCustomerCacheData($customerId, 'customer_points_balance');

        if ($customerPointsBalance == null) {
            $customerPointsBalance
                = $this->pointsSummaryService->getCustomerRewardPointsBalance($customerId);
            $this->setCustomerCacheData($customerId, 'customer_points_balance', $customerPointsBalance);
        }
        return $customerPointsBalance;
    }

    /**
     * Retrieve customer cache data
     *
     * @param int $customerId
     * @param string $keyData
     * @return string
     */
    private function getCustomerCacheData($customerId, $keyData)
    {
        if (isset($this->customerCacheData[$customerId], $this->customerCacheData[$customerId][$keyData])) {
            return $this->customerCacheData[$customerId][$keyData];
        }
        return null;
    }

    /**
     * Set customer cache
     *
     * @param  int $customerId
     * @param  string $keyData
     * @param  string $valueData
     * @return RateCalculator
     */
    private function setCustomerCacheData($customerId, $keyData, $valueData)
    {
        if (!isset($this->customerCacheData[$customerId])) {
            $this->customerCacheData[$customerId] = [];
        }
        $this->customerCacheData[$customerId][$keyData] = $valueData;
        return $this;
    }
}
