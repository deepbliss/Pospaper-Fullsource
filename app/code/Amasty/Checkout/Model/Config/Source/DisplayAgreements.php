<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Model\Config\Source;

class DisplayAgreements implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'payment_method', 'label' => __('Below the selected payment method')],
            ['value' => 'order_totals', 'label' => __('Below the Order Total')]
        ];
    }
}
