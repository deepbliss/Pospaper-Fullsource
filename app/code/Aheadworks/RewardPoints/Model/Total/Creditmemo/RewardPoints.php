<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Total\Creditmemo;

use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;
use Aheadworks\RewardPoints\Model\Calculator\RateCalculator;

/**
 * Class Aheadworks\RewardPoints\Model\Total\Creditmemo\RewardPoints
 */
class RewardPoints extends AbstractTotal
{
    /**
     * @var RateCalculator
     */
    private $rateCalculator;

    /**
     * @param RateCalculator $rateCalculator
     */
    public function __construct(
        RateCalculator $rateCalculator
    ) {
        $this->rateCalculator = $rateCalculator;
    }

    /**
     *  {@inheritDoc}
     */
    public function collect(Creditmemo $creditmemo)
    {
        $creditmemo->setAwUseRewardPoints(false);
        $creditmemo->setAwRewardPointsAmount(0);
        $creditmemo->setBaseAwRewardPointsAmount(0);
        $creditmemo->setAwRewardPoints(0);
        $creditmemo->setBaseAwRewardPointsRefunded(0);
        $creditmemo->setAwRewardPointsRefunded(0);
        $creditmemo->setBaseAwRewardPointsReimbursed(0);
        $creditmemo->setAwRewardPointsReimbursed(0);
        $creditmemo->setAwRewardPointsDescription('');

        $totalPointsAmount = 0;
        $baseTotalPointsAmount = 0;

        $order = $creditmemo->getOrder();
        $customerId = $order->getCustomerId();
        $websiteId = $order->getStore()->getWebsiteId();
        if ($order->getBaseAwRewardPointsAmount() && $order->getBaseAwRewardPointsInvoiced() != 0) {
            // Calculate how much shipping discount should be applied basing on how much shipping should be refunded
            $creditmemoBaseShippingAmount = (float)$creditmemo->getBaseShippingAmount();
            if ($creditmemoBaseShippingAmount) {
                $baseShippingDiscount = $creditmemoBaseShippingAmount *
                    ($order->getBaseAwRewardPointsShippingAmount() + $order->getBaseShippingDiscountAmount()) /
                    $order->getBaseShippingAmount();
                $shippingDiscount = $order->getShippingAmount() *
                    $baseShippingDiscount / $order->getBaseShippingAmount();

                $totalPointsAmount = $totalPointsAmount + $shippingDiscount;
                $baseTotalPointsAmount = $baseTotalPointsAmount + $baseShippingDiscount;
            }

            /** @var $item \Magento\Sales\Model\Order\Creditmemo\Item */
            foreach ($creditmemo->getAllItems() as $item) {
                $orderItem = $item->getOrderItem();
                if ($orderItem->isDummy()) {
                    continue;
                }

                $orderItemPointsAmount = (double)$orderItem->getAwRewardPointsInvoiced();
                $baseOrderItemPointsAmount = (double)$orderItem->getBaseAwRewardPointsInvoiced();
                $orderItemQty = $orderItem->getQtyInvoiced();

                if ($orderItemPointsAmount && $orderItemQty) {
                    // Resolve rounding problems
                    $pointsAmount = $orderItemPointsAmount - $orderItem->getAwRewardPointsRefunded();
                    $basePointsAmount = $baseOrderItemPointsAmount - $orderItem->getBaseAwRewardPointsRefunded();
                    if (!$item->isLast()) {
                        $activeQty = $orderItemQty - $orderItem->getQtyRefunded();
                        $pointsAmount = $creditmemo->roundPrice(
                            $pointsAmount / $activeQty * $item->getQty(),
                            'regular',
                            true
                        );
                        $basePointsAmount = $creditmemo->roundPrice(
                            $basePointsAmount / $activeQty * $item->getQty(),
                            'base',
                            true
                        );
                    }

                    $item->setAwRewardPointsAmount($pointsAmount);
                    $item->setBaseAwRewardPointsAmount($basePointsAmount);

                    $totalPointsAmount += $pointsAmount;
                    $baseTotalPointsAmount += $basePointsAmount;
                }
            }

            $usedPoints = $this->rateCalculator->calculateSpendPoints(
                $customerId,
                $baseTotalPointsAmount,
                $websiteId,
                $order->getAwRewardPointsBlnceInvoiced() - $order->getAwRewardPointsBlnceRefunded()
            );
            $usedPoints = $usedPoints > $order->getAwRewardPointsBlnceInvoiced()
                ? $order->getAwRewardPointsBlnceInvoiced()
                : $usedPoints;

            if ($usedPoints > 0) {
                $creditmemo->setAwUseRewardPoints($order->getAwUseRewardPoints());
                $creditmemo->setAwRewardPoints($usedPoints);
                $creditmemo->setAwRewardPointsDescription(__('%1 Reward Points', $usedPoints));
                $creditmemo->setBaseAwRewardPointsAmount(-$baseTotalPointsAmount);
                $creditmemo->setAwRewardPointsAmount(-$totalPointsAmount);

                $availablePoints = $usedPoints;
                /** @var $item \Magento\Sales\Model\Order\Invoice\Item */
                foreach ($creditmemo->getAllItems() as $item) {
                    $orderItem = $item->getOrderItem();
                    if ($orderItem->isDummy()) {
                        continue;
                    }

                    $rewardPoints = $this->rateCalculator->calculateSpendPoints(
                        $customerId,
                        $item->getBaseAwRewardPointsAmount(),
                        $websiteId,
                        $orderItem->getAwRewardPointsBlnceInvoiced() - $orderItem->getAwRewardPointsBlnceRefunded()
                    );
                    $rewardPoints = $rewardPoints > $availablePoints
                        ? $availablePoints
                        : $rewardPoints;

                    $item->setAwRewardPoints($rewardPoints);
                    $availablePoints -= $rewardPoints;
                }
            }
        }

        $creditmemo->setGrandTotal($creditmemo->getGrandTotal() - $totalPointsAmount);
        $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() - $baseTotalPointsAmount);

        if ($creditmemo->getGrandTotal() <= 0) {
            $creditmemo->setAllowZeroGrandTotal(true);
        }

        return $this;
    }
}
