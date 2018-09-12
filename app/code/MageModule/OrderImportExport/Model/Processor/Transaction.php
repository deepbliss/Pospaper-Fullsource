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

class Transaction extends \MageModule\OrderImportExport\Model\Processor\AbstractProcessor implements
    \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
{
    /**
     * @var \Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface
     */
    private $transactionBuilder;

    /**
     * @var \Magento\Sales\Api\TransactionRepositoryInterface
     */
    private $transactionRepository;

    /**
     * Transaction constructor.
     *
     * @param \Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface $transactionBuilder
     * @param \Magento\Sales\Api\TransactionRepositoryInterface               $transactionRepository
     * @param array                                                           $excludedFields
     */
    public function __construct(
        \Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface $transactionBuilder,
        \Magento\Sales\Api\TransactionRepositoryInterface $transactionRepository,
        $excludedFields = []
    ) {
        parent::__construct($excludedFields);
        $this->transactionBuilder    = $transactionBuilder;
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @param array                                                             $data
     * @param \Magento\Sales\Api\Data\OrderInterface|\Magento\Sales\Model\Order $order
     *
     * @return $this
     */
    public function process(array $data, \Magento\Sales\Api\Data\OrderInterface $order)
    {
        /** @var \Magento\Sales\Api\Data\OrderPaymentInterface|\Magento\Sales\Model\Order\Payment $payment */
        $payment = $order->getPayment();
        if ($payment && $order->getInvoice()) {
            $transactionBuilder = $this->transactionBuilder;
            $transactionBuilder->reset();
            $transactionBuilder->setOrder($order);
            $transactionBuilder->setPayment($payment);
            $transactionBuilder->setSalesDocument($order->getInvoice());

            if ($payment->getTransactionId()) {
                $transactionBuilder->setTransactionId($payment->getTransactionId());
            } else {
                $transactionBuilder->setTransactionId($payment->getLastTransId());
            }

            $transaction = $transactionBuilder->build(
                \Magento\Sales\Model\Order\Payment\Transaction::TYPE_CAPTURE
            );

            if ($transaction) {
                $this->transactionRepository->save($transaction);
            }
        }

        return $this;
    }
}
