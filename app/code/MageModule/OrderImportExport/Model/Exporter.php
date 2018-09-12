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

namespace MageModule\OrderImportExport\Model;

class Exporter implements \MageModule\OrderImportExport\Api\ExporterInterface
{
    /**
     * @var \MageModule\Core\Helper\Data
     */
    private $helper;

    /**
     * @var \MageModule\Core\Helper\File
     */
    private $fileHelper;

    /**
     * @var \MageModule\Core\Helper\Db\Collection
     */
    private $collectionHelper;

    /**
     * @var \MageModule\OrderImportExport\Api\Data\ExportConfigInterface
     */
    private $config;

    /**
     * @var \MageModule\Core\Model\Data\Formatter
     */
    private $formatter;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    private $file;

    /**
     * @var \MageModule\Core\Framework\File\Csv
     */
    private $csv;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $frontColumns;

    /**
     * @var array
     */
    private $data = [];

    /**
     * Exporter constructor.
     *
     * @param \MageModule\Core\Helper\Data                                 $helper
     * @param \MageModule\Core\Helper\File                                 $fileHelper
     * @param \MageModule\Core\Helper\Db\Collection                        $collectionHelper
     * @param \MageModule\Core\Framework\File\CsvFactory                   $csvFactory
     * @param \MageModule\Core\Model\Data\Formatter                        $formatter
     * @param \MageModule\OrderImportExport\Api\Data\ExportConfigInterface $config
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory   $collectionFactory
     * @param \Magento\Framework\Filesystem\Io\File                        $file
     * @param \Psr\Log\LoggerInterface                                     $logger
     * @param array                                                        $frontColumns
     */
    public function __construct(
        \MageModule\Core\Helper\Data $helper,
        \MageModule\Core\Helper\File $fileHelper,
        \MageModule\Core\Helper\Db\Collection $collectionHelper,
        \MageModule\Core\Framework\File\CsvFactory $csvFactory,
        \MageModule\Core\Model\Data\Formatter $formatter,
        \MageModule\OrderImportExport\Api\Data\ExportConfigInterface $config,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $collectionFactory,
        \Magento\Framework\Filesystem\Io\File $file,
        \Psr\Log\LoggerInterface $logger,
        $frontColumns = []
    ) {
        $this->helper            = $helper;
        $this->fileHelper        = $fileHelper;
        $this->collectionHelper  = $collectionHelper;
        $this->csv               = $csvFactory->create();
        $this->formatter         = $formatter;
        $this->config            = $config;
        $this->collectionFactory = $collectionFactory;
        $this->file              = $file;
        $this->logger            = $logger;
        $this->frontColumns      = $frontColumns;
    }

    /**
     * @param bool $forceReload
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getData($forceReload = false)
    {
        if (!$this->data || $forceReload) {
            /** @var \Magento\Sales\Model\ResourceModel\Order\Collection $collection */
            $collection = $this->collectionFactory->create();

            $filters = [];
            if ($this->config->getFrom()) {
                $filters['from'] = $this->collectionHelper->getFromDateFilter($this->config->getFrom());
            }

            if ($this->config->getTo()) {
                $filters['to'] = $this->collectionHelper->getToDateFilter($this->config->getTo());
            }

            if ($filters) {
                $collection->addAttributeToFilter('created_at', $filters);
            }

            $array = [];

            /** @var \Magento\Sales\Model\Order $order */
            foreach ($collection as $order) {
                $order->setStoreCode($order->getStore()->getCode());
                $order->getStatusHistories();
                $billingAddress = $order->getBillingAddress();
                if ($billingAddress) {
                    $this->helper->addPrefix('billing_', $billingAddress);
                    $order->addData($billingAddress->getData());
                }

                $shippingAddress = $order->getShippingAddress();
                if ($shippingAddress) {
                    $this->helper->addPrefix('shipping_', $shippingAddress);
                    $order->addData($shippingAddress->getData());
                }

                $payment = $order->getPayment();
                if ($payment) {
                    $paymentData    = $payment->getData();
                    $additionalInfo = $payment->getAdditionalInformation();
                    if (is_array($additionalInfo)) {
                        foreach ($additionalInfo as $key => $value) {
                            $paymentData[$key] = $value;
                        }
                    }
                    ksort($paymentData);
                    $this->helper->addPrefix('payment_', $paymentData);
                    $order->addData($paymentData);
                }

                $items = [];

                foreach ($order->getAllItems() as $item) {
                    $options = $item->getProductOptions();
                    if (is_array($options)) {
                        $item->setData('product_options', $options);
                    }

                    if (!$item->getParentItemId()) {
                        $items[$item->getId()] = $item;
                    } else {
                        if (isset($items[$item->getParentItemId()])) {
                            $bundleItems = $items[$item->getParentItemId()]->getBundleItems();
                            if (!is_array($items[$item->getParentItemId()]->getBundleItems())) {
                                $bundleItems = [];
                            }

                            $bundleItems[] = $item;
                            $items[$item->getParentItemId()]->setBundleItems($bundleItems);
                        }
                    }
                }

                $order->setItems($items);

                $orderData = $order->getData();
                ksort($orderData);

                $newOrderData = [];
                foreach ($this->frontColumns as $key) {
                    if (isset($orderData[$key])) {
                        $newOrderData[$key] = $orderData[$key];
                        unset($orderData[$key]);
                    }
                }

                $newOrderData += $orderData;

                $order->setData($newOrderData);

                $array[] = $this->formatter->format($order);
            }

            $this->helper->equalizeArrayKeys($array);
            $this->helper->addHeadersRowToArray($array);

            $this->data = $array;
        }

        return $this->data;
    }

    /**
     * Gathers data and exports to csv file, returns path of csv file
     *
     * @param bool $forceReload
     *
     * @return string
     * @throws \Exception
     */
    public function export($forceReload = false)
    {
        $filepath = $this->getFilepath(true);
        $this->file->checkAndCreateFolder(
            $this->fileHelper->getDirname($filepath)
        );

        $data = $this->getData($forceReload);
        $this->csv->setEnclosure($this->config->getEnclosure());
        $this->csv->setDelimiter($this->config->getDelimiter());
        $this->csv->saveData($filepath, $data);

        return $filepath;
    }

    /**
     * Directory to which the file will be exported
     *
     * @param bool $absolute
     *
     * @return string
     */
    public function getDirectory($absolute = true)
    {
        $directory = $this->config->getDirectory();
        if ($absolute === true) {
            $directory = $this->fileHelper->getAbsolutePath($directory);
        }

        return $directory;
    }

    /**
     * @param bool $absolute
     *
     * @return string
     */
    public function getFilepath($absolute = true)
    {
        return $this->fileHelper->assembleFilepath(
            [
                $this->getDirectory($absolute),
                $this->config->getFilename()
            ],
            $absolute
        );
    }
}
