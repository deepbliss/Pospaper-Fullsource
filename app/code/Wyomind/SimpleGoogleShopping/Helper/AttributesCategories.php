<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Helper;

/**
 * Attributes management
 */
class AttributesCategories extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * {g_google_product_category} attribute processing
     * @param \Wyomind\Simplegoogleshopping\Model\Feeds $model
     * @param array $options
     * @param \Magento\Catalog\Model\Product $product
     * @param string $reference
     * @return string g:google_product_category xml tags
     */
    public function googleProductCategory(
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
        $values = [];
        $type = (!isset($options["type"])) ? "longest" : $options["type"];

        foreach ($item->getCategoryIds() as $key => $category) {
            if (isset($model->categoriesMapping[$category])) {
                $values[] = $model->categoriesMapping[$category];
            }
        }
        usort($values, ["\Wyomind\SimpleGoogleShopping\Helper\Attributes", "cmp"]);

        if ($type == "shortest") {
            $values = array_reverse($values);
        }
        $googleProductCategory = array_shift($values);
        $value = "";
        if ($googleProductCategory != "") {
            $value = $googleProductCategory;
        }
        return $value;
    }

    public function categories($model, $options, $product, $reference)
    {

        $item = $model->checkReference($reference, $product);
        if ($item == null) {
            return "";
        }

        $nth = 1;
        $from = 1;
        $length = null;
        $separator = " > ";

        $return = !isset($options["url"]) ? 'name' : 'url';

        $opts = ["nth", "from", "length", "spearator"];
        foreach ($opts as $opt) {
            if (isset($options[$opt])) {
                $$opt = $options[$opt];
            }
        }

        $path = 0;
        $categorieList = [];

        foreach ($item->getCategoryIds() as $key => $category) {


            $isIncategoryFilter = $model->params["simplegoogleshopping_category_filter"] && isset($model->categories[$category]) && isset($model->categories[$category]["path"]);
            $isOutcategoryFilter = !$model->params["simplegoogleshopping_category_filter"] && isset($model->categories[$category]) && isset($model->categories[$category]["path"]);

            if (isset($model->categories[$category]) && $model->categories[$category]["include_in_menu"] && $model->categoriesFilterList[0] == "*") {
                $path++;
                $categorieList[$path] = [];

                $pathIds = explode("/", $model->categories[$category]["path"]);
                if (in_array($model->rootCategory, $pathIds)) {
                    foreach ($pathIds as $pathId) {
                        if (isset($model->categories[$pathId]) && $model->categories[$pathId][$return] != null) {
                            $categorieList[$path][] = ($model->categories[$pathId][$return]);
                        }
                    }
                }
            } elseif (isset($model->categories[$category]) && $model->categories[$category]["include_in_menu"]) {

                $path++;
                $categorieList[$path] = [];

                $pathIds = explode("/", $model->categories[$category]["path"]);


                if (in_array($model->rootCategory, $pathIds)) {
                    foreach ($pathIds as $pathId) {
                        if (($isIncategoryFilter && in_array($category, $model->categoriesFilterList)) || ($isOutcategoryFilter && !in_array($category, $model->categoriesFilterList))) {

                            if (isset($model->categories[$pathId]) && $model->categories[$pathId][$return] != null) {
                                $categorieList[$path][] = ($model->categories[$pathId][$return]);
                            }
                        }
                    }
                }
            }
        }
        usort($categorieList, ["\Wyomind\SimpleGoogleShopping\Helper\Attributes", "cmpArray"]);
        $categorieList = array_filter($categorieList);
        $newCatList = [];
        foreach ($categorieList as $path => $list) {
            if (count($list) != 0) {
                $newCatList[] = $list;
            }
        }

        $categorieList = $newCatList;
        $item->setCategoriesArray($categorieList);

        if ($nth < 0) {
            $nth = count($categorieList) + $nth;
        } else {
            $nth -= 1;
        }
        if (isset($categorieList[$nth])) {
            $category = $categorieList[$nth];
        } else {
            $category = [];
        }

        if ($from > 0) {
            $from -= 1;
        }

        return implode($separator, array_slice($category, $from, $length));
    }

    /**
     * {category_mapping} attribute processing
     * @param \Wyomind\Simplegoogleshopping\Model\Feeds $model
     * @param array $options
     * @param \Magento\Catalog\Model\Product $product
     * @param string $reference
     * @return string mapped category
     */
    public function categoryMapping(
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
        $num = (isset($options["index"])) ? $options["index"] : 0;
        $value = "";
        $n = 0;
        foreach ($item->getCategoryIds() as $key => $category) {
            if (isset($model->categoriesMapping[$category])) {
                if ($n == $num) {
                    $value .= $model->categoriesMapping[$category];
                    break;
                }
                $n++;
            }
        }
        return $value;
    }
}
