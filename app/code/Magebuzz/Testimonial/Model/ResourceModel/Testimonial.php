<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */
namespace Magebuzz\Testimonial\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Testimonial extends AbstractDb{
    /**
     * @return mixed
     */
    protected function _construct()
    {
        $this->_init('mb_testimonial', 'mb_testimonial_id');
    }

}