<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Source\Transaction;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Aheadworks\RewardPoints\Model\Source\Transaction\Expire
 */
class Expire implements ArrayInterface
{
    /**#@+
     * Constants for keys of data array.
     * Identical to the name of the getter in snake case
     */
    const EXPIRE_IN_X_DAYS = 'expire_in_x_days';
    const EXPIRE_ON_EXACT_DAYS = 'expire_on_exact_days';
    /**#@-*/

    /**
     * @var array
     */
    private $options = [];

    /**
     *  {@inheritDoc}
     */
    public function toOptionArray()
    {
        if (empty($this->options)) {
            $this->options = [
                ['value' => self::EXPIRE_IN_X_DAYS, 'label' => __('In X days')],
                ['value' => self::EXPIRE_ON_EXACT_DAYS, 'label' => __('On exact date')],
            ];
        }
        return $this->options;
    }
}
