<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Model\ResourceModel;

/**
 */
class TaxClass extends \Magento\Tax\Model\ResourceModel\TaxClass
{

    public function getTaxRates()
    {
        $connection = $this->getConnection();
        $sql = $connection->select()
            ->from($this->getMainTable(), ['class_id', 'tcr.tax_country_id', 'tcr.rate', 'dcr.code'])
            ->joinLeft(["tc" => $this->getTable('tax_calculation')], "tc.product_tax_class_id = class_id", "tc.tax_calculation_rate_id")
            ->joinLeft(["tcr" => $this->getTable('tax_calculation_rate')], "tcr.tax_calculation_rate_id = tc.tax_calculation_rate_id", ["tcr.rate", "tax_country_id", "tax_region_id"])
            ->joinLeft(["dcr" => $this->getTable('directory_country_region')], "dcr.region_id=tcr.tax_region_id", "code")
            ->joinInner(["cg" => $this->getTable('customer_group')], "cg.tax_class_id=tc.customer_tax_class_id AND cg.customer_group_code='NOT LOGGED IN'")
            ->order('class_id DESC')
            ->order('tc.tax_calculation_rate_id ASC');

        $result = $connection->fetchAll($sql);
        if ($result !== false) {
            $rates = [];
            $temp = "";
            $x = 0;
            foreach ($result as $taxRate) {
                if ($temp != $taxRate["class_id"]) {
                    $x = 0;
                } else {
                    $x++;
                }
                $temp = $taxRate["class_id"];
                $rates[$taxRate["class_id"]][$x]["rate"] = $taxRate["rate"];
                $rates[$taxRate["class_id"]][$x]["code"] = $taxRate["code"];
                $rates[$taxRate["class_id"]][$x]["country"] = $taxRate["tax_country_id"];
            }
            return $rates;
        } else {
            return null;
        }
    }
}
