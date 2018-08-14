<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Observer\Admin;

use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\ScopeInterface;

class ViewInformation implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->scopeConfig->isSetFlag('amasty_checkout/general/enabled', ScopeInterface::SCOPE_STORE))
            return;

        $elementName = $observer->getElementName();

        if ('order_info' == $elementName) {
            $block = $observer->getLayout()->getBlock($elementName);
            if ($block->hasData('amcheckout_delivery'))
                return;

            $transport = $observer->getTransport();
            $html = $transport->getOutput();

            $deliveryBlock = $observer->getLayout()
                ->createBlock('Amasty\Checkout\Block\Adminhtml\Sales\Order\Delivery');

            $html .= $deliveryBlock->toHtml();
            $block->setData('amcheckout_delivery', true);

            $transport->setOutput($html);
        }
    }
}
