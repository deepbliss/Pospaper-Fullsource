<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\ResourceModel\ProductShare;

use Aheadworks\RewardPoints\Model\ProductShare;
use Aheadworks\RewardPoints\Model\ResourceModel\ProductShare as ProductShareResource;

/**
 * Class Aheadworks\RewardPoints\Model\ResourceModel\ProductShare\Collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    //@codeCoverageIgnoreStart

    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init(ProductShare::class, ProductShareResource::class);
    }
    //@codeCoverageIgnoreEnd

    /**
     * Add customer filter
     *
     * @param int|string $customerId
     * @return $this
     */
    public function addCustomerFilter($customerId)
    {
        $this->addFieldToFilter('customer_id', ['eq' => $customerId]);
        return $this;
    }
}
