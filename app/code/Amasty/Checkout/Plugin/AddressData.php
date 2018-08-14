<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Plugin;

class AddressData
{
    /**
     * @var \Amasty\Checkout\Helper\Address
     */
    protected $addressHelper;

    public function __construct(
        \Amasty\Checkout\Helper\Address $addressHelper
    ) {
        $this->addressHelper = $addressHelper;
    }

    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        foreach ([$addressInformation->getShippingAddress(), $addressInformation->getBillingAddress()] as $address) {
            $this->addressHelper->fillEmpty($address);
        }

        return [$cartId, $addressInformation];
    }
}
