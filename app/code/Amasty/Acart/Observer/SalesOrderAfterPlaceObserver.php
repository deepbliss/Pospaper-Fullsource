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

class SalesOrderAfterPlaceObserver implements ObserverInterface
{
    /**
     * @var \Amasty\Acart\Model\RuleQuoteFactory
     */
    private $ruleQuoteFactory;

    public function __construct(
        \Amasty\Acart\Model\RuleQuoteFactory $ruleQuoteFactory
    ) {
        $this->ruleQuoteFactory = $ruleQuoteFactory;
    }

    public function execute(EventObserver $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getOrder();

        if (!$order) {
            return;
        }

        /** @var \Amasty\Acart\Model\RuleQuote $ruleQuote */
        $ruleQuote = $this->ruleQuoteFactory->create();
        $ruleQuote->buyQuote($order->getQuoteId());
    }
}
