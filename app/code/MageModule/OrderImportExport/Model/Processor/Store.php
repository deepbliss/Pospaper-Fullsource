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

class Store extends \MageModule\OrderImportExport\Model\Processor\AbstractProcessor implements
    \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
{
    /**
     * @var \MageModule\OrderImportExport\Model\Cache
     */
    private $cache;

    /**
     * @var \Magento\Store\Api\StoreRepositoryInterface
     */
    private $repository;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * Store constructor.
     *
     * @param \MageModule\OrderImportExport\Model\Cache   $cache
     * @param \Magento\Store\Api\StoreRepositoryInterface $repository
     * @param \Magento\Store\Model\StoreManagerInterface  $storeManager
     */
    public function __construct(
        \MageModule\OrderImportExport\Model\Cache $cache,
        \Magento\Store\Api\StoreRepositoryInterface $repository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        $excludedFields = []
    ) {
        parent::__construct($excludedFields);
        $this->cache        = $cache;
        $this->repository   = $repository;
        $this->storeManager = $storeManager;
    }

    /**
     * @param array                                                             $data
     * @param \Magento\Sales\Api\Data\OrderInterface|\Magento\Sales\Model\Order $order
     *
     * @return $this
     */
    public function process(array $data, \Magento\Sales\Api\Data\OrderInterface $order)
    {
        /**
         * if store_code is present, look up store by code. Otherwise, use default store
         *
         * In the future, we will allow user to set option to state whether or not the various
         * entity ids in the import file are expected to match the entity ids in the target database.
         * In that case, we will start matching store by store id
         */
        if (isset($data['store_code'])) {
            $store = $this->getByCode($data['store_code']);
        } elseif (isset($data['store_id'])) {
            $store = $this->getById($data['store_id']);
        } else {
            $store = $this->storeManager->getDefaultStoreView();
        }

        if ($store !== false && $store !== null) {
            $order->setStore($store);
            $order->setStoreId($store->getId());
            $order->setStoreName($store->getName());
        }

        return $this;
    }

    /**
     * @param int $id
     *
     * @return bool|\Magento\Store\Api\Data\StoreInterface|\Magento\Store\Model\Store
     */
    private function getById($id)
    {
        try {
            /** @var \Magento\Store\Api\Data\StoreInterface|\Magento\Store\Model\Store $object */
            $object = $this->cache->getStore($id);
            if ($object === false) {
                $object = $this->repository->getById($id);
                $this->cache->addStore($id, $object);
                $this->cache->addStore($object->getCode(), $object);

                $website = $object->getWebsite();
                $this->cache->addWebsite($website->getId(), $website);
                $this->cache->addWebsite($website->getCode(), $website);
            }
        } catch (\Exception $e) {
            $object = $this->getById(
                $this->storeManager->getDefaultStoreView()->getId()
            );
        }

        return $object;
    }

    /**
     * @param string $code
     *
     * @return bool|\Magento\Store\Api\Data\StoreInterface|\Magento\Store\Model\Store
     */
    private function getByCode($code)
    {
        try {
            /** @var \Magento\Store\Api\Data\StoreInterface|\Magento\Store\Model\Store $object */
            $object = $this->cache->getStore($code);
            if ($object === false) {
                $object = $this->repository->get($code);
                $this->cache->addStore($code, $object);
                $this->cache->addStore($object->getId(), $object);

                $website = $object->getWebsite();
                $this->cache->addWebsite($website->getId(), $website);
                $this->cache->addWebsite($website->getCode(), $website);
            }
        } catch (\Exception $e) {
            $object = $this->getById(
                $this->storeManager->getDefaultStoreView()->getId()
            );
        }

        return $object;
    }
}
