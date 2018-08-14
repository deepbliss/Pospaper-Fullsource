<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Api;

use Aheadworks\RewardPoints\Api\Data\PointsSummaryInterface;

/**
 * @api
 */
interface PointsSummaryRepositoryInterface
{
    /**
     * Retrieve points summary data by id
     *
     * @param  int $id
     * @return PointsSummaryInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * Retrieve points summary data by customer id
     *
     * @param  int $customerId
     * @return PointsSummaryInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($customerId);

    /**
     * Create new instance
     *
     * @return PointsSummaryInterface
     */
    public function create();

    /**
     * Save points summary data
     *
     * @param  PointsSummaryInterface $pointsSummary
     * @return PointsSummaryInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(PointsSummaryInterface $pointsSummary);

    /**
     * Delete points summary by id
     *
     * @param  int $id
     * @return boolean
     */
    public function deleteById($id);

    /**
     * Delete points summary data
     *
     * @param  PointsSummaryInterface $pointsSummary
     * @return boolean
     */
    public function delete(PointsSummaryInterface $pointsSummary);
}
