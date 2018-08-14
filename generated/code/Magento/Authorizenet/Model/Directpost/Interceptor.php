<?php
namespace Magento\Authorizenet\Model\Directpost;

/**
 * Interceptor class for @see \Magento\Authorizenet\Model\Directpost
 */
class Interceptor extends \Magento\Authorizenet\Model\Directpost implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory, \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory, \Magento\Payment\Helper\Data $paymentData, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Payment\Model\Method\Logger $logger, \Magento\Framework\Module\ModuleListInterface $moduleList, \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate, \Magento\Authorizenet\Helper\Data $dataHelper, \Magento\Authorizenet\Model\Directpost\Request\Factory $requestFactory, \Magento\Authorizenet\Model\Directpost\Response\Factory $responseFactory, \Magento\Authorizenet\Model\TransactionService $transactionService, \Magento\Framework\HTTP\ZendClientFactory $httpClientFactory, \Magento\Sales\Model\OrderFactory $orderFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Quote\Api\CartRepositoryInterface $quoteRepository, \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender, \Magento\Sales\Api\TransactionRepositoryInterface $transactionRepository, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $paymentData, $scopeConfig, $logger, $moduleList, $localeDate, $dataHelper, $requestFactory, $responseFactory, $transactionService, $httpClientFactory, $orderFactory, $storeManager, $quoteRepository, $orderSender, $transactionRepository, $resource, $resourceCollection, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function denyPayment(\Magento\Payment\Model\InfoInterface $payment)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'denyPayment');
        if (!$pluginInfo) {
            return parent::denyPayment($payment);
        } else {
            return $this->___callPlugins('denyPayment', func_get_args(), $pluginInfo);
        }
    }
}
