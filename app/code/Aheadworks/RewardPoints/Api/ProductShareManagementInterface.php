<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Api;

use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * @api
 */
interface ProductShareManagementInterface
{
    /**
     * Adds a product share.
     *
     * @param  int $customerId
     * @param  int $productId
     * @param  string $network
     * @return boolean
     * @throws AlreadyExistsException The specified product share already exists.
     * @throws CouldNotSaveException The specified product share not be added.
     */
    public function add($customerId, $productId, $network);
}
