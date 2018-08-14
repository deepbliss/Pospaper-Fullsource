<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Plugin\Model\Quote;

use Magento\Sales\Api\Data\OrderExtensionFactory;

/**
 * Class ConvertQuoteAddressToOrder
 *
 * @package Aheadworks\RewardPoints\Plugin\Model\Quote
 */
class ConvertQuoteAddressToOrder
{
    /**
     * @var OrderExtensionFactory
     */
    private $orderExtensionFactory;

    /**
     * @param OrderExtensionFactory $orderExtensionFactory
     */
    public function __construct(
        OrderExtensionFactory $orderExtensionFactory
    ) {
        $this->orderExtensionFactory = $orderExtensionFactory;
    }

    /**
     * @param \Magento\Quote\Model\Quote\Address\ToOrder $subject
     * @param \Closure $proceed
     * @param \Magento\Quote\Model\Quote\Address $quoteAddress
     * @param array $data
     * @return \Magento\Sales\Api\Data\OrderInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundConvert(
        \Magento\Quote\Model\Quote\Address\ToOrder $subject,
        \Closure $proceed,
        \Magento\Quote\Model\Quote\Address $quoteAddress,
        $data = []
    ) {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $proceed($quoteAddress, $data);

        $extensionAttributes = $order->getExtensionAttributes()
            ? $order->getExtensionAttributes()
            : $this->orderExtensionFactory->create();

        $extensionAttributes->setAwRewardPointsShippingAmount($quoteAddress->getAwRewardPointsShippingAmount());
        $extensionAttributes->setBaseAwRewardPointsShippingAmount($quoteAddress->getBaseAwRewardPointsShippingAmount());
        $extensionAttributes->setAwRewardPointsShipping($quoteAddress->getAwRewardPointsShipping());

        $order->setExtensionAttributes($extensionAttributes);
        return $order;
    }
}
