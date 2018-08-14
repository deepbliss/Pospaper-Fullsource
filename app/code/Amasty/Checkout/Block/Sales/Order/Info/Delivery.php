<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */

namespace Amasty\Checkout\Block\Sales\Order\Info;

use Magento\Framework\View\Element\Template\Context;

class Delivery extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;
    /**
     * @var \Amasty\Checkout\Model\Delivery
     */
    protected $delivery;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    public function __construct(
        Context $context,
        \Magento\Framework\Registry $registry,
        \Amasty\Checkout\Model\Delivery $delivery,
        \Magento\Checkout\Model\Session $checkoutSession,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->delivery = $delivery;
        $this->checkoutSession = $checkoutSession;
    }

    protected function _getOrderId()
    {
        if ($orderId = $this->getData('order_id'))
            return $orderId;

        if ($this->registry->registry('current_order')) {
            return $this->registry->registry('current_order')->getId();
        }
        if ($this->registry->registry('current_invoice')) {
            return $this->registry->registry('current_invoice')->getOrderId();
        }
        if ($this->registry->registry('current_shipment')) {
            return $this->registry->registry('current_shipment')->getOrderId();
        }

        return false;
    }

    protected function _getQuoteId()
    {
        if ($orderId = $this->getData('quote_id'))
            return $orderId;

        if ($quoteId = $this->checkoutSession->getQuoteId()) {
            return $quoteId;
        }

        return false;
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('sales/order/info/delivery.phtml');
    }

    public function getDeliveryDateFields()
    {
        if ($quoteId = $this->_getQuoteId()) {
            $delivery = $this->delivery->findByQuoteId($quoteId);
        }
        else if ($orderId = $this->_getOrderId()) {
            $delivery = $this->delivery->findByOrderId($orderId);
        }
        else
            return false;

        if (!$delivery->getId())
            return false;

        return $this->getDeliveryFields($delivery);
    }

    public function getDeliveryFields($delivery)
    {
        $time = $delivery->getData('time');

        $fields = [
            [
                'label' => __('Delivery Date'),
                'value' => $this->_localeDate->formatDateTime(
                    $this->_localeDate->date(new \DateTime($delivery->getData('date'))),
                    \IntlDateFormatter::MEDIUM,
                    \IntlDateFormatter::NONE
                ),
            ],
            [
                'label' => __('Delivery Time'),
                'value' => $time . ':00 - ' . (($time) + 1) . ':00',
            ],
            [
                'label' => __('Delivery Comment'),
                'value' => $delivery->getData('comment'),
            ],
        ];

        return $fields;
    }
}
