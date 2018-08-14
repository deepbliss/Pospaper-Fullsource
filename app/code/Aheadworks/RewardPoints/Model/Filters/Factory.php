<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Filters;

use Aheadworks\RewardPoints\Model\Filters\Transaction\CustomerSelection;
use Aheadworks\RewardPoints\Model\Filters\Transaction\ExpirationDate;
use Magento\Framework\Filter\AbstractFactory;
use Magento\Framework\Stdlib\DateTime\Filter\Date;

/**
 * Class Aheadworks\RewardPoints\Model\Filters\Factory
 */
class Factory extends AbstractFactory
{
    /**
     * @var array
     */
    protected $invokableClasses = [
        'date' => Date::class,
        'expdate' => ExpirationDate::class,
        'custselect' => CustomerSelection::class,
    ];
}
