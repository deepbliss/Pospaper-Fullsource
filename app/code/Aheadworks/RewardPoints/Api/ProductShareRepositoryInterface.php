<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Api;

use Aheadworks\RewardPoints\Api\Data\ProductShareInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * @api
 */
interface ProductShareRepositoryInterface
{
    /**
     * Retrieve product share by id
     *
     * @param  int $id
     * @return ProductShareInterface
     */
    public function getById($id);

    /**
     * Retrieve product share
     *
     * @param  int $customerId
     * @param  int $productId
     * @param  string $network
     * @return ProductShareInterface
     */
    public function get($customerId, $productId, $network);

    /**
     * Save product share
     *
     * @param  ProductShareInterface $productShare
     * @return ProductShareInterface
     */
    public function save(ProductShareInterface $productShare);

    /**
     * Delete product share by id
     *
     * @param  int $id
     * @return boolean
     */
    public function deleteById($id);

    /**
     * Delete product share
     *
     * @param ProductShareInterface $earnRate
     * @return boolean
     */
    public function delete(ProductShareInterface $productShare);
}
