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

class Payment extends \MageModule\OrderImportExport\Model\Processor\AbstractProcessor implements
    \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
{
    /**
     * @var \Magento\Sales\Api\Data\OrderPaymentInterfaceFactory
     */
    private $orderPaymentFactory;

    /**
     * Payment constructor.
     *
     * @param \Magento\Sales\Api\Data\OrderPaymentInterfaceFactory $orderPaymentFactory
     * @param array                                                $excludedFields
     */
    public function __construct(
        \Magento\Sales\Api\Data\OrderPaymentInterfaceFactory $orderPaymentFactory,
        $excludedFields = []
    ) {
        parent::__construct($excludedFields);
        $this->orderPaymentFactory = $orderPaymentFactory;
    }

    /**
     * @param array                                  $data
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     *
     * @return $this|mixed
     */
    public function process(array $data, \Magento\Sales\Api\Data\OrderInterface $order)
    {
        $paymentData = [];
        foreach ($data as $key => &$value) {
            if (strpos($key, 'payment_', 0) !== false) {
                $paymentData[str_replace('payment_', '', $key)] = $value;
            }
        }

        $this->removeExcludedFields($paymentData);
        ksort($paymentData);

        /** @var \Magento\Sales\Api\Data\OrderPaymentInterface|\Magento\Sales\Model\Order\Payment $payment */
        $payment = $this->orderPaymentFactory->create();
        $payment->addData($paymentData);
        $payment->setData($payment::ADDITIONAL_INFORMATION, $paymentData);
        $order->setPayment($payment);

        return $this;
    }
}
