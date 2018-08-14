<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Helper;

/**
 * Attributes management
 */
class AttributesImages extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * {image} attribute processing
     * @param \Wyomind\Simplegoogleshopping\Model\Feeds $model
     * @param array $options
     * @param \Magento\Catalog\Model\Product $product
     * @param string $reference
     * @return string product's image
     */
    public function imageLink(
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
        $baseImage = $item->getImage();
        $value = "";
        if (!isset($options['index']) || $options['index'] == 0) {
            if ($item->getImage() != null && $item->getImage() != "" && $item->getImage() != 'no_selection') {
                $path = 'catalog/product/' . $item->getImage();
                $value = $model->baseImg . str_replace('//', '/', $path);
            } else {
                if ($model->defaultImage != "") {
                    $value = $model->baseImg . '/catalog/product/placeholder/' . $model->defaultImage;
                }
            }
        } elseif (isset($model->gallery[$item->getId()]['src'][$options['index'] - 1]) && $options['index'] > 0) {
            if ($model->gallery[$item->getId()]['src'][$options['index'] - 1] != $baseImage) {
                $path = 'catalog/product/' . $model->gallery[$item->getId()]['src'][$options['index'] - 1];
                $value = $model->baseImg . str_replace('//', '/', $path);
            }
        }
        return $value;
    }
}
