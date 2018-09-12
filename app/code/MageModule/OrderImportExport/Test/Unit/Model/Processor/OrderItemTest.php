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

namespace MageModule\OrderImportExport\Test\Unit\Model\Processor;

class OrderItemTest extends \MageModule\Core\Test\Unit\AbstractTestCase
{
    /**
     * @var \MageModule\OrderImportExport\Model\Processor\OrderItem
     */
    private $processor;

    /**
     * @var \Magento\Sales\Api\Data\OrderInterface|\Magento\Sales\Model\Order
     */
    private $order;

    public function setUp()
    {
        parent::setUp();

        $productHelperMock = $this->getMockBuilder(\MageModule\Core\Helper\Catalog\Product::class)
            ->disableOriginalConstructor()
            ->setMethods(['getEntityIdSkuPairs', 'getSkuEntityIdPairs'])
            ->getMock();

        $productHelperMock->method('getEntityIdSkuPairs')->willReturn([]);
        $productHelperMock->method('getSkuEntityIdPairs')->willReturn([]);

        $orderItemFactoryMock = $this->getMockBuilder('\Magento\Sales\Api\Data\OrderItemInterfaceFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $callback = function () {
            return $this->objectManager->getObject(\Magento\Sales\Model\Order\Item::class);
        };

        $orderItemFactoryMock->method('create')->willReturnCallback($callback);
        $orderItemFactoryMock->method('create')->willReturn(
            $this->objectManager->getObject(\Magento\Sales\Model\Order\Item::class)
        );

        $this->processor = $this->objectManager->getObject(
            \MageModule\OrderImportExport\Model\Processor\OrderItem::class,
            [
                'productHelper'    => $productHelperMock,
                'orderItemFactory' => $orderItemFactoryMock,
                'calculation'      => $this->objectManager->getObject(
                    \Magento\Tax\Model\Calculation::class,
                    ['priceCurrency' => $this->objectManager->getObject(\Magento\Directory\Model\PriceCurrency::class)]
                )
            ]
        );

        /** @var \Magento\Sales\Model\Order $order */
        $this->order = $this->objectManager->getObject(\Magento\Sales\Model\Order::class);
    }

