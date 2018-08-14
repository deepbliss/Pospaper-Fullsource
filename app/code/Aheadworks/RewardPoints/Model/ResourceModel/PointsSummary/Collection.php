<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\ResourceModel\PointsSummary;

use Aheadworks\RewardPoints\Model\PointsSummary;
use Aheadworks\RewardPoints\Model\ResourceModel\PointsSummary as PointsSummaryResource;

/**
 * Class Aheadworks\RewardPoints\Model\ResourceModel\PointsSummary\Collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init(PointsSummary::class, PointsSummaryResource::class);
    }
}
