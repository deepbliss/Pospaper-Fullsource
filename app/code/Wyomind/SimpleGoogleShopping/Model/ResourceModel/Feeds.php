<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Model\ResourceModel;

/**
 * Simple google shopping data feed mysql resource
 */
class Feeds extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init('simplegoogleshopping_feeds', 'simplegoogleshopping_id');
    }
}
