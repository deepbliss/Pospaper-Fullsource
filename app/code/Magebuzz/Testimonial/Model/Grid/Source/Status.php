<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */
namespace Magebuzz\Testimonial\Model\Grid\Source;

use Magento\Framework\Option\ArrayInterface;

class Status implements ArrayInterface
{
    protected $_testimonial;

    /**
     * Status constructor.
     */
    public function __construct(\Magebuzz\Testimonial\Model\Testimonial $testimonial)
    {
        $this->_testimonial = $testimonial;
    }

    public function toOptionArray()
    {
        $statusAvailable = $this->_testimonial->getAvailableStatuses();
        $options = ['value' => '', 'label' => ''];
        foreach ($statusAvailable as $key => $value) {
            $options[] = [
                'value' => $key,
                'label' => $value
            ];
        }
        return $options;
    }

}