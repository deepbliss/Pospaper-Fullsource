<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model;

use Aheadworks\RewardPoints\Api\SpendRateRepositoryInterface;
use Aheadworks\RewardPoints\Api\Data\SpendRateInterface;
use Aheadworks\RewardPoints\Api\Data\SpendRateInterfaceFactory;
use Aheadworks\RewardPoints\Model\ResourceModel\SpendRate as SpendRateResource;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Aheadworks\RewardPoints\Model\SpendRateRepository
 */
class SpendRateRepository implements SpendRateRepositoryInterface
{
    /**
     * @var SpendRateResource
     */
    private $resource;

    /**
     * @var SpendRateInterfaceFactory
     */
    private $spendRateFactory;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var SpendRateInterface[]
     */
    private $instancesById = [];

    /**
     * @param SpendRateResource $resource
     * @param SpendRateInterfaceFactory $spendRateFactory
     * @param EntityManager $entityManager
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        SpendRateResource $resource,
        SpendRateInterfaceFactory $spendRateFactory,
        EntityManager $entityManager,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->spendRateFactory = $spendRateFactory;
        $this->entityManager = $entityManager;
        $this->storeManager = $storeManager;
    }

    /**
     *  {@inheritDoc}
     */
    public function get($customerGroupId, $lifetimeSalesAmount, $websiteId = null, $min = false)
    {
        if (null == $websiteId) {
            $websiteId = $this->getWebsiteId();
        }
        $rateId = $this->resource->getRateRowId($customerGroupId, $lifetimeSalesAmount, $websiteId, $min);
        return $this->getById($rateId);
    }

    /**
     *  {@inheritDoc}
     */
    public function getById($id)
    {
        if (isset($this->instancesById[$id])) {
            return $this->instancesById[$id];
        }
        $spendRate = $this->spendRateFactory->create();
        $this->entityManager->load($spendRate, $id);
        $this->instancesById[$spendRate->getId()] = $spendRate;
        return $spendRate;
    }

    /**
     *  {@inheritDoc}
     */
    public function save(SpendRateInterface $spendRate)
    {
        $this->entityManager->save($spendRate);
        $this->instancesById[$spendRate->getId()] = $spendRate;
        return $spendRate;
    }

    /**
     *  {@inheritDoc}
     */
    public function delete(SpendRateInterface $spendRate)
    {
        unset($this->instancesById[$spendRate->getId()]);
        $this->entityManager->delete($spendRate);
        return true;
    }

    /**
     *  {@inheritDoc}
     */
    public function deleteById($id)
    {
        $spendRate = $this->getById($id);
        return $this->delete($spendRate);
    }

    /**
     *  {@inheritDoc}
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
    }

    /**
     * Retrieve website id
     *
     * @return int
     */
    private function getWebsiteId()
    {
        return $this->storeManager->getStore()->getWebsiteId();
    }
}
