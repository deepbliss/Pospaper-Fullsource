<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Total\Quote;

use Aheadworks\RewardPoints\Model\Calculator\RateCalculator;
use Aheadworks\RewardPoints\Model\Calculator\Spending as SpendingCalculator;
use Aheadworks\RewardPoints\Model\Config;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Aheadworks\RewardPoints\Model\Quote\Address\Total\RewardPoints
 */
class RewardPoints extends AbstractTotal
{
    /**
     * @var bool
     */
    private $isFirstTimeResetRun = true;

    /**
     * @var RateCalculator
     */
    private $rateCalculator;

    /**
     * @var SpendingCalculator
     */
    private $spendingCalculator;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param RateCalculator $rateCalculator
     * @param SpendingCalculator $spendingCalculator
     * @param Config $config
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        RateCalculator $rateCalculator,
        SpendingCalculator $spendingCalculator,
        Config $config,
        StoreManagerInterface $storeManager
    ) {
        $this->setCode('aw_reward_points');
        $this->rateCalculator = $rateCalculator;
        $this->spendingCalculator = $spendingCalculator;
        $this->config = $config;
        $this->storeManager = $storeManager;
    }

    /**
     *  {@inheritDoc}
     */
    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        $websiteId = $this->storeManager->getStore($quote->getStoreId())->getWebsiteId();
        $customerId = $quote->getCustomerId();
        $address = $shippingAssignment->getShipping()->getAddress();
        $items = $shippingAssignment->getItems();
        $this->reset($total, $quote, $address, $items);

        if (!count($items)) {
            return $this;
        }
        if (!$customerId || !$quote->getAwUseRewardPoints()) {
            $quote->setAwUseRewardPoints(false);
            $this->reset($total, $quote, $address, $items, true);
            return $this;
        }

        /** @var $rewardPointsData SpendingCalculator\Data */
        $rewardPointsData = $this->spendingCalculator->calculateAmountForRewardPoints(
            $items,
            $address,
            $customerId,
            $websiteId
        );
        if (!$rewardPointsData->getAvailablePoints()) {
            $quote->setAwUseRewardPoints(false);
            $this->reset($total, $quote, $address, $items, true);
            return $this;
        }

        /** @var \Magento\Quote\Model\Quote\Item $item */
        foreach ($items as $item) {
            if (!$this->spendingCalculator->canApplyRewardPoints($item)) {
                continue;
            }
            // To determine the child item discount, we calculate the parent
            if ($item->getParentItem()) {
                continue;
            }

            if ($item->getHasChildren() && $item->isChildrenCalculated()) {
                $this->spendingCalculator->process($item, $customerId, $websiteId);
                $this->spendingCalculator->distributeRewardPoints($item, $customerId, $websiteId);
            } else {
                $this->spendingCalculator->process($item, $customerId, $websiteId);
            }
        }

        $this->spendingCalculator->processShipping($address, $customerId, $websiteId);
        $this->addRewardPointsToTotal(
            $rewardPointsData->getUsedPointsAmount(),
            $rewardPointsData->getBaseUsedPointsAmount(),
            $rewardPointsData->getUsedPoints(),
            __('%1 Reward Points', $rewardPointsData->getUsedPoints())
        );

        $total->setSubtotalWithDiscount(
            $total->getSubtotal() + $total->getAwRewardPointsAmount()
        );
        $total->setBaseSubtotalWithDiscount(
            $total->getBaseSubtotal() + $total->getBaseAwRewardPointsAmount()
        );

        $quote->setAwRewardPointsAmount($total->getAwRewardPointsAmount());
        $quote->setBaseAwRewardPointsAmount($total->getBaseAwRewardPointsAmount());
        $quote->setAwRewardPoints($total->getAwRewardPoints());
        $quote->setAwRewardPointsDescription($total->getAwRewardPointsDescription());

        $address->setAwUseRewardPoints($quote->getAwUseRewardPoints());
        $address->setAwRewardPointsAmount($total->getAwRewardPointsAmount());
        $address->setBaseAwRewardPointsAmount($total->getBaseAwRewardPointsAmount());
        $address->setAwRewardPoints($total->getAwRewardPoints());
        $address->setAwRewardPointsDescription($total->getAwRewardPointsDescription());

        return $this;
    }

    /**
     *  {@inheritDoc}
     */
    public function fetch(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        $result = null;
        $amount = $total->getAwRewardPointsAmount();

        if ($amount != 0) {
            $description = $total->getAwRewardPointsDescription();
            $result = [
                'code' => $this->getCode(),
                'title' => strlen($description) ? __($description)
                    : __('%1 Reward Points', $total->getAwRewardPoints()),
                'value' => $amount,
            ];
        }
        return $result;
    }

    /**
     * Reset reward points total
     *
     * @param Total $total
     * @param Quote $quote
     * @param AddressInterface $address
     * @param \Magento\Quote\Api\Data\CartItemInterface[] $items
     * @param bool $reset
     * @return RewardPoints
     */
    private function reset(Total $total, Quote $quote, AddressInterface $address, $items, $reset = false)
    {
        if ($this->isFirstTimeResetRun || $reset) {
            $this->_addAmount(0);
            $this->_addBaseAmount(0);

            $total->setAwRewardPoints(0);
            $total->setAwRewardPointsDescription('');

            $quote->setAwRewardPointsAmount(0);
            $quote->setBaseAwRewardPointsAmount(0);
            $quote->setAwRewardPoints(0);
            $quote->setAwRewardPointsDescription('');

            $address->setAwUseRewardPoints(false);
            $address->setAwRewardPointsAmount(0);
            $address->setBaseAwRewardPointsAmount(0);
            $address->setAwRewardPoints(0);
            $address->setAwRewardPointsDescription('');
            $address->setAwRewardPointsShippingAmount(0);
            $address->setBaseAwRewardPointsShippingAmount(0);
            $address->setAwRewardPointsShipping(0);

            /** @var \Magento\Quote\Model\Quote\Item $item */
            foreach ($items as $item) {
                $item->setAwRewardPointsAmount(0);
                $item->setBaseAwRewardPointsAmount(0);
                $item->setAwRewardPoints(0);

                if ($item->getHasChildren() && $item->isChildrenCalculated()) {
                    foreach ($item->getChildren() as $child) {
                        $child->setAwRewardPointsAmount(0);
                        $child->setBaseAwRewardPointsAmount(0);
                        $child->setAwRewardPoints(0);
                    }
                }
            }
            $this->isFirstTimeResetRun = false;
        }
        return $this;
    }

    /**
     * Add reward points
     *
     * @param  float $rewardPointsAmount
     * @param  float $baseRewardPointsAmount
     * @param  int $pointsForUse
     * @param  string $description
     * @return RewardPoints
     */
    private function addRewardPointsToTotal(
        $rewardPointsAmount,
        $baseRewardPointsAmount,
        $pointsForUse,
        $description
    ) {
        $this->_addAmount(-$rewardPointsAmount);
        $this->_addBaseAmount(-$baseRewardPointsAmount);

        $this->_getTotal()->setAwRewardPoints($pointsForUse);
        $this->_getTotal()->setAwRewardPointsDescription($description);
        return $this;
    }
}
