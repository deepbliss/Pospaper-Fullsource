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
use Magento\Sales\Api\Data\OrderItemInterface;

class Invoice extends \MageModule\OrderImportExport\Model\Processor\AbstractProcessor implements
    \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
{
    /**
     * @var \Magento\Sales\Model\Service\InvoiceService
     */
    private $service;

    /**
     * @var \Magento\Sales\Api\InvoiceRepositoryInterface
     */
    private $repository;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * Invoice constructor.
     *
     * @param \Magento\Sales\Api\InvoiceManagementInterface $service
     * @param \Magento\Sales\Api\InvoiceRepositoryInterface $repository
     * @param \Magento\Sales\Api\OrderRepositoryInterface   $orderRepository
     * @param array                                         $excludedFields
     */
    public function __construct(
        \Magento\Sales\Api\InvoiceManagementInterface $service,
        \Magento\Sales\Api\InvoiceRepositoryInterface $repository,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        $excludedFields = []
    ) {
        parent::__construct($excludedFields);
        $this->service         = $service;
        $this->repository      = $repository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param array                                                             $data
     * @param \Magento\Sales\Api\Data\OrderInterface|\Magento\Sales\Model\Order $order
     *
     * @return $this|mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function process(array $data, \Magento\Sales\Api\Data\OrderInterface $order)
    {
        if ($this->getConfig()->getCreateInvoice() && !$order->getInvoiceCollection()->getSize()) {
            $this->removeExcludedFields($data);

            $qtys = [];

            $products = $data[ImporterInterface::KEY_PRODUCTS_ORDERED];

            /** @var \Magento\Sales\Api\Data\OrderItemInterface|\Magento\Sales\Model\Order\Item $item */
            foreach ($order->getAllItems() as $item) {
                $key       = $item->getData('key');
                $parentKey = $item->getData('parent_key');

                if ($parentKey && isset($products[$parentKey]['bundle_items'][$key][OrderItemInterface::QTY_INVOICED])) {
                    $qty = $products[$parentKey]['bundle_items'][$key][OrderItemInterface::QTY_INVOICED];
                } elseif (isset($products[$key][OrderItemInterface::QTY_INVOICED])) {
                    $qty = $products[$key][OrderItemInterface::QTY_INVOICED];
                } else {
                    $qty = $item->getQtyToInvoice();
                }

                $qty = (float)$qty;
                if (!$qty) {
                    continue;
                }

                $qtys[$item->getId()] = $qty;
            }

            if ($qtys) {
                /** @var \Magento\Sales\Model\Order\Invoice $invoice */
                $invoice = $this->service->prepareInvoice($order, $qtys);
                if ($invoice->getItems()) {
                    if (round($invoice->getGrandTotal(), 4) === round($order->getTotalPaid(), 4)) {
                        $invoice->setState($invoice::STATE_PAID);
                    }

                    $payment = $order->getPayment();
                    $transactionId = $payment->getCcTransId();
                    if (!$transactionId) {
                        $transactionId = $payment->getLastTransId();
                    }

                    $invoice->setTransactionId($transactionId);
                    $invoice->setSendEmail(false);
                    $invoice->register();
                    $invoice->pay();
                    $this->repository->save($invoice);
                    $this->orderRepository->save($order);
                }

                /** set this here for possible use in order classes */
                $order->setInvoice($invoice);
            }
        }

        return $this;
    }
}
