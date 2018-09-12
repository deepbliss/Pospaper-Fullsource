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
 * @author        MageModule, LLC admin@magemodule.com
 * @copyright     2018 MageModule, LLC
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\OrderImportExport\Model\Processor;

use Magento\Sales\Api\Data\OrderAddressInterface;

class Address extends \MageModule\OrderImportExport\Model\Processor\AbstractProcessor implements
    \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
{
    /**
     * @var \MageModule\OrderImportExport\Model\Cache
     */
    private $cache;

    /**
     * @var \MageModule\OrderImportExport\Helper\Address
     */
    private $helper;

    /**
     * @var \Magento\Customer\Api\Data\AddressInterfaceFactory
     */
    private $objectFactory;

    /**
     * @var \Magento\Customer\Api\AddressRepositoryInterface
     */
    private $repository;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Address\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \Magento\Sales\Api\Data\OrderAddressInterfaceFactory
     */
    private $orderAddressFactory;

    /**
     * @var string
     */
    private $addressType;

    /**
     * Address constructor.
     *
     * @param \MageModule\OrderImportExport\Model\Cache                       $cache
     * @param \MageModule\OrderImportExport\Helper\Address                    $helper
     * @param \Magento\Customer\Api\Data\AddressInterfaceFactory              $addressFactory
     * @param \Magento\Customer\Api\AddressRepositoryInterface                $repository
     * @param \Magento\Customer\Model\ResourceModel\Address\CollectionFactory $collectionFactory
     * @param \Magento\Sales\Api\Data\OrderAddressInterfaceFactory            $orderAddressFactory
     * @param string                                                          $addressType
     * @param array                                                           $excludedFields
     */
    public function __construct(
        \MageModule\OrderImportExport\Model\Cache $cache,
        \MageModule\OrderImportExport\Helper\Address $helper,
        \Magento\Customer\Api\Data\AddressInterfaceFactory $addressFactory,
        \Magento\Customer\Api\AddressRepositoryInterface $repository,
        \Magento\Customer\Model\ResourceModel\Address\CollectionFactory $collectionFactory,
        \Magento\Sales\Api\Data\OrderAddressInterfaceFactory $orderAddressFactory,
        $addressType,
        $excludedFields = []
    ) {
        parent::__construct($excludedFields);
        $this->cache               = $cache;
        $this->helper              = $helper;
        $this->objectFactory       = $addressFactory;
        $this->repository          = $repository;
        $this->collectionFactory   = $collectionFactory;
        $this->orderAddressFactory = $orderAddressFactory;
        $this->addressType         = strtolower($addressType);
    }

    /**
     * @param array                                  $data
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     *
     * @return $this|mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function process(array $data, \Magento\Sales\Api\Data\OrderInterface $order)
    {
        $customerId = $order->getCustomerId();
        $this->cacheAddresses($customerId);

        $address = $this->toOrderAddress(
            $data,
            $this->getAddress($data, $order)
        );
        $address->setEmail($order->getCustomerEmail());
        $this->removeExcludedFields($address);

        $function = 'set' . ucfirst($this->addressType) . 'Address';
        $order->{$function}($address);

        return $this;
    }

    /**
     * Tries to obtain address from cache. If not exists, saves the address if customer is not a guest.
     *
     * @param array                                                             $data
     * @param \Magento\Sales\Api\Data\OrderInterface|\Magento\Sales\Model\Order $order
     *
     * @return bool|\Magento\Customer\Api\Data\AddressInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getAddress(array $data, \Magento\Sales\Api\Data\OrderInterface $order)
    {
        $customerId = $order->getCustomerId();

        /** @var \Magento\Customer\Api\Data\AddressInterface $address */
        $address = $this->objectFactory->create();
        $address->setCustomerId($customerId);

        $key = $this->getPrefixedKey(OrderAddressInterface::COMPANY);
        if (isset($data[$key])) {
            $address->setCompany($data[$key]);
        }

        $key = $this->getPrefixedKey(OrderAddressInterface::FIRSTNAME);
        if (isset($data[$key]) && !empty($data[$key])) {
            $address->setFirstname($data[$key]);
        } else {
            $address->setFirstname('N/A');
        }

        $key = $this->getPrefixedKey(OrderAddressInterface::MIDDLENAME);
        if (isset($data[$key])) {
            $address->setMiddlename($data[$key]);
        }

        $key = $this->getPrefixedKey(OrderAddressInterface::LASTNAME);
        if (isset($data[$key]) && !empty($data[$key])) {
            $address->setLastname($data[$key]);
        } else {
            $address->setLastname('N/A');
        }

        $key = $this->getPrefixedKey(OrderAddressInterface::PREFIX);
        if (isset($data[$key])) {
            $address->setPrefix($data[$key]);
        }

        $key = $this->getPrefixedKey(OrderAddressInterface::SUFFIX);
        if (isset($data[$key])) {
            $address->setSuffix($data[$key]);
        }

        $street = [];

        $streetFullKey = $this->getPrefixedKey('street_full');
        $streetKey     = $this->getPrefixedKey(OrderAddressInterface::STREET);
        if (isset($data[$streetFullKey])) {
            $street[] = $data[$streetFullKey];
        } elseif (isset($data[$streetKey])) {
            if (is_array($data[$streetKey])) {
                $street = $data[$streetKey];
            } else {
                $street[] = $data[$streetKey];
            }
        } else {
            for ($i = 1; $i <= 8; $i++) {
                $key = $this->getPrefixedKey(OrderAddressInterface::STREET . $i);
                if (isset($data[$key])) {
                    $street[] = $data[$key];
                }
            }
        }
        $address->setStreet($street);

        $key = $this->getPrefixedKey(OrderAddressInterface::CITY);
        if (isset($data[$key])) {
            $address->setCity($data[$key]);
        }

        $key = $this->getPrefixedKey('country');
        if (isset($data[$key])) {
            $address->setCountryId(
                $this->helper->getCountryId($data[$key])
            );
        }

        $key = $this->getPrefixedKey(OrderAddressInterface::COUNTRY_ID);
        if (isset($data[$key])) {
            $address->setCountryId(
                $this->helper->getCountryId($data[$key])
            );
        }

        $key = $this->getPrefixedKey('region');
        if (isset($data[$key])) {
            $region =  $this->helper->getRegion($data[$key], $address->getCountryId());
            if ($region instanceof \Magento\Customer\Api\Data\RegionInterface) {
                $address->setRegion($region);
            }
        }

        $key = $this->getPrefixedKey(OrderAddressInterface::REGION_ID);
        if (isset($data[$key])) {
            $region = $this->helper->getRegion($data[$key], $address->getCountryId());
            if ($region instanceof \Magento\Customer\Api\Data\RegionInterface) {
                $address->setRegion($region);
            }
        }

        $key = $this->getPrefixedKey(OrderAddressInterface::POSTCODE);
        if (isset($data[$key])) {
            $address->setPostcode($data[$key]);
        }

        $key = $this->getPrefixedKey(OrderAddressInterface::TELEPHONE);
        if (isset($data[$key]) && strlen($data[$key])) {
            $address->setTelephone($data[$key]);
        } else {
            $address->setTelephone('212-555-1212');
        }

        $key = $this->getPrefixedKey(OrderAddressInterface::FAX);
        if (isset($data[$key])) {
            $address->setFax($data[$key]);
        }

        $key = $this->getPrefixedKey(OrderAddressInterface::VAT_ID);
        if (isset($data[$key])) {
            $address->setVatId($data[$key]);
        }

        $key = 'default_' . $this->addressType;
        if (isset($data[$key])) {
            $function = 'setIsDefault' . ucfirst($this->addressType);
            $address->{$function}((bool)$data[$key]);
        }

        if (!$order->getCustomerIsGuest() && $customerId) {
            $key           = $this->helper->getAddressComparisonString($address);
            $cachedAddress = $this->cache->getAddress($customerId, $key);
            if ($cachedAddress !== false) {
                return $cachedAddress;
            }

            $this->repository->save($address);
            $this->cacheAddress($address);
        }

        return $address;
    }

    /**
     * @param array                                       $data
     * @param \Magento\Customer\Api\Data\AddressInterface $address
     *
     * @return \Magento\Sales\Api\Data\OrderAddressInterface
     */
    private function toOrderAddress(array $data, \Magento\Customer\Api\Data\AddressInterface $address)
    {
        /** @var \Magento\Sales\Api\Data\OrderAddressInterface $orderAddress */
        $orderAddress = $this->orderAddressFactory->create();
        $orderAddress->setCustomerId($address->getCustomerId());
        $orderAddress->setCustomerAddressId($address->getId());
        $orderAddress->setCustomerAddressData($address);
        $orderAddress->setPrefix($address->getPrefix());
        $orderAddress->setFirstname($address->getFirstname());
        $orderAddress->setMiddlename($address->getMiddlename());
        $orderAddress->setLastname($address->getLastname());
        $orderAddress->setSuffix($address->getSuffix());
        $orderAddress->setCompany($address->getCompany());
        $orderAddress->setStreet($address->getStreet());
        $orderAddress->setCity($address->getCity());

        $region = $address->getRegion();
        if ($region !== null) {
            $orderAddress->setRegion($region->getRegion());
            $orderAddress->setRegionId($region->getRegionId());
            $orderAddress->setRegionCode($region->getRegionCode());
        }

        $orderAddress->setPostcode($address->getPostcode());
        $orderAddress->setCountryId($address->getCountryId());
        $orderAddress->setTelephone($address->getTelephone());
        $orderAddress->setFax($address->getFax());
        $orderAddress->setVatId($address->getVatId());

        $key = $this->getPrefixedKey(OrderAddressInterface::VAT_IS_VALID);
        if (isset($data[$key])) {
            $orderAddress->setVatIsValid($data[$key]);
        }

        $key = $this->getPrefixedKey(OrderAddressInterface::VAT_REQUEST_DATE);
        if (isset($data[$key])) {
            $orderAddress->setVatRequestDate($data[$key]);
        }

        $key = $this->getPrefixedKey(OrderAddressInterface::VAT_REQUEST_ID);
        if (isset($data[$key])) {
            $orderAddress->setVatRequestId($data[$key]);
        }

        $key = $this->getPrefixedKey(OrderAddressInterface::VAT_REQUEST_SUCCESS);
        if (isset($data[$key])) {
            $orderAddress->setVatRequestSuccess($data[$key]);
        }

        return $orderAddress;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function getPrefixedKey($key)
    {
        return trim($this->addressType) . '_' . trim($key);
    }

    /**
     * @param int $customerId
     *
     * @return $this
     */
    private function cacheAddresses($customerId)
    {
        if ($customerId && !$this->cache->hasAddresses($customerId)) {
            /** @var \Magento\Customer\Model\ResourceModel\Address\Collection $collection */
            $collection = $this->collectionFactory->create();
            $collection->setCustomerFilter([$customerId]);

            /** @var \Magento\Customer\Model\Address $address */
            foreach ($collection as $address) {
                $this->cacheAddress($address->getDataModel());
            }
        }

        return $this;
    }

    /**
     * @param \Magento\Customer\Api\Data\AddressInterface $address
     *
     * @return $this
     */
    private function cacheAddress(\Magento\Customer\Api\Data\AddressInterface $address)
    {
        $this->cache->addAddress($address->getCustomerId(), $address->getId(), $address);
        $this->cache->addAddress(
            $address->getCustomerId(),
            $this->helper->getAddressComparisonString($address),
            $address
        );

        return $this;
    }
}
