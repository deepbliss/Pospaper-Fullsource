<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Plugin\Model\Tax\Total\Quote;

use Magento\Quote\Model\Quote\Item\AbstractItem;

/**
 * Class CommonTaxCollectorPlugin
 *
 * @package Aheadworks\RewardPoints\Plugin\Model\Tax\Sales\Quote
 */
class CommonTaxCollectorPlugin
{
    /**
     * Update discount amount value
     *
     * @param \Magento\Tax\Model\Sales\Total\Quote\CommonTaxCollector $subject
     * @param \Closure $proceed
     * @param \Magento\Tax\Api\Data\QuoteDetailsItemInterfaceFactory $itemDataObjectFactory
     * @param AbstractItem $item
     * @param bool $priceIncludesTax
     * @param bool $useBaseCurrency
     * @param string $parentCode
     * @return \Magento\Tax\Api\Data\QuoteDetailsItemInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundMapItem(
        $subject,
        $proceed,
        \Magento\Tax\Api\Data\QuoteDetailsItemInterfaceFactory $itemDataObjectFactory,
        AbstractItem $item,
        $priceIncludesTax,
        $useBaseCurrency,
        $parentCode = null
    ) {
        $itemDataObject = $proceed(
            $itemDataObjectFactory,
            $item,
            $priceIncludesTax,
            $useBaseCurrency,
            $parentCode
        );

        if ($useBaseCurrency) {
            $itemDataObject->setDiscountAmount(
                $itemDataObject->getDiscountAmount() + $item->getBaseAwRewardPointsAmount()
            );
        } else {
            $itemDataObject->setDiscountAmount(
                $itemDataObject->getDiscountAmount() + $item->getAwRewardPointsAmount()
            );
        }
        return $itemDataObject;
    }
}
