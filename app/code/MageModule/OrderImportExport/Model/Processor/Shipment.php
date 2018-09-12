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

class Shipment extends \MageModule\OrderImportExport\Model\Processor\AbstractProcessor implements
    \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
{
    /**
     * @var \Magento\Sales\Model\Order\ShipmentFactory
     */
    private $shipmentFactory;

    /**
     * @var \Magento\Sales\Api\ShipmentRepositoryInterface
     */
    private $repository;

    /**
     * Shipment constructor.
     *
     * @param \Magento\Sales\Model\Order\ShipmentFactory      $shipmentFactory
     * @param \Magento\Sales\Api\ShipmentRepositoryInterface  $repository
     * @param array                                           $excludedFields
     */
    public function __construct(
        \Magento\Sales\Model\Order\ShipmentFactory $shipmentFactory,
        \Magento\Sales\Api\ShipmentRepositoryInterface $repository,
        $excludedFields = []
    ) {
        parent::__construct($excludedFields);
        $this->shipmentFactory = $shipmentFactory;
        $this->repository          = $repository;
    }

    /**
     * @param array                                  $data
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     *
     * @return $this|mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function process(array $data, \Magento\Sales\Api\Data\OrderInterface $order)
    {
        if ($this->getConfig()->getCreateShipment()) {
            $this->removeExcludedFields($data);

            $qtys = [];

            $products = $data[ImporterInterface::KEY_PRODUCTS_ORDERED];

            /** @var \Magento\Sales\Api\Data\OrderItemInterface|\Magento\Sales\Model\Order\Item $item */
            foreach ($order->getAllItems() as $item) {
                $key       = $item->getData('key');
                $parentKey = $item->getData('parent_key');

                if ($parentKey && isset($products[$parentKey]['bundle_items'][$key][OrderItemInterface::QTY_SHIPPED])) {
                    $qty = $products[$parentKey]['bundle_items'][$key][OrderItemInterface::QTY_SHIPPED];
                } elseif (isset($products[$key][OrderItemInterface::QTY_SHIPPED])) {
                    $qty = $products[$key][OrderItemInterface::QTY_SHIPPED];
                } else {
                    $qty = $item->getQtyToShip();
                }

                $qty = (float)$qty;
                if (!$qty) {
                    continue;
                }

                $qtys[$item->getId()] = $qty;
            }

            if ($qtys) {
                /** @var \Magento\Sales\Api\Data\ShipmentInterface|\Magento\Sales\Model\Order\Shipment $shipment */
                $shipment = $this->shipmentFactory->create($order, $qtys);
                $shipment->setSendEmail(false);
                $shipment->register();
                $this->repository->save($shipment);
            }
        }

        return $this;
    }
}
