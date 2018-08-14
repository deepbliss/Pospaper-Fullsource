<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\ResourceModel;

use Magento\Sales\Model\Order as SalesOrder;

/**
 * Flat sales order resource
 */
class Order extends \Magento\Sales\Model\ResourceModel\Order
{
    /**
     * Check if customer ordered $productId
     *
     * @param int $customerId
     * @param int $productId
     * @return boolean
     */
    public function isCustomersOwnerOfProductId($customerId, $productId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), 'entity_id')
            ->joinInner(
                ['items' => $this->getTable('sales_order_item')],
                'entity_id = items.order_id AND items.product_id = ' . $productId,
                ['product_id' => 'product_id']
            )
            ->where('customer_id = '. $customerId)
            ->where($this->getMainTable() . '.state IN (?)', [SalesOrder::STATE_COMPLETE]);
        return (bool) $connection->fetchOne($select);
    }
}
