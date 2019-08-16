<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */

/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Amasty\Acart\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

use Magento\Framework\App\Config\ScopeConfigInterface;

class SalesruleValidatorProcess implements ObserverInterface
{
    protected $_ruleQuoteFactory;

    protected $_historyFactory;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        \Amasty\Acart\Model\HistoryFactory $historyFactory,
        \Amasty\Acart\Model\RuleQuoteFactory $ruleQuoteFactory
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_historyFactory = $historyFactory;
        $this->_ruleQuoteFactory = $ruleQuoteFactory;
    }

    public function execute(EventObserver $observer)
    {
        if ($this->_scopeConfig->getValue('amasty_acart/general/customer_coupon')) {
            $salesRule = $observer->getEvent()->getRule();

            $history = $this->_historyFactory->create()->load($salesRule->getId(), 'sales_rule_id');

            if ($history->getId()) {
                $ruleQuote = $this->_ruleQuoteFactory->create()->load($history->getRuleQuoteId());
                if ($ruleQuote->getId()) {
                    $customerEmail = $ruleQuote->getCustomerId()
                        ?
                        $observer->getEvent()->getQuote()->getCustomer()->getEmail()
                        :
                        $observer->getEvent()->getQuote()->getBillingAddress()->getEmail();

                    if ($ruleQuote->getQuoteId() != $observer->getEvent()->getQuote()->getId()
                        && $customerEmail != $ruleQuote->getCustomerEmail()
                    ) {
                        $observer->getEvent()->getQuote()->setCouponCode("");
                    }
                }
            }
        }
    }
}
