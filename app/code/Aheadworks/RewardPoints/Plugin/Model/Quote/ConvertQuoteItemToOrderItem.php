<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Plugin\Model\Quote;

/**
 * Class ConvertQuoteItemToOrderItem
 *
 * @package Aheadworks\RewardPoints\Plugin\Model\Quote
 */
class ConvertQuoteItemToOrderItem
{
    /**
     * @param \Magento\Quote\Model\Quote\Item\ToOrderItem $subject
     * @param \Closure $proceed
     * @param \Magento\Quote\Model\Quote\Item\AbstractItem $item
     * @param array $additional
     * @return \Magento\Sales\Model\Order\Item
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundConvert(
        \Magento\Quote\Model\Quote\Item\ToOrderItem $subject,
        \Closure $proceed,
        \Magento\Quote\Model\Quote\Item\AbstractItem $item,
        $additional = []
    ) {
        /** @var $orderItem \Magento\Sales\Model\Order\Item */
        $orderItem = $proceed($item, $additional);

        $orderItem->setBaseAwRewardPointsAmount($item->getBaseAwRewardPointsAmount());
        $orderItem->setAwRewardPointsAmount($item->getAwRewardPointsAmount());
        $orderItem->setAwRewardPoints($item->getAwRewardPoints());

        return $orderItem;
    }
}
