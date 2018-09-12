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

namespace MageModule\OrderImportExport\Test\Integration\Model\Processor;

class InvoiceTest extends \MageModule\OrderImportExport\Test\Integration\AbstractTestCase
{
    /**
     * Can be any normal, exported csv containing orders
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function dataProvider()
    {
        $this->setUp();

        return $this->getSampleFileData('order-full-data.csv');
    }

    /**
     * @dataProvider dataProvider
     *
     * @param array $input
     * @param array $expected
     *
     * @throws \MageModule\Core\Exception\FieldValidatorException
     * @throws \MageModule\Core\Exception\ValidatorException
     * @throws \MageModule\OrderImportExport\Exception\AddressException
     * @throws \MageModule\OrderImportExport\Exception\CreditMemoException
     * @throws \MageModule\OrderImportExport\Exception\CustomerException
     * @throws \MageModule\OrderImportExport\Exception\InvoiceException
     * @throws \MageModule\OrderImportExport\Exception\OrderException
     * @throws \MageModule\OrderImportExport\Exception\OrderItemException
     * @throws \MageModule\OrderImportExport\Exception\PaymentException
     * @throws \MageModule\OrderImportExport\Exception\ShipmentException
     * @throws \MageModule\OrderImportExport\Exception\ShippingException
     * @throws \MageModule\OrderImportExport\Exception\StatusHistoryException
     * @throws \MageModule\OrderImportExport\Exception\StoreException
     * @throws \MageModule\OrderImportExport\Exception\TransactionException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function testCreateInvoice(array $input, array $expected)
    {
        $this->getCoreHelper()->nullifyEmpty($expected);

        /** @var \MageModule\OrderImportExport\Model\Config\Import $config */
        $config = $this->objectManager->create(\MageModule\OrderImportExport\Model\Config\Import::class);
        $config->setCreateInvoice(true);
        $config->setCreateCreditMemo(false);
        $config->setCreateShipment(false);
        $config->setDelimiter(',');
        $config->setEnclosure('"');
        $config->setErrorLimit(100);
        $config->setImportOrderNumber(false);

        /** @var \MageModule\OrderImportExport\Model\Importer $importer */
        $importer = $this->objectManager->create(
            \MageModule\OrderImportExport\Model\Importer::class,
            ['config' => $config]
        );

        /** @var \Magento\Sales\Model\Order $order */
        $order = $importer->import($input);

        $this->assertTrue($order->getInvoice() instanceof \Magento\Sales\Api\Data\InvoiceInterface);

        /** @var \Magento\Sales\Model\Order\Invoice $invoice */
        $invoice = $order->getInvoiceCollection()->getFirstItem();

        if (isset($expected['base_currency_code'])) {
            $this->assertEquals((string)$expected['base_currency_code'], (string)$invoice->getBaseCurrencyCode());
        }
        $this->assertEquals((string)$order->getBaseCurrencyCode(), (string)$invoice->getBaseCurrencyCode());

        if (isset($expected['order_currency_code'])) {
            $this->assertEquals((string)$expected['order_currency_code'], (string)$invoice->getOrderCurrencyCode());
        }
        $this->assertEquals((string)$order->getOrderCurrencyCode(), (string)$invoice->getOrderCurrencyCode());

        if (isset($expected['global_currency_code'])) {
            $this->assertEquals((string)$expected['global_currency_code'], (string)$invoice->getGlobalCurrencyCode());
        }
        $this->assertEquals((string)$order->getGlobalCurrencyCode(), (string)$invoice->getGlobalCurrencyCode());

        if (isset($expected['store_currency_code'])) {
            $this->assertEquals((string)$expected['store_currency_code'], (string)$invoice->getStoreCurrencyCode());
        }
        $this->assertEquals((string)$order->getStoreCurrencyCode(), (string)$invoice->getStoreCurrencyCode());

        if (isset($expected['base_discount_amount'])) {
            $this->assertEquals((float)$expected['base_discount_amount'], (float)$invoice->getBaseDiscountAmount());
        }
        $this->assertEquals((float)$order->getBaseDiscountAmount(), (float)$invoice->getBaseDiscountAmount());

