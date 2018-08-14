<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Plugin\Model\Order;

use Aheadworks\RewardPoints\Model\Calculator\RateCalculator;

/**
 * Class CreditmemoPlugin
 *
 * @package Aheadworks\RewardPoints\Plugin\Model\Order
 */
class CreditmemoPlugin
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
     * Set value for refund to Reward Points
     *
     * @param \Magento\Sales\Model\Order\Creditmemo $subject
     * @param \Closure $proceed
     * @return \Magento\Sales\Model\Order\Creditmemo
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundCollectTotals(
        $subject,
        $proceed
    ) {
        $creditmemo = $proceed();

        $creditmemo->setAwRewardPointsRefundValue(0);
        $order = $creditmemo->getOrder();
        $refundToCustomer = $this->rateCalculator->calculatePointsRefundToCustomer(
            $order->getCustomerId(),
            $creditmemo->getBaseGrandTotal(),
            $order->getStore()->getWebsiteId()
        );
        $creditmemo->setAwRewardPointsRefundValue($refundToCustomer);

        return $creditmemo;
    }
}
