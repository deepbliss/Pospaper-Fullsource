<?php

/**
 * Copyright © 2016 Commerce Extensions. All rights reserved.
 */

namespace CommerceExtensions\OrderImportExport\Plugin;

class CustomOrderNumber
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->objectManager = $objectManager;
        $this->_storeManager = $storeManager;
    }

    /**
     * Retreive new incrementId
     *
     * @param int $storeId
     * @return string
     */
    public function aroundReserveOrderId(
        \Magento\Quote\Model\Quote $quote,
        \Closure $closure
    )
    {
        $storeId = $quote->getStoreId();
        $incrementId = $quote->getReservedOrderId();
        if (!$this->_getStoreConfig('customnumber/general/enabled', $storeId) || $incrementId){
            return $closure();
        }
		
        $customnumber = $this->_getNotCachedConfig('customnumber', $storeId);
        
		if ($customnumber->getValue()!="") {
			$quote->setReservedOrderId($customnumber->getValue());
			$customnumber->setValue("");//set it to nothing
			$customnumber->save();
		}


        return $closure();
    }

    protected function _getStoreConfig($path, $storeId)
    {
        return $this->scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Gets not cached config row as object.
     *
     * @param string $path
     * @param int $storeId
     * @return \Magento\Framework\App\Config\Value
     */
    protected function _getNotCachedConfig( $path, $storeId)
    {
        $type = 'order';
        $cfg = $this->_getStoreConfig('customnumber/' . $type, $storeId);

        $scope   = 'default';
        $scopeId = 0;
		
        //'core/config_data_collection'
        $collection = $this->objectManager->create("Magento\Config\Model\ResourceModel\Config\Data\Collection");
        $collection->addFieldToFilter('scope', $scope);
        $collection->addFieldToFilter('scope_id', $scopeId);
        $collection->addFieldToFilter('path', 'customnumber/' . $type . '/' . $path);
        $collection->setPageSize(1);

        $v = $this->objectManager->create('Magento\Framework\App\Config\Value');
        if (count($collection)){
            $v = $collection->getFirstItem();
        }
        else {
            $v->setScope($scope);
            $v->setScopeId($scopeId);
            $v->setPath('customnumber/' . $type . '/' . $path);
        }

        return $v;
    }
}
