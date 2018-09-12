<?php
/**
 * Copyright (c) 2018 MageModule, LLC: All rights reserved
 *
 * LICENSE: This source file is subject to our standard End User License
 * Agreeement (EULA) that is available through the world-wide-web at the
 * following URI: http://www.magemodule.com/magento2-ext-license.html.
 *
 * If you did not receive a copy of the EULA and are unable to obtain it through
 * the web, please send a note to admin@magemodule.com so that we can mail
 * you a copy immediately.
 *
 * @author       MageModule, LLC admin@magemodule.com
 * @copyright   2018 MageModule, LLC
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\OrderImportExport\Helper;

use Magento\Customer\Api\Data\AddressInterface as CustomerAddress;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Framework\DataObject;

class Address
{
    /**
     * @var \Magento\Customer\Api\AddressRepositoryInterface
     */
    private $addressRepository;

    /**
     * @var \Magento\Customer\Model\Address\Mapper
     */
    private $addressMapper;

    /**
     * @var \Magento\Directory\Model\Config\Source\Country
     */
    private $countryList;

    /**
     * @var \Magento\Customer\Api\Data\RegionInterfaceFactory
     */
    private $regionFactory;

    /**
     * @var \Magento\Directory\Model\ResourceModel\Region\CollectionFactory
     */
    private $regionCollectionFactory;

    /**
     * Address constructor.
     *
     * @param \Magento\Customer\Api\AddressRepositoryInterface                $addressRepository
     * @param \Magento\Customer\Model\Address\Mapper                          $addressMapper
     * @param \Magento\Directory\Model\Config\Source\Country                  $country
     * @param \Magento\Customer\Api\Data\RegionInterfaceFactory               $regionFactory
     * @param \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory
     */
    public function __construct(
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Customer\Model\Address\Mapper $addressMapper,
        \Magento\Directory\Model\Config\Source\Country $country,
        \Magento\Customer\Api\Data\RegionInterfaceFactory $regionFactory,
        \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory
    ) {
        $this->addressRepository       = $addressRepository;
        $this->addressMapper           = $addressMapper;
        $this->countryList             = $country->toOptionArray(false);
        $this->regionFactory           = $regionFactory;
        $this->regionCollectionFactory = $regionCollectionFactory;
    }

    /**
     * @param \Magento\Customer\Api\Data\AddressInterface|\Magento\Framework\DataObject|array|string $address
     *
     * @return string|bool
     */
    public function getCountryId($address)
    {
        if ($address instanceof \Magento\Customer\Api\Data\AddressInterface) {
            return $address->getCountryId();
        }

        $needle = null;
        if (is_string($address)) {
            $needle = strtolower(trim($address));
        } elseif (is_array($address)) {
            $needle = strtolower($address[AddressInterface::COUNTRY_ID]);
        }

        if ($needle !== null && $needle !== '') {
            foreach ($this->countryList as $country) {
                if ($country['value'] !== '') {
                    $haystack = [strtolower($country['value']), strtolower($country['label'])];
                    if (in_array($needle, $haystack)) {
                        return $country['value'];
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param \Magento\Customer\Api\Data\AddressInterface|\Magento\Framework\DataObject|array|string $address
     * @param string                                                                                 $countryId
     *
     * @return bool|\Magento\Customer\Api\Data\RegionInterface
     */
    public function getRegion($address, $countryId)
    {
        if ($address instanceof \Magento\Customer\Api\Data\AddressInterface) {
            return $address->getRegion();
        } elseif (is_string($address) && $address) {
            /** @var \Magento\Directory\Model\ResourceModel\Region\Collection $collection */
            $collection = $this->regionCollectionFactory->create();
            $collection->addCountryFilter($countryId);
            $collection->addFieldToFilter(
                ['main_table.code', 'rname.name', 'main_table.region_id'],
                [$address, $address, $address]
            );

            /** @var \Magento\Customer\Api\Data\RegionInterface $region */
            $region = $this->regionFactory->create();

            $options = $collection->getItems();
            if ($options) {
                /** @var \Magento\Directory\Model\Region $item */
                $item = $collection->getFirstItem();
                $region->setRegionCode($item->getCode());
                $region->setRegion($item->getName());
                $region->setRegionId($item->getId());
            } else {
                $region->setRegion($address);
                $region->setRegionCode($address);
            }

            return $region;
        }

        return false;
    }

    /**
     * @param \Magento\Customer\Api\Data\AddressInterface|\Magento\Framework\DataObject|array $address1
     * @param \Magento\Customer\Api\Data\AddressInterface|\Magento\Framework\DataObject|array $address2
     *
     * @return bool
     */
    public function compareAddresses($address1, $address2)
    {
        $string1 = $this->getAddressComparisonString($address1);
        $string2 = $this->getAddressComparisonString($address2);

        return strcasecmp($string1, $string2) === 0;
    }

    /**
     * @param \Magento\Customer\Api\Data\AddressInterface|\Magento\Framework\DataObject|array $address
     *
     * @return string
     */
    public function getAddressComparisonString($address)
    {
        $data = [];
        if ($address instanceof CustomerAddress) {
            $countryId = $this->getCountryId($address);
            $region    = $this->getRegion($address, $countryId);

            $data = [
                CustomerAddress::COMPANY    => $address->getCompany(),
                CustomerAddress::FIRSTNAME  => $address->getFirstname(),
                CustomerAddress::MIDDLENAME => $address->getMiddlename(),
                CustomerAddress::LASTNAME   => $address->getLastname(),
                CustomerAddress::PREFIX     => $address->getPrefix(),
                CustomerAddress::SUFFIX     => $address->getSuffix(),
                CustomerAddress::STREET     => $address->getStreet(),
                CustomerAddress::CITY       => $address->getCity(),
                CustomerAddress::REGION     => $region,
                CustomerAddress::COUNTRY_ID => $countryId,
                CustomerAddress::POSTCODE   => $address->getPostcode(),
                CustomerAddress::TELEPHONE  => $address->getTelephone(),
                CustomerAddress::FAX        => $address->getFax(),
                CustomerAddress::VAT_ID     => $address->getVatId()
            ];
        } elseif ($address instanceof DataObject) {
            $data = $address->getData();
        } elseif (is_array($address)) {
            $data = $address;
        }
        $data = array_map(
            function ($value) {
                if (is_string($value)) {
                    return $value;
                } elseif (is_array($value)) {
                    return implode('', $value);
                } elseif ($value instanceof \Magento\Customer\Api\Data\RegionInterface) {
                    return $value->getRegion();
                }
            },
            $data
        );

        return preg_replace('/[^\da-z]/i', '', strtolower(implode('', array_filter($data, 'strlen'))));
    }

    /**
     * Matches an address to one of the customers stored addresses.
     * If it exists, returns the id of the address object, otherwise, returns false
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param \Magento\Customer\Api\Data\AddressInterface  $address
     *
     * @return bool|int|null
     */
    public function getAddressId(
        \Magento\Customer\Api\Data\CustomerInterface $customer,
        \Magento\Customer\Api\Data\AddressInterface $address
    ) {
        foreach ($customer->getAddresses() as $matchAddress) {
            if ($this->compareAddresses($address, $matchAddress)) {
                return $matchAddress->getId();
            }
        }

        return false;
    }
}
