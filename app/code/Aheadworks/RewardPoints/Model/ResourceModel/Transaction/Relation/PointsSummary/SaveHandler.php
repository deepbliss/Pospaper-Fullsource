<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\ResourceModel\Transaction\Relation\PointsSummary;

use Aheadworks\RewardPoints\Model\Service\PointsSummaryService;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;

/**
 * Class Aheadworks\RewardPoints\Model\ResourceModel\Transaction\Relation\PointsSummary\SaveHandler
 */
class SaveHandler implements ExtensionInterface
{
    /**
     * @var PointsSummaryService
     */
    private $pointsSummaryService;

    /**
     * @param PointsSummaryService $pointsSummaryService
     */
    public function __construct(
        PointsSummaryService $pointsSummaryService
    ) {
        $this->pointsSummaryService = $pointsSummaryService;
    }

    /**
     *  {@inheritDoc}
     */
    public function execute($entity, $arguments = [])
    {
        $this->pointsSummaryService->addPointsSummaryToCustomer($entity);
        return $entity;
    }
}
