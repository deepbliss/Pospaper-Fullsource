<?php
namespace Nextopia\Search\Observer;

class LoadDifferentLayout implements \Magento\Framework\Event\ObserverInterface
{
    protected $helper;

    public function __construct(\Nextopia\Search\Helper\Data $helper)
    {
        $this->helper = $helper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer) {
        if($observer->getFullActionName() == "catalog_category_view" 
                && $this->helper->isNavEnabled()
                && $this->helper->isClientOneLineCodeAvailable()) {
            $observer->getLayout()->getUpdate()->addHandle("catalog_category_view_nsearch_nav_switch");
        }
        return $this;
    }
}