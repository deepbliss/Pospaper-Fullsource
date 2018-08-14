<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Observer;

use Magento\Framework\Event\ObserverInterface;
use Aheadworks\RewardPoints\Model\Config;

/**
 * Class Aheadworks\RewardPoints\Observer\Refund
 */
class Refund implements ObserverInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * Set refund amount to creditmemo
     * used for event: sales_order_creditmemo_refund
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $creditmemo = $observer->getEvent()->getCreditmemo();
        $order = $creditmemo->getOrder();

        // If refund points from admin to customer
        if ($creditmemo->getBaseAwRewardPointsRefund()) {
            $order->setBaseAwRewardPointsRefund(
                $order->getBaseAwRewardPointsRefund() + $creditmemo->getBaseAwRewardPointsRefund()
            );
            $order->setAwRewardPointsRefund(
                $order->getAwRewardPointsRefund() + $creditmemo->getAwRewardPointsRefund()
            );
            $order->setAwRewardPointsBlnceRefund(
                $order->getAwRewardPointsBlnceRefund() + $creditmemo->getAwRewardPointsBlnceRefund()
            );
        }

        // If refund points
        if ($creditmemo->getBaseAwRewardPointsAmount()) {
            $order->setBaseAwRewardPointsRefunded(
                $order->getBaseAwRewardPointsRefunded() + $creditmemo->getBaseAwRewardPointsAmount()
            );
            $order->setAwRewardPointsRefunded(
                $order->getAwRewardPointsRefunded() + $creditmemo->getAwRewardPointsAmount()
            );
            $order->setAwRewardPointsBlnceRefunded(
                $order->getAwRewardPointsBlnceRefunded() + $creditmemo->getAwRewardPoints()
            );

            /** @var $item \Magento\Sales\Model\Order\Invoice\Item */
            foreach ($creditmemo->getAllItems() as $item) {
                $orderItem = $item->getOrderItem();
                if ($orderItem->isDummy()) {
                    continue;
                }

                $orderItem->setAwRewardPointsRefunded(
                    $orderItem->getAwRewardPointsRefunded() + $item->getAwRewardPointsAmount()
                );
                $orderItem->setBaseAwRewardPointsRefunded(
                    $orderItem->getBaseAwRewardPointsRefunded() + $item->getBaseAwRewardPointsAmount()
                );
                $orderItem->setAwRewardPointsBlnceRefunded(
                    $orderItem->getAwRewardPointsBlnceRefunded() + $item->getAwRewardPoints()
                );
            }

            if ($this->config->isReimburseRefundPoints($order->getStore()->getWebsiteId())) {
                $creditmemo->setBaseAwRewardPointsReimbursed(abs($creditmemo->getBaseAwRewardPointsAmount()));
                $creditmemo->setAwRewardPointsReimbursed(abs($creditmemo->getAwRewardPointsAmount()));
                $creditmemo->setAwRewardPointsBlnceReimbursed($creditmemo->getAwRewardPoints());
                $order->setBaseAwRewardPointsReimbursed(
                    $order->getBaseAwRewardPointsReimbursed() + abs($creditmemo->getBaseAwRewardPointsAmount())
                );
                $order->setAwRewardPointsReimbursed(
                    $order->getAwRewardPointsReimbursed() + abs($creditmemo->getAwRewardPointsAmount())
                );
                $order->setAwRewardPointsBlnceReimbursed(
                    $order->getAwRewardPointsBlnceReimbursed() + $creditmemo->getAwRewardPointsBlnceReimbursed()
                );

                /** @var $item \Magento\Sales\Model\Order\Invoice\Item */
                foreach ($creditmemo->getAllItems() as $item) {
                    $orderItem = $item->getOrderItem();
                    if ($orderItem->isDummy()) {
                        continue;
                    }

                    $item->setAwRewardPointsReimbursed($item->getAwRewardPointsAmount());
                    $item->setBaseAwRewardPointsReimbursed($item->getBaseAwRewardPointsAmount());
                    $item->setAwRewardPointsBlnceReimbursed($item->getAwRewardPoints());

                    $orderItem->setAwRewardPointsReimbursed(
                        $orderItem->getAwRewardPointsReimbursed() + $item->getAwRewardPointsAmount()
                    );
                    $orderItem->setBaseAwRewardPointsReimbursed(
                        $orderItem->getBaseAwRewardPointsReimbursed() + $item->getBaseAwRewardPointsAmount()
                    );
                    $orderItem->setAwRewardPointsBlnceReimbursed(
                        $orderItem->getAwRewardPointsBlnceReimbursed() + $item->getAwRewardPoints()
                    );
                }
            }

            // we need to update flag after credit memo was refunded and order's properties changed
            if ($order->getAwRewardPointsInvoiced() < 0
                && $order->getAwRewardPointsInvoiced() == $order->getAwRewardPointsRefunded()
            ) {
                $order->setForcedCanCreditmemo(false);
            }
        }

        return $this;
    }
}
