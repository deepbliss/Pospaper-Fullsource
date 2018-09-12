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

use MageModule\Core\Exception\ValidatorException;
use MageModule\Core\Exception\FieldValidatorException;
use MageModule\OrderImportExport\Exception\ImportException;
use MageModule\OrderImportExport\Exception\StatusHistoryException;
use MageModule\OrderImportExport\Exception\StoreException;
use MageModule\OrderImportExport\Exception\CustomerException;
use MageModule\OrderImportExport\Exception\AddressException;
use MageModule\OrderImportExport\Exception\OrderItemException;
use MageModule\OrderImportExport\Exception\ShippingException;
use MageModule\OrderImportExport\Exception\PaymentException;
use MageModule\OrderImportExport\Exception\TransactionException;
use MageModule\OrderImportExport\Exception\OrderException;
use MageModule\OrderImportExport\Exception\InvoiceException;
use MageModule\OrderImportExport\Exception\ShipmentException;
use MageModule\OrderImportExport\Exception\CreditMemoException;

class Importer implements \MageModule\OrderImportExport\Api\ImporterInterface
{
    /**
     * @var \MageModule\Core\Helper\Data
     */
    private $helper;

    /**
     * @var \MageModule\Core\Model\Data\Mapper
     */
    private $mapper;

    /**
     * @var \MageModule\Core\Model\Data\Sanitizer
     */
    private $sanitizer;

    /**
     * @var \MageModule\Core\Model\Data\Validator
     */
    private $orderDataValidator;

    /**
     * @var \MageModule\Core\Model\Data\Validator
     */
    private $orderItemDataValidator;

    /**
     * @var \MageModule\OrderImportExport\Model\Parser\ParserInterface
     */
    private $orderItemParser;

    /**
     * @var \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
     */
    private $storeProcessor;

    /**
     * @var \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
     */
    private $customerProcessor;

    /**
     * @var \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
     */
    private $billingAddressProcessor;

    /**
     * @var \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
     */
    private $shippingAddressProcessor;

    /**
     * @var \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
     */
    private $paymentProcessor;

    /**
     * @var \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
     */
    private $transactionProcessor;

    /**
     * @var \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
     */
    private $shippingProcessor;

    /**
     * @var \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
     */
    private $itemProcessor;

    /**
     * @var \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
     */
    private $statusHistoryProcessor;

    /**
     * @var \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
     */
    private $orderProcessor;

    /**
     * @var \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
     */
    private $invoiceProcessor;

    /**
     * @var \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
     */
    private $shipmentProcessor;

    /**
     * @var \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
     */
    private $creditMemoProcessor;

    /**
     * @var \Magento\Sales\Api\Data\OrderInterfaceFactory|\Magento\Sales\Model\OrderFactory
     */
    private $orderFactory;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var array
     */
    private $excludedFields;

    /**
     * Importer constructor.
     *
     * @param \MageModule\Core\Helper\Data                                     $helper
     * @param \MageModule\Core\Model\Data\Mapper                               $mapper
     * @param \MageModule\Core\Model\Data\Sanitizer                            $sanitizer
     * @param \MageModule\Core\Model\Data\Validator                            $orderDataValidator
     * @param \MageModule\Core\Model\Data\Validator                            $orderItemDataValidator
     * @param \MageModule\OrderImportExport\Api\Data\ImportConfigInterface     $config
     * @param \MageModule\OrderImportExport\Model\Parser\ParserInterface       $orderItemParser
     * @param \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $storeProcessor
     * @param \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $customerProcessor
     * @param \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $billingAddressProcessor
     * @param \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $shippingAddressProcessor
     * @param \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $paymentProcessor
     * @param \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $transactionProcessor
     * @param \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $shippingProcessor
     * @param \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $itemProcessor
     * @param \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $statusHistoryProcessor
     * @param \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $orderProcessor
     * @param \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $invoiceProcessor
     * @param \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $shipmentProcessor
     * @param \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $creditMemoProcessor
     * @param \Magento\Sales\Api\Data\OrderInterfaceFactory                    $orderFactory
     * @param \Magento\Sales\Api\OrderRepositoryInterface                      $orderRepository
     * @param array                                                            $excludedFields
     */
    public function __construct(
        \MageModule\Core\Helper\Data $helper,
        \MageModule\Core\Model\Data\Mapper $mapper,
        \MageModule\Core\Model\Data\Sanitizer $sanitizer,
        \MageModule\Core\Model\Data\Validator $orderDataValidator,
        \MageModule\Core\Model\Data\Validator $orderItemDataValidator,
        \MageModule\OrderImportExport\Api\Data\ImportConfigInterface $config,
        \MageModule\OrderImportExport\Model\Parser\ParserInterface $orderItemParser,
        \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $storeProcessor,
        \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $customerProcessor,
        \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $billingAddressProcessor,
        \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $shippingAddressProcessor,
        \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $paymentProcessor,
        \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $transactionProcessor,
        \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $shippingProcessor,
        \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $itemProcessor,
        \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $statusHistoryProcessor,
        \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $orderProcessor,
        \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $invoiceProcessor,
        \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $shipmentProcessor,
        \MageModule\OrderImportExport\Model\Processor\ProcessorInterface $creditMemoProcessor,
        \Magento\Sales\Api\Data\OrderInterfaceFactory $orderFactory,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        array $excludedFields = []
    ) {
        $this->helper                   = $helper;
        $this->mapper                   = $mapper;
        $this->sanitizer                = $sanitizer;
        $this->orderDataValidator       = $orderDataValidator;
        $this->orderItemDataValidator   = $orderItemDataValidator;
        $this->orderItemParser          = $orderItemParser;
        $this->storeProcessor           = $storeProcessor->setConfig($config);
        $this->customerProcessor        = $customerProcessor->setConfig($config);
        $this->billingAddressProcessor  = $billingAddressProcessor->setConfig($config);
        $this->shippingAddressProcessor = $shippingAddressProcessor->setConfig($config);
        $this->paymentProcessor         = $paymentProcessor->setConfig($config);
        $this->transactionProcessor     = $transactionProcessor->setConfig($config);
        $this->shippingProcessor        = $shippingProcessor->setConfig($config);
        $this->itemProcessor            = $itemProcessor->setConfig($config);
        $this->statusHistoryProcessor   = $statusHistoryProcessor->setConfig($config);
        $this->orderProcessor           = $orderProcessor->setConfig($config);
        $this->invoiceProcessor         = $invoiceProcessor->setConfig($config);
        $this->shipmentProcessor        = $shipmentProcessor->setConfig($config);
        $this->creditMemoProcessor      = $creditMemoProcessor->setConfig($config);
        $this->orderFactory             = $orderFactory;
        $this->orderRepository          = $orderRepository;
        $this->excludedFields           = array_keys($excludedFields);
    }