        if (isset($expected['base_discount_invoiced'])) {
            $this->assertEquals((float)$expected['base_discount_invoiced'], (float)$invoice->getBaseDiscountAmount());
        }
        $this->assertEquals((float)$order->getBaseDiscountInvoiced(), (float)$invoice->getBaseDiscountAmount());

        if (isset($expected['discount_amount'])) {
            $this->assertEquals((float)$expected['discount_amount'], (float)$invoice->getDiscountAmount());
        }
        $this->assertEquals((float)$order->getDiscountAmount(), (float)$invoice->getDiscountAmount());

        if (isset($expected['discount_invoiced'])) {
            $this->assertEquals((float)$expected['discount_invoiced'], (float)$invoice->getDiscountAmount());
        }
        $this->assertEquals((float)$order->getDiscountInvoiced(), (float)$invoice->getDiscountAmount());

        if (isset($expected['base_discount_tax_compensation_amount'])) {
            $this->assertEquals((float)$expected['base_discount_tax_compensation_amount'], (float)$invoice->getBaseDiscountTaxCompensationAmount());
        }
        $this->assertEquals((float)$order->getBaseDiscountTaxCompensationAmount(), (float)$invoice->getBaseDiscountTaxCompensationAmount());

        if (isset($expected['base_discount_tax_compensation_invoiced'])) {
            $this->assertEquals((float)$expected['base_discount_tax_compensation_invoiced'], (float)$invoice->getBaseDiscountTaxCompensationAmount());
        }
        $this->assertEquals((float)$order->getBaseDiscountTaxCompensationInvoiced(), (float)$invoice->getBaseDiscountTaxCompensationAmount());

        if (isset($expected['discount_tax_compensation_amount'])) {
            $this->assertEquals((float)$expected['discount_tax_compensation_amount'], (float)$invoice->getDiscountTaxCompensationAmount());
        }
        $this->assertEquals((float)$order->getDiscountTaxCompensationAmount(), (float)$invoice->getDiscountTaxCompensationAmount());

        if (isset($expected['discount_tax_compensation_invoiced'])) {
            $this->assertEquals((float)$expected['discount_tax_compensation_invoiced'], (float)$invoice->getDiscountTaxCompensationAmount());
        }
        $this->assertEquals((float)$order->getDiscountTaxCompensationInvoiced(), (float)$invoice->getDiscountTaxCompensationAmount());

        if (isset($expected['base_shipping_discount_tax_compensation_amnt'])) {
            $this->assertEquals((float)$expected['base_shipping_discount_tax_compensation_amnt'], (float)$invoice->getBaseShippingDiscountTaxCompensationAmnt());
        }
        $this->assertEquals((float)$order->getBaseShippingDiscountTaxCompensationAmnt(), (float)$invoice->getBaseShippingDiscountTaxCompensationAmnt());

        if (isset($expected['shipping_discount_tax_compensation_amount'])) {
            $this->assertEquals((float)$expected['shipping_discount_tax_compensation_amount'], (float)$invoice->getShippingDiscountTaxCompensationAmount());
        }
        $this->assertEquals((float)$order->getShippingDiscountTaxCompensationAmount(), (float)$invoice->getShippingDiscountTaxCompensationAmount());

        if (isset($expected['base_tax_amount'])) {
            $this->assertEquals((float)$expected['base_tax_amount'], (float)$invoice->getBaseTaxAmount());
        }
        $this->assertEquals((float)$order->getBaseTaxAmount(), (float)$invoice->getBaseTaxAmount());

        if (isset($expected['base_tax_invoiced'])) {
            $this->assertEquals((float)$expected['base_tax_invoiced'], (float)$invoice->getBaseTaxAmount());
        }
        $this->assertEquals((float)$order->getBaseTaxInvoiced(), (float)$invoice->getBaseTaxAmount());

        if (isset($expected['tax_amount'])) {
            $this->assertEquals((float)$expected['tax_amount'], (float)$invoice->getTaxAmount());
        }
        $this->assertEquals((float)$order->getTaxAmount(), (float)$invoice->getTaxAmount());

        if (isset($expected['tax_invoiced'])) {
            $this->assertEquals((float)$expected['tax_invoiced'], (float)$invoice->getTaxAmount());
        }
        $this->assertEquals((float)$order->getTaxInvoiced(), (float)$invoice->getTaxAmount());

