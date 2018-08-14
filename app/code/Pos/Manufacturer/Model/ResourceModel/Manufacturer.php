<?php
/**
 * Copyright © 2015 Pos. All rights reserved.
 */
namespace Pos\Manufacturer\Model\ResourceModel;

/**
 * Manufacturer resource
 */
class Manufacturer extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('manufacturer_manufacturer', 'id');
    }

  
}
