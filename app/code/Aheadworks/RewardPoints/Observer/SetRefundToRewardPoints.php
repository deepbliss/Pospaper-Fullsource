<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Aheadworks\RewardPoints\Model\Calculator\RateCalculator;

/**
 * Class SetRefundToRewardPoints
 *
 * @package Aheadworks\RewardPoints\Observer
 */
class SetRefundToRewardPoints implements ObserverInterface
{
    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var RateCalculator
     */
    private $rateCalculator;

    /**
     * @param PriceCurrencyInterface $priceCurrency
     * @param RateCalculator $rateCalculator
     */
    public function __construct(
        PriceCurrencyInterface $priceCurrency,
        RateCalculator $rateCalculator
    ) {
        $this->priceCurrency = $priceCurrency;
        $this->rateCalculator = $rateCalculator;
    }

    /**
     * Set refund flag to creditmemo based on user input
     * used for event: adminhtml_sales_order_creditmemo_register_before
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $input = $observer->getEvent()->getInput();
        $creditMemo = $observer->getEvent()->getCreditmemo();
        $order = $creditMemo->getOrder();

        if (isset($input['refund_to_aw_reward_points_enable']) && isset($input['refund_to_aw_reward_points'])) {
            $enable = $input['refund_to_aw_reward_points_enable'];
            $points = $input['refund_to_aw_reward_points'];
            if ($enable && is_numeric($points)) {
                $creditMemo->setAwRewardPointsBlnceRefund($points);

                $amount = $this->rateCalculator->calculateRewardDiscount(
                    $order->getCustomerId(),
                    $points,
                    $order->getStore()->getWebsiteId()
                );

                $creditMemo->setBaseAwRewardPointsRefund($amount);
                $amount = $this->priceCurrency->round(
                    $amount * $creditMemo->getOrder()->getBaseToOrderRate()
                );
                $creditMemo->setAwRewardPointsRefund($amount);
            }
        }

        return $this;
    }
}
