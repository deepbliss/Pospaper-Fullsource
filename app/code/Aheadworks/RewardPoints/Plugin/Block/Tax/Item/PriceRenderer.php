<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Plugin\Block\Tax\Item;

use Magento\Sales\Model\Order\CreditMemo\Item as CreditMemoItem;
use Magento\Sales\Model\Order\Invoice\Item as InvoiceItem;
use Magento\Sales\Model\Order\Item as OrderItem;

/**
 * Class PriceRenderer
 *
 * @package Aheadworks\RewardPoints\Plugin\Block\Tax\Item
 */
class PriceRenderer
{
    /**
     * Subtract reward points data
     *
     * @param \Magento\Tax\Block\Item\Price\Renderer $subject
     * @param \Closure $proceed
     * @param OrderItem|InvoiceItem|CreditMemoItem $item
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundGetTotalAmount(
        \Magento\Tax\Block\Item\Price\Renderer $subject,
        \Closure $proceed,
        $item
    ) {
        $totalAmount = $proceed($item);
        // Convert to the same type
        return (string)(float)$totalAmount - (string)(float)$item->getAwRewardPointsAmount();
    }

    /**
     * Subtract reward points data
     *
     * @param \Magento\Tax\Block\Item\Price\Renderer $subject
     * @param \Closure $proceed
     * @param OrderItem|InvoiceItem|CreditMemoItem $item
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundGetBaseTotalAmount(
        \Magento\Tax\Block\Item\Price\Renderer $subject,
        \Closure $proceed,
        $item
    ) {
        $totalAmount = $proceed($item);
        // Convert to the same type
        return (string)(float)$totalAmount - (string)(float)$item->getBaseAwRewardPointsAmount();
    }
}
