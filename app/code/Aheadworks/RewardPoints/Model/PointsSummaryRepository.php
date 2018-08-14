<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model;

use Aheadworks\RewardPoints\Api\PointsSummaryRepositoryInterface;
use Aheadworks\RewardPoints\Api\Data\PointsSummaryInterfaceFactory;
use Aheadworks\RewardPoints\Api\Data\PointsSummaryInterface;
use Aheadworks\RewardPoints\Model\ResourceModel\PointsSummary as PointsSummaryResource;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class Aheadworks\RewardPoints\Model\PointsSummaryRepository
 */
class PointsSummaryRepository implements PointsSummaryRepositoryInterface
{
    /**
     * @var PointsSummaryResource
     */
    private $resource;

    /**
     * @var PointsSummaryInterfaceFactory
     */
    private $pointsSummaryFactory;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var PointsSummary[]
     */
    private $instances = [];

    /**
     * @var PointsSummary[]
     */
    private $instancesById = [];

    /**
     * @param PointsSummaryResource $resource
     * @param PointsSummaryInterfaceFactory $pointsSummaryFactory
     * @param EntityManager $entityManager
     */
    public function __construct(
        PointsSummaryResource $resource,
        PointsSummaryInterfaceFactory $pointsSummaryFactory,
        EntityManager $entityManager
    ) {
        $this->resource = $resource;
        $this->pointsSummaryFactory = $pointsSummaryFactory;
        $this->entityManager = $entityManager;
    }

    /**
     *  {@inheritDoc}
     */
    public function get($customerId)
    {
        if (isset($this->instances[$customerId])) {
            return $this->instances[$customerId];
        }
        $id = $this->resource->getIdByCustomerId($customerId);

        if (!$id) {
            throw new NoSuchEntityException(__('Requested points summary doesn\'t exist'));
        }

        return $this->getById($id);
    }

    /**
     *  {@inheritDoc}
     */
    public function getById($id)
    {
        if (isset($this->instancesById[$id])) {
            return $this->instancesById[$id];
        }

        /** @var $pointsSummary PointsSummary **/
        $pointsSummary = $this->create();
        $this->entityManager->load($pointsSummary, $id);

        if (!$pointsSummary->getSummaryId()) {
            throw new NoSuchEntityException(__('Requested points summary doesn\'t exist'));
        }

        $this->instances[$pointsSummary->getCustomerId()] = $pointsSummary;
        $this->instancesById[$pointsSummary->getSummaryId()] = $pointsSummary;

        return $pointsSummary;
    }

    /**
     *  {@inheritDoc}
     */
    public function create()
    {
        return $this->pointsSummaryFactory->create();
    }

    /**
     *  {@inheritDoc}
     */
    public function save(PointsSummaryInterface $pointsSummary)
    {
        try {
            $this->entityManager->save($pointsSummary);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        $this->instances[$pointsSummary->getCustomerId()] = $pointsSummary;
        $this->instancesById[$pointsSummary->getSummaryId()] = $pointsSummary;
        return $pointsSummary;
    }

    /**
     *  {@inheritDoc}
     */
    public function delete(PointsSummaryInterface $pointsSummary)
    {
        unset($this->instances[$pointsSummary->getCustomerId()]);
        unset($this->instancesById[$pointsSummary->getSummaryId()]);
        $this->entityManager->delete($pointsSummary);
        return true;
    }

    /**
     *  {@inheritDoc}
     */
    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }
}
