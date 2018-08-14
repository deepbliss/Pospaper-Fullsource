<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model;

use Aheadworks\RewardPoints\Api\ProductShareRepositoryInterface;
use Aheadworks\RewardPoints\Api\Data\ProductShareInterface;
use Aheadworks\RewardPoints\Api\Data\ProductShareInterfaceFactory;
use Aheadworks\RewardPoints\Model\ResourceModel\ProductShare as ProductShareResource;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Aheadworks\RewardPoints\Model\ProductShareRepository
 */
class ProductShareRepository implements ProductShareRepositoryInterface
{
    /**
     * @var ProductShareResource
     */
    private $resource;

    /**
     * @var ProductShareInterfaceFactory
     */
    private $productShareFactory;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ProductShareInterface[]
     */
    private $instancesById = [];

    /**
     * @param ProductShareResource $resource
     * @param ProductShareInterfaceFactory $productShareFactory
     * @param EntityManager $entityManager
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ProductShareResource $resource,
        ProductShareInterfaceFactory $productShareFactory,
        EntityManager $entityManager,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->productShareFactory = $productShareFactory;
        $this->entityManager = $entityManager;
        $this->storeManager = $storeManager;
    }

    /**
     *  {@inheritDoc}
     */
    public function get($customerId, $productId, $network)
    {
        $shareId = $this->resource->getShareRowId($customerId, $productId, $network);
        return $this->getById($shareId);
    }

    /**
     * {@inheritDoc}
     */
    public function getById($id)
    {
        if (isset($this->instancesById[$id])) {
            return $this->instancesById[$id];
        }
        $productShare = $this->productShareFactory->create();
        $this->entityManager->load($productShare, $id);
        $this->instancesById[$productShare->getId()] = $productShare;
        return $productShare;
    }

    /**
     *  {@inheritDoc}
     */
    public function save(ProductShareInterface $productShare)
    {
        $this->entityManager->save($productShare);
        $this->instancesById[$productShare->getId()] = $productShare;
        return $productShare;
    }

    /**
     *  {@inheritDoc}
     */
    public function delete(ProductShareInterface $productShare)
    {
        unset($this->instancesById[$productShare->getId()]);
        $this->entityManager->delete($productShare);
        return true;
    }

    /**
     *  {@inheritDoc}
     */
    public function deleteById($id)
    {
        $productShare = $this->getById($id);
        return $this->delete($productShare);
    }
}
