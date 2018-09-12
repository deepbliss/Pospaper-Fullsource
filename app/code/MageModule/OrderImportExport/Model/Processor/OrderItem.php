<?php
/**
 * Copyright (c) 2018 MageModule, LLC: All rights reserved
 *
 * LICENSE: This source file is subject to our standard End User License
 * Agreeement (EULA) that is available through the world-wide-web at the
 * following URI: http://www.magemodule.com/magento2-ext-license.html.
 *
 * If you did not receive a copy of the EULA and are unable to obtain it through
 * the web, please send a note to admin@magemodule.com so that we can mail
 * you a copy immediately.
 *
 * @author       MageModule, LLC admin@magemodule.com
 * @copyright   2018 MageModule, LLC
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\OrderImportExport\Model\Processor;

use MageModule\OrderImportExport\Api\ImporterInterface;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\Product\Type\AbstractType;
use Magento\Sales\Api\Data\OrderItemInterface;

class OrderItem extends \MageModule\OrderImportExport\Model\Processor\AbstractProcessor implements
    \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
{
    /**
     * @var \Magento\Sales\Api\Data\OrderItemInterfaceFactory
     */
    private $orderItemFactory;

    /**
     * @var array
     */
    private $skuEntityIdPairs;

    /**
     * @var \Magento\Tax\Model\Calculation
     */
    private $calculation;

    /**
     * OrderItem constructor.
     *
     * @param \MageModule\Core\Helper\Catalog\Product           $helper
     * @param \Magento\Sales\Api\Data\OrderItemInterfaceFactory $orderItemFactory
     * @param \Magento\Tax\Model\Calculation                    $calculation
     * @param array                                             $excludedFields
     */
    public function __construct(
        \MageModule\Core\Helper\Catalog\Product $helper,
        \Magento\Sales\Api\Data\OrderItemInterfaceFactory $orderItemFactory,
        \Magento\Tax\Model\Calculation $calculation,
        $excludedFields = []
    ) {
        parent::__construct($excludedFields);
        $this->orderItemFactory = $orderItemFactory;
        $this->skuEntityIdPairs = $helper->getSkuEntityIdPairs();
        $this->calculation      = $calculation;
    }

    /**
     * @param array                                                             $data
     * @param \Magento\Sales\Api\Data\OrderInterface|\Magento\Sales\Model\Order $order
     *
     * @return $this
     */
    public function process(array $data, \Magento\Sales\Api\Data\OrderInterface $order)
    {
        $items = [];

        foreach ($data[ImporterInterface::KEY_PRODUCTS_ORDERED] as $key => $product) {
            /** @var \Magento\Sales\Api\Data\OrderItemInterface|\Magento\Sales\Model\Order\Item $item */
            $item = $this->createItem($product);
            $item->setOrder($order);
            $item->setStoreId($order->getStoreId());
            $item->setData('key', $key);
            $items[] = $item;

            /** @var \Magento\Sales\Api\Data\OrderItemInterface|\Magento\Sales\Model\Order\Item $childItem */
            foreach ($item->getChildrenItems() as $childKey => $childItem) {
                $childItem->setOrder($order);
                $childItem->setStoreId($order->getStoreId());
                $childItem->setData('parent_key', $key);
                $childItem->setData('key', $childKey);
                $items[] = $childItem;
            }
        }

        $order->setItems($items);

        return $this;
    }

    /**
     * @param array $data
     *
     * @return \Magento\Sales\Api\Data\OrderItemInterface|\Magento\Sales\Model\Order\Item
     */
    private function createItem(array $data)
    {
        /** @var \Magento\Sales\Api\Data\OrderItemInterface|\Magento\Sales\Model\Order\Item $item */
        $item = $this->orderItemFactory->create();
        $item->addData($data);

        $options = [];

        if (isset($data[ImporterInterface::KEY_PRODUCT_BUNDLE_ITEMS]) &&
            $item->getProductType() === Type::TYPE_BUNDLE
        ) {
            $bundleOptions = [];
            foreach ($data[ImporterInterface::KEY_PRODUCT_BUNDLE_ITEMS] as $childData) {
                /** @var \Magento\Sales\Api\Data\OrderItemInterface|\Magento\Sales\Model\Order\Item $childItem */
                $childItem = $this->createItem($childData);
                $childItem->setParentItem($item);

                $bundleOptions[] = [
                    'label' => $childItem->getName(),
                    'value' => [
                        [
                            'title' => $childItem->getName(),
                            'qty'   => $childItem->getQtyOrdered(),
                            'price' => $childItem->getPrice()
                        ]
                    ]
                ];

                end($bundleOptions);
                $key = key($bundleOptions);

                $options['info_buyRequest']['bundle_option'][]     = $key;
                $options['info_buyRequest']['bundle_option_qty'][] = $childItem->getQtyOrdered();
            }

            $options['product_calculations'] = AbstractType::CALCULATE_CHILD;
            $options['bundle_options']       = $bundleOptions;
        }

        $this->prepareData($item);

        $options['options']                    = $item->getData(ImporterInterface::KEY_PRODUCT_OPTIONS);
        $options['info_buyRequest']['options'] = $item->getData(ImporterInterface::KEY_PRODUCT_OPTIONS);

        $item->setProductOptions($options);

        return $item;
    }

    /**
     * @param \Magento\Sales\Api\Data\OrderItemInterface|\Magento\Sales\Model\Order\Item $item
     */
    private function prepareData(\Magento\Sales\Api\Data\OrderItemInterface $item)
    {
        $this->removeExcludedFields($item);

        foreach ($item->getData() as $key => $value) {
            $item->setOrigData($key, $value);
        }

        if (isset($this->skuEntityIdPairs[$item->getSku()])) {
            $item->setProductId($this->skuEntityIdPairs[$item->getSku()]);
        }

        if (!$item->getQtyOrdered()) {
            $item->setQtyOrdered(1);
        }

        $item->setQtyOrdered((float)$item->getQtyOrdered());
        $item->setQtyBackordered((float)$item->getQtyBackordered());
        $item->setIsVirtual((int)$item->getIsVirtual());
        $item->setIsQtyDecimal((int)$item->getIsQtyDecimal());
        $item->setFreeShipping((int)$item->getFreeShipping());
        $item->setRowWeight($item->getRowWeight());

        /** regardless of config settings, price NEVER includes discount or tax */
        if ($item->getBasePrice() === null) {
            $item->setBasePrice($item->getPrice());
        }

        $baseAndStoreSame = $item->getPrice() === $item->getBasePrice();

        if ($item->getBaseTaxAmount() === null) {
            if ($baseAndStoreSame) {
                $item->setBaseTaxAmount($item->getTaxAmount());
            } else {
                $amount = $this->calculation->calcTaxAmount(
                    $item->getBasePrice(),
                    $item->getTaxPercent(),
                    false,
                    true
                );
                $item->setBaseTaxAmount($amount);
            }
        }

        /** regardless of config settings, price_incl_tax ALWAYS includes tax calculated before discount */
        if ($item->getPriceInclTax() === null) {
            $amount = $this->calculation->calcTaxAmount(
                $item->getPrice(),
                $item->getTaxPercent(),
                false,
                true
            );
            $item->setPriceInclTax($item->getPrice() + $amount);
        }

        if ($item->getBasePriceInclTax() === null) {
            if ($baseAndStoreSame) {
                $item->setBasePriceInclTax($item->getPriceInclTax());
            } else {
                $amount = $this->calculation->calcTaxAmount(
                    $item->getBasePrice(),
                    $item->getTaxPercent(),
                    false,
                    true
                );
                $item->setBasePriceInclTax($amount + $item->getBasePrice());
            }
        }

        /** regardless of config settings, original_price NEVER includes discount or tax */
        if ($item->getOriginalPrice() === null) {
            $item->setOriginalPrice($item->getPrice());
        }

        if ($item->getBaseOriginalPrice() === null) {
            $item->setBaseOriginalPrice($item->getBasePrice());
        }

        /** we don't calculate discount amount because it can be applied before tax, after tax, etc */
        $item->setBaseDiscountAmount(
            (float)($item->getBaseDiscountAmount() ? $item->getBaseDiscountAmount() : $item->getDiscountAmount())
        );

        /** regardless of config settings, row total is ALWAYS price * qty. do not include discount in this number */
        if ($item->getRowTotal() === null) {
            $item->setRowTotal($item->getPrice() * $item->getQtyOrdered());
        }

        if ($item->getBaseRowTotal() === null) {
            if ($baseAndStoreSame) {
                $item->setBaseRowTotal($item->getRowTotal());
            } else {
                $item->setBaseRowTotal($item->getBasePrice() * $item->getQtyOrdered());
            }
        }

        /** regardless of config settings, row_total_inc_tax total is ALWAYS price_incl_tax * qty. do not include discount in this number */
        if ($item->getRowTotalInclTax() === null) {
            $amount = $this->calculation->calcTaxAmount(
                $item->getRowTotal(),
                $item->getTaxPercent(),
                false,
                true
            );
            $item->setRowTotalInclTax($item->getRowTotal() + $amount);
        }

        if ($item->getBaseRowTotalInclTax() === null) {
            if ($baseAndStoreSame) {
                $item->setBaseRowTotalInclTax($item->getRowTotalInclTax());
            } else {
                $amount = $this->calculation->calcTaxAmount(
                    $item->getBaseRowTotal(),
                    $item->getTaxPercent(),
                    false,
                    true
                );
                $item->setBaseRowTotalInclTax($item->getBaseRowTotal() + $amount);
            }
        }

        if ($item->getBaseWeeeTaxAppliedAmount() === null && $baseAndStoreSame) {
            $item->setBaseWeeeTaxAppliedAmount($item->getWeeeTaxAppliedAmount());
        }

        if ($item->getWeeeTaxAppliedRowAmount() === null) {
            $item->setWeeeTaxAppliedRowAmount($item->getWeeeTaxAppliedAmount() * $item->getQtyOrdered());
        }

        if ($item->getBaseWeeeTaxAppliedRowAmnt() === null) {
            if ($baseAndStoreSame) {
                $item->setBaseWeeeTaxAppliedRowAmnt($item->getWeeeTaxAppliedRowAmount());
            } else {
                $item->setBaseWeeeTaxAppliedRowAmnt($item->getBaseWeeeTaxAppliedAmount() * $item->getQtyOrdered());
            }
        }

        if ($item->getProductType() === \Magento\Catalog\Model\Product\Type::TYPE_BUNDLE) {
            $keys = [
                OrderItemInterface::PRICE_INCL_TAX      => [],
                OrderItemInterface::BASE_PRICE_INCL_TAX => []
            ];

            foreach ($item->getChildrenItems() as $childItem) {
                foreach ($keys as $key => $value) {
                    $keys[$key][] = $childItem->getQtyOrdered() * $this->calculation->round($childItem->getData($key));
                }
            }

            foreach ($keys as $key => &$array) {
                $item->setData($key, array_sum($array));
            }

            $keys = [
                OrderItemInterface::ROW_TOTAL_INCL_TAX      => [],
                OrderItemInterface::BASE_ROW_TOTAL_INCL_TAX => []
            ];

            foreach ($item->getChildrenItems() as $childItem) {
                foreach ($keys as $key => $value) {
                    $keys[$key][] = (float)$childItem->getData($key);
                }
            }

            foreach ($keys as $key => &$array) {
                $item->setData($key, array_sum($array));
            }
        }

        if (!is_array($item->getData(ImporterInterface::KEY_PRODUCT_OPTIONS))) {
            $item->setData(ImporterInterface::KEY_PRODUCT_OPTIONS, []);
        }

        $options = $item->getData(ImporterInterface::KEY_PRODUCT_OPTIONS);
        if ($options) {
            $finalOptions = [];
            foreach ($options as &$option) {
                $label          = isset($option['label']) ? $option['label'] : null;
                $value          = isset($option['value']) ? $option['value'] : null;
                $finalOptions[] = [
                    'label'        => $label,
                    'value'        => $value,
                    'print_value'  => $value,
                    'option_id'    => '',
                    'option_type'  => 'field',
                    'option_value' => $value,
                    'custom_view'  => false,
                ];
            }

            $item->setData(ImporterInterface::KEY_PRODUCT_OPTIONS, $finalOptions);
        }
    }
}
