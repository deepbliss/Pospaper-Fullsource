<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Total\Invoice;

use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;
use Aheadworks\RewardPoints\Model\Calculator\RateCalculator;

/**
 * Class Aheadworks\RewardPoints\Model\Total\Invoice\RewardPoints
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
     * {@inheritDoc}
     */
    public function collect(Invoice $invoice)
    {
        $invoice->setAwUseRewardPoints(false);
        $invoice->setAwRewardPointsAmount(0);
        $invoice->setBaseAwRewardPointsAmount(0);
        $invoice->setAwRewardPoints(0);
        $invoice->setAwRewardPointsDescription('');

        $totalPointsAmount = 0;
        $baseTotalPointsAmount = 0;

        $order = $invoice->getOrder();
        $customerId = $order->getCustomerId();
        $websiteId = $order->getStore()->getWebsiteId();
        if ($order->getBaseAwRewardPointsAmount()
            && $order->getBaseAwRewardPointsInvoiced() != $order->getBaseAwRewardPointsAmount()
        ) {
            // Checking if Reward Points shipping amount was added in previous invoices
            $addRewardPointsShippingAmount = true;
            foreach ($order->getInvoiceCollection() as $previousInvoice) {
                if ($previousInvoice->getAwRewardPointsAmount()) {
                    $addRewardPointsShippingAmount = false;
                }
            }

            if ($addRewardPointsShippingAmount) {
                $totalPointsAmount = $totalPointsAmount + $order->getAwRewardPointsShippingAmount();
                $baseTotalPointsAmount = $baseTotalPointsAmount + $order->getBaseAwRewardPointsShippingAmount();
            }

            /** @var $item \Magento\Sales\Model\Order\Invoice\Item */
            foreach ($invoice->getAllItems() as $item) {
                $orderItem = $item->getOrderItem();
                if ($orderItem->isDummy()) {
                    continue;
                }

                $orderItemPointsAmount = (double)$orderItem->getAwRewardPointsAmount();
                $baseOrderItemPointsAmount = (double)$orderItem->getBaseAwRewardPointsAmount();
                $orderItemQty = $orderItem->getQtyOrdered();

                if ($orderItemPointsAmount && $orderItemQty) {
                    // Resolve rounding problems
                    $pointsAmount = $orderItemPointsAmount - $orderItem->getAwRewardPointsInvoiced();
                    $basePointsAmount = $baseOrderItemPointsAmount - $orderItem->getBaseAwRewardPointsInvoiced();
                    if (!$item->isLast()) {
                        $activeQty = $orderItemQty - $orderItem->getQtyInvoiced();
                        $pointsAmount = $invoice->roundPrice(
                            $pointsAmount / $activeQty * $item->getQty(),
                            'regular',
                            true
                        );
                        $basePointsAmount = $invoice->roundPrice(
                            $basePointsAmount / $activeQty * $item->getQty(),
                            'base',
                            true
                        );
                    }

                    $item->setAwRewardPointsAmount($pointsAmount);
                    $item->setBaseAwRewardPointsAmount($basePointsAmount);

                    $orderItem->setAwRewardPointsInvoiced(
                        $orderItem->getAwRewardPointsInvoiced() + $item->getAwRewardPointsAmount()
                    );
                    $orderItem->setBaseAwRewardPointsInvoiced(
                        $orderItem->getBaseAwRewardPointsInvoiced() + $item->getBaseAwRewardPointsAmount()
                    );

                    $totalPointsAmount += $pointsAmount;
                    $baseTotalPointsAmount += $basePointsAmount;
                }
            }

            $usedPoints = $this->rateCalculator->calculateSpendPoints(
                $customerId,
                $baseTotalPointsAmount,
                $websiteId,
                $order->getAwRewardPoints() - $order->getAwRewardPointsBlnceInvoiced()
            );
            $usedPoints = $usedPoints > $order->getAwRewardPoints()
                ? $order->getAwRewardPoints()
                : $usedPoints;

            if ($usedPoints > 0) {
                $invoice->setAwUseRewardPoints($order->getAwUseRewardPoints());
                $invoice->setAwRewardPoints($usedPoints);
                $invoice->setAwRewardPointsDescription(__('%1 Reward Points', $usedPoints));
                $invoice->setBaseAwRewardPointsAmount(-$baseTotalPointsAmount);
                $invoice->setAwRewardPointsAmount(-$totalPointsAmount);
                $order->setAwRewardPointsBlnceInvoiced($order->getAwRewardPointsBlnceInvoiced() + $usedPoints);

                $availablePoints = $usedPoints;
                /** @var $item \Magento\Sales\Model\Order\Invoice\Item */
                foreach ($invoice->getAllItems() as $item) {
                    $orderItem = $item->getOrderItem();
                    if ($orderItem->isDummy()) {
                        continue;
                    }

                    $rewardPoints = $this->rateCalculator->calculateSpendPoints(
                        $customerId,
                        $item->getBaseAwRewardPointsAmount(),
                        $websiteId,
                        $orderItem->getAwRewardPoints() - $orderItem->getAwRewardPointsBlnceInvoiced()
                    );
                    $rewardPoints = $rewardPoints > $availablePoints
                        ? $availablePoints
                        : $rewardPoints;

                    $item->setAwRewardPoints($rewardPoints);
                    $orderItem->setAwRewardPointsBlnceInvoiced(
                        $orderItem->getAwRewardPointsBlnceInvoiced() + $item->getAwRewardPoints()
                    );
                    $availablePoints -= $rewardPoints;
                }
            }

            $invoice->setGrandTotal($invoice->getGrandTotal() - $totalPointsAmount);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() - $baseTotalPointsAmount);
        }
        return $this;
    }
}