        if (isset($expected['base_shipping_amount'])) {
            $this->assertEquals((float)$expected['base_shipping_amount'], (float)$invoice->getBaseShippingAmount());
        }
        $this->assertEquals((float)$order->getBaseShippingAmount(), (float)$invoice->getBaseShippingAmount());

        if (isset($expected['base_shipping_invoiced'])) {
            $this->assertEquals((float)$expected['base_shipping_invoiced'], (float)$invoice->getBaseShippingAmount());
        }
        $this->assertEquals((float)$order->getBaseShippingInvoiced(), (float)$invoice->getBaseShippingAmount());

        if (isset($expected['shipping_amount'])) {
            $this->assertEquals((float)$expected['shipping_amount'], (float)$invoice->getShippingAmount());
        }
        $this->assertEquals((float)$order->getShippingAmount(), (float)$invoice->getShippingAmount());

        if (isset($expected['shipping_invoiced'])) {
            $this->assertEquals((float)$expected['shipping_invoiced'], (float)$invoice->getShippingAmount());
        }
        $this->assertEquals((float)$order->getShippingInvoiced(), (float)$invoice->getShippingAmount());

        if (isset($expected['base_subtotal'])) {
            $this->assertEquals((float)$expected['base_subtotal'], (float)$invoice->getBaseSubtotal());
        }
        $this->assertEquals((float)$order->getBaseSubtotal(), (float)$invoice->getBaseSubtotal());

        if (isset($expected['base_subtotal_invoiced'])) {
            $this->assertEquals((float)$expected['base_subtotal_invoiced'], (float)$invoice->getBaseSubtotal());
        }
        $this->assertEquals((float)$order->getBaseSubtotalInvoiced(), (float)$invoice->getBaseSubtotal());

        if (isset($expected['subtotal'])) {
            $this->assertEquals((float)$expected['subtotal'], (float)$invoice->getSubtotal());
        }
        $this->assertEquals((float)$order->getSubtotal(), (float)$invoice->getSubtotal());

        if (isset($expected['subtotal_invoiced'])) {
            $this->assertEquals((float)$expected['subtotal_invoiced'], (float)$invoice->getSubtotal());
        }
        $this->assertEquals((float)$order->getSubtotalInvoiced(), (float)$invoice->getSubtotal());

        if (isset($expected['base_subtotal_incl_tax'])) {
            $this->assertEquals((float)$expected['base_subtotal_incl_tax'], (float)$invoice->getBaseSubtotalInclTax());
        }
        $this->assertEquals((float)$order->getBaseSubtotalInclTax(), (float)$invoice->getBaseSubtotalInclTax());

        if (isset($expected['subtotal_incl_tax'])) {
            $this->assertEquals((float)$expected['subtotal_incl_tax'], (float)$invoice->getSubtotalInclTax());
        }
        $this->assertEquals((float)$order->getSubtotalInclTax(), (float)$invoice->getSubtotalInclTax());

        if (isset($expected['base_shipping_incl_tax'])) {
            $this->assertEquals((float)$expected['base_shipping_incl_tax'], (float)$invoice->getBaseShippingInclTax());
        }
        $this->assertEquals((float)$order->getBaseShippingInclTax(), (float)$invoice->getBaseShippingInclTax());

        if (isset($expected['shipping_incl_tax'])) {
            $this->assertEquals((float)$expected['shipping_incl_tax'], (float)$invoice->getBaseShippingInclTax());
        }
        $this->assertEquals((float)$order->getShippingInclTax(), (float)$invoice->getShippingInclTax());

        if (isset($expected['base_grand_total'])) {
            $this->assertEquals((float)$expected['base_grand_total'], (float)$invoice->getBaseGrandTotal());
        }
        $this->assertEquals((float)$order->getBaseGrandTotal(), (float)$invoice->getBaseGrandTotal());

        if (isset($expected['grand_total'])) {
            $this->assertEquals((float)$expected['grand_total'], (float)$invoice->getGrandTotal());
        }
        $this->assertEquals((float)$order->getGrandTotal(), (float)$invoice->getGrandTotal());

        if (isset($expected['base_total_invoiced'])) {
            $this->assertEquals((float)$expected['base_total_invoiced'], (float)$invoice->getBaseGrandTotal());
        }
        $this->assertEquals((float)$order->getBaseTotalInvoiced(), (float)$invoice->getBaseGrandTotal());

