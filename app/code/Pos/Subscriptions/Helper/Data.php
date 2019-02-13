<?php

namespace Pos\Subscriptions\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const SUBSCRIPTION_DISCOUNT = 5;

    public function getSubscriptionDiscount()
    {
        return self::SUBSCRIPTION_DISCOUNT;
    }
}