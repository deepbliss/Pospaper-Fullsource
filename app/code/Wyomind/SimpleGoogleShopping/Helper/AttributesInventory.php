<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Helper;

/**
 * Attributes management
 */
class AttributesInventory extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * {g_availability} attribute processing
     * @param \Wyomind\Simplegoogleshopping\Model\Feeds $model
     * @param array $options
     * @param \Magento\Catalog\Model\Product $product
     * @param string $reference
     * @return string the availability of the product enlcosed between tags
     */
    public function availability(
        $model,
        $options,
        $product,
        $reference
    )
    {
        $item = $model->checkReference($reference, $product);
        if ($item == null) {
            return "";
        }

        $inStock = (!isset($options['in_stock'])) ? 'in stock' : $options['in_stock'];
        $outOfStock = (!isset($options['out_of_stock'])) ? "out of stock" : $options['out_of_stock'];
        $availableForOrder = (!isset($options['pre_order'])) ? "preorder" : $options['pre_order'];

        if (($item->getManageStock() && !$item->getUseConfigManageStock() && !$model->manageStock) || ($item->getUseConfigManageStock() && $model->manageStock) || ($item->getManageStock() && !$item->getUseConfigManageStock())) {
            if ($item->getIsInStock() > 0) {
                if ($item->getTypeId() == "configurable") {
                    if (isset($model->configurableQty[$item->getId()])) {
                        $qty = $model->configurableQty[$item->getId()];
                    } else {
                        $qty = $item->getQty();
                    }
                } else {
                    $qty = $item->getQty();
                }
                if ($qty > 0) {
                    $value = $inStock;
                } else {
                    if ($item->getBackorders() || ($item->getUseConfigBackorders() && $model->backorders)) {
                        $value = $availableForOrder;
                    } else {
                        $value = $outOfStock;
                    }
                }
            } else {
                $value = $outOfStock;
            }
        } else {
            $value = $inStock;
        }
        return $value;
    }

    /**
     * {stock_status} attribute processing
     * @param \Wyomind\Simplegoogleshopping\Model\Feeds $model
     * @param array $options
     * @param \Magento\Catalog\Model\Product $product
     * @param string $reference
     * @return string quantity of the product
     */
    public function isInStock(
        $model,
        $options,
        $product,
        $reference
    )
    {
        $inStock = (!isset($options['in_stock'])) ? 'in stock' : $options['in_stock'];
        $outOfStock = (!isset($options['out_of_stock'])) ? "out of stock" : $options['out_of_stock'];
        $item = $model->checkReference($reference, $product);
        if ($item == null) {
            return "";
        }
        $value = ($item->getIs_in_stock() > 0) ? $inStock : $outOfStock;
        return $value;
    }

    /**
     * {qty} attribute processing
     * @param \Wyomind\Simplegoogleshopping\Model\Feeds $model
     * @param array $options
     * @param \Magento\Catalog\Model\Product $product
     * @param string $reference
     * @return string quantity of the product
     */
    public function qty(
        $model,
        $options,
        $product,
        $reference
    )
    {
        $item = $model->checkReference($reference, $product);
        if ($item == null) {
            return "";
        }
        $float = (!isset($options['float'])) ? 0 : $options['float'];
        if ($product->getTypeID() == "configurable") {
            if (!isset($model->configurableQty[$product->getId()])) { // configurable product without chil
                $value = number_format($item->getQty(), $float, '.', '');
            } else {
                $value = number_format($model->configurableQty[$product->getId()], $float, '.', '');
            }
        } elseif ($reference == "configurable") {
            $value = number_format($model->configurableQty[$item->getId()], $float, '.', '');
        } else {
            $value = number_format($item->getQty(), $float, '.', '');
        }
        return $value;
    }
}
