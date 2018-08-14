<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\ResourceModel;

use Aheadworks\RewardPoints\Model\PointsSummary as PointsSummaryModel;

/**
 * Class Aheadworks\RewardPoints\Model\ResourceModel\PointsSummary
 */
class PointsSummary extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     *  {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init('aw_rp_points_summary', 'summary_id');
    }

    /**
     * Load points summary by customer id
     *
     * @param  PointsSummaryModel $pointsSummary
     * @param  int $customerId
     * @return \Aheadworks\RewardPoints\Model\ResourceModel\PointsSummary
     */
    public function loadByCustomerId(PointsSummaryModel $pointsSummary, $customerId)
    {
        return $this->load($pointsSummary, $customerId, PointsSummaryModel::CUSTOMER_ID);
    }

    /**
     * Get id by customer id
     *
     * @param int $customerId
     * @return int
     */
    public function getIdByCustomerId($customerId)
    {
        $connection = $this->getConnection();

        $select = $connection->select()
            ->from($this->getMainTable(), 'summary_id')
            ->where('customer_id = :customer_id');

        $bind = [':customer_id' => (int)$customerId];

        return $connection->fetchOne($select, $bind);
    }
}
