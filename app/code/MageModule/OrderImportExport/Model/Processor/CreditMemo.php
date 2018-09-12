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
 * @author        MageModule, LLC admin@magemodule.com
 * @copyright     2018 MageModule, LLC
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\OrderImportExport\Model\Processor;

use MageModule\OrderImportExport\Api\ImporterInterface;
use Magento\Sales\Api\Data\CreditmemoInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;

class CreditMemo extends \MageModule\OrderImportExport\Model\Processor\AbstractProcessor implements
    \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
{
    /**
     * @var \Magento\Sales\Model\Order\CreditmemoFactory
     */
    private $creditmemoFactory;

    /**
     * @var \Magento\Sales\Model\Service\CreditmemoService
     */
    private $service;

    /**
     * CreditMemo constructor.
     *
     * @param \Magento\Sales\Model\Order\CreditmemoFactory     $creditmemoFactory
     * @param \Magento\Sales\Api\CreditmemoManagementInterface $service
     * @param array                                            $excludedFields
     */
    public function __construct(
        \Magento\Sales\Model\Order\CreditmemoFactory $creditmemoFactory,
        \Magento\Sales\Api\CreditmemoManagementInterface $service,
        $excludedFields = []
    ) {
        parent::__construct($excludedFields);
        $this->creditmemoFactory = $creditmemoFactory;
        $this->service           = $service;
    }

    /**
     * @param array                                  $data
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     *
     * @return $this
     */
    public function process(array $data, \Magento\Sales\Api\Data\OrderInterface $order)
    {
        $creditmemoData = [];

        $keys = [
            CreditmemoInterface::BASE_TAX_AMOUNT                       => OrderInterface::BASE_TAX_REFUNDED,
            CreditmemoInterface::TAX_AMOUNT                            => OrderInterface::TAX_REFUNDED,
            CreditmemoInterface::BASE_DISCOUNT_AMOUNT                  => OrderInterface::BASE_DISCOUNT_REFUNDED,
            CreditmemoInterface::DISCOUNT_AMOUNT                       => OrderInterface::DISCOUNT_REFUNDED,
            CreditmemoInterface::BASE_SHIPPING_TAX_AMOUNT              => OrderInterface::BASE_SHIPPING_TAX_REFUNDED,
            CreditmemoInterface::SHIPPING_TAX_AMOUNT                   => OrderInterface::SHIPPING_TAX_REFUNDED,
            CreditmemoInterface::BASE_SHIPPING_AMOUNT                  => OrderInterface::BASE_SHIPPING_REFUNDED,
            CreditmemoInterface::SHIPPING_AMOUNT                       => OrderInterface::SHIPPING_REFUNDED,
            CreditmemoInterface::BASE_DISCOUNT_TAX_COMPENSATION_AMOUNT => OrderInterface::BASE_DISCOUNT_TAX_COMPENSATION_REFUNDED,
            CreditmemoInterface::DISCOUNT_TAX_COMPENSATION_AMOUNT      => OrderInterface::DISCOUNT_TAX_COMPENSATION_REFUNDED,
            CreditmemoInterface::BASE_ADJUSTMENT_NEGATIVE              => OrderInterface::BASE_ADJUSTMENT_NEGATIVE,
            CreditmemoInterface::ADJUSTMENT_NEGATIVE                   => OrderInterface::ADJUSTMENT_NEGATIVE,
            CreditmemoInterface::BASE_ADJUSTMENT_POSITIVE              => OrderInterface::BASE_ADJUSTMENT_POSITIVE,
            CreditmemoInterface::ADJUSTMENT_POSITIVE                   => OrderInterface::ADJUSTMENT_POSITIVE,
            CreditmemoInterface::SUBTOTAL                              => OrderInterface::BASE_SUBTOTAL_REFUNDED,
            CreditmemoInterface::SUBTOTAL                              => OrderInterface::SUBTOTAL_REFUNDED,
            CreditmemoInterface::BASE_GRAND_TOTAL                      => OrderInterface::BASE_TOTAL_REFUNDED,
            CreditmemoInterface::GRAND_TOTAL                           => OrderInterface::TOTAL_REFUNDED
        ];

        if ($this->getConfig()->getCreateCreditMemo() &&
            !$order->getCreditmemosCollection()->getSize() &&
            $order->getInvoice()
        ) {
            $this->removeExcludedFields($data);
            $products = $data[ImporterInterface::KEY_PRODUCTS_ORDERED];

            /** @var \Magento\Sales\Api\Data\OrderItemInterface|\Magento\Sales\Model\Order\Item $item */
            foreach ($order->getAllItems() as $item) {
                $key       = $item->getData('key');
                $parentKey = $item->getData('parent_key');

                if ($parentKey && isset($products[$parentKey]['bundle_items'][$key][OrderItemInterface::QTY_REFUNDED])) {
                    $qty = $products[$parentKey]['bundle_items'][$key][OrderItemInterface::QTY_REFUNDED];
                } elseif (isset($products[$key][OrderItemInterface::QTY_REFUNDED])) {
                    $qty = $products[$key][OrderItemInterface::QTY_REFUNDED];
                } else {
                    $qty = $item->getQtyToRefund();
                }

                $qty = (float)$qty;
                if (!$qty) {
                    continue;
                }

                $creditmemoData['qtys'][$item->getId()] = $qty;
            }

            foreach ($keys as $creditmemoKey => $orderKey) {
                if (isset($data[$orderKey])) {
                    $creditmemoData[$creditmemoKey] = (float)$data[$orderKey];
                }
            }
        }

        if ($creditmemoData) {
            /** @var \Magento\Sales\Api\Data\CreditmemoInterface|\Magento\Sales\Model\Order\Creditmemo $creditmemo */
            $creditmemo = $this->creditmemoFactory->createByOrder($order, $creditmemoData);
            $creditmemo->setState($creditmemo::STATE_REFUNDED);
            $creditmemo->setSendEmail(false);

            /** re-apply the import data to ensure that the collectTotals method doesn't overpower imported data*/
            foreach ($keys as $creditmemoKey => $orderKey) {
                if (isset($data[$orderKey])) {
                    $creditmemo->setData($creditmemoKey, (float)$data[$orderKey]);
                }
            }

            $this->service->refund($creditmemo);
        }

        return $this;
    }
}
