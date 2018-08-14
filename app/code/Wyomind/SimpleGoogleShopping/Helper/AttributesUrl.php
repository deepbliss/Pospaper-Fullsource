<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Helper;

/**
 * Attributes management
 */
class AttributesUrl extends \Magento\Framework\App\Helper\AbstractHelper
{

    public function host(
        $model,
        $options,
        $product,
        $reference
    )
    {

        unset($options);
        unset($product);
        unset($reference);
        return $model->getStoreUrl();
    }

    public function urlConfig(
        $model,
        $options,
        $product,
        $reference
    )
    {

        unset($reference);
        $parent = $model->checkReference("configurable", $product);
        if ($parent == null) {
            return "";
        }
        $attributes = $parent->getTypeInstance(true)->getConfigurableAttributes($parent);

        $url = $this->url($model, $options, $product, 'configurable') . "?ps="; // ps parameter is for Google Microdata
        $atts = [];
        foreach ($attributes as $attribute) {
            $att = $attribute->getProductAttribute()->getData('attribute_code');
            $id = $attribute->getProductAttribute()->getData('attribute_id');
            if ($product->getData($att) == "") {
                $product->load($product->getId());
            }
            $atts[] = $id . '=' . $product->getData($att);
        }

        return $url . base64_encode(implode('&', $atts)) . "#" . implode('&', $atts);
    }

    /**
     * {url} attribute processing
     * @param \Wyomind\Simplegoogleshopping\Model\Feeds $model
     * @param array $options
     * @param \Magento\Catalog\Model\Product $product
     * @param string $reference
     * @return string url of the product
     */
    public function url(
        $model,
        $options,
        $product,
        $reference
    )
    {

        unset($options);
        $item = $model->checkReference($reference, $product);
        if ($item == null) {
            return "";
        }

        if ($item->getRequest_path()) {
            // shortest
            if ($model->urlRewrites == \Wyomind\SimpleGoogleShopping\Model\Config\UrlRewrite::SHORTEST_URL) {
                $arr = explode(",", $item->getRequest_path());
                usort($arr, ['\Wyomind\SimpleGoogleShopping\Helper\Attributes', 'cmp']);
                $value = $model->storeUrl . array_pop($arr);
            } elseif ($model->urlRewrites == \Wyomind\SimpleGoogleShopping\Model\Config\UrlRewrite::LONGEST_URL) { // longest
                $arr = explode(",", $item->getRequest_path());
                usort($arr, ['\Wyomind\SimpleGoogleShopping\Helper\Attributes', 'cmp']);
                $value = $model->storeUrl . array_shift($arr);
            } else {
                $value = $model->storeUrl . $item->getRequest_path();
            }
        } else {
            $value = "";
        }
        if ($value == "") { // last fallback
            if ($item->getUrlKey() != "") {
                $value = $model->storeUrl . "catalog/product/view/id/" . $item->getId();
            }
        }
        return $value;
    }

    /**
     * {uri}
     * @param \Wyomind\Simplegoogleshopping\Model\Feeds $model
     * @param array $options
     * @param \Magento\Catalog\Model\Product $product
     * @param string $reference
     * @return string
     */
    public function uri(
        $model,
        $options,
        $product,
        $reference
    )
    {

        unset($options);
        $item = $model->checkReference($reference, $product);
        if ($item == null) {
            return "";
        }

        if ($item->getRequest_path()) {
            // shortest
            if ($model->urlRewrites == \Wyomind\SimpleGoogleShopping\Model\Config\UrlRewrite::SHORTEST_URL) {
                $arr = explode(",", $item->getRequest_path());
                usort($arr, ['\Wyomind\SimpleGoogleShopping\Helper\Attributes', 'cmp']);
                $value = array_pop($arr);
            } elseif ($model->urlRewrites == \Wyomind\SimpleGoogleShopping\Model\Config\UrlRewrite::LONGEST_URL) { // longest
                $arr = explode(",", $item->getRequest_path());
                usort($arr, ['\Wyomind\SimpleGoogleShopping\Helper\Attributes', 'cmp']);
                $value = array_shift($arr);
            } else {
                $value = $item->getRequest_path();
            }
        } else {
            $value = str_replace($model->storeUrl, '', $item->getProductUrl());
        }
        return $value;
    }
}
