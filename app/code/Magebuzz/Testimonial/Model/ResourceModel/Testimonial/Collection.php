<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */
namespace Magebuzz\Testimonial\Model\ResourceModel\Testimonial;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'mb_testimonial_id';

    /**
     * Define resources model
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magebuzz\Testimonial\Model\Testimonial', 'Magebuzz\Testimonial\Model\ResourceModel\Testimonial');
    }

}