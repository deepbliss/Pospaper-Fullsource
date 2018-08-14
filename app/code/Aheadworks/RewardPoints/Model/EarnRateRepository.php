<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model;

use Aheadworks\RewardPoints\Api\EarnRateRepositoryInterface;
use Aheadworks\RewardPoints\Api\Data\EarnRateInterface;
use Aheadworks\RewardPoints\Api\Data\EarnRateInterfaceFactory;
use Aheadworks\RewardPoints\Model\ResourceModel\EarnRate as EarnRateResource;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Aheadworks\RewardPoints\Model\EarnRateRepository
 */
class EarnRateRepository implements EarnRateRepositoryInterface
{
    /**
     * @var EarnRateResource
     */
    private $resource;

    /**
     * @var EarnRateInterfaceFactory
     */
    private $earnRateFactory;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var EarnRateInterface[]
     */
    private $instancesById = [];

    /**
     * @param EarnRateResource $resource
     * @param EarnRateInterfaceFactory $earnRateFactory
     * @param EntityManager $entityManager
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        EarnRateResource $resource,
        EarnRateInterfaceFactory $earnRateFactory,
        EntityManager $entityManager,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->earnRateFactory = $earnRateFactory;
        $this->entityManager = $entityManager;
        $this->storeManager = $storeManager;
    }

    /**
     *  {@inheritDoc}
     */
    public function get($customerGroupId, $lifetimeSalesAmount, $websiteId = null)
    {
        if (null == $websiteId) {
            $websiteId = $this->getWebsiteId();
        }
        $rateId = $this->resource->getRateRowId($customerGroupId, $lifetimeSalesAmount, $websiteId);
        return $this->getById($rateId);
    }

    /**
     * {@inheritDoc}
     */
    public function getById($id)
    {
        if (isset($this->instancesById[$id])) {
            return $this->instancesById[$id];
        }
        $earnRate = $this->earnRateFactory->create();
        $this->entityManager->load($earnRate, $id);
        $this->instancesById[$earnRate->getId()] = $earnRate;
        return $earnRate;
    }

    /**
     *  {@inheritDoc}
     */
    public function save(EarnRateInterface $earnRate)
    {
        $this->entityManager->save($earnRate);
        $this->instancesById[$earnRate->getId()] = $earnRate;
        return $earnRate;
    }

    /**
     *  {@inheritDoc}
     */
    public function delete(EarnRateInterface $earnRate)
    {
        unset($this->instancesById[$earnRate->getId()]);
        $this->entityManager->delete($earnRate);
        return true;
    }

    /**
     *  {@inheritDoc}
     */
    public function deleteById($id)
    {
        $earnRate = $this->getById($id);
        return $this->delete($earnRate);
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
