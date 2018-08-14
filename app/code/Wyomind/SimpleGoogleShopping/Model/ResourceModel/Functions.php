<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Model\ResourceModel;

/**
 * Function resource
 */
class Functions extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * internal constructor
     */
    protected function _construct()
    {
        $this->_init('simplegoogleshopping_functions', 'id');
    }
}
