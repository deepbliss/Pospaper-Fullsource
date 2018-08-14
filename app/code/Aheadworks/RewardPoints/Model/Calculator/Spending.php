<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Calculator;

use Aheadworks\RewardPoints\Model\Config;
use Aheadworks\RewardPoints\Model\CategoryAllowed;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\Quote\Api\Data\AddressInterface;
use Aheadworks\RewardPoints\Model\Validator\Pool as ValidatorPool;
use Aheadworks\RewardPoints\Model\Calculator\Spending\DataFactory as SpendingDataFactory;
use Aheadworks\RewardPoints\Model\Calculator\Spending\Data as SpendingData;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Model\Quote\Address;

/**
 * Class Spending
 *
 * @package Aheadworks\RewardPoints\Model\Calculator
 */
class Spending
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var RateCalculator
     */
    private $rateCalculator;

    /**
     * @var CategoryAllowed
     */
    private $categoryAllowed;

    /**
     * @var ValidatorPool
     */
    private $validators;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var SpendingDataFactory
     */
    private $spendingDataFactory;

    /**
     * @var SpendingData
     */
    private $rewardPointsData;

    /**
     * @param Config $config
     * @param RateCalculator $rateCalculator
     * @param CategoryAllowed $categoryAllowed
     * @param ValidatorPool $validators
     * @param PriceCurrencyInterface $priceCurrency
     * @param SpendingDataFactory $spendingDataFactory
     */
    public function __construct(
        Config $config,
        RateCalculator $rateCalculator,
        CategoryAllowed $categoryAllowed,
        ValidatorPool $validators,
        PriceCurrencyInterface $priceCurrency,
        SpendingDataFactory $spendingDataFactory
    ) {
        $this->config = $config;
        $this->rateCalculator = $rateCalculator;
        $this->categoryAllowed = $categoryAllowed;
        $this->validators = $validators;
        $this->priceCurrency = $priceCurrency;
        $this->spendingDataFactory = $spendingDataFactory;
    }

    /**
     * Quote item reward points calculation process
     *
     * @param AbstractItem $item
     * @param int $customerId
     * @param int $websiteId
     * @return $this
     */
    public function process(AbstractItem $item, $customerId, $websiteId)
    {
        $item->setAwRewardPointsAmount(0);
        $item->setBaseAwRewardPointsAmount(0);
        $item->setAwRewardPoints(0);

        $itemPrice = $this->getItemPrice($item);
        if ($itemPrice < 0) {
            return $this;
        }

        $this->applyPoints($item, $customerId, $websiteId);
        return $this;
    }

    /**
     * Distribute reward points at parent item to children items
     *
     * @param AbstractItem $item
     * @param int $customerId
     * @param int $websiteId
     * @return $this
     */
    public function distributeRewardPoints(AbstractItem $item, $customerId, $websiteId)
    {
        $roundingDelta = [];
        $keys = [
            'aw_reward_points_amount',
            'base_aw_reward_points_amount'
        ];

        // Calculate parent price with discount for bundle dynamic product
        if ($item->getHasChildren() && $item->isChildrenCalculated()) {
            $parentBaseRowTotal = $this->getItemBasePrice($item) * $item->getTotalQty();
            foreach ($item->getChildren() as $child) {
                $parentBaseRowTotal = $parentBaseRowTotal - $child->getBaseDiscountAmount();
            }
        } else {
            $parentBaseRowTotal = $this->getItemBasePrice($item) * $item->getTotalQty();
        }
        $parentAwRewardPoints = $item->getAwRewardPoints();
        foreach ($keys as $key) {
            // Initialize the rounding delta to a tiny number to avoid floating point precision problem
            $roundingDelta[$key] = 0.0000001;
        }
        foreach ($item->getChildren() as $child) {
            $ratio = ($this->getItemBasePrice($child) * $child->getTotalQty() - $child->getBaseDiscountAmount())
                / $parentBaseRowTotal;
            foreach ($keys as $key) {
                if (!$item->hasData($key)) {
                    continue;
                }
                $value = $item->getData($key) * $ratio;
                $roundedValue = $this->priceCurrency->round($value + $roundingDelta[$key]);
                $roundingDelta[$key] += $value - $roundedValue;
                $child->setData($key, $roundedValue);
            }
            $rewardPoints = $this->rateCalculator->calculateSpendPoints(
                $customerId,
                $child->getBaseAwRewardPointsAmount(),
                $websiteId
            );
            $rewardPoints = min($rewardPoints, $parentAwRewardPoints);
            $child->setAwRewardPoints($rewardPoints);
            $parentAwRewardPoints = $parentAwRewardPoints - $rewardPoints;
        }

        $item->setAwRewardPointsAmount(0);
        $item->setBaseAwRewardPointsAmount(0);
        $item->setAwRewardPoints(0);
        return $this;
    }

    /**
     * Shipping reward points calculation process
     *
     * @param AddressInterface $address
     * @param int $customerId
     * @param int $websiteId
     * @return $this
     */
    public function processShipping(AddressInterface $address, $customerId, $websiteId)
    {
        $shippingRewardPointsAmount = min(
            $this->rewardPointsData->getAvailablePointsAmountLeft(),
            $this->rewardPointsData->getShippingAmount()
        );
        $shippingBaseRewardPointsAmount = min(
            $this->rewardPointsData->getBaseAvailablePointsAmountLeft(),
            $this->rewardPointsData->getBaseShippingAmount()
        );
        $rewardPoints = $this->rateCalculator->calculateSpendPoints(
            $customerId,
            $shippingBaseRewardPointsAmount,
            $websiteId
        );
        $shippingRewardPoints = min($rewardPoints, $this->rewardPointsData->getAvailablePointsLeft());

        $address->setAwRewardPointsShippingAmount($shippingRewardPointsAmount);
        $address->setBaseAwRewardPointsShippingAmount($shippingBaseRewardPointsAmount);
        $address->setAwRewardPointsShipping($shippingRewardPoints);

        $this->rewardPointsData->setUsedPoints(
            $this->rewardPointsData->getUsedPoints() + $shippingRewardPoints
        );
        $this->rewardPointsData->setUsedPointsAmount(
            $this->rewardPointsData->getUsedPointsAmount() + $shippingRewardPointsAmount
        );
        $this->rewardPointsData->setBaseUsedPointsAmount(
            $this->rewardPointsData->getBaseUsedPointsAmount() + $shippingBaseRewardPointsAmount
        );

        return $this;
    }

    /**
     * Check on elements we can apply the points
     *
     * @param AbstractItem $item
     * @return bool
     */
    public function canApplyRewardPoints(AbstractItem $item)
    {
        $result = true;
        $categoryIds = $item->getProduct()->getCategoryIds();
        if (!$this->categoryAllowed->isAllowedCategoryForSpendPoints($categoryIds)) {
            return false;
        }

        /** @var \Zend_Validate_Interface $validator */
        foreach ($this->validators->getValidators('spending') as $validator) {
            $result = $validator->isValid($item);
            if (!$result) {
                break;
            }
        }

        return $result;
    }

    /**
     * Retrieve calculate Reward Points amount for applying
     *
     * @param \Magento\Quote\Api\Data\CartItemInterface[] $items
     * @param AddressInterface|Address $address
     * @param int $customerId
     * @param int $websiteId
     * @return SpendingData
     */
    public function calculateAmountForRewardPoints($items, AddressInterface $address, $customerId, $websiteId)
    {
        $this->rewardPointsData = $this->spendingDataFactory->create();
        $maxTotal = 0;
        $validItemsCount = 0;

        if (!is_array($items) || empty($items)) {
            return $this->rewardPointsData;
        }

        /** @var \Magento\Quote\Model\Quote\Item $item **/
        foreach ($items as $item) {
            if ($item->getParentItem()) {
                continue;
            }

            if ($this->canApplyRewardPoints($item)) {
                // For dynamic bundle
                if ($item->getHasChildren() && $item->isChildrenCalculated()) {
                    foreach ($item->getChildren() as $child) {
                        $maxTotal = $maxTotal + $this->getItemBasePrice($child) * $child->getTotalQty()
                            - $child->getBaseDiscountAmount();
                    }
                } else {
                    $maxTotal = $maxTotal + $this->getItemBasePrice($item) * $item->getTotalQty()
                        - $item->getBaseDiscountAmount();
                }
            }
            $validItemsCount++;
        }

        $baseItemsTotal = $maxTotal;
        $baseShippingAmount = 0;
        if ($this->config->isApplyingPointsToShipping($websiteId)) {
            if ($address->getBaseShippingAmountForDiscount() !== null) {
                $baseShippingAmount = $address->getBaseShippingAmountForDiscount();
            } elseif ($this->config->isShippingPriceIncludesTax($address->getQuote()->getStore()->getId())) {
                $baseShippingAmount = $address->getBaseShippingInclTax();
            } else {
                $baseShippingAmount = $address->getBaseShippingAmount();
            }
            $maxTotal = $maxTotal + $baseShippingAmount - $address->getBaseShippingDiscountAmount();
        }

        if ($shareCoveredValue = $this->config->getShareCoveredValue($websiteId)) {
            $shareCoveredValue = max(0, intval($shareCoveredValue));
            $maxTotal = $maxTotal * $shareCoveredValue / 100;
            $baseItemsTotal = $baseItemsTotal * $shareCoveredValue / 100;
        }

        if (!$maxTotal) {
            return $this->rewardPointsData;
        }
        $rewardPoints = $this->rateCalculator->calculateSpendPoints($customerId, $maxTotal, $websiteId);
        if (!$rewardPoints) {
            return $this->rewardPointsData;
        }
        $baseRewardPointsAmount = $this->rateCalculator->calculateRewardDiscount(
            $customerId,
            $rewardPoints,
            $websiteId
        );
        $baseRewardPointsAmount = min($baseRewardPointsAmount, $maxTotal);

        $this->rewardPointsData->setBaseAvailablePointsAmount($baseRewardPointsAmount);
        $this->rewardPointsData->setAvailablePointsAmount(
            $this->rateCalculator->convertCurrency($baseRewardPointsAmount)
        );
        $this->rewardPointsData->setAvailablePoints($rewardPoints);
        $this->rewardPointsData->setItemsCount($validItemsCount);
        $this->rewardPointsData->setBaseItemsTotal($baseItemsTotal);
        $this->rewardPointsData->setItemsTotal($this->rateCalculator->convertCurrency($baseItemsTotal));
        $this->rewardPointsData->setBaseShippingAmount($baseShippingAmount);
        $this->rewardPointsData->setShippingAmount($this->rateCalculator->convertCurrency($baseShippingAmount));
        return $this->rewardPointsData;
    }

    /**
     * Apply points amount to item
     *
     * @param AbstractItem $item
     * @param int $customerId
     * @param int $websiteId
     * @return void
     */
    private function applyPoints($item, $customerId, $websiteId)
    {
        $qty = $item->getTotalQty();
        $itemPrice = $this->getItemPrice($item);
        $baseItemPrice = $this->getItemBasePrice($item);

        $itemRewardPointsAmount = $this->rewardPointsData->getAvailablePointsAmountLeft();
        $itemBaseRewardPointsAmount = $this->rewardPointsData->getBaseAvailablePointsAmountLeft();

        // Calculate item price with discount for bundle dynamic product
        if ($item->getHasChildren() && $item->isChildrenCalculated()) {
            $itemPrice = $itemPrice * $qty;
            $baseItemPrice = $baseItemPrice * $qty;
            foreach ($item->getChildren() as $child) {
                $itemPrice = $itemPrice - $child->getDiscountAmount();
                $baseItemPrice = $baseItemPrice - $child->getBaseDiscountAmount();
            }
        } else {
            $itemPrice = $itemPrice * $qty - $item->getDiscountAmount();
            $baseItemPrice = $baseItemPrice * $qty - $item->getBaseDiscountAmount();
        }

        if ($this->rewardPointsData->getItemsCount() > 1) {
            $rateForItem = $baseItemPrice / $this->rewardPointsData->getBaseItemsTotal();
            $itemBaseRewardPointsAmount = $this->rewardPointsData->getBaseAvailablePointsAmount() * $rateForItem;

            $rateForItem = $itemPrice / $this->rewardPointsData->getItemsTotal();
            $itemRewardPointsAmount = $this->rewardPointsData->getAvailablePointsAmount() * $rateForItem;

            $this->rewardPointsData->setItemsCount($this->rewardPointsData->getItemsCount() - 1);
        }

        $rewardPointsAmount = min($itemRewardPointsAmount, $itemPrice);
        $baseRewardPointsAmount = min($itemBaseRewardPointsAmount, $baseItemPrice);
        $rewardPoints = $this->rateCalculator->calculateSpendPoints($customerId, $baseRewardPointsAmount, $websiteId);
        $rewardPoints = min($rewardPoints, $this->rewardPointsData->getAvailablePointsLeft());

        $item->setAwRewardPointsAmount($rewardPointsAmount);
        $item->setBaseAwRewardPointsAmount($baseRewardPointsAmount);
        $item->setAwRewardPoints($rewardPoints);

        $this->rewardPointsData->setUsedPoints(
            $this->rewardPointsData->getUsedPoints() + $rewardPoints
        );
        $this->rewardPointsData->setUsedPointsAmount(
            $this->rewardPointsData->getUsedPointsAmount() + $rewardPointsAmount
        );
        $this->rewardPointsData->setBaseUsedPointsAmount(
            $this->rewardPointsData->getBaseUsedPointsAmount() + $baseRewardPointsAmount
        );
    }

    /**
     * Retrieve item price
     *
     * @param AbstractItem $item
     * @return float
     */
    private function getItemPrice($item)
    {
        $price = $item->getDiscountCalculationPrice();
        $calcPrice = $item->getCalculationPrice();
        return $price === null
            ? $calcPrice
            : $price;
    }

    /**
     * Retrieve item base price
     *
     * @param AbstractItem $item
     * @return float
     */
    private function getItemBasePrice($item)
    {
        $price = $item->getDiscountCalculationPrice();
        return $price !== null
            ? $item->getBaseDiscountCalculationPrice()
            : $item->getBaseCalculationPrice();
    }
}
