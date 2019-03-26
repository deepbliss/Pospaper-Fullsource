<?php

namespace Pos\Subscriptions\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const SUBSCRIPTION_DISCOUNT = 5;

    public function getSubscriptionDiscount()
    {
        return self::SUBSCRIPTION_DISCOUNT;
    }

    public function isActive()
    {
        return $this->scopeConfig->getValue('subscriptions/general/active', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}