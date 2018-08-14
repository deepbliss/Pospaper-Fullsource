<?php

/**
 * Copyright © 2015 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Helper;

/**
 * Attributes management
 */
class AttributesPrices extends \Magento\Framework\App\Helper\AbstractHelper
{

    protected $_coreDate = null;
    protected $_localeDate = null;
    protected $_customerSession = null;
    protected $_ruleFactory = null;
    protected $_salesRuleCollectionFactory = null;
    protected $_coreHelper = null;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $coreDate
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $coreDate,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\CatalogRule\Model\ResourceModel\RuleFactory $ruleFactory,
        \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory $salesRuleCollectionFactory,
        \Wyomind\Core\Helper\data $coreHelper
    )
    {

        $this->_coreDate = $coreDate;
        $this->_localeDate = $localeDate;
        $this->_customerSession = $customerSession;
        $this->_ruleFactory = $ruleFactory;
        $this->_salesRuleCollectionFactory = $salesRuleCollectionFactory;
        $this->_coreHelper = $coreHelper;
        parent::__construct($context);
    }

    public function validateCond(
        $conds,
        $item
    )
    {

        if ($item->getProductId() == "") {
            $item->setProductId($item->getId());
        }

        $all = $conds->getAggregator() === 'all';
        $true = (bool)$conds->getValue();
        $found = $all;
        foreach ($conds->getConditions() as $cond) {
            $validated = $cond->validate($item);
            if ($all && !$validated || !$all && $validated) {
                $found = $validated;
                break;
            }
        }

        if ($found && $true) {
            return true;
        } elseif (!$found && !$true) {
            return true;
        }
        return false;
    }

    /**
     *
     * @param type $model
     * @param type $options
     * @param type $product
     * @param type $reference
     * @return string
     */
    public function promotionId(
        $model,
        $options,
        $product,
        $reference
    )
    {

        if (!$this->_coreHelper->moduleIsEnabled("Wyomind_GoogleMerchantPromotions")) {
            return "";
        }

        $item = $model->checkReference($reference, $product);
        if ($item == null) {
            return "";
        }

        $notProceeded = [
            "Magento\SalesRule\Model\Rule\Condition\Product\Subselect",
            "Magento\SalesRule\Model\Rule\Condition\Address"
        ];

        $rules = $this->_salesRuleCollectionFactory->create();
        $rules->addFieldToFilter("transferable_to_google_merchant", 1);
        foreach ($rules as $rule) {
            if ($rule->getIsActive()) {
                $conditions = $rule->getConditions();
                $all = $conditions->getAggregator() === 'all';
                $true = (bool)$conditions->getValue();
                $rtnCond = ($all) ? true : false;
                $rtnCond = (!count($conditions->getConditions())) ? true : $rtnCond;


                foreach ($conditions->getConditions() as $cond) {
                    if (!in_array($cond->getType(), $notProceeded)) {
                        if ($cond->getType() == "Magento\SalesRule\Model\Rule\Condition\Product\Found") {
                            $validated = $this->validateCond($cond, $item);
                        } else {
                            $validated = $cond->validate($item);
                        }
                        if ($all && $validated !== $true) {
                            $rtnCond = false;
                        } elseif (!$all && $validated === $true) {
                            $rtnCond = true;
                            break;
                        }
                    } else {
                        $rtnCond = false;
                    }
                }

                $actions = $rule->getActions();
                $all = $actions->getAggregator() === 'all';
                $true = (bool)$actions->getValue();
                $rtnAct = ($all) ? true : false;
                $rtnAct = (!count($actions->getConditions())) ? true : $rtnAct;

                foreach ($actions->getConditions() as $act) {
                    $validated = $act->validate($item);
                    if ($all && $validated !== $true) {
                        $rtnAct = false;
                    } elseif (!$all && $validated === $true) {
                        $rtnAct = true;
                        break;
                    }
                }
                if ($rtnAct && $rtnCond) {
                    return $rule->getData('rule_id');
                }
            }
        }

        return "";
    }

    /**
     * {g_sale_price} attribute processing
     * @param \Wyomind\Simplegoogleshopping\Model\Feeds $model
     * @param array $options
     * @param \Magento\Catalog\Model\Product $product
     * @param string $reference
     * @return string g:sale_price + g:sale_price_effective_date xml tags
     */
    public function price(
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

        $timestamp = $this->_localeDate->scopeDate($model->params['store_id']);
        $websiteId = $model->storeManager->getStore()->getWebsiteId();

        $custGrpId = $this->_customerSession->getCustomerGroupId();
        $rulePrice = $this->_ruleFactory->create()->getRulePrice($timestamp, $websiteId, $custGrpId, $item->getId());

        if ($rulePrice !== false) {
            $priceRules = sprintf('%.2f', round($rulePrice, 2));
        } else {
            $priceRules = $item->getPrice();
        }
        /* si la date de début et fixée mais pas la date de fin */
        if ($item->getSpecialFromDate() && !$item->getSpecialToDate()) {
            /* si la date de promo est valide */
            if ($item->getSpecialFromDate() <= $this->_coreDate->date("Y-m-d H:i:s")) {
                if ($item->getTypeID() == "bundle") {
                    if (($item->getPriceType() || (!$item->getPriceType() && $item->getSpecialPrice() < $item->getPrice())) && $item->getSpecialPrice() > 0) {
                        if ($item->getPriceType()) {
                            $price = $item->getPrice() * $item->getSpecialPrice() / 100;
                        } else {
                            $price = $item->getSpecialPrice();
                        }
                    } else {
                        $price = $item->getPrice();
                    }
                } else { /* si le prix special existe */
                    $price = ($item->getSpecialPrice() && $item->getSpecialPrice() < $item->getPrice()) ? $item->getSpecialPrice() : $priceRules;
                }
            } else { /* sinon on affiche le prix normal */
                if ($item->getTypeID() == "bundle") {
                    $price = $item->getPrice();
                } else {
                    $price = $priceRules;
                }
            }
        } elseif ($item->getSpecialFromDate() && $item->getSpecialToDate()) { /* si la date de début et fixée ainsi que la date de fin */
            /* si la date de promo est valide */
            if ($item->getSpecialFromDate() <= $this->_coreDate->date("Y-m-d H:i:s") && $this->_coreDate->date("Y-m-d H:i:s") < $item->getSpecialToDate()) {
                if ($item->getTypeID() == "bundle") {
                    if (($item->getPriceType() || (!$item->getPriceType() && $item->getSpecialPrice() < $item->getPrice())) && $item->getSpecialPrice() > 0) {
                        if ($item->getPriceType()) {
                            $price = $item->getPrice() * $item->getSpecialPrice() / 100;
                        } else {
                            $price = $item->getSpecialPrice();
                        }
                    } else {
                        $price = $item->getPrice();
                    }
                } else { /* si le prix special existe */
                    $price = ($item->getSpecialPrice() && $item->getSpecialPrice() < $item->getPrice()) ? $item->getSpecialPrice() : $priceRules;
                }
            } else { /* sinon on affiche le prix normal */
                if ($item->getTypeID() == "bundle") {
                    $price = $item->getPrice();
                } else {
                    $price = $priceRules;
                }
            }
        } else {
            if ($item->getTypeID() == "bundle") {
                if (($item->getPriceType() || (!$item->getPriceType() && $item->getSpecialPrice() < $item->getPrice())) && $item->getSpecialPrice() > 0) {
                    if ($item->getPriceType()) {
                        $price = number_format($item->getPrice() * $item->getSpecialPrice() / 100, 2, ".", "");
                    } else {
                        $price = $item->getSpecialPrice();
                    }
                } else {
                    $price = $item->getPrice();
                }
            } else { /* si le prix special existe */
                $price = ($item->getSpecialPrice() && $item->getSpecialPrice() < $item->getPrice()) ? $item->getSpecialPrice() : $priceRules;
            }
        }

        if ($priceRules !== false) {
            if ($priceRules < $price) {
                $value = $priceRules;
            } else {
                $value = $price;
            }
        } else {
            $value = $price;
        }
        $value = $this->applyTaxThenCurrency($model, $item->getTaxClassId(), number_format($value, 2, ".", ""), $options, $reference);
        return $value;
    }

    public function salePriceEffectiveDate(
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
        $offsetHours = $this->_coreDate->getGmtOffset("hours");

        if ($offsetHours > 0) {
            $sign = "+";
            $offset = str_pad(abs(floor($offsetHours)), 2, 0, STR_PAD_LEFT) . '' . str_pad((abs($offsetHours) - floor(abs($offsetHours))) * 60, 2, 0, STR_PAD_LEFT);
        } else {
            $sign = "-";
            $offset = str_pad(abs(floor($offsetHours)), 2, 0, STR_PAD_LEFT) . '' . str_pad((abs($offsetHours) - floor(abs($offsetHours))) * 60, 2, 0, STR_PAD_LEFT);
        }
        $from = substr(str_replace(' ', 'T', $item->getSpecialFromDate()), 0, -3);
        $to = substr(str_replace(' ', 'T', $item->getSpecialToDate()), 0, -3);

        $value = "";
        if ($to) {
            $value .= $from . $sign . $offset . "/" . $to . $sign . $offset;
        }
        return $value;
    }

    /**
     * {min_price} attribute processing
     * @param \Wyomind\Simplegoogleshopping\Model\Feeds $model
     * @param array $options
     * @param \Magento\Catalog\Model\Product $product
     * @param string $reference
     * @return float the min price for bundle / configurable products
     */
    public function minPrice(
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
        $price = $item->getMinPrice();
        $value = $this->applyTaxThenCurrency($model, $item->getTaxClassId(), $price, $options, $reference);
        return $value;
    }

    /**
     * {max_price} attribute processing
     * @param \Wyomind\Simplegoogleshopping\Model\Feeds $model
     * @param array $options
     * @param \Magento\Catalog\Model\Product $product
     * @param string $reference
     * @return float the max price for bundle / configurable products
     */
    public function maxPrice(
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
        $price = $item->getMaxPrice();
        $value = $this->applyTaxThenCurrency($model, $item->getTaxClassId(), $price, $options, $reference);
        return $value;
    }

    /**
     * {special_price} attribute processing
     * @param \Wyomind\Simplegoogleshopping\Model\Feeds $model
     * @param array $options
     * @param \Magento\Catalog\Model\Product $product
     * @param string $reference
     * @return float the special of a product if it exists, the normal price else
     */
    public function specialPrice(
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
        $price = null;
        if ($item->getSpecialFromDate() && !$item->getSpecialToDate()) {
            if ($item->getSpecialFromDate() <= $this->_coreDate->date("Y-m-d H:i:s")) {
                if ($item->getTypeId() == 'bundle') {
                    if ($item->getPriceType()) {
                        $price = number_format($item->getPrice() * $item->getSpecialPrice() / 100, 2, ".", "");
                    } else {
                        $price = $item->getSpecialPrice();
                    }
                } else {
                    $price = $item->getSpecial_price();
                }
            }
        } elseif ($item->getSpecialFromDate() && $item->getSpecialToDate()) {
            if ($item->getSpecialFromDate() <= $this->_coreDate->date("Y-m-d H:i:s") && $this->_coreDate->date("Y-m-d H:i:s") < $item->getSpecialToDate()) {
                if ($item->getTypeId() == 'bundle') {
                    if ($item->getPriceType()) {
                        $price = number_format($item->getPrice() * $item->getSpecialPrice() / 100, 2, ".", "");
                    } else {
                        $price = $item->getSpecialPrice();
                    }
                } else {
                    $price = $item->getSpecial_price();
                }
            }
        } else {
            if ($item->getTypeId() == 'bundle') {
                if ($item->getPriceType()) {
                    $price = number_format($item->getPrice() * $item->getSpecialPrice() / 100, 2, ".", "");
                } else {
                    $price = $item->getSpecialPrice();
                }
            } else {
                $price = $item->getSpecial_price();
            }
        }

        if ($price > 0) {
            $value = $this->applyTaxThenCurrency($model, $item->getTaxClassId(), $price, $options, $reference);
        } else {
            $value = "";
        }
        return $value;
    }

    /**
     * {price_rules} attribute processing
     * @param \Wyomind\Simplegoogleshopping\Model\Feeds $model
     * @param array $options
     * @param \Magento\Catalog\Model\Product $product
     * @param string $reference
     * @return the price defined by catalog price rules if existing, special price else, normal price else
     */
    public function priceRules(
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
        $timestamp = $this->_localeDate->scopeDate($model->params['store_id']);
        $websiteId = $model->storeManager->getStore()->getWebsiteId();

        $custGrpId = $this->_customerSession->getCustomerGroupId();
        $rulePrice = $this->_ruleFactory->create()->getRulePrice($timestamp, $websiteId, $custGrpId, $item->getId());

        if ($rulePrice !== false) {
            $priceRules = sprintf('%.2f', round($rulePrice, 2));
        } else {
            $priceRules = "";
        }

        if ($priceRules != "") {
            $value = $this->applyTaxThenCurrency($model, $item->getTaxClassId(), $priceRules, $options, $reference);
        } else {
            $value = "";
        }
        return $value;
    }

    public function hasSalePrice(
        $model,
        $options,
        $product,
        $reference
    )
    {

        return $this->salePrice($model, $options, $product, $reference) != "";
    }

    /**
     * {is_special_price} attribute processing
     * @param \Wyomind\Simplegoogleshopping\Model\Feeds $model
     * @param array $options
     * @param \Magento\Catalog\Model\Product $product
     * @param string $reference
     * @return 0 if there is a special price, 0 else
     */
    public function hasSpecialPrice(
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
        $true = (!isset($options["yes"])) ? 1 : $options["yes"];
        $false = (!isset($options["no"])) ? 0 : $options["no"];

        if ($item->getSpecialFromDate() && !$item->getSpecialToDate()) {
            if ($item->getSpecialFromDate() <= $this->_coreDate->date("Y-m-d H:i:s")) {
                if ($item->getTypeID() == "bundle") {
                    $value = (($item->getPriceType() || (!$item->getPriceType() && $item->getSpecialPrice() < $item->getPrice())) && $item->getSpecialPrice() > 0) ? $true : $false;
                } else {
                    $value = ($item->getSpecialPrice() && $item->getSpecialPrice() < $item->getPrice()) ? $true : $false;
                }
            } else {
                if ($item->getTypeID() == "bundle") {
                    $value = $false;
                } else {
                    $value = $false;
                }
            }
        } elseif ($item->getSpecialFromDate() && $item->getSpecialToDate()) {
            if ($item->getSpecialFromDate() <= $this->_coreDate->date("Y-m-d H:i:s") && $this->_coreDate->date("Y-m-d H:i:s") < $item->getSpecialToDate()) {
                if ($item->getTypeID() == "bundle") {
                    $value = (($item->getPriceType() || (!$item->getPriceType() && $item->getSpecialPrice() < $item->getPrice())) && $item->getSpecialPrice() > 0) ? $true : $false;
                } else {
                    $value = ($item->getSpecialPrice() && $item->getSpecialPrice() < $item->getPrice()) ? $true : $false;
                }
            } else {
                if ($item->getTypeID() == "bundle") {
                    $value = $false;
                } else {
                    $value = $false;
                }
            }
        } else {
            if ($item->getTypeID() == "bundle") {
                $value = (($item->getPriceType() || (!$item->getPriceType() && $item->getSpecialPrice() < $item->getPrice())) && $item->getSpecialPrice() > 0) ? $true : $false;
            } else {
                $value = ($item->getSpecialPrice() && $item->getSpecialPrice() < $item->getPrice()) ? $true : $false;
            }
        }
        return $value;
    }

    /**
     * {price} attribute processing
     * @param \Wyomind\Simplegoogleshopping\Model\Feeds $model
     * @param array $options
     * @param \Magento\Catalog\Model\Product $product
     * @param string $reference
     * @return float the price of the product
     */
    public function salePrice(
        $model,
        $options,
        $product,
        $reference
    )
    {

        $priceRules = $this->priceRules($model, $options, $product, $reference);
        $specialPrice = $this->specialPrice($model, $options, $product, $reference);
        if ($priceRules != "" && $specialPrice != "") {
            if ($priceRules < $specialPrice) {
                return $priceRules;
            } else {
                return $specialPrice;
            }
        } elseif ($priceRules != "") {
            return $priceRules;
        } elseif ($specialPrice != "") {
            return $specialPrice;
        } else {
            return "";
        }
    }

    /**
     * {normal_price} attribute processing
     * @param \Wyomind\Simplegoogleshopping\Model\Feeds $model
     * @param array $options
     * @param \Magento\Catalog\Model\Product $product
     * @param string $reference
     * @return float the normal price of the product
     */
    public function normalPrice(
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
        $price = $item->getPrice();
        $value = $this->applyTaxThenCurrency($model, $item->getTaxClassId(), $price, $options, $reference);
        return $value;
    }

    /**
     * {final_price} attribute processing
     * @param \Wyomind\Simplegoogleshopping\Model\Feeds $model
     * @param array $options
     * @param \Magento\Catalog\Model\Product $product
     * @param string $reference
     * @return string formatted version of the final price
     */
    public function finalPrice(
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
        $price = $item->getFinalPrice();
        $value = $this->applyTaxThenCurrency($model, $item->getTaxClassId(), $price, $options, $reference);
        return $value;
    }

    /**
     * Apply vat rate and currency to a price
     * @param int $taxClassId the tax class id
     * @param float $price original price
     * @param array $options attribute options
     * @param string $reference parent reference
     * @return float the final price
     */
    public function applyTaxThenCurrency(
        $model,
        $taxClassId,
        $price,
        $options,
        $reference
    )
    {

        unset($reference);
        $vat = (!isset($options['vat_rate'])) ? false : $options['vat_rate'];
        $valueTax = $this->applyTax($model, $price, $model->priceIncludesTax, $taxClassId, $vat);
        $currency = (!isset($options['currency'])) ? $model->defaultCurrency : $options['currency'];
        $valueCur = $this->applyCurrencyRate($model, $valueTax, $currency);
        $value = number_format($valueCur, 2, '.', '');
        return $value;
    }

    /**
     * Apply a currency rate
     * @param float $price
     * @param string $currency
     * @return float
     */
    public function applyCurrencyRate(
        $model,
        $price,
        $currency
    )
    {

        $currencies = $model->listOfCurrencies;
        if (isset($currencies[$currency])) {
            return $price * $currencies[$currency];
        } else {
            return $price;
        }
    }

    /**
     * Apply a vat tax rate
     * @param float $priceOrig the original price
     * @param boolean $priceIncludeTax
     * @param int $taxClassId
     * @param float $vat apply VAT ?
     * @return float
     */
    public function applyTax(
        $model,
        $priceOrig,
        $priceIncludeTax,
        $taxClassId,
        $vat = false
    )
    {

        $rates = $model->taxRates;
        $price = number_format($priceOrig, 2, '.', '');

        if ($vat === false) { // $vat=false -> automatique
            if (!$priceIncludeTax && isset($rates[$taxClassId])) { /* si la TVA est multiple on renvoie le prix HT */
                if (count($rates[$taxClassId]) > 1) {
                    return $price;
                } else {/* si la TVA est unique on calcul le prix TTC */
                    return $price * ($rates[$taxClassId][0]['rate'] / 100 + 1);
                }
            } else {
                return $price;
            }
        } elseif ($vat === "0") { // $vat=='0' -> exclure la TVA
            if ($priceIncludeTax && isset($rates[$taxClassId])) { // cas 1 : prix inclus la TVA donc extraire
                if (count($rates[$taxClassId]) > 1) { /* si la TVA est multiple on renvoie le prix HT */
                    return $price;
                } else { /* si la TVA est unique on retire  la TVA au prix */
                    return 100 * $price / (100 + ($rates[$taxClassId][0]['rate']));
                }
            } else { // cas 2 : prix exclus la TVA
                return $price;
            }
        } else { // $vat==true -> forcer une TVA
            if (is_numeric($vat)) { // $vat is_numeric
                if ($taxClassId != 0) { /* si on force le calcul de la TVA sur un produit taxé */
                    return $price * ($vat / 100 + 1);
                } elseif ($taxClassId == 0) { /* si on force le calcul de la TVA sur un produit non taxé; */
                    return $price;
                }
            } else { // $vat is_string
                $vat = explode('/', $vat);
                $rateToApply = 0;
                $rateToRemove = false;

                if (substr($vat[0], 0, 1) == "-") {
                    $vat[0] = substr($vat[0], 1);
                    $rateToRemove = true;
                }
                if (isset($rates[$taxClassId])) {
                    foreach ($rates[$taxClassId] as $rate) {
                        if ($rate['country'] == $vat[0]) {
                            if (!isset($vat[1]) || $rate['code'] == $vat[1]) {
                                $rateToApply = $rate['rate'];
                                break;
                            }
                        }
                    }
                    if (!$rateToRemove) {
                        return $price * ($rateToApply / 100 + 1);
                    } else {
                        return 100 * $price / (100 + ($rateToApply));
                    }
                } else {
                    return $price;
                }
            }
        }
    }
}
