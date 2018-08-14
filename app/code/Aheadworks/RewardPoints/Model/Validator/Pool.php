<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Validator;

/**
 * Class Pool
 *
 * @package Aheadworks\RewardPoints\Model\Validator
 */
class Pool
{
    /**
     * @var array
     */
    protected $validators = [];

    /**
     * @param array $validators
     */
    public function __construct(
        array $validators = []
    ) {
        $this->validators = $validators;
    }

    /**
     * Retrieve validators
     *
     * @param string $type
     * @return array
     */
    public function getValidators($type)
    {
        return isset($this->validators[$type])
            ? $this->validators[$type]
            : [];
    }
}
