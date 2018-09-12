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

namespace MageModule\OrderImportExport\Model\Processor;

use Magento\Sales\Api\Data\OrderInterface;

class Customer extends \MageModule\OrderImportExport\Model\Processor\AbstractProcessor implements
    \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
{
    /**
     * @var \MageModule\OrderImportExport\Model\Cache
     */
    private $cache;

    /**
     * @var \Magento\Customer\Api\Data\CustomerInterfaceFactory
     */
    private $objectFactory;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $repository;

    /**
     * @var \Magento\Store\Api\StoreRepositoryInterface
     */
    private $storeRepository;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\CollectionFactory
     */
    private $groupCollectionFactory;

    /**
     * Customer constructor.
     *
     * @param \MageModule\OrderImportExport\Model\Cache                     $cache
     * @param \Magento\Customer\Api\Data\CustomerInterfaceFactory           $objectFactory
     * @param \Magento\Customer\Api\CustomerRepositoryInterface             $repository
     * @param \Magento\Store\Api\StoreRepositoryInterface                   $storeRepository
     * @param \Magento\Customer\Model\ResourceModel\Group\CollectionFactory $groupCollectionFactory
     * @param array                                                         $excludedFields
     */
    public function __construct(
        \MageModule\OrderImportExport\Model\Cache $cache,
        \Magento\Customer\Api\Data\CustomerInterfaceFactory $objectFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $repository,
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository,
        \Magento\Customer\Model\ResourceModel\Group\CollectionFactory $groupCollectionFactory,
        $excludedFields = []
    ) {
        parent::__construct($excludedFields);
        $this->cache                  = $cache;
        $this->objectFactory          = $objectFactory;
        $this->repository             = $repository;
        $this->storeRepository        = $storeRepository;
        $this->groupCollectionFactory = $groupCollectionFactory;
    }

    /**
     * @param array                                                             $data
     * @param \Magento\Sales\Api\Data\OrderInterface|\Magento\Sales\Model\Order $order
     *
     * @return $this|mixed
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\State\InputMismatchException
     */
    public function process(array $data, \Magento\Sales\Api\Data\OrderInterface $order)
    {
        if (isset($data[OrderInterface::CUSTOMER_IS_GUEST])) {
            $order->setCustomerIsGuest($data[OrderInterface::CUSTOMER_IS_GUEST]);
        }

        $useBillingData = !isset($data[OrderInterface::CUSTOMER_FIRSTNAME]) &&
                          !isset($data[OrderInterface::CUSTOMER_LASTNAME]);

        if ($useBillingData) {
            if (isset($data['billing_prefix'])) {
                $data[OrderInterface::CUSTOMER_PREFIX] = $data['billing_prefix'];
            }

            if (isset($data['billing_firstname'])) {
                $data[OrderInterface::CUSTOMER_FIRSTNAME] = $data['billing_firstname'];
            }

            if (isset($data['billing_middlename'])) {
                $data[OrderInterface::CUSTOMER_MIDDLENAME] = $data['billing_middlename'];
            }

            if (isset($data['billing_lastname'])) {
                $data[OrderInterface::CUSTOMER_LASTNAME] = $data['billing_lastname'];
            }

            if (isset($data['billing_suffix'])) {
                $data[OrderInterface::CUSTOMER_SUFFIX] = $data['billing_suffix'];
            }
        }

        if (!isset($data[OrderInterface::CUSTOMER_EMAIL]) && isset($data['billing_email'])) {
            $data[OrderInterface::CUSTOMER_EMAIL] = $data['billing_email'];
        }

        $customer = $this->getCustomer($data, $order);

        $order->setCustomer($customer);
        $order->setCustomerId($customer->getId());
        $order->setCustomerDob($customer->getDob());
        $order->setCustomerEmail($customer->getEmail());
        $order->setCustomerPrefix($customer->getPrefix());
        $order->setCustomerFirstname($customer->getFirstname());
        $order->setCustomerMiddlename($customer->getMiddlename());
        $order->setCustomerLastname($customer->getLastname());
        $order->setCustomerSuffix($customer->getSuffix());
        $order->setCustomerGender($customer->getGender());
        $order->setCustomerGroupId($customer->getGroupId());
        $order->setCustomerTaxvat($customer->getTaxvat());

        return $this;
    }

    /**
     * @param int $id
     *
     * @return bool|\Magento\Customer\Api\Data\CustomerInterface|\Magento\Customer\Model\Data\Customer
     */
    private function getById($id)
    {
        try {
            $object = $this->cache->getCustomer($id);
            if ($id && $object === false) {
                $object = $this->repository->getById($id);
                $this->cache->addCustomer($id, $object);
                $this->cache->addCustomer($object->getEmail(), $object);
            }
        } catch (\Exception $e) {
            $object = false;
        }

        return $object;
    }

    /**
     * @param string $email
     *
     * @return bool|\Magento\Customer\Api\Data\CustomerInterface|\Magento\Customer\Model\Data\Customer
     */
    private function getByEmail($email)
    {
        try {
            $object = $this->cache->getCustomer($email);
            if ($email && $object === false) {
                $object = $this->repository->get($email);
                $this->cache->addCustomer($email, $object);
                $this->cache->addCustomer($object->getId(), $object);
            }
        } catch (\Exception $e) {
            $object = false;
        }

        return $object;
    }

    /**
     * @param string|int $value
     *
     * @return bool|int
     */
    public function getGroupId($value)
    {
        $this->cacheCustomerGroups();
        $group = $this->cache->getCustomerGroup($value);
        if ($group !== false) {
            return $group->getId();
        }

        return false;
    }

    /**
     * @return $this
     */
    private function cacheCustomerGroups()
    {
        if (!$this->cache->hasCustomerGroups()) {
            /** @var \Magento\Customer\Model\ResourceModel\Group\Collection $collection */
            $collection = $this->groupCollectionFactory->create();
            foreach ($collection as $group) {
                $this->cache->addCustomerGroup($group->getId(), $group);
                $this->cache->addCustomerGroup($group->getCode(), $group);
            }
        }

        return $this;
    }

    /**
     * @param string|int $value
     *
     * @return bool|int
     */
    private function getGender($value)
    {
        if (strtolower(trim($value)) === 'male') {
            return 1;
        }

        if (strtolower(trim($value)) === 'female') {
            return 2;
        }

        if (is_numeric($value) && ((int)$value === 1 || (int)$value === 2)) {
            return (int)$value;
        }

        return false;
    }

    /**
     * @param array                                  $data
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     *
     * @return bool|\Magento\Customer\Api\Data\CustomerInterface|\Magento\Customer\Model\Customer|\Magento\Customer\Model\Data\Customer
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\State\InputMismatchException
     */
    private function getCustomer(array $data, \Magento\Sales\Api\Data\OrderInterface $order)
    {
        $customer = false;

        if (isset($data[OrderInterface::CUSTOMER_EMAIL]) && !$order->getCustomerIsGuest()) {
            $customer = $this->getByEmail($data[OrderInterface::CUSTOMER_EMAIL]);
        }

        if ($customer === false && !$order->getCustomerIsGuest()) {
            if (isset($data[OrderInterface::CUSTOMER_ID])) {
                $customer = $this->getById($data[OrderInterface::CUSTOMER_ID]);
            }
        }

        /** create new customer if customer cannot be found by email or id */
        if ($customer === false) {
            /** @var \Magento\Customer\Api\Data\CustomerInterface|\Magento\Customer\Model\Customer $customer */
            $customer = $this->objectFactory->create();

            if (isset($data[OrderInterface::CUSTOMER_GENDER])) {
                $gender = $this->getGender($data[OrderInterface::CUSTOMER_GENDER]);
                if ($gender !== false) {
                    $customer->setGender($gender);
                }
            }

            if (isset($data[OrderInterface::CUSTOMER_GROUP_ID])) {
                $groupId = $this->getGroupId($data[OrderInterface::CUSTOMER_GROUP_ID]);
                if ($groupId === false) {
                    $groupId = $order->getStore()->getWebsite()->getDefaultGroupId();
                }
            } else {
                $groupId = $order->getStore()->getWebsite()->getDefaultGroupId();
            }

            $customer->setGroupId($groupId);

            if (isset($data[OrderInterface::CUSTOMER_PREFIX])) {
                $customer->setPrefix($data[OrderInterface::CUSTOMER_PREFIX]);
            }

            if (isset($data[OrderInterface::CUSTOMER_FIRSTNAME])) {
                $customer->setFirstname($data[OrderInterface::CUSTOMER_FIRSTNAME]);
            }

            if (isset($data[OrderInterface::CUSTOMER_MIDDLENAME])) {
                $customer->setMiddlename($data[OrderInterface::CUSTOMER_MIDDLENAME]);
            }

            if (isset($data[OrderInterface::CUSTOMER_LASTNAME])) {
                $customer->setLastname($data[OrderInterface::CUSTOMER_LASTNAME]);
            }

            if (isset($data[OrderInterface::CUSTOMER_SUFFIX])) {
                $customer->setSuffix($data[OrderInterface::CUSTOMER_SUFFIX]);
            }

            if (isset($data[OrderInterface::CUSTOMER_DOB])) {
                $customer->setDob($data[OrderInterface::CUSTOMER_DOB]);
            }

            if (isset($data[OrderInterface::CUSTOMER_EMAIL])) {
                $customer->setEmail($data[OrderInterface::CUSTOMER_EMAIL]);
            }

            if (isset($data[OrderInterface::CUSTOMER_TAXVAT])) {
                $customer->setTaxvat($data[OrderInterface::CUSTOMER_TAXVAT]);
            }

            if (isset($data['customer_confirmation'])) {
                $customer->setConfirmation($data['customer_confirmation']);
            }

            if (isset($data['customer_disable_auto_group_change'])) {
                $customer->setDisableAutoGroupChange($data['customer_disable_auto_group_change']);
            }

            $customer->setWebsiteId($order->getStore()->getWebsiteId());
            $customer->setStoreId($order->getStore()->getId());
            $customer->setCreatedIn($order->getStore()->getName());

            if (!$order->getCustomerIsGuest()) {
                $this->repository->save($customer);
                $customer = $this->repository->get($customer->getEmail());
            }
        }

        if (!$this->cache->getCustomer($customer->getId()) &&
            !$this->cache->getCustomer($customer->getEmail())
        ) {
            $this->cache->addCustomer($customer->getId(), $customer);
            $this->cache->addCustomer($customer->getEmail(), $customer);
        }

        return $customer;
    }
}