    /**
     * @param array $data - Each $data array represents a single order
     *
     * @return \Magento\Sales\Api\Data\OrderInterface|\Magento\Sales\Model\Order
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
     */
    public function import(array $data)
    {
        $this->helper->removeElements($data, $this->excludedFields);
        $this->helper->nullifyEmpty($data);
        $this->mapper->map($data);
        $this->sanitizer->sanitize($data);

        $validatorResults = $this->orderDataValidator->validate($data);
        if (is_array($validatorResults)) {
            throw new ValidatorException($validatorResults);
        }

        $data[self::KEY_PRODUCTS_ORDERED] = $this->orderItemParser->parse($data[self::KEY_PRODUCTS_ORDERED]);

        /** validate products_ordered column */
        foreach ($data[self::KEY_PRODUCTS_ORDERED] as $item) {
            $validatorResults = $this->orderItemDataValidator->validate($item);
            if (is_array($validatorResults)) {
                throw new FieldValidatorException(self::KEY_PRODUCTS_ORDERED, $validatorResults);
            }
        }

        /** @var  \Magento\Sales\Api\Data\OrderInterface|\Magento\Framework\DataObject $order */
        $order = $this->orderFactory->create();
        $order->addData($data);

        try {
            $this->storeProcessor->process($data, $order);
        } catch (\Exception $e) {
            throw new StoreException(
                ImportException::IMPORT_STATUS_NO,
                __($e->getMessage())
            );
        }

        try {
            $this->customerProcessor->process($data, $order);
        } catch (\Exception $e) {
            throw new CustomerException(
                ImportException::IMPORT_STATUS_NO,
                __($e->getMessage())
            );
        }

        try {
            $this->itemProcessor->process($data, $order);
        } catch (\Exception $e) {
            throw new OrderItemException(
                ImportException::IMPORT_STATUS_NO,
                __($e->getMessage())
            );
        }

        try {
            $this->billingAddressProcessor->process($data, $order);
            if ($order->getIsNotVirtual()) {
                $this->shippingAddressProcessor->process($data, $order);
            }
        } catch (\Exception $e) {
            throw new AddressException(
                ImportException::IMPORT_STATUS_NO,
                __($e->getMessage())
            );
        }

        try {
            $this->shippingProcessor->process($data, $order);
        } catch (\Exception $e) {
            throw new ShippingException(
                ImportException::IMPORT_STATUS_NO,
                __($e->getMessage())
            );
        }

        try {
            $this->paymentProcessor->process($data, $order);
        } catch (\Exception $e) {
            throw new PaymentException(
                ImportException::IMPORT_STATUS_NO,
                __($e->getMessage())
            );
        }

        try {
            $this->statusHistoryProcessor->process($data, $order);
        } catch (\Exception $e) {
            throw new StatusHistoryException(
                ImportException::IMPORT_STATUS_NO,
                __($e->getMessage())
            );
        }

        try {
            $this->orderProcessor->process($data, $order);
            $this->orderRepository->save($order);
        } catch (\Exception $e) {
            throw new OrderException(
                ImportException::IMPORT_STATUS_NO,
                __($e->getMessage())
            );
        }

        try {
            $this->invoiceProcessor->process($data, $order);
        } catch (\Exception $e) {
            throw new InvoiceException(
                ImportException::IMPORT_STATUS_YES,
                __($e->getMessage())
            );
        }

        try {
            $this->transactionProcessor->process($data, $order);
        } catch (\Exception $e) {
            throw new TransactionException(
                ImportException::IMPORT_STATUS_YES,
                __($e->getMessage())
            );
        }

        try {
            $this->shipmentProcessor->process($data, $order);
        } catch (\Exception $e) {
            throw new ShipmentException(
                ImportException::IMPORT_STATUS_YES,
                __($e->getMessage())
            );
        }

        try {
            $this->creditMemoProcessor->process($data, $order);
        } catch (\Exception $e) {
            throw new CreditMemoException(
                ImportException::IMPORT_STATUS_YES,
                __($e->getMessage())
            );
        }

        try {
            $this->orderRepository->save($order);
        } catch (\Exception $e) {
            throw new OrderException(
                ImportException::IMPORT_STATUS_NO,
                __($e->getMessage())
            );
        }

        return $order;
    }
}