    /**
     * This data provider includes orders that were placed with taxes, discounts
     * caclulated both before and after tax is applied and multiple currencies
     *
     * @return array
     */
    public function dataProviderCreateItems()
    {
        return [
            /** in this data set, discount is calculated on price including tax */
            [
                'input'    => [
                    'sku'                     => '24-MB01',
                    'name'                    => 'Joust Duffle Bag',
                    'product_type'            => 'simple',
                    'original_price'          => '34.0000',
                    'price'                   => '34.0000',
                    'tax_amount'              => '14.0300',
                    'tax_percent'             => '8.2500',
                    'weee_tax_applied_amount' => '15.0000',
                    'discount_amount'         => '36.8100',
                    'row_total'               => '170.0000',
                    'qty_ordered'             => '5.0000',
                    'qty_invoiced'            => '0.0000',
                    'qty_shipped'             => '0.0000',
                    'qty_backordered'         => '0',
                    'qty_refunded'            => '0.0000',
                    'qty_returned'            => '0',
                    'qty_canceled'            => '0.0000',
                    'options'                 => ''
                ],
                'expected' => [
                    'qty_ordered'                           => '5.0000',
                    'price'                                 => '34.0000',
                    'base_price'                            => '34.0000',
                    'original_price'                        => '34.0000',
                    'base_original_price'                   => '34.0000',
                    'tax_percent'                           => '8.2500',
                    'tax_amount'                            => '14.0300',
                    'base_tax_amount'                       => '14.0300',
                    'discount_percent'                      => '20.0000',
                    'discount_amount'                       => '36.8100',
                    'base_discount_amount'                  => '36.8100',
                    'row_total'                             => '170.0000',
                    'base_row_total'                        => '170.0000',
                    'tax_before_discount'                   => null,
                    'base_tax_before_discount'              => null,
                    'price_incl_tax'                        => '36.8100',
                    'base_price_incl_tax'                   => '36.8100',
                    'row_total_incl_tax'                    => '184.0300',
                    'base_row_total_incl_tax'               => '184.0300',
                    'discount_tax_compensation_amount'      => '0.0000',
                    'base_discount_tax_compensation_amount' => '0.0000',
                    'weee_tax_applied_amount'               => '15.0000',
                    'base_weee_tax_applied_amount'          => '15.0000',
                    'weee_tax_applied_row_amount'           => '75.0000',
                    'base_weee_tax_applied_row_amnt'        => '75.0000',
                    'weee_tax_disposition'                  => '0.0000',
                    'base_weee_tax_disposition'             => '0.0000',
                    'weee_tax_row_disposition'              => '0.0000',
                    'base_weee_tax_row_disposition'         => '0.0000',
                ]
            ],
            /** in this data set, tax is calculated after discount is deducted */
            [
                'input'    => [
                    'sku'                     => '24-MB01',
                    'name'                    => 'Joust Duffle Bag',
                    'product_type'            => 'simple',
                    'original_price'          => '34.0000',
                    'price'                   => '34.0000',
                    'tax_amount'              => '10.9900',
                    'tax_percent'             => '8.2500',
                    'weee_tax_applied_amount' => '15.0000',
                    'discount_amount'         => '36.8100',
                    'row_total'               => '170.0000',
                    'qty_ordered'             => '5.0000',
                    'qty_invoiced'            => '0.0000',
                    'qty_shipped'             => '0.0000',
                    'qty_backordered'         => '0',
                    'qty_refunded'            => '0.0000',
                    'qty_returned'            => '0',
                    'qty_canceled'            => '0.0000',
                    'options'                 => '',
                ],
                'expected' => [
                    'qty_ordered'                           => '5.0000',
                    'price'                                 => '34.0000',
                    'base_price'                            => '34.0000',
                    'original_price'                        => '34.0000',
                    'base_original_price'                   => '34.0000',
                    'tax_percent'                           => '8.2500',
                    'tax_amount'                            => '10.9900',
                    'base_tax_amount'                       => '10.9900',
                    'discount_percent'                      => '20.0000',
                    'discount_amount'                       => '36.8100',
                    'base_discount_amount'                  => '36.8100',
                    'row_total'                             => '170.0000',
                    'base_row_total'                        => '170.0000',
                    'base_tax_before_discount'              => null,
                    'tax_before_discount'                   => null,
                    'price_incl_tax'                        => '36.8100',
                    'base_price_incl_tax'                   => '36.8100',
                    'row_total_incl_tax'                    => '184.0300',
                    'base_row_total_incl_tax'               => '184.0300',
                    'discount_tax_compensation_amount'      => '0.0000',
                    'base_discount_tax_compensation_amount' => '0.0000',
                    'weee_tax_applied_amount'               => '15.0000',
                    'weee_tax_applied_row_amount'           => '75.0000',
                    'weee_tax_disposition'                  => '0.0000',
                    'weee_tax_row_disposition'              => '0.0000',
                    'base_weee_tax_applied_amount'          => '15.0000',
                    'base_weee_tax_applied_row_amnt'        => '75.0000',
                    'base_weee_tax_disposition'             => '0.0000',
                    'base_weee_tax_row_disposition'         => '0.0000',
                ]
            ],
            /** test with only the minimum required fields */
            [
                'input'    => [
                    'sku'             => '24-MB01',
                    'name'            => 'Joust Duffle Bag',
                    'price'           => '34.0000',
                    'tax_amount'      => '14.0300',
                    'tax_percent'     => '8.2500',
                    'discount_amount' => '36.8100',
                    'qty_ordered'     => '5.0000'
                ],
                'expected' => [
                    'qty_ordered'                           => '5.0000',
                    'price'                                 => '34.0000',
                    'base_price'                            => '34.0000',
                    'original_price'                        => '34.0000',
                    'base_original_price'                   => '34.0000',
                    'tax_percent'                           => '8.2500',
                    'tax_amount'                            => '14.0300',
                    'base_tax_amount'                       => '14.0300',
                    'discount_percent'                      => '20.0000',
                    'discount_amount'                       => '36.8100',
                    'base_discount_amount'                  => '36.8100',
                    'row_total'                             => '170.0000',
                    'base_row_total'                        => '170.0000',
                    'tax_before_discount'                   => null,
                    'base_tax_before_discount'              => null,
                    'price_incl_tax'                        => '36.8100',
                    'base_price_incl_tax'                   => '36.8100',
                    'row_total_incl_tax'                    => '184.0300',
                    'base_row_total_incl_tax'               => '184.0300',
                    'discount_tax_compensation_amount'      => '0.0000',
                    'base_discount_tax_compensation_amount' => '0.0000',
                    'weee_tax_applied_amount'               => '15.0000',
                    'base_weee_tax_applied_amount'          => '15.0000',
                    'weee_tax_applied_row_amount'           => '75.0000',
                    'base_weee_tax_applied_row_amnt'        => '75.0000',
                    'weee_tax_disposition'                  => '0.0000',
                    'base_weee_tax_disposition'             => '0.0000',
                    'weee_tax_row_disposition'              => '0.0000',
                    'base_weee_tax_row_disposition'         => '0.0000',
                ]
            ],
            /** this data set is an order with a base currency of USD but purchase made with Chinese Yuan */
            [
                'input'    => [
                    'sku'                          => '24-MB01',
                    'name'                         => 'Joust Duffle Bag',
                    'product_type'                 => 'simple',
                    'original_price'               => '215.4784',
                    'base_original_price'          => '34.0000',
                    'price'                        => '215.4800',
                    'base_price'                   => '34.0000',
                    'tax_amount'                   => '69.6400',
                    'base_tax_amount'              => '10.9900',
                    'tax_percent'                  => '8.2500',
                    'weee_tax_applied_amount'      => '95.0600',
                    'base_weee_tax_applied_amount' => '15.0000',
                    'discount_amount'              => '233.2600',
                    'base_discount_amount'         => '36.8100',
                    'row_total'                    => '1077.4000',
                    'base_row_total'               => '170.0000',
                    'qty_ordered'                  => '5.0000',
                    'qty_invoiced'                 => '0.0000',
                    'qty_shipped'                  => '0.0000',
                    'qty_backordered'              => '0',
                    'qty_refunded'                 => '0.0000',
                    'qty_returned'                 => '0',
                    'qty_canceled'                 => '0.0000',
                    'options'                      => '',
                ],
                'expected' => [
                    'qty_ordered'                           => '5.0000',
                    'price'                                 => '215.4800',
                    'base_price'                            => '34.0000',
                    'original_price'                        => '215.4784',
                    'base_original_price'                   => '34.0000',
                    'tax_percent'                           => '8.2500',
                    'tax_amount'                            => '69.6400',
                    'base_tax_amount'                       => '10.9900',
                    'discount_percent'                      => '20.0000',
                    'discount_amount'                       => '233.2600',
                    'base_discount_amount'                  => '36.8100',
                    'row_total'                             => '1077.4000',
                    'base_row_total'                        => '170.0000',
                    'base_tax_before_discount'              => null,
                    'tax_before_discount'                   => null,
                    'price_incl_tax'                        => '233.2600',
                    'base_price_incl_tax'                   => '36.8100',
                    'row_total_incl_tax'                    => '1166.2900',
                    'base_row_total_incl_tax'               => '184.0300',
                    'discount_tax_compensation_amount'      => '0.0000',
                    'base_discount_tax_compensation_amount' => '0.0000',
                    'weee_tax_applied_amount'               => '95.0600',
                    'weee_tax_applied_row_amount'           => '475.3000',
                    'weee_tax_disposition'                  => '0.0000',
                    'weee_tax_row_disposition'              => '0.0000',
                    'base_weee_tax_applied_amount'          => '15.0000',
                    'base_weee_tax_applied_row_amnt'        => '75.0000',
                    'base_weee_tax_disposition'             => '0.0000',
                    'base_weee_tax_row_disposition'         => '0.0000',
                ]
            ],
            /** this data set is an order with a base currency of USD but purchase made with Chinese Yuan but with only the minimum fields */
            [
                'input'    => [
                    'sku'                  => '24-MB01',
                    'name'                 => 'Joust Duffle Bag',
                    'price'                => '215.4800',
                    'base_price'           => '34.0000',
                    'tax_amount'           => '69.6400',
                    'base_tax_amount'      => '10.9900',
                    'tax_percent'          => '8.2500',
                    'discount_amount'      => '233.2600',
                    'base_discount_amount' => '36.8100',
                    'qty_ordered'          => '5.0000'
                ],
                'expected' => [
                    'qty_ordered'                           => '5.0000',
                    'price'                                 => '215.4800',
                    'base_price'                            => '34.0000',
                    'original_price'                        => '215.4784',
                    'base_original_price'                   => '34.0000',
                    'tax_percent'                           => '8.2500',
                    'tax_amount'                            => '69.6400',
                    'base_tax_amount'                       => '10.9900',
                    'discount_percent'                      => '20.0000',
                    'discount_amount'                       => '233.2600',
                    'base_discount_amount'                  => '36.8100',
                    'row_total'                             => '1077.4000',
                    'base_row_total'                        => '170.0000',
                    'base_tax_before_discount'              => null,
                    'tax_before_discount'                   => null,
                    'price_incl_tax'                        => '233.2600',
                    'base_price_incl_tax'                   => '36.8100',
                    'row_total_incl_tax'                    => '1166.2900',
                    'base_row_total_incl_tax'               => '184.0300',
                    'discount_tax_compensation_amount'      => '0.0000',
                    'base_discount_tax_compensation_amount' => '0.0000',
                    'weee_tax_applied_amount'               => '95.0600',
                    'weee_tax_applied_row_amount'           => '475.3000',
                    'weee_tax_disposition'                  => '0.0000',
                    'weee_tax_row_disposition'              => '0.0000',
                    'base_weee_tax_applied_amount'          => '15.0000',
                    'base_weee_tax_applied_row_amnt'        => '75.0000',
                    'base_weee_tax_disposition'             => '0.0000',
                    'base_weee_tax_row_disposition'         => '0.0000',
                ]
            ],
            /** this data set contains a configurable product */
            [
                'input'    => [
                    'sku'                          => 'WT09-M-Yellow',
                    'name'                         => 'Breathe-Easy Tank',
                    'product_type'                 => 'configurable',
                    'original_price'               => '24.5902',
                    'base_original_price'          => '34.0000',
                    'price'                        => '24.5900',
                    'price_incl_tax'               => '26.6200',
                    'base_price'                   => '34.0000',
                    'base_price_incl_tax'          => '36.8100',
                    'tax_amount'                   => '7.9500',
                    'base_tax_amount'              => '10.9900',
                    'tax_percent'                  => '8.2500',
                    'weee_tax_applied_amount'      => '0.0000',
                    'base_weee_tax_applied_amount' => '0.0000',
                    'discount_amount'              => '26.6200',
                    'base_discount_amount'         => '36.8100',
                    'row_total'                    => '122.9500',
                    'row_total_incl_tax'           => '133.0900',
                    'base_row_total'               => '170.0000',
                    'base_row_total_incl_tax'      => '184.0300',
                    'qty_ordered'                  => '5.0000',
                    'qty_invoiced'                 => '0.0000',
                    'qty_shipped'                  => '0.0000',
                    'qty_backordered'              => '0',
                    'qty_refunded'                 => '0.0000',
                    'qty_returned'                 => '0',
                    'qty_canceled'                 => '0.0000',
                    'options'                      =>
                        [
                            [
                                'label'        => 'Size',
                                'value'        => 'M',
                                'option_id'    => '142',
                                'option_value' => '169',
                            ],
                            [
                                'label'        => 'Color',
                                'value'        => 'Yellow',
                                'option_id'    => '93',
                                'option_value' => '60',
                            ],
                        ],
                ],
                'expected' => [
                    'product_type'                          => 'configurable',
                    'qty_ordered'                           => '5.0000',
                    'price'                                 => '24.5900',
                    'base_price'                            => '34.0000',
                    'original_price'                        => '24.5902',
                    'base_original_price'                   => '34.0000',
                    'tax_percent'                           => '8.2500',
                    'tax_amount'                            => '7.9500',
                    'base_tax_amount'                       => '10.9900',
                    'discount_percent'                      => '20.0000',
                    'discount_amount'                       => '26.6200',
                    'base_discount_amount'                  => '36.8100',
                    'row_total'                             => '122.9500',
                    'base_row_total'                        => '170.0000',
                    'base_tax_before_discount'              => null,
                    'tax_before_discount'                   => null,
                    'price_incl_tax'                        => '26.6200',
                    'base_price_incl_tax'                   => '36.8100',
                    'row_total_incl_tax'                    => '133.0900',
                    'base_row_total_incl_tax'               => '184.0300',
                    'discount_tax_compensation_amount'      => '0.0000',
                    'base_discount_tax_compensation_amount' => '0.0000',
                    'weee_tax_applied_amount'               => '0.0000',
                    'weee_tax_applied_row_amount'           => '0.0000',
                    'weee_tax_disposition'                  => '0.0000',
                    'weee_tax_row_disposition'              => '0.0000',
                    'base_weee_tax_applied_amount'          => '0.0000',
                    'base_weee_tax_applied_row_amnt'        => '0.0000',
                    'base_weee_tax_disposition'             => '0.0000',
                    'base_weee_tax_row_disposition'         => '0.0000'

                ]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderCreateItems
     *
     * @param array $input
     * @param array $expected
     *
     * @throws \ReflectionException
     */
    public function testCreateItem(array $input, array $expected)
    {
        /** @var \Magento\Sales\Model\Order\Item $item */
        $item = $this->invokeMethod(
            $this->processor,
            'createItem',
            [$input]
        );

        $data = $item->getData();
        $this->floatify($data);
        $item->setData($data);
        $this->floatify($expected);

        $this->assertEquals($expected['qty_ordered'], $item->getQtyOrdered());
        $this->assertEquals($expected['price'], $item->getPrice());
        $this->assertEquals($expected['price_incl_tax'], $item->getPriceInclTax());
        $this->assertEquals($expected['base_price_incl_tax'], $item->getBasePriceInclTax());
        $this->assertEquals($expected['base_price'], $item->getBasePrice());

        /** its ok to round this bc original price gets stored with precision 4 but price gets stored with precision 2 */
        $this->assertEquals(round($expected['original_price'], 2), round($item->getOriginalPrice(), 2));
        $this->assertEquals(round($expected['base_original_price'], 2), round($item->getBaseOriginalPrice(), 2));
        $this->assertEquals($expected['tax_percent'], $item->getTaxPercent());
        $this->assertEquals($expected['tax_amount'], $item->getTaxAmount());
        $this->assertEquals($expected['base_tax_amount'], $item->getBaseTaxAmount());
        if (isset($input['discount_percent'])) {
            $this->assertEquals($expected['discount_percent'], $item->getDiscountPercent());
        }
        $this->assertEquals($expected['discount_amount'], $item->getDiscountAmount());
        $this->assertEquals($expected['discount_tax_compensation_amount'], $item->getDiscountTaxCompensationAmount());
        $this->assertEquals($expected['base_discount_tax_compensation_amount'], $item->getBaseDiscountTaxCompensationAmount());
        $this->assertEquals($expected['base_discount_amount'], $item->getBaseDiscountAmount());
        $this->assertEquals($expected['row_total'], $item->getRowTotal());
        $this->assertEquals($expected['base_row_total'], $item->getBaseRowTotal());
        $this->assertEquals($expected['row_total_incl_tax'], $item->getRowTotalInclTax());
        $this->assertEquals($expected['base_row_total_incl_tax'], $item->getBaseRowTotalInclTax());
        $this->assertEquals($expected['tax_before_discount'], $item->getTaxBeforeDiscount());
        $this->assertEquals($expected['base_tax_before_discount'], $item->getBaseTaxBeforeDiscount());
        if (isset($input['weee_tax_applied_amount'])) {
            $this->assertEquals($expected['weee_tax_applied_amount'], $item->getWeeeTaxAppliedAmount());
        }
        if (isset($input['base_weee_tax_applied_amount'])) {
            $this->assertEquals($expected['base_weee_tax_applied_amount'], $item->getBaseWeeeTaxAppliedAmount());
        }
        if (isset($input['weee_tax_applied_row_amount'])) {
            $this->assertEquals($expected['weee_tax_applied_row_amount'], $item->getWeeeTaxAppliedRowAmount());
        }
        if (isset($input['base_weee_tax_applied_row_amnt'])) {
            $this->assertEquals($expected['base_weee_tax_applied_row_amnt'], $item->getBaseWeeeTaxAppliedRowAmnt());
        }
        $this->assertEquals($expected['weee_tax_disposition'], $item->getWeeeTaxDisposition());
        $this->assertEquals($expected['base_weee_tax_disposition'], $item->getBaseWeeeTaxDisposition());
        $this->assertEquals($expected['weee_tax_row_disposition'], $item->getWeeeTaxRowDisposition());
        $this->assertEquals($expected['base_weee_tax_row_disposition'], $item->getBaseWeeeTaxRowDisposition());

        if (isset($input['options']) && is_array($input['options'])) {
            $productOptions = $item->getProductOptions();
            foreach ($input['options'] as $key => $option) {
                $this->assertEquals($option['label'], $productOptions['options'][$key]['label']);
                $this->assertEquals($option['value'], $productOptions['options'][$key]['value']);
                $this->assertEquals($option['value'], $productOptions['options'][$key]['print_value']);
                $this->assertEquals($option['value'], $productOptions['options'][$key]['option_value']);
                $this->assertEquals('field', $productOptions['options'][$key]['option_type']);
                $this->assertEquals($option['label'], $productOptions['options'][$key]['label']);
                $this->assertFalse($productOptions['options'][$key]['custom_view']);
                $this->assertEmpty($productOptions['options'][$key]['option_id']);
            }
        }
    }

    /**
     * @return array
     */
    public function dataProviderBundleProduct()
    {
        return [
            [
                'input'    => [
                    'sku'                          => '24-WG080-24-WG084-24-WG088-24-WG082-blue-24-WG087',
                    'name'                         => 'Sprite Yoga Companion Kit',
                    'product_type'                 => 'bundle',
                    'original_price'               => '55.6895',
                    'base_original_price'          => '77.0000',
                    'price'                        => '55.7000',
                    'price_incl_tax'               => '60.3000',
                    'base_price'                   => '77.0000',
                    'base_price_incl_tax'          => '83.3500',
                    'tax_amount'                   => '3.6000',
                    'base_tax_amount'              => '4.9800',
                    'tax_percent'                  => '0',
                    'weee_tax_applied_amount'      => '0.0000',
                    'base_weee_tax_applied_amount' => '0.0000',
                    'discount_amount'              => '0.0000',
                    'base_discount_amount'         => '0.0000',
                    'row_total'                    => '55.7000',
                    'row_total_incl_tax'           => '60.3000',
                    'base_row_total'               => '77.0000',
                    'base_row_total_incl_tax'      => '83.3500',
                    'qty_ordered'                  => '1.0000',
                    'qty_invoiced'                 => '0.0000',
                    'qty_shipped'                  => '0.0000',
                    'qty_backordered'              => '0',
                    'qty_refunded'                 => '0.0000',
                    'qty_returned'                 => '0',
                    'qty_canceled'                 => '0.0000',
                    'bundle_items'                 =>
                        [
                            [
                                'sku'                          => '24-WG082-blue',
                                'name'                         => 'Sprite Stasis Ball 65 cm',
                                'product_type'                 => 'simple',
                                'original_price'               => '19.5275',
                                'base_original_price'          => '27.0000',
                                'price'                        => '19.5300',
                                'price_incl_tax'               => '21.1400',
                                'base_price'                   => '27.0000',
                                'base_price_incl_tax'          => '29.2300',
                                'tax_amount'                   => '1.2600',
                                'base_tax_amount'              => '1.7400',
                                'tax_percent'                  => '8.2500',
                                'weee_tax_applied_amount'      => '0.0000',
                                'base_weee_tax_applied_amount' => '0.0000',
                                'discount_amount'              => '4.2300',
                                'base_discount_amount'         => '5.8500',
                                'row_total'                    => '19.5300',
                                'row_total_incl_tax'           => '21.1400',
                                'base_row_total'               => '27.0000',
                                'base_row_total_incl_tax'      => '29.2300',
                                'qty_ordered'                  => '1.0000',
                                'qty_invoiced'                 => '0.0000',
                                'qty_shipped'                  => '0.0000',
                                'qty_backordered'              => '0',
                                'qty_refunded'                 => '0.0000',
                                'qty_returned'                 => '0',
                                'qty_canceled'                 => '0.0000',
                            ],
                            [
                                'sku'                          => '24-WG084',
                                'name'                         => 'Sprite Foam Yoga Brick',
                                'product_type'                 => 'simple',
                                'original_price'               => '3.6162',
                                'base_original_price'          => '5.0000',
                                'price'                        => '3.6200',
                                'price_incl_tax'               => '3.9200',
                                'base_price'                   => '5.0000',
                                'base_price_incl_tax'          => '5.4100',
                                'tax_amount'                   => '0.4700',
                                'base_tax_amount'              => '0.6500',
                                'tax_percent'                  => '8.2500',
                                'weee_tax_applied_amount'      => '0.0000',
                                'base_weee_tax_applied_amount' => '0.0000',
                                'discount_amount'              => '1.5700',
                                'base_discount_amount'         => '2.1600',
                                'row_total'                    => '7.2400',
                                'row_total_incl_tax'           => '7.8400',
                                'base_row_total'               => '10.0000',
                                'base_row_total_incl_tax'      => '10.8200',
                                'qty_ordered'                  => '2.0000',
                                'qty_invoiced'                 => '0.0000',
                                'qty_shipped'                  => '0.0000',
                                'qty_backordered'              => '0',
                                'qty_refunded'                 => '0.0000',
                                'qty_returned'                 => '0',
                                'qty_canceled'                 => '0.0000',
                            ],
                            [
                                'sku'                          => '24-WG087',
                                'name'                         => 'Sprite Yoga Strap 10 foot',
                                'product_type'                 => 'simple',
                                'original_price'               => '15.1880',
                                'base_original_price'          => '21.0000',
                                'price'                        => '15.1900',
                                'price_incl_tax'               => '16.4400',
                                'base_price'                   => '21.0000',
                                'base_price_incl_tax'          => '22.7400',
                                'tax_amount'                   => '0.9800',
                                'base_tax_amount'              => '1.3600',
                                'tax_percent'                  => '8.2500',
                                'weee_tax_applied_amount'      => '0.0000',
                                'base_weee_tax_applied_amount' => '0.0000',
                                'discount_amount'              => '3.2800',
                                'base_discount_amount'         => '4.5500',
                                'row_total'                    => '15.1900',
                                'row_total_incl_tax'           => '16.4400',
                                'base_row_total'               => '21.0000',
                                'base_row_total_incl_tax'      => '22.7400',
                                'qty_ordered'                  => '1.0000',
                                'qty_invoiced'                 => '0.0000',
                                'qty_shipped'                  => '0.0000',
                                'qty_backordered'              => '0',
                                'qty_refunded'                 => '0.0000',
                                'qty_returned'                 => '0',
                                'qty_canceled'                 => '0.0000',
                            ],
                            [
                                'sku'                          => '24-WG088',
                                'name'                         => 'Sprite Foam Roller',
                                'product_type'                 => 'simple',
                                'original_price'               => '13.7416',
                                'base_original_price'          => '19.0000',
                                'price'                        => '13.7400',
                                'price_incl_tax'               => '14.8800',
                                'base_price'                   => '19.0000',
                                'base_price_incl_tax'          => '20.5600',
                                'tax_amount'                   => '0.8900',
                                'base_tax_amount'              => '1.2300',
                                'tax_percent'                  => '8.2500',
                                'weee_tax_applied_amount'      => '0.0000',
                                'base_weee_tax_applied_amount' => '0.0000',
                                'discount_amount'              => '2.9800',
                                'base_discount_amount'         => '4.1100',
                                'row_total'                    => '13.7400',
                                'row_total_incl_tax'           => '14.8800',
                                'base_row_total'               => '19.0000',
                                'base_row_total_incl_tax'      => '20.5600',
                                'qty_ordered'                  => '1.0000',
                                'qty_invoiced'                 => '0.0000',
                                'qty_shipped'                  => '0.0000',
                                'qty_backordered'              => '0',
                                'qty_refunded'                 => '0.0000',
                                'qty_returned'                 => '0',
                                'qty_canceled'                 => '0.0000',
                            ],
                        ],
                ],
                /**
                 * the first item is the bundle product. the rest are the children. the child products
                 * must be in the same order as they are in the input array above
                 */
                'expected' => [
                    [
                        'product_type'                          => 'bundle',
                        'qty_ordered'                           => '1.0000',
                        'price'                                 => '55.7000',
                        'base_price'                            => '77.0000',
                        'original_price'                        => '55.6895',
                        'base_original_price'                   => '77.0000',
                        'tax_percent'                           => null,
                        'tax_amount'                            => '3.6000',
                        'base_tax_amount'                       => '4.9800',
                        'discount_percent'                      => '20.0000',
                        'discount_amount'                       => '0.0000',
                        'base_discount_amount'                  => '0.0000',
                        'row_total'                             => '55.7000',
                        'base_row_total'                        => '77.0000',
                        'base_tax_before_discount'              => null,
                        'tax_before_discount'                   => null,
                        'price_incl_tax'                        => '60.3000',
                        'base_price_incl_tax'                   => '83.3500',
                        'row_total_incl_tax'                    => '60.3000',
                        'base_row_total_incl_tax'               => '83.3500',
                        'discount_tax_compensation_amount'      => null,
                        'base_discount_tax_compensation_amount' => null,
                        'weee_tax_applied_amount'               => '0.0000',
                        'weee_tax_applied_row_amount'           => '0.0000',
                        'weee_tax_disposition'                  => '0.0000',
                        'weee_tax_row_disposition'              => '0.0000',
                        'base_weee_tax_applied_amount'          => '0.0000',
                        'base_weee_tax_applied_row_amnt'        => '0.0000',
                        'base_weee_tax_disposition'             => '0.0000',
                        'base_weee_tax_row_disposition'         => '0.0000',
                    ],
                    [
                        'product_type'                          => 'simple',
                        'qty_ordered'                           => '1.0000',
                        'price'                                 => '19.5300',
                        'base_price'                            => '27.0000',
                        'original_price'                        => '19.5275',
                        'base_original_price'                   => '27.0000',
                        'tax_percent'                           => '8.2500',
                        'tax_amount'                            => '1.2600',
                        'base_tax_amount'                       => '1.7400',
                        'discount_percent'                      => '0.0000',
                        'discount_amount'                       => '4.2300',
                        'base_discount_amount'                  => '5.8500',
                        'row_total'                             => '19.5300',
                        'base_row_total'                        => '27.0000',
                        'base_tax_before_discount'              => null,
                        'tax_before_discount'                   => null,
                        'price_incl_tax'                        => '21.1400',
                        'base_price_incl_tax'                   => '29.2300',
                        'row_total_incl_tax'                    => '21.1400',
                        'base_row_total_incl_tax'               => '29.2300',
                        'discount_tax_compensation_amount'      => '0.0000',
                        'base_discount_tax_compensation_amount' => '0.0000',
                        'weee_tax_applied_amount'               => '0.0000',
                        'weee_tax_applied_row_amount'           => '0.0000',
                        'weee_tax_disposition'                  => '0.0000',
                        'weee_tax_row_disposition'              => '0.0000',
                        'base_weee_tax_applied_amount'          => '0.0000',
                        'base_weee_tax_applied_row_amnt'        => '0.0000',
                        'base_weee_tax_disposition'             => '0.0000',
                        'base_weee_tax_row_disposition'         => '0.0000',
                    ],
                    [
                        'product_type'                          => 'simple',
                        'qty_ordered'                           => '2.0000',
                        'price'                                 => '3.6200',
                        'base_price'                            => '5.0000',
                        'original_price'                        => '3.6162',
                        'base_original_price'                   => '5.0000',
                        'tax_percent'                           => '8.2500',
                        'tax_amount'                            => '0.4700',
                        'base_tax_amount'                       => '0.6500',
                        'discount_percent'                      => '0.0000',
                        'discount_amount'                       => '1.5700',
                        'base_discount_amount'                  => '2.1600',
                        'row_total'                             => '7.2400',
                        'base_row_total'                        => '10.0000',
                        'base_tax_before_discount'              => null,
                        'tax_before_discount'                   => null,
                        'price_incl_tax'                        => '3.9200',
                        'base_price_incl_tax'                   => '5.4100',
                        'row_total_incl_tax'                    => '7.8400',
                        'base_row_total_incl_tax'               => '10.8200',
                        'discount_tax_compensation_amount'      => '0.0000',
                        'base_discount_tax_compensation_amount' => '0.0000',
                        'weee_tax_applied_amount'               => '0.0000',
                        'weee_tax_applied_row_amount'           => '0.0000',
                        'weee_tax_disposition'                  => '0.0000',
                        'weee_tax_row_disposition'              => '0.0000',
                        'base_weee_tax_applied_amount'          => '0.0000',
                        'base_weee_tax_applied_row_amnt'        => '0.0000',
                        'base_weee_tax_disposition'             => '0.0000',
                        'base_weee_tax_row_disposition'         => '0.0000',
                    ],
                    [
                        'product_type'                          => 'simple',
                        'qty_ordered'                           => '1.0000',
                        'price'                                 => '15.1900',
                        'base_price'                            => '21.0000',
                        'original_price'                        => '15.1880',
                        'base_original_price'                   => '21.0000',
                        'tax_percent'                           => '8.2500',
                        'tax_amount'                            => '0.9800',
                        'base_tax_amount'                       => '1.3600',
                        'discount_percent'                      => '0.0000',
                        'discount_amount'                       => '3.2800',
                        'base_discount_amount'                  => '4.5500',
                        'row_total'                             => '15.1900',
                        'base_row_total'                        => '21.0000',
                        'base_tax_before_discount'              => null,
                        'tax_before_discount'                   => null,
                        'price_incl_tax'                        => '16.4400',
                        'base_price_incl_tax'                   => '22.7400',
                        'row_total_incl_tax'                    => '16.4400',
                        'base_row_total_incl_tax'               => '22.7400',
                        'discount_tax_compensation_amount'      => '0.0000',
                        'base_discount_tax_compensation_amount' => '0.0000',
                        'weee_tax_applied_amount'               => '0.0000',
                        'weee_tax_applied_row_amount'           => '0.0000',
                        'weee_tax_disposition'                  => '0.0000',
                        'weee_tax_row_disposition'              => '0.0000',
                        'base_weee_tax_applied_amount'          => '0.0000',
                        'base_weee_tax_applied_row_amnt'        => '0.0000',
                        'base_weee_tax_disposition'             => '0.0000',
                        'base_weee_tax_row_disposition'         => '0.0000',
                    ],
                    [
                        'product_type'                          => 'simple',
                        'qty_ordered'                           => '1.0000',
                        'price'                                 => '13.7400',
                        'base_price'                            => '19.0000',
                        'original_price'                        => '13.7416',
                        'base_original_price'                   => '19.0000',
                        'tax_percent'                           => '8.2500',
                        'tax_amount'                            => '0.8900',
                        'base_tax_amount'                       => '1.2300',
                        'discount_percent'                      => '0.0000',
                        'discount_amount'                       => '2.9800',
                        'base_discount_amount'                  => '4.1100',
                        'row_total'                             => '13.7400',
                        'base_row_total'                        => '19.0000',
                        'base_tax_before_discount'              => null,
                        'tax_before_discount'                   => null,
                        'price_incl_tax'                        => '14.8800',
                        'base_price_incl_tax'                   => '20.5600',
                        'row_total_incl_tax'                    => '14.8800',
                        'base_row_total_incl_tax'               => '20.5600',
                        'discount_tax_compensation_amount'      => '0.0000',
                        'base_discount_tax_compensation_amount' => '0.0000',
                        'weee_tax_applied_amount'               => '0.0000',
                        'weee_tax_applied_row_amount'           => '0.0000',
                        'weee_tax_disposition'                  => '0.0000',
                        'weee_tax_row_disposition'              => '0.0000',
                        'base_weee_tax_applied_amount'          => '0.0000',
                        'base_weee_tax_applied_row_amnt'        => '0.0000',
                        'base_weee_tax_disposition'             => '0.0000',
                        'base_weee_tax_row_disposition'         => '0.0000',
                    ]
                ]
            ],
            /** this is the same data set as above but with only the required fields */
            [
                'input'    => [
                    'sku'                     => '24-WG080-24-WG084-24-WG088-24-WG082-blue-24-WG087',
                    'name'                    => 'Sprite Yoga Companion Kit',
                    'product_type'            => 'bundle',
                    'price'                   => '55.7000',
                    'price_incl_tax'          => '60.3000',
                    'base_price'              => '77.0000',
                    'base_price_incl_tax'     => '83.3500',
                    'tax_amount'              => '3.6000',
                    'base_tax_amount'         => '4.9800',
                    'tax_percent'             => '0',
                    'discount_amount'         => '0.0000',
                    'base_discount_amount'    => '0.0000',
                    'row_total'               => '55.7000',
                    'row_total_incl_tax'      => '60.3000',
                    'base_row_total'          => '77.0000',
                    'base_row_total_incl_tax' => '83.3500',
                    'qty_ordered'             => '1.0000',
                    'bundle_items'            =>
                        [
                            [
                                'sku'                     => '24-WG082-blue',
                                'name'                    => 'Sprite Stasis Ball 65 cm',
                                'product_type'            => 'simple',
                                'price'                   => '19.5300',
                                'price_incl_tax'          => '21.1400',
                                'base_price'              => '27.0000',
                                'base_price_incl_tax'     => '29.2300',
                                'tax_amount'              => '1.2600',
                                'base_tax_amount'         => '1.7400',
                                'tax_percent'             => '8.2500',
                                'discount_amount'         => '4.2300',
                                'base_discount_amount'    => '5.8500',
                                'row_total'               => '19.5300',
                                'row_total_incl_tax'      => '21.1400',
                                'base_row_total'          => '27.0000',
                                'base_row_total_incl_tax' => '29.2300',
                                'qty_ordered'             => '1.0000'
                            ],
                            [
                                'sku'                     => '24-WG084',
                                'name'                    => 'Sprite Foam Yoga Brick',
                                'product_type'            => 'simple',
                                'price'                   => '3.6200',
                                'price_incl_tax'          => '3.9200',
                                'base_price'              => '5.0000',
                                'base_price_incl_tax'     => '5.4100',
                                'tax_amount'              => '0.4700',
                                'base_tax_amount'         => '0.6500',
                                'tax_percent'             => '8.2500',
                                'discount_amount'         => '1.5700',
                                'base_discount_amount'    => '2.1600',
                                'row_total'               => '7.2400',
                                'row_total_incl_tax'      => '7.8400',
                                'base_row_total'          => '10.0000',
                                'base_row_total_incl_tax' => '10.8200',
                                'qty_ordered'             => '2.0000'
                            ],
                            [
                                'sku'                     => '24-WG087',
                                'name'                    => 'Sprite Yoga Strap 10 foot',
                                'product_type'            => 'simple',
                                'price'                   => '15.1900',
                                'price_incl_tax'          => '16.4400',
                                'base_price'              => '21.0000',
                                'base_price_incl_tax'     => '22.7400',
                                'tax_amount'              => '0.9800',
                                'base_tax_amount'         => '1.3600',
                                'tax_percent'             => '8.2500',
                                'discount_amount'         => '3.2800',
                                'base_discount_amount'    => '4.5500',
                                'row_total'               => '15.1900',
                                'row_total_incl_tax'      => '16.4400',
                                'base_row_total'          => '21.0000',
                                'base_row_total_incl_tax' => '22.7400',
                                'qty_ordered'             => '1.0000'
                            ],
                            [
                                'sku'                     => '24-WG088',
                                'name'                    => 'Sprite Foam Roller',
                                'product_type'            => 'simple',
                                'price'                   => '13.7400',
                                'price_incl_tax'          => '14.8800',
                                'base_price'              => '19.0000',
                                'base_price_incl_tax'     => '20.5600',
                                'tax_amount'              => '0.8900',
                                'base_tax_amount'         => '1.2300',
                                'tax_percent'             => '8.2500',
                                'discount_amount'         => '2.9800',
                                'base_discount_amount'    => '4.1100',
                                'row_total'               => '13.7400',
                                'row_total_incl_tax'      => '14.8800',
                                'base_row_total'          => '19.0000',
                                'base_row_total_incl_tax' => '20.5600',
                                'qty_ordered'             => '1.0000',
                                'qty_invoiced'            => '0.0000'
                            ],
                        ],
                ],
                /**
                 * the first item is the bundle product. the rest are the children. the child products
                 * must be in the same order as they are in the input array above
                 */
                'expected' => [
                    [
                        'product_type'                          => 'bundle',
                        'qty_ordered'                           => '1.0000',
                        'price'                                 => '55.7000',
                        'base_price'                            => '77.0000',
                        'original_price'                        => '55.70',
                        'base_original_price'                   => '77.0000',
                        'tax_percent'                           => null,
                        'tax_amount'                            => '3.6000',
                        'base_tax_amount'                       => '4.9800',
                        'discount_percent'                      => '20.0000',
                        'discount_amount'                       => '0.0000',
                        'base_discount_amount'                  => '0.0000',
                        'row_total'                             => '55.7000',
                        'base_row_total'                        => '77.0000',
                        'base_tax_before_discount'              => null,
                        'tax_before_discount'                   => null,
                        'price_incl_tax'                        => '60.3000',
                        'base_price_incl_tax'                   => '83.3500',
                        'row_total_incl_tax'                    => '60.3000',
                        'base_row_total_incl_tax'               => '83.3500',
                        'discount_tax_compensation_amount'      => null,
                        'base_discount_tax_compensation_amount' => null,
                        'weee_tax_applied_amount'               => '0.0000',
                        'weee_tax_applied_row_amount'           => '0.0000',
                        'weee_tax_disposition'                  => '0.0000',
                        'weee_tax_row_disposition'              => '0.0000',
                        'base_weee_tax_applied_amount'          => '0.0000',
                        'base_weee_tax_applied_row_amnt'        => '0.0000',
                        'base_weee_tax_disposition'             => '0.0000',
                        'base_weee_tax_row_disposition'         => '0.0000',
                    ],
                    [
                        'product_type'                          => 'simple',
                        'qty_ordered'                           => '1.0000',
                        'price'                                 => '19.5300',
                        'base_price'                            => '27.0000',
                        'original_price'                        => '19.5275',
                        'base_original_price'                   => '27.0000',
                        'tax_percent'                           => '8.2500',
                        'tax_amount'                            => '1.2600',
                        'base_tax_amount'                       => '1.7400',
                        'discount_percent'                      => '0.0000',
                        'discount_amount'                       => '4.2300',
                        'base_discount_amount'                  => '5.8500',
                        'row_total'                             => '19.5300',
                        'base_row_total'                        => '27.0000',
                        'base_tax_before_discount'              => null,
                        'tax_before_discount'                   => null,
                        'price_incl_tax'                        => '21.1400',
                        'base_price_incl_tax'                   => '29.2300',
                        'row_total_incl_tax'                    => '21.1400',
                        'base_row_total_incl_tax'               => '29.2300',
                        'discount_tax_compensation_amount'      => '0.0000',
                        'base_discount_tax_compensation_amount' => '0.0000',
                        'weee_tax_applied_amount'               => '0.0000',
                        'weee_tax_applied_row_amount'           => '0.0000',
                        'weee_tax_disposition'                  => '0.0000',
                        'weee_tax_row_disposition'              => '0.0000',
                        'base_weee_tax_applied_amount'          => '0.0000',
                        'base_weee_tax_applied_row_amnt'        => '0.0000',
                        'base_weee_tax_disposition'             => '0.0000',
                        'base_weee_tax_row_disposition'         => '0.0000',
                    ],
                    [
                        'product_type'                          => 'simple',
                        'qty_ordered'                           => '2.0000',
                        'price'                                 => '3.6200',
                        'base_price'                            => '5.0000',
                        'original_price'                        => '3.6162',
                        'base_original_price'                   => '5.0000',
                        'tax_percent'                           => '8.2500',
                        'tax_amount'                            => '0.4700',
                        'base_tax_amount'                       => '0.6500',
                        'discount_percent'                      => '0.0000',
                        'discount_amount'                       => '1.5700',
                        'base_discount_amount'                  => '2.1600',
                        'row_total'                             => '7.2400',
                        'base_row_total'                        => '10.0000',
                        'base_tax_before_discount'              => null,
                        'tax_before_discount'                   => null,
                        'price_incl_tax'                        => '3.9200',
                        'base_price_incl_tax'                   => '5.4100',
                        'row_total_incl_tax'                    => '7.8400',
                        'base_row_total_incl_tax'               => '10.8200',
                        'discount_tax_compensation_amount'      => '0.0000',
                        'base_discount_tax_compensation_amount' => '0.0000',
                        'weee_tax_applied_amount'               => '0.0000',
                        'weee_tax_applied_row_amount'           => '0.0000',
                        'weee_tax_disposition'                  => '0.0000',
                        'weee_tax_row_disposition'              => '0.0000',
                        'base_weee_tax_applied_amount'          => '0.0000',
                        'base_weee_tax_applied_row_amnt'        => '0.0000',
                        'base_weee_tax_disposition'             => '0.0000',
                        'base_weee_tax_row_disposition'         => '0.0000',
                    ],
                    [
                        'product_type'                          => 'simple',
                        'qty_ordered'                           => '1.0000',
                        'price'                                 => '15.1900',
                        'base_price'                            => '21.0000',
                        'original_price'                        => '15.1880',
                        'base_original_price'                   => '21.0000',
                        'tax_percent'                           => '8.2500',
                        'tax_amount'                            => '0.9800',
                        'base_tax_amount'                       => '1.3600',
                        'discount_percent'                      => '0.0000',
                        'discount_amount'                       => '3.2800',
                        'base_discount_amount'                  => '4.5500',
                        'row_total'                             => '15.1900',
                        'base_row_total'                        => '21.0000',
                        'base_tax_before_discount'              => null,
                        'tax_before_discount'                   => null,
                        'price_incl_tax'                        => '16.4400',
                        'base_price_incl_tax'                   => '22.7400',
                        'row_total_incl_tax'                    => '16.4400',
                        'base_row_total_incl_tax'               => '22.7400',
                        'discount_tax_compensation_amount'      => '0.0000',
                        'base_discount_tax_compensation_amount' => '0.0000',
                        'weee_tax_applied_amount'               => '0.0000',
                        'weee_tax_applied_row_amount'           => '0.0000',
                        'weee_tax_disposition'                  => '0.0000',
                        'weee_tax_row_disposition'              => '0.0000',
                        'base_weee_tax_applied_amount'          => '0.0000',
                        'base_weee_tax_applied_row_amnt'        => '0.0000',
                        'base_weee_tax_disposition'             => '0.0000',
                        'base_weee_tax_row_disposition'         => '0.0000',
                    ],
                    [
                        'product_type'                          => 'simple',
                        'qty_ordered'                           => '1.0000',
                        'price'                                 => '13.7400',
                        'base_price'                            => '19.0000',
                        'original_price'                        => '13.7416',
                        'base_original_price'                   => '19.0000',
                        'tax_percent'                           => '8.2500',
                        'tax_amount'                            => '0.8900',
                        'base_tax_amount'                       => '1.2300',
                        'discount_percent'                      => '0.0000',
                        'discount_amount'                       => '2.9800',
                        'base_discount_amount'                  => '4.1100',
                        'row_total'                             => '13.7400',
                        'base_row_total'                        => '19.0000',
                        'base_tax_before_discount'              => null,
                        'tax_before_discount'                   => null,
                        'price_incl_tax'                        => '14.8800',
                        'base_price_incl_tax'                   => '20.5600',
                        'row_total_incl_tax'                    => '14.8800',
                        'base_row_total_incl_tax'               => '20.5600',
                        'discount_tax_compensation_amount'      => '0.0000',
                        'base_discount_tax_compensation_amount' => '0.0000',
                        'weee_tax_applied_amount'               => '0.0000',
                        'weee_tax_applied_row_amount'           => '0.0000',
                        'weee_tax_disposition'                  => '0.0000',
                        'weee_tax_row_disposition'              => '0.0000',
                        'base_weee_tax_applied_amount'          => '0.0000',
                        'base_weee_tax_applied_row_amnt'        => '0.0000',
                        'base_weee_tax_disposition'             => '0.0000',
                        'base_weee_tax_row_disposition'         => '0.0000',
                    ]
                ]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderBundleProduct
     *
     * @param array $input
     * @param array $expected
     *
     * @throws \ReflectionException
     */
    public function testCreateBundleItem(array $input, array $expected)
    {
        /** @var \Magento\Sales\Model\Order\Item $item */
        $item = $this->invokeMethod(
            $this->processor,
            'createItem',
            [$input]
        );

        $bundleItem = $item;
        $items      = array_merge([$item], $item->getChildrenItems());

        foreach ($items as $key => $item) {
            $data = $item->getData();
            $this->floatify($data);
            $item->setData($data);
            $this->floatify($expected[$key]);

            $this->assertEquals($expected[$key]['qty_ordered'], $item->getQtyOrdered());
            $this->assertEquals($expected[$key]['price'], $item->getPrice());
            $this->assertEquals($expected[$key]['price_incl_tax'], $item->getPriceInclTax());
            $this->assertEquals($expected[$key]['base_price_incl_tax'], $item->getBasePriceInclTax());
            $this->assertEquals($expected[$key]['base_price'], $item->getBasePrice());

            /** its ok to round this bc original price gets stored with precision 4 but price gets stored with precision 2 */
            $this->assertEquals($expected[$key]['tax_percent'], $item->getTaxPercent());
            $this->assertEquals($expected[$key]['tax_amount'], $item->getTaxAmount());
            $this->assertEquals($expected[$key]['base_tax_amount'], $item->getBaseTaxAmount());
            if (isset($input['discount_percent'])) {
                $this->assertEquals($expected[$key]['discount_percent'], $item->getDiscountPercent());
            }
            $this->assertEquals($expected[$key]['discount_amount'], $item->getDiscountAmount());
            $this->assertEquals($expected[$key]['discount_tax_compensation_amount'], $item->getDiscountTaxCompensationAmount());
            $this->assertEquals($expected[$key]['base_discount_tax_compensation_amount'], $item->getBaseDiscountTaxCompensationAmount());
            $this->assertEquals($expected[$key]['base_discount_amount'], $item->getBaseDiscountAmount());
            $this->assertEquals($expected[$key]['row_total'], $item->getRowTotal());
            $this->assertEquals($expected[$key]['base_row_total'], $item->getBaseRowTotal());
            $this->assertEquals($expected[$key]['row_total_incl_tax'], $item->getRowTotalInclTax());
            $this->assertEquals($expected[$key]['base_row_total_incl_tax'], $item->getBaseRowTotalInclTax());
            $this->assertEquals($expected[$key]['tax_before_discount'], $item->getTaxBeforeDiscount());
            $this->assertEquals($expected[$key]['base_tax_before_discount'], $item->getBaseTaxBeforeDiscount());
            if (isset($input['weee_tax_applied_amount'])) {
                $this->assertEquals($expected[$key]['weee_tax_applied_amount'], $item->getWeeeTaxAppliedAmount());
            }
            if (isset($input['base_weee_tax_applied_amount'])) {
                $this->assertEquals($expected[$key]['base_weee_tax_applied_amount'], $item->getBaseWeeeTaxAppliedAmount());
            }
            if (isset($input['weee_tax_applied_row_amount'])) {
                $this->assertEquals($expected[$key]['weee_tax_applied_row_amount'], $item->getWeeeTaxAppliedRowAmount());
            }
            if (isset($input['base_weee_tax_applied_row_amnt'])) {
                $this->assertEquals($expected[$key]['base_weee_tax_applied_row_amnt'], $item->getBaseWeeeTaxAppliedRowAmnt());
            }
            $this->assertEquals($expected[$key]['weee_tax_disposition'], $item->getWeeeTaxDisposition());
            $this->assertEquals($expected[$key]['base_weee_tax_disposition'], $item->getBaseWeeeTaxDisposition());
            $this->assertEquals($expected[$key]['weee_tax_row_disposition'], $item->getWeeeTaxRowDisposition());
            $this->assertEquals($expected[$key]['base_weee_tax_row_disposition'], $item->getBaseWeeeTaxRowDisposition());

            if ($key > 0) {
                $options = $bundleItem->getProductOptions();
                $itemKey = $key - 1;
                $this->assertEquals($itemKey, $options['info_buyRequest']['bundle_option'][$itemKey]);
                $this->assertEquals((float)$input['bundle_items'][$itemKey]['qty_ordered'], (float)$options['info_buyRequest']['bundle_option_qty'][$itemKey]);
                $this->assertEquals($input['bundle_items'][$itemKey]['name'], $options['bundle_options'][$itemKey]['label']);
                $this->assertEquals($input['bundle_items'][$itemKey]['name'], $options['bundle_options'][$itemKey]['value'][0]['title']);
                $this->assertEquals((float)$input['bundle_items'][$itemKey]['qty_ordered'], (float)$options['bundle_options'][$itemKey]['value'][0]['qty']);
                $this->assertEquals(round($input['bundle_items'][$itemKey]['price'], 2), round($options['bundle_options'][$itemKey]['value'][0]['price'], 2));
            }
        }
    }
}
