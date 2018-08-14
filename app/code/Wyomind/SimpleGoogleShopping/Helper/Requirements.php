<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Helper;

/**
 * Google Shopping requirements
 */
class Requirements extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @return array
     */
    public function getRequirements()
    {
        return [
            /* Basic Product Information */
            [
                "label" => "id",
                "tag" => "g_id",
                "required" => true,
                "occurrence" => 1,
                "length" => 50,
                "type" => "Alphanumeric",
            ],
            [
                "label" => "title",
                "tag" => "title",
                "required" => true,
                "occurrence" => 1,
                "length" => 150,
                "type" => "Text",
            ],
            [
                "label" => "description",
                "tag" => "g_description",
                "required" => true,
                "occurrence" => 1,
                "length" => 5000,
                "type" => "Text",
            ],
            [
                "label" => "google_product_category",
                "tag" => "g_google_product_category",
                "required" => true,
                "occurrence" => 1,
                "type" => "GoogleProductCategory",
            ],
            [
                "label" => "product_type",
                "tag" => "g_product_type",
                "required" => false,
                "recommended" => true,
                "occurrence" => 10,
                "length" => 750,
                "type" => "Text",
            ],
            [
                "label" => "link",
                "tag" => "link",
                "required" => true,
                "occurrence" => 1,
                "length" => 2000,
                "type" => "Url",
            ],
            [
                "label" => "mobile_link",
                "tag" => "g_mobile_link",
                "required" => false,
                "recommended" => false,
                "occurrence" => 1,
                "length" => 2000,
                "type" => "Url",
            ],
            [
                "label" => "image_link",
                "tag" => "g_image_link",
                "required" => true,
                "occurrence" => 1,
                "length" => 2000,
                "type" => "Url",
            ],
            [
                "label" => "additional_image_link",
                "tag" => "g_additional_image_link",
                "required" => false,
                "recommended" => false,
                "occurrence" => 9,
                "length" => 2000,
                "type" => "Url",
            ],
            [
                "label" => "condition",
                "tag" => "g_condition",
                "required" => true,
                "occurrence" => 1,
                "type" => "RegExp",
                "regexp" => "new|used|refurbished",
                "say" => "new,used or refurbished"
            ],
            /* Availability & Price */
            [
                "label" => "availability",
                "tag" => "g_availability",
                "required" => true,
                "occurrence" => 1,
                "type" => "RegExp",
                "regexp" => "in stock|out of stock|preorder",
                "say" => "in stock,out of stock or preorder"
            ],
            [
                "label" => "availability_date",
                "tag" => "g_availability_date",
                "required" => true,
                "occurrence" => 1,
                "type" => "RegExp",
                "regexp" => "20[0-9]{2}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}[-|+]{1}[0-9]{4}",
                "say" => "YYYY-MM-DDThh:mm+hh:mm",
                "depends" => ["availability" => ["eq" => "preorder"]]
            ],
            [
                "label" => "price",
                "tag" => "g_price",
                "required" => true,
                "occurrence" => 1,
                "type" => "Price"
            ],
            [
                "label" => "sale_price",
                "tag" => "g_sale_price",
                "required" => false,
                "occurrence" => 1,
                "type" => "Price",
            ],
            [
                "label" => "sale_price_effective_date",
                "tag" => "g_sale_price_effective_date",
                "required" => false,
                "occurrence" => 1,
                "type" => "RegExp",
                "say" => "YYYY-MM-DDThh:mm+hh:mm/YYYY-MM-DDThh:mm+hh:mm",
                "regexp" => "20[0-9]{2}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}[\-+]{1}[0-9]{4}\/20[0-9]{2}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}[\-+]{1}[0-9]{4}"
            ],
            /* Unique Product Identifiers */
            [
                "label" => "brand",
                "tag" => "g_brand",
                "required" => true,
                "recommended" => true,
                "occurrence" => 1,
                "length" => 70,
                "type" => "Text",
                "depends" => ["identifier_exists" => ["neq" => "FALSE"], "google_product_category" => ["like" => "Apparel & Accessories"]]
            ],
            [
                "label" => "brand",
                "tag" => "g_brand",
                "required" => false,
                "recommended" => true,
                "occurrence" => 1,
                "length" => 70,
                "type" => "Text",
                "depends" => ["identifier_exists" => ["neq" => "FALSE"]]
            ],
            [
                "label" => "gtin",
                "tag" => "g_gtin",
                "required" => false,
                "recommended" => true,
                "occurrence" => 1,
                "length" => 50,
                "type" => "RegExp",
                "regexp" => "[0-9]{8}|[0-9]{12}|[0-9]{13}",
                "say" => "8,12 or 13 digit number",
                "depends" => ["identifier_exists" => ["neq" => "FALSE"]]
            ],
            [
                "label" => "mpn",
                "tag" => "g_mpn",
                "required" => false,
                "recommended" => true,
                "occurrence" => 1,
                "length" => 70,
                "type" => "Alphanumeric",
                "depends" => ["identifier_exists" => ["neq" => "FALSE"]]
            ],
            [
                "label" => "identifier_exists",
                "tag" => "g_identifier_exists",
                "required" => false,
                "occurrence" => 1,
                "type" => "Boolean",
            ],
            /* Apparel Products */
            [
                "label" => "gender",
                "tag" => "g_gender",
                "required" => true,
                "occurrence" => 1,
                "type" => "RegExp",
                "regexp" => "male|female|unisex",
                "say" => "male,female or unisex",
                "depends" => ["google_product_category" => ["like" => "Apparel & Accessories"]]
            ],
            [
                "label" => "age_group",
                "tag" => "g_age_group",
                "required" => true,
                "occurrence" => 1,
                "type" => "RegExp",
                "regexp" => "newborn|infant|toddler|kids|adult",
                "say" => "newborn, infant, toddler, kids or adult",
                "depends" => ["google_product_category" => ["like" => "Apparel & Accessories"]]
            ],
            [
                "label" => "color",
                "tag" => "g_color",
                "required" => true,
                "occurrence" => 1,
                "length" => 100,
                "type" => "Text",
                "depends" => ["google_product_category" => ["like" => "Apparel & Accessories"]]
            ],
            [
                "label" => "size",
                "tag" => "g_size",
                "required" => true,
                "occurrence" => 1,
                "length" => 100,
                "type" => "Text",
                "depends" => ["google_product_category" => ["like" => "Apparel & Accessories > Clothing", "like" => "Apparel & Accessories > Shoes"]]
            ],
            [
                "label" => "size",
                "tag" => "g_size",
                "required" => false,
                "recommended" => true,
                "occurrence" => 1,
                "length" => 100,
                "type" => "Text",
                "depends" => ["google_product_category" => ["like" => "Apparel & Accessories"]]
            ],
            [
                "label" => "size_type",
                "tag" => "g_size_type",
                "required" => false,
                "recommended" => true,
                "occurrence" => 1,
                "type" => "RegExp",
                "regexp" => "regular|petite|plus|big and tall|maternity",
                "say" => "regular, petite, plus, big and tall or maternity",
                "depends" => ["google_product_category" => ["like" => "Apparel & Accessories > Clothing", "like" => "Apparel & Accessories > Shoes"]]
            ],
            [
                "label" => "size_system",
                "tag" => "g_size_system",
                "required" => false,
                "recommended" => true,
                "occurrence" => 1,
                "type" => "RegExp",
                "regexp" => "US|UK|EU|DE|FR|JP|CN|IT|BR|MEX|AU",
                "say" => "US, UK, EU, DE, FR, JP, CN, IT, BR, MEX or AU",
                "depends" => ["google_product_category" => ["like" => "Apparel & Accessories > Clothing", "like" => "Apparel & Accessories > Shoes"]]
            ],
            /* Product Variants */
            [
                "label" => "item_group_id",
                "tag" => "g_item_group_id",
                "required" => false,
                "occurrence" => 1,
                "length" => 50,
                "type" => "Alphanumeric",
            ],
            [
                "label" => "color",
                "tag" => "g_color",
                "required" => false,
                "occurrence" => 1,
                "length" => 100,
                "type" => "Text"
            ],
            [
                "label" => "size",
                "tag" => "g_size",
                "required" => false,
                "occurrence" => 1,
                "length" => 100,
                "type" => "Text"
            ],
            [
                "label" => "material",
                "tag" => "g_material",
                "required" => false,
                "recommended" => true,
                "occurrence" => 1,
                "length" => 200,
                "type" => "Text",
                "depends" => ["google_product_category" => ["like" => "Apparel & Accessories"]]
            ],
            [
                "label" => "pattern",
                "tag" => "g_pattern",
                "required" => false,
                "recommended" => true,
                "occurrence" => 1,
                "length" => 100,
                "type" => "Text",
                "depends" => ["google_product_category" => ["like" => "Apparel & Accessories"]]
            ],
            /* Tax & Shipping */
            [
                "label" => "shipping",
                "tag" => "g_shipping",
                "required" => false,
                "occurrence" => 1,
            ],
            [
                "label" => "shipping_weight",
                "tag" => "g_shipping_weight",
                "required" => false,
                "occurrence" => 1,
                "type" => "RegExp",
                "regexp" => "[0-9\.]+lb|oz|g|kg",
                "say" => "Number + weight type (lb, oz, g or kg)",
            ],
            [
                "label" => "shipping_label",
                "tag" => "g_shipping_label",
                "required" => false,
                "occurrence" => 1,
                "length" => 100,
                "type" => "Text"
            ],
            /* Product combinaison */
            [
                "label" => "multipack",
                "tag" => "g_multipack",
                "required" => false,
                "occurrence" => 1,
                "type" => "Integer"
            ],
            [
                "label" => "is_bundle",
                "tag" => "g_is_bundle",
                "required" => false,
                "occurrence" => 1,
                "type" => "Boolean",
            ],
            /* Adult products */
            [
                "label" => "adult",
                "tag" => "g_adult",
                "required" => false,
                "occurrence" => 1,
                "type" => "Boolean",
            ],
            /* Adwords campaign */
            [
                "label" => "adwords_redirect",
                "tag" => "g_adwords_redirect",
                "required" => false,
                "occurrence" => 1,
                "length" => 2000,
                "type" => "Url",
            ],
            [
                "label" => "custom_label_0",
                "tag" => "g_custom_label_0",
                "required" => false,
                "occurrence" => 1,
                "length" => 100,
                "type" => "Text"
            ],
            [
                "label" => "custom_label_1",
                "tag" => "g_custom_label_1",
                "required" => false,
                "occurrence" => 1,
                "length" => 100,
                "type" => "Text"
            ],
            [
                "label" => "custom_label_2",
                "tag" => "g_custom_label_2",
                "required" => false,
                "occurrence" => 1,
                "length" => 100,
                "type" => "Text"
            ],
            [
                "label" => "custom_label_3",
                "tag" => "g_custom_label_3",
                "required" => false,
                "occurrence" => 1,
                "length" => 100,
                "type" => "Text"
            ],
            [
                "label" => "custom_label_4",
                "tag" => "g_custom_label_4",
                "required" => false,
                "occurrence" => 1,
                "length" => 100,
                "type" => "Text"
            ],
            /* Additional Attributes */
            [
                "label" => "excluded_destination",
                "tag" => "g_excluded_destination",
                "required" => false,
                "occurrence" => 1,
                "type" => "RegExp",
                "regexp" => "shopping",
            ],
            [
                "label" => "expiration_date",
                "tag" => "g_expiration_date",
                "required" => false,
                "occurrence" => 1,
                "type" => "RegExp",
                "regexp" => "20[0-9]{2}-[0-9]{2}-[0-9]{2}",
                "say" => "YYYY-MM-dd",
            ],
            /* Unit prices */
            [
                "label" => "unit_pricing_measure",
                "tag" => "g_unit_pricing_measure",
                "required" => false,
                "occurrence" => 1,
                "type" => "RegExp",
                "regexp" => "[0-9\.]+mg|g|kg|ml|cl|l|cbm|cm|m|sqm",
                "say" => "Number + measure type (mg, g, kg, ml, cl, l, cbm, cm, m or sqm)",
            ],
            [
                "label" => "unit_pricing_base_measure",
                "tag" => "g_unit_pricing_base_measure",
                "required" => false,
                "occurrence" => 1,
                "type" => "RegExp",
                "regexp" => "[0-9\.]+mg|g|kg|ml|cl|l|cbm|cm|m|sqm",
                "say" => "Number + measure type (mg, g, kg, ml, cl, l, cbm, cm, m or sqm)",
            ],
            /* Energy Labels */
            [
                "label" => "energy_efficiency_class",
                "tag" => "g_energy_efficiency_class",
                "required" => false,
                "occurrence" => 1,
                "type" => "RegExp",
                "regexp" => "G|F|E|D|C|B|A|A+|A++|A+++",
                "say" => "G, F, E, D, C, B, A, A+, A++ or  A+++",
            ],
            /* Merchant Promotions Attribute */
            [
                "label" => "promotion_id",
                "tag" => "g_promotion_id",
                "required" => false,
                "occurrence" => 1,
                "length" => INF,
                "type" => "Text"
            ]
        ];
    }
}
