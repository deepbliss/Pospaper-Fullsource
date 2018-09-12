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

use Magento\Sales\Api\Data\OrderInterface;

class Order extends \MageModule\OrderImportExport\Model\Processor\AbstractProcessor implements
    \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
{
    /**
     * @param array                                                             $data
     * @param \Magento\Sales\Api\Data\OrderInterface|\Magento\Sales\Model\Order $order
     *
     * @return $this
     */
    public function process(array $data, \Magento\Sales\Api\Data\OrderInterface $order)
    {
        $order->setSendEmail(false);
        $order->setCanSendNewEmailFlag(false);

        if ($this->getConfig()->getImportOrderNumber() && $order->getIncrementId()) {
            $this->prepareIncrementId($order);
        } else {
            $order->unsetData(OrderInterface::INCREMENT_ID);
        }

        $this->prepareData($order);

        return $this;
    }

    /**
     * Confirms that Increment ID can be used or unsets it from the order if it cannot
     *
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     */
    private function prepareIncrementId(\Magento\Sales\Api\Data\OrderInterface &$order)
    {
        $resource = $order->getResource();
        $adapter  = $resource->getConnection();

        $select = $adapter->select()->from($resource->getMainTable(), ['count' => 'COUNT(*)']);
        $select->where(OrderInterface::INCREMENT_ID . ' = ?', $order->getIncrementId());
        $result = $adapter->fetchOne($select);
        if ($result) {
            $order->unsetData(OrderInterface::INCREMENT_ID);
        }
    }

    /**
     * @param \Magento\Sales\Api\Data\OrderInterface|\Magento\Sales\Model\Order $order
     */
    private function prepareData(\Magento\Sales\Api\Data\OrderInterface &$order)
    {
        $this->removeExcludedFields($order);

        $items = $order->getAllItems();

        if ($order->getDiscountAmount() === null) {
            $amount = 0;

            /** @var \Magento\Sales\Api\Data\OrderItemInterface|\Magento\Sales\Model\Order\Item $item $item */
            foreach ($items as &$item) {
                $amount += (float)$item->getDiscountAmount();
            }

            $order->setDiscountAmount(0 - $amount);
        }

        if ($order->getSubtotal() === null) {
            $amount = 0;

            /** @var \Magento\Sales\Api\Data\OrderItemInterface|\Magento\Sales\Model\Order\Item $item $item */
            foreach ($items as &$item) {
                $amount += (float)$item->getRowTotal();
            }

            $order->setSubtotal($amount);
        }

        if ($order->getTaxAmount() === null) {
            $amount = 0;

            /** @var \Magento\Sales\Api\Data\OrderItemInterface|\Magento\Sales\Model\Order\Item $item $item */
            foreach ($items as &$item) {
                $amount += (float)$item->getTaxAmount();
            }

            $order->setTaxAmount($amount);
        }

        if ($order->getSubtotalInclTax() === null) {
            $amount = 0;

            /** @var \Magento\Sales\Api\Data\OrderItemInterface|\Magento\Sales\Model\Order\Item $item $item */
            foreach ($items as &$item) {
                $amount += (float)$item->getRowTotalInclTax();
            }

            $order->setSubtotalInclTax($amount);
        }

        if ($order->getWeight() === null) {
            $amount = 0;

            /** @var \Magento\Sales\Api\Data\OrderItemInterface|\Magento\Sales\Model\Order\Item $item $item */
            foreach ($items as &$item) {
                if (!$item->getIsVirtual()) {
                    $amount += (float)$item->getWeight();
                }
            }

            $order->setWeight($amount);
        }

        if ($order->getDiscountTaxCompensationAmount() === null) {
            $amount = 0;

            /** @var \Magento\Sales\Api\Data\OrderItemInterface|\Magento\Sales\Model\Order\Item $item $item */
            foreach ($items as &$item) {
                $amount += (float)$item->getDiscountTaxCompensationAmount();
            }

            $order->setDiscountTaxCompensationAmount($amount);
        }

        if ($order->getSubtotal() && ($order->getGrandTotal() === null)) {
            $order->setGrandTotal(
                $order->getSubtotal() +
                $order->getTaxAmount() +
                $order->getShippingAmount() +
                $order->getDiscountAmount()
            );
        }

        if ($order->getShippingAmount() === null) {
            $order->setShippingAmount(0);
        }

        if ($order->getShippingTaxAmount() === null) {
            $order->setShippingTaxAmount(0);
        }

        if ($order->getBaseDiscountAmount() === null) {
            $order->setBaseDiscountAmount($order->getDiscountAmount());
        }

        if ($order->getBaseDiscountTaxCompensationAmount() === null) {
            $order->setBaseDiscountTaxCompensationAmount($order->getDiscountTaxCompensationAmount());
        }

        if ($order->getBaseGrandTotal() === null) {
            $order->setBaseGrandTotal($order->getGrandTotal());
        }

        if ($order->getBaseShippingAmount() === null) {
            $order->setBaseShippingAmount($order->getShippingAmount());
        }

        if ($order->getBaseShippingDiscountAmount() === null) {
            $order->setBaseShippingDiscountAmount($order->getShippingDiscountAmount());
        }

        if ($order->getBaseShippingDiscountTaxCompensationAmnt() === null) {
            $order->setBaseShippingDiscountTaxCompensationAmnt($order->getShippingDiscountTaxCompensationAmount());
        }

        if ($order->getBaseShippingInclTax() === null) {
            $order->setBaseShippingInclTax($order->getShippingInclTax());
        }

        if ($order->getBaseShippingTaxAmount() === null) {
            $order->setBaseShippingTaxAmount($order->getShippingTaxAmount());
        }

        if ($order->getBaseSubtotal() === null) {
            $order->setBaseSubtotal($order->getSubtotal());
        }

        if ($order->getBaseSubtotalInclTax() === null) {
            $order->setBaseSubtotalInclTax($order->getSubtotalInclTax());
        }

        if ($order->getBaseTaxAmount() === null) {
            $order->setBaseTaxAmount($order->getTaxAmount());
        }

        if ($order->getBaseTotalDue() === null) {
            $order->setBaseTotalDue($order->getTotalDue());
        }

        if ($order->getBaseTotalPaid() === null) {
            $order->setBaseTotalPaid($order->getTotalPaid());
        }

        if ($order->getBaseTotalQtyOrdered() === null) {
            $order->setBaseTotalQtyOrdered($order->getTotalQtyOrdered());
        }

        /** @var \Magento\Customer\Api\Data\CustomerInterface|\Magento\Customer\Model\Customer $customer */
        $customer = $order->getCustomer();

        if ($order->getCustomerDob() === null) {
            $order->setCustomerDob($customer->getDob());
        }

        if ($order->getCustomerFirstname() === null) {
            $order->setCustomerFirstname($customer->getFirstname());
        }

        if ($order->getCustomerLastname() === null) {
            $order->setCustomerLastname($customer->getLastname());
        }

        if ($order->getCustomerMiddlename() === null) {
            $order->setCustomerMiddlename($customer->getMiddlename());
        }

        if ($order->getCustomerPrefix() === null) {
            $order->setCustomerPrefix($customer->getPrefix());
        }

        if ($order->getCustomerSuffix() === null) {
            $order->setCustomerSuffix($customer->getSuffix());
        }

        if ($order->getCustomerTaxvat() === null) {
            $order->setCustomerTaxvat($customer->getTaxvat());
        }

        if ($order->getStoreName() === null) {
            $order->setStoreName($order->getStore()->getName());
        }

        $store = $order->getStore();
        if (!$order->getStoreCurrencyCode()) {
            $order->setStoreCurrencyCode($store->getCurrentCurrencyCode());
        }

        if (!$order->getOrderCurrencyCode()) {
            $order->setOrderCurrencyCode($order->getStoreCurrencyCode());
        }

        if (!$order->getBaseCurrencyCode()) {
            $order->setBaseCurrencyCode($store->getBaseCurrencyCode());
        }

        if (!$order->hasData(OrderInterface::STATE)) {
            $order->setData(OrderInterface::STATE, $order::STATE_PROCESSING);
        }

        if (!$order->hasData(OrderInterface::STATUS)) {
            $order->setData(OrderInterface::STATUS, $order::STATE_PROCESSING);
        }
    }
}
