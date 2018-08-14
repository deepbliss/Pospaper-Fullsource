<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Checkout
 */


namespace Amasty\Checkout\Model;

use Amasty\Checkout\Api\PaymentInformationManagementInterface;

class PaymentInformationManagement extends \Magento\Checkout\Model\PaymentInformationManagement
    implements PaymentInformationManagementInterface
{
    /**
     * @var \Amasty\Checkout\Helper\CheckoutData
     */
    protected $checkoutDataHelper;

    public function __construct(
        \Magento\Quote\Api\BillingAddressManagementInterface $billingAddressManagement,
        \Magento\Quote\Api\PaymentMethodManagementInterface $paymentMethodManagement,
        \Magento\Quote\Api\CartManagementInterface $cartManagement,
        \Magento\Checkout\Model\PaymentDetailsFactory $paymentDetailsFactory,
        \Magento\Quote\Api\CartTotalRepositoryInterface $cartTotalsRepository,

        \Amasty\Checkout\Helper\CheckoutData $checkoutDataHelper
    ) {
        parent::__construct(
            $billingAddressManagement, $paymentMethodManagement, $cartManagement, $paymentDetailsFactory,
            $cartTotalsRepository
        );

        $this->checkoutDataHelper = $checkoutDataHelper;
    }

    public function savePaymentInformationAndPlaceOrder(
        $cartId,
        \Magento\Quote\Api\Data\PaymentInterface $paymentMethod,
        \Magento\Quote\Api\Data\AddressInterface $billingAddress = null,
        $amcheckout = null
    ) {
        $this->checkoutDataHelper->beforePlaceOrder($amcheckout);

        $result = parent::savePaymentInformationAndPlaceOrder($cartId, $paymentMethod, $billingAddress);

        $this->checkoutDataHelper->afterPlaceOrder($amcheckout);

        return $result;
    }
}
