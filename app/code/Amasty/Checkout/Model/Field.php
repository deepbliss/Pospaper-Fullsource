<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */

namespace Amasty\Checkout\Model;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Model\AbstractModel;

class Field extends AbstractModel
{
    const XML_PATH_CONFIG = 'customer/address/';

    const MAGENTO_REQUIRE_CONFIG_VALUE = 'req';

    /**
     * @var array
     */
    private $notChangeableFields = [
        'postcode',
        'region'
    ];

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var ResourceModel\Field\CollectionFactory
     */
    protected $attributeCollectionFactory;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Amasty\Checkout\Model\ResourceModel\Field $resource,
        \Amasty\Checkout\Model\ResourceModel\Field\Collection $resourceCollection,
        \Amasty\Checkout\Model\ResourceModel\Field\CollectionFactory $attributeCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        
        $this->attributeCollectionFactory = $attributeCollectionFactory;
        $this->scopeConfig = $scopeConfig;
    }

    protected function _construct()
    {
        $this->_init('Amasty\Checkout\Model\ResourceModel\Field');
    }

    public function getInheritedAttributes()
    {
        return [
            'region_id' => 'region',
            'vat_is_valid' => 'vat_id',
            'vat_request_id' => 'vat_id',
            'vat_request_date' => 'vat_id',
            'vat_request_success' => 'vat_id',
        ];
    }

    public function getConfig($store = null)
    {
        /** @var \Amasty\Checkout\Model\ResourceModel\Field\Collection $collection */
        $collection = $this->attributeCollectionFactory->create();
        
        $collection
            ->joinStore($store)
            ->joinAttribute()
            ->setOrder('sort_order', 'ASC')
        ;

        $result = [];

        foreach ($collection as $item) {
            $result[$item->getData('attribute_code')] = $item;
        }

        return $result;
    }

    /**
     * The method gets tooltip
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTooltipInfo()
    {
        $fieldCode = $this->getData('attribute_code');
        $tooltip = __('To configure which customer attributes will be required to checkout please check settings at Stores > Configuration > Customers > Customer Configuration > Name and Address Options');
        if ($fieldCode == 'postcode') {
            $tooltip = __('To configure Postcode requirement for certain countries please check settings at Stores > Configuration > General > General > Country Options');
        } else if ($fieldCode == 'region') {
            $tooltip = __('To configure State requirement for certain countries please check settings at Stores > Configuration > General > General > State Options');
        }

        return $tooltip;
    }

    /**
     * The method checks field is require or not
     *
     * @return bool
     */
    public function isRequired()
    {
        return $this->isMagentoRequired() || $this->getData('required');
    }

    /**
     * The method checks field is changeable
     *
     * @return bool
     */
    public function isNotChangeable()
    {
        $fieldCode = $this->getData('attribute_code');

        return in_array($fieldCode, $this->notChangeableFields) ?: $this->isMagentoRequired();
    }

    /**
     * The method checks field to have required store config
     *
     * @return bool
     */
    private function isMagentoRequired()
    {
        $mageConfigValue = $this->getMagentoConfigValue();

        return (bool)($mageConfigValue == self::MAGENTO_REQUIRE_CONFIG_VALUE);
    }

    /**
     * The method gets store config value
     *
     * @return mixed
     */
    private function getMagentoConfigValue()
    {
        $configKey = $this->getData('attribute_code') == 'vat_id' ? 'taxvat' : $this->getData('attribute_code');

        return $this->scopeConfig->getValue(self::XML_PATH_CONFIG . $configKey . '_show', ScopeInterface::SCOPE_STORE);
    }
}
