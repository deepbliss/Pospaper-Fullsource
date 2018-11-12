<?php

namespace WeltPixel\GoogleTagManager\Observer;

use Magento\Framework\Event\ObserverInterface;

class CompareAddProductObserver implements ObserverInterface
{
    /**
     * @var \WeltPixel\GoogleTagManager\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;


    /**
     * @param \WeltPixel\GoogleTagManager\Helper\Data $helper
     */
    public function __construct(\WeltPixel\GoogleTagManager\Helper\Data $helper,
                                \Magento\Customer\Model\Session $customerSession)
    {
        $this->helper = $helper;
        $this->customerSession = $customerSession;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return self
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->helper->isEnabled()) {
            return $this;
        }

        $product = $observer->getData('product');

        $this->customerSession->setAddToCompareData($this->helper->addToComparePushData($product));

        return $this;
    }
}