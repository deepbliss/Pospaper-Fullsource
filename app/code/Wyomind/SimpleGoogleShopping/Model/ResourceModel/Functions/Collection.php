<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Model\ResourceModel\Functions;

/**
 * Custom functions collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Wyomind\SimpleGoogleShopping\Model\Functions', 'Wyomind\SimpleGoogleShopping\Model\ResourceModel\Functions');
    }
}
