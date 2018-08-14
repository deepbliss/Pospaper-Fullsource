<?php
namespace Magento\Paypal\Model\Hostedpro;

/**
 * Interceptor class for @see \Magento\Paypal\Model\Hostedpro
 */
class Interceptor extends \Magento\Paypal\Model\Hostedpro implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory, \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory, \Magento\Payment\Helper\Data $paymentData, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Payment\Model\Method\Logger $logger, \Magento\Framework\Module\ModuleListInterface $moduleList, \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate, \Magento\Paypal\Model\ProFactory $proFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\UrlInterface $urlBuilder, \Magento\Framework\App\RequestInterface $requestHttp, \Magento\Paypal\Model\CartFactory $cartFactory, \Magento\Paypal\Model\Hostedpro\RequestFactory $hostedproRequestFactory, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $paymentData, $scopeConfig, $logger, $moduleList, $localeDate, $proFactory, $storeManager, $urlBuilder, $requestHttp, $cartFactory, $hostedproRequestFactory, $resource, $resourceCollection, $data);
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
