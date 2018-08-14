<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

/**
 * Class Aheadworks\RewardPoints\Observer\RedeemForOrder
 */
class RedeemForOrder implements ObserverInterface
{
    /**
     *  {@inheritDoc}
     */
    public function execute(Observer $observer)
    {
        $event = $observer->getEvent();
        /** @var $order \Magento\Sales\Model\Order **/
        $order = $event->getOrder();
        /** @var $quote \Magento\Quote\Model\Quote $quote */
        $quote = $event->getQuote();

        if ($quote->getAwUseRewardPoints()) {
            $order->setAwUseRewardPoints($quote->getAwUseRewardPoints());
            $order->setAwRewardPointsAmount($quote->getAwRewardPointsAmount());
            $order->setBaseAwRewardPointsAmount($quote->getBaseAwRewardPointsAmount());
            $order->setAwRewardPoints($quote->getAwRewardPoints());
            $order->setAwRewardPointsDescription($quote->getAwRewardPointsDescription());

            $order->setAwRewardPointsShippingAmount(
                $order->getExtensionAttributes()->getAwRewardPointsShippingAmount()
            );
            $order->setBaseAwRewardPointsShippingAmount(
                $order->getExtensionAttributes()->getBaseAwRewardPointsShippingAmount()
            );
            $order->setAwRewardPointsShipping(
                $order->getExtensionAttributes()->getAwRewardPointsShipping()
            );
        }
    }
}
