<?php
namespace Magento\Paypal\Model\Payflowlink;

/**
 * Interceptor class for @see \Magento\Paypal\Model\Payflowlink
 */
class Interceptor extends \Magento\Paypal\Model\Payflowlink implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory, \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory, \Magento\Payment\Helper\Data $paymentData, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Payment\Model\Method\Logger $logger, \Magento\Framework\Module\ModuleListInterface $moduleList, \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Payment\Model\Method\ConfigInterfaceFactory $configFactory, \Magento\Paypal\Model\Payflow\Service\Gateway $gateway, \Magento\Paypal\Model\Payflow\Service\Response\Handler\HandlerInterface $errorHandler, \Magento\Framework\Math\Random $mathRandom, \Magento\Paypal\Model\Payflow\RequestFactory $requestFactory, \Magento\Quote\Api\CartRepositoryInterface $quoteRepository, \Magento\Sales\Model\OrderFactory $orderFactory, \Magento\Framework\App\RequestInterface $requestHttp, \Magento\Store\Model\WebsiteFactory $websiteFactory, \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $paymentData, $scopeConfig, $logger, $moduleList, $localeDate, $storeManager, $configFactory, $gateway, $errorHandler, $mathRandom, $requestFactory, $quoteRepository, $orderFactory, $requestHttp, $websiteFactory, $orderSender, $resource, $resourceCollection, $data);
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
