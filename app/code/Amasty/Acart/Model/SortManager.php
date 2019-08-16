<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model;

class SortManager
{
    /**
     * @param array $products
     *
     * @return array
     */
    public function sortProducts($products)
    {
        $sortedProducts = [];

        /** @var \Magento\Catalog\Model\Product $product */
        foreach ($products as $product) {
            $sortOrder = $product->getPosition();

            while (array_key_exists($sortOrder, $sortedProducts)) {
                $sortOrder++;
            }

            $sortedProducts[$sortOrder] = $product;
        }

        ksort($sortedProducts, SORT_NUMERIC);

        return $sortedProducts;
    }
}
