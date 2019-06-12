<?php

namespace Adroll\Pixel\Observer;

use Adroll\Pixel\Helper\Config as ConfigHelper;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\StoreManagerInterface;

class ConfigChangeObserver implements ObserverInterface {
    public function __construct(ConfigHelper $configHelper, StoreManagerInterface $storeManager)
    {
        $this->_configHelper = $configHelper;
        $this->_storeManager = $storeManager;
    }

    public function execute(Observer $observer)
    {
        $eventName = $observer->getEvent()->getName();
        $groupIds = array();
        switch ($eventName) {
            case 'store_group_delete_after':
                $groupIds[] = $observer->getData('store_group')->getId();
                break;
            case 'store_delete_after':
            case 'store_add':
                $groupIds[] = $observer->getData('store')->getGroup()->getId();
                break;
            case 'admin_system_config_changed_section_general':
            case 'admin_system_config_changed_section_currency':
                // if observer data has store: get parent group
                $storeId = $observer->getData('store');
                $websiteId = $observer->getData('website');

                if ($storeId) {
                    $groupIds[] = $this->_storeManager->getStore($storeId)->getGroup()->getId();
                } else if ($websiteId) {
                    $groupIds = $this->_storeManager->getWebsite($websiteId)->getGroupIds();
                } else {
                    foreach ($this->_storeManager->getGroups(true) as $group) {
                        $groupIds[] = $group->getId();
                    }
                }
                break;
        }

        foreach ($groupIds as $groupId) {
            $this->_configHelper->notifyShopifypyConfigChange($groupId);
        }
    }
}
