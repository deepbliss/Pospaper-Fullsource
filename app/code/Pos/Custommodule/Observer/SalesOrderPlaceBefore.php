<?php

namespace Pos\Custommodule\Observer;

use Magento\Framework\Event\ObserverInterface;

class SalesOrderPlaceBefore implements ObserverInterface
{
    protected $logger;

    public function __construct(\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getData('order');
        if($order->getPayment() && $method = $order->getPayment()->getMethod()) {
            if($method == 'purchaseorder') {
                $incrementId = $order->getIncrementId();
                $order->setIncrementId('PO-'.$incrementId);
            }
        }
    }
}