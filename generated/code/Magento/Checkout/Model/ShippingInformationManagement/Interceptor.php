<?php
namespace Magento\Checkout\Model\ShippingInformationManagement;

/**
 * Interceptor class for @see \Magento\Checkout\Model\ShippingInformationManagement
 */
class Interceptor extends \Magento\Checkout\Model\ShippingInformationManagement implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Quote\Api\PaymentMethodManagementInterface $paymentMethodManagement, \Magento\Checkout\Model\PaymentDetailsFactory $paymentDetailsFactory, \Magento\Quote\Api\CartTotalRepositoryInterface $cartTotalsRepository, \Magento\Quote\Api\CartRepositoryInterface $quoteRepository, \Magento\Quote\Model\QuoteAddressValidator $addressValidator, \Psr\Log\LoggerInterface $logger, \Magento\Customer\Api\AddressRepositoryInterface $addressRepository, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Quote\Model\Quote\TotalsCollector $totalsCollector, \Magento\Quote\Api\Data\CartExtensionFactory $cartExtensionFactory = null, \Magento\Quote\Model\ShippingAssignmentFactory $shippingAssignmentFactory = null, \Magento\Quote\Model\ShippingFactory $shippingFactory = null)
    {
        $this->___init();
        parent::__construct($paymentMethodManagement, $paymentDetailsFactory, $cartTotalsRepository, $quoteRepository, $addressValidator, $logger, $addressRepository, $scopeConfig, $totalsCollector, $cartExtensionFactory, $shippingAssignmentFactory, $shippingFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function saveAddressInformation($cartId, \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'saveAddressInformation');
        if (!$pluginInfo) {
            return parent::saveAddressInformation($cartId, $addressInformation);
        } else {
            return $this->___callPlugins('saveAddressInformation', func_get_args(), $pluginInfo);
        }
    }
}
