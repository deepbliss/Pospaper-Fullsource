<?php
namespace Amasty\Checkout\Model\GuestPaymentInformationManagement;

/**
 * Interceptor class for @see \Amasty\Checkout\Model\GuestPaymentInformationManagement
 */
class Interceptor extends \Amasty\Checkout\Model\GuestPaymentInformationManagement implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Quote\Api\GuestBillingAddressManagementInterface $billingAddressManagement, \Magento\Quote\Api\GuestPaymentMethodManagementInterface $paymentMethodManagement, \Magento\Quote\Api\GuestCartManagementInterface $cartManagement, \Magento\Checkout\Api\PaymentInformationManagementInterface $paymentInformationManagement, \Magento\Quote\Model\QuoteIdMaskFactory $quoteIdMaskFactory, \Magento\Quote\Api\CartRepositoryInterface $cartRepository, \Amasty\Checkout\Helper\CheckoutData $checkoutDataHelper)
    {
        $this->___init();
        parent::__construct($billingAddressManagement, $paymentMethodManagement, $cartManagement, $paymentInformationManagement, $quoteIdMaskFactory, $cartRepository, $checkoutDataHelper);
    }

    /**
     * {@inheritdoc}
     */
    public function savePaymentInformationAndPlaceOrder($cartId, $email, \Magento\Quote\Api\Data\PaymentInterface $paymentMethod, \Magento\Quote\Api\Data\AddressInterface $billingAddress = null, $amcheckout = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'savePaymentInformationAndPlaceOrder');
        if (!$pluginInfo) {
            return parent::savePaymentInformationAndPlaceOrder($cartId, $email, $paymentMethod, $billingAddress, $amcheckout);
        } else {
            return $this->___callPlugins('savePaymentInformationAndPlaceOrder', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function savePaymentInformation($cartId, $email, \Magento\Quote\Api\Data\PaymentInterface $paymentMethod, \Magento\Quote\Api\Data\AddressInterface $billingAddress = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'savePaymentInformation');
        if (!$pluginInfo) {
            return parent::savePaymentInformation($cartId, $email, $paymentMethod, $billingAddress);
        } else {
            return $this->___callPlugins('savePaymentInformation', func_get_args(), $pluginInfo);
        }
    }
}
