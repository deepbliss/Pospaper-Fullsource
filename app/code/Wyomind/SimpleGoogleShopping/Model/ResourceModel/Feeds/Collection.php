<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Model\ResourceModel\Feeds;

/**
 * Simple google shopping data feeds collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Wyomind\SimpleGoogleShopping\Model\Feeds', 'Wyomind\SimpleGoogleShopping\Model\ResourceModel\Feeds');
    }

    public function getList($feedsIds)
    {
        if (!empty($feedsIds)) {
            $this->getSelect()->where("simplegoogleshopping_id IN (" . implode(',', $feedsIds) . ")");
        }
        return $this;
    }
}
