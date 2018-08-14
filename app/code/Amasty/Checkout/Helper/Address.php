<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Amasty\Checkout\Model\Field;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\ScopeInterface;

class Address extends AbstractHelper
{
    /**
     * @var Field
     */
    protected $fieldSingleton;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Magento\Directory\Helper\Data
     */
    protected $directoryData;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        Field $fieldSingleton,
        \Magento\Directory\Helper\Data $directoryData
    ) {
        parent::__construct($context);
        $this->fieldSingleton = $fieldSingleton;
        $this->storeManager = $storeManager;
        $this->directoryData = $directoryData;
    }

    public function fillEmpty(\Magento\Quote\Model\Quote\Address $address)
    {
        if (!$this->scopeConfig->isSetFlag('amasty_checkout/general/enabled', ScopeInterface::SCOPE_STORE))
            return;

        $fieldConfig = $this->fieldSingleton->getConfig(
            $this->storeManager->getStore()->getId()
        );

        $fieldConfig['region_id'] = $fieldConfig['region'];

        $requiredFields = [
            'firstname',
            'lastname',
            'street',
            'city',
            'telephone',
            'postcode',
            'country_id',
            'region_id'
        ];

        foreach ($requiredFields as $code) {
            if (!isset($fieldConfig[$code]))
                continue;

            /** @var \Amasty\Checkout\Model\Field $field */
            $field = $fieldConfig[$code];

            if (
                ((!$address->hasData($code) || $address->getData($code) == 0) && !$field->getData('enabled'))
                ||
                ($address->hasData($code) && !$address->getData($code) && !$field->getData('required'))
            ) {
                $defaultValue = '-';

                if ($code == 'country_id') {
                    $defaultValue = $this->scopeConfig->getValue(
                        'amasty_checkout/default_values/address_country_id',
                        ScopeInterface::SCOPE_STORE
                    );

                    if (!$defaultValue) {
                        $defaultValue = $this->scopeConfig->getValue(
                            'general/country/default',
                            ScopeInterface::SCOPE_STORE
                        );
                    }
                }
                else if ($code == 'region_id') {
                    if ($this->directoryData->isRegionRequired($address->getCountryId())) {
                        $regionCollection = $address->getCountryModel()->getRegionCollection();
                        if (!$regionCollection->count() && empty($address->getRegion())) {
                            $defaultValue = '-';
                            $address->setRegion('-');
                        } elseif (
                            $regionCollection->count()
                            && !in_array(
                                $address->getRegionId(),
                                array_column($regionCollection->getData(), 'region_id')
                            )
                        ) {
                            $defaultValue = $this->scopeConfig->getValue(
                                'amasty_checkout/default_values/address_region_id',
                                ScopeInterface::SCOPE_STORE
                            );

                            if (!$defaultValue || $defaultValue === "null") {
                                $defaultValue = $regionCollection->getFirstItem()->getData('region_id');
                            }
                        }
                    }
                    else {
                        continue;
                    }
                }

                $address->setData($code, $defaultValue);
            }
        }
    }
}
