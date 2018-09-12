<?php

namespace Nextopia\Search\Model\Config\Backend;

use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Store\Model\ScopeInterface;
use \Nextopia\Search\Helper\Data;

/**
 * Backend model for client id config value
 */
class NxtSearch extends \Magento\Framework\App\Config\Value
{
    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ScopeConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {

        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * Validate a client id value
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSave()
    {
        $data = $this->_getData('fieldset_data');
        $client_id = $data["public_client_id"];

        $value = $this->getValue();

        if ($value == "1" && !Data::isClientOneLineCodeAvailable($client_id)) {
            $msg = __('Please enter a valid Public Client Id to enable the Search. Refer the installation document on how to get the Valid Public Client id');
            throw new \Magento\Framework\Exception\LocalizedException($msg);
        }

        return parent::beforeSave();
    }
}