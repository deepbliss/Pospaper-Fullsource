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

class Shipping extends \MageModule\OrderImportExport\Model\Processor\AbstractProcessor implements
    \MageModule\OrderImportExport\Model\Processor\ProcessorInterface
{
    /**
     * @param array                                                                $data
     * @param \Magento\Sales\Api\Data\OrderInterface|\Magento\Framework\DataObject $order
     *
     * @return $this
     */
    public function process(array $data, \Magento\Sales\Api\Data\OrderInterface $order)
    {
        $this->removeExcludedFields($order);

        if ($order->getData('shipping_method')) {
            $shippingMethod = trim(strtolower($order->getData('shipping_method')));
            $shippingMethod = str_replace(' ', '_', $shippingMethod);
            $shippingMethod = preg_replace("/[^A-Za-z0-9_]/", '', $shippingMethod);
            $order->setData('shipping_method', $shippingMethod);
        } else {
            $order->setData('shipping_method', 'placeholder_method');
        }

        return $this;
    }
}
