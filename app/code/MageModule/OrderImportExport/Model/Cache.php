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

namespace MageModule\OrderImportExport\Model;

/**
 * Class Cache
 *
 * The purpose of this class is to store repeatable data so that it does not need to
 * be loaded over and over again. Also saves us quite a bit of memory
 *
 * @package MageModule\OrderImportExport\Model
 */
class Cache
{
    /**
     * @var array|\Magento\Store\Api\Data\WebsiteInterface[]
     */
    private $websites = [];

    /**
     * @var array|\Magento\Store\Api\Data\StoreInterface[]
     */
    private $stores = [];

    /**
     * @var array|\Magento\Customer\Api\Data\CustomerInterface[]
     */
    private $customers = [];

    /**
     * @var array|\Magento\Customer\Api\Data\GroupInterface[]
     */
    private $customerGroups = [];

    /**
     * @var array|\Magento\Customer\Api\Data\AddressInterface[]
     */
    private $addresses = [];

    /**
     * @var array|\Magento\Payment\Api\Data\PaymentMethodInterface[]
     */
    private $paymentMethods = [];

    /**
     * Store website objects using website id or website code
     *
     * @param string|int                               $key
     * @param \Magento\Store\Api\Data\WebsiteInterface $object
     *
     * @return $this
     */
    public function addWebsite($key, \Magento\Store\Api\Data\WebsiteInterface $object)
    {
        if ($key !== null && $key !== '') {
            $this->websites[$key] = $object;
        }

        return $this;
    }

    /**
     * @param string|int $key
     *
     * @return bool|\Magento\Store\Api\Data\WebsiteInterface|\Magento\Store\Model\Website
     */
    public function getWebsite($key)
    {
        return array_key_exists($key, $this->websites) ? $this->websites[$key] : false;
    }

    /**
     * Store store objects using store id or store code
     *
     * @param string|int                             $key
     * @param \Magento\Store\Api\Data\StoreInterface $object
     *
     * @return $this
     */
    public function addStore($key, \Magento\Store\Api\Data\StoreInterface $object)
    {
        if ($key !== null && $key !== '') {
            $this->stores[$key] = $object;
        }

        return $this;
    }

    /**
     * @param string|int $key
     *
     * @return bool|\Magento\Store\Api\Data\StoreInterface|\Magento\Store\Model\Store
     */
    public function getStore($key)
    {
        return array_key_exists($key, $this->stores) ? $this->stores[$key] : false;
    }

    /**
     * Store customer objects by email or customer id
     *
     * @param string|int                                   $key
     * @param \Magento\Customer\Api\Data\CustomerInterface $object
     *
     * @return $this
     */
    public function addCustomer($key, \Magento\Customer\Api\Data\CustomerInterface $object)
    {
        if ($key !== null && $key !== '') {
            $this->customers[$key] = $object;
        }

        return $this;
    }

    /**
     * @param string|int $key
     *
     * @return bool|\Magento\Customer\Api\Data\CustomerInterface|\Magento\Customer\Model\Customer
     */
    public function getCustomer($key)
    {
        return array_key_exists($key, $this->customers) ? $this->customers[$key] : false;
    }

    /**
     * @param                                           $key
     * @param \Magento\Customer\Model\Group             $object
     *
     * @return $this
     */
    public function addCustomerGroup($key, \Magento\Customer\Model\Group $object)
    {
        if ($key !== null && $key !== '') {
            $this->customerGroups[$key] = $object;
        }

        return $this;
    }

    /**
     * @param string|int $key
     *
     * @return bool|\Magento\Customer\Model\Group
     */
    public function getCustomerGroup($key)
    {
        return array_key_exists($key, $this->customerGroups) ? $this->customerGroups[$key] : false;
    }

    /**
     * @return bool
     */
    public function hasCustomerGroups()
    {
        return !empty($this->customerGroups);
    }

    /**
     * Store address by customer id & stringified address OR customer id and address id
     *
     * @param int                                         $customerId
     * @param int|string                                  $key
     * @param \Magento\Customer\Api\Data\AddressInterface $object
     *
     * @return $this
     */
    public function addAddress($customerId, $key, \Magento\Customer\Api\Data\AddressInterface $object)
    {
        if ($key !== null && $key !== '') {
            $this->addresses[$customerId][$key] = $object;
        }

        return $this;
    }

    /**
     * Get address by customer id & stringified address OR customer id and address id
     *
     * @param int        $customerId
     * @param int|string $key
     *
     * @return bool|\Magento\Customer\Api\Data\AddressInterface
     */
    public function getAddress($customerId, $key)
    {
        if (isset($this->addresses[$customerId][$key])) {
            return $this->addresses[$customerId][$key];
        }

        return false;
    }

    /**
     * @param int $customerId
     *
     * @return bool
     */
    public function hasAddresses($customerId)
    {
        return array_key_exists($customerId, $this->addresses);
    }

    /**
     * @param string|int                                       $key
     * @param \Magento\Payment\Api\Data\PaymentMethodInterface $object
     *
     * @return $this
     */
    public function addPaymentMethod($key, \Magento\Payment\Api\Data\PaymentMethodInterface $object)
    {
        if ($key !== null && $key !== '') {
            $this->paymentMethods[$key] = $object;
        }

        return $this;
    }

    /**
     * @param string|int $key
     *
     * @return bool|\Magento\Payment\Api\Data\PaymentMethodInterface
     */
    public function getPaymentMethod($key)
    {
        return array_key_exists($key, $this->paymentMethods) ? $this->paymentMethods[$key] : false;
    }

    /**
     * @return bool
     */
    public function hasPaymentMethods()
    {
        return !empty($this->paymentMethods);
    }
}
