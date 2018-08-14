<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class SalesOrderLoadAfter
 *
 * @package Aheadworks\RewardPoints\Observer
 */
class SalesOrderLoadAfter implements ObserverInterface
{
    /**
     * Set forced canCreditmemo flag
     * used for event: sales_order_load_after
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        if ($order->canUnhold()) {
            return $this;
        }

        if ($order->isCanceled() || $order->getState() === \Magento\Sales\Model\Order::STATE_CLOSED) {
            return $this;
        }

        if ((abs($order->getAwRewardPointsInvoiced()) - abs($order->getAwRewardPointsRefunded())) > 0) {
            $order->setForcedCanCreditmemo(true);
        }

        return $this;
    }
}
