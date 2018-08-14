<?php
namespace Magento\Checkout\Model\Type\Onepage;

/**
 * Interceptor class for @see \Magento\Checkout\Model\Type\Onepage
 */
class Interceptor extends \Magento\Checkout\Model\Type\Onepage implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Event\ManagerInterface $eventManager, \Magento\Checkout\Helper\Data $helper, \Magento\Customer\Model\Url $customerUrl, \Psr\Log\LoggerInterface $logger, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Customer\Model\Session $customerSession, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\App\RequestInterface $request, \Magento\Customer\Model\AddressFactory $customrAddrFactory, \Magento\Customer\Model\FormFactory $customerFormFactory, \Magento\Customer\Model\CustomerFactory $customerFactory, \Magento\Sales\Model\OrderFactory $orderFactory, \Magento\Framework\DataObject\Copy $objectCopyService, \Magento\Framework\Message\ManagerInterface $messageManager, \Magento\Customer\Model\Metadata\FormFactory $formFactory, \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerDataFactory, \Magento\Framework\Math\Random $mathRandom, \Magento\Framework\Encryption\EncryptorInterface $encryptor, \Magento\Customer\Api\AddressRepositoryInterface $addressRepository, \Magento\Customer\Api\AccountManagementInterface $accountManagement, \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender, \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository, \Magento\Quote\Api\CartRepositoryInterface $quoteRepository, \Magento\Framework\Api\ExtensibleDataObjectConverter $extensibleDataObjectConverter, \Magento\Quote\Api\CartManagementInterface $quoteManagement, \Magento\Framework\Api\DataObjectHelper $dataObjectHelper, \Magento\Quote\Model\Quote\TotalsCollector $totalsCollector)
    {
        $this->___init();
        parent::__construct($eventManager, $helper, $customerUrl, $logger, $checkoutSession, $customerSession, $storeManager, $request, $customrAddrFactory, $customerFormFactory, $customerFactory, $orderFactory, $objectCopyService, $messageManager, $formFactory, $customerDataFactory, $mathRandom, $encryptor, $addressRepository, $accountManagement, $orderSender, $customerRepository, $quoteRepository, $extensibleDataObjectConverter, $quoteManagement, $dataObjectHelper, $totalsCollector);
    }

    /**
     * {@inheritdoc}
     */
    public function saveShippingMethod($shippingMethod)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'saveShippingMethod');
        if (!$pluginInfo) {
            return parent::saveShippingMethod($shippingMethod);
        } else {
            return $this->___callPlugins('saveShippingMethod', func_get_args(), $pluginInfo);
        }
    }
}