        if (isset($expected['total_invoiced'])) {
            $this->assertEquals((float)$expected['total_invoiced'], (float)$invoice->getGrandTotal());
        }
        $this->assertEquals((float)$order->getTotalInvoiced(), (float)$invoice->getGrandTotal());

        if (isset($expected['base_total_paid'])) {
            $this->assertEquals((float)$expected['base_total_paid'], (float)$invoice->getBaseGrandTotal());
        }
        $this->assertEquals((float)$order->getBaseTotalPaid(), (float)$invoice->getBaseGrandTotal());

        if (isset($expected['grand_total'])) {
            $this->assertEquals((float)$expected['grand_total'], (float)$invoice->getGrandTotal());
        }
        $this->assertEquals((float)$order->getTotalPaid(), (float)$invoice->getGrandTotal());

        /** @var \Magento\Sales\Model\Order\Payment $payment */
        $payment = $order->getPayment();
        $this->assertEquals((float)$payment->getBaseAmountPaid(), (float)$order->getBaseTotalInvoiced());

        if (isset($expected['payment_base_amount_paid'])) {
            $this->assertEquals((float)$expected['payment_base_amount_paid'], (float)$invoice->getBaseGrandTotal());
        }
        $this->assertEquals((float)$payment->getBaseAmountPaid(), (float)$invoice->getBaseGrandTotal());

        if (isset($expected['payment_amount_paid'])) {
            $this->assertEquals((float)$expected['payment_amount_paid'], (float)$invoice->getGrandTotal());
        }
        $this->assertEquals((float)$payment->getAmountPaid(), (float)$invoice->getGrandTotal());
        $this->assertEquals((float)$payment->getAmountPaid(), (float)$order->getGrandTotal());

        $this->assertEquals((float)$payment->getBaseShippingCaptured(), (float)$order->getBaseShippingAmount());

        if (isset($expected['payment_base_shipping_amount'])) {
            $this->assertEquals((float)$expected['payment_base_shipping_amount'], (float)$invoice->getBaseShippingAmount());
        }

        if (isset($expected['payment_base_shipping_captured'])) {
            $this->assertEquals((float)$expected['payment_base_shipping_captured'], (float)$invoice->getBaseShippingAmount());
        }
        $this->assertEquals((float)$payment->getBaseShippingCaptured(), (float)$invoice->getBaseShippingAmount());
        $this->assertEquals((float)$payment->getBaseShippingCaptured(), (float)$order->getBaseShippingInclTax());
        $this->assertEquals((float)$payment->getBaseShippingCaptured(), (float)$invoice->getBaseShippingInclTax());
        $this->assertEquals((float)$payment->getShippingCaptured(), (float)$order->getShippingAmount());

        if (isset($expected['payment_shipping_amount'])) {
            $this->assertEquals((float)$expected['payment_shipping_amount'], (float)$invoice->getShippingAmount());
        }

        if (isset($expected['payment_shipping_captured'])) {
            $this->assertEquals((float)$expected['payment_shipping_captured'], (float)$invoice->getShippingAmount());
        }
        $this->assertEquals((float)$payment->getShippingCaptured(), (float)$invoice->getShippingAmount());
        $this->assertEquals((float)$payment->getShippingCaptured(), (float)$order->getShippingInclTax());
        $this->assertEquals((float)$payment->getShippingCaptured(), (float)$invoice->getShippingInclTax());

        $invoiceQty = 0;

        /** @var \Magento\Sales\Model\Order\Invoice\Item $item */
        foreach ($invoice->getAllItems() as $item) {
            if (!$item->getOrderItem()->getParentItemId()) {
                $invoiceQty += (float)$item->getQty();
            }
        }

        if (isset($expected['base_total_qty_ordered'])) {
            $this->assertEquals((float)$expected['base_total_qty_ordered'], $invoiceQty);
        }

        if ($order->getBaseTotalQtyOrdered()) {
            $this->assertEquals((float)$order->getBaseTotalQtyOrdered(), $invoiceQty);
        }

        if (isset($expected['total_qty_ordered'])) {
            $this->assertEquals((float)$expected['total_qty_ordered'], $invoiceQty);
        }
        $this->assertEquals((float)$order->getTotalQtyOrdered(), $invoiceQty);
    }
}
