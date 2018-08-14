<?php
namespace Magento\Paypal\Model\Method\Agreement;

/**
 * Interceptor class for @see \Magento\Paypal\Model\Method\Agreement
 */
class Interceptor extends \Magento\Paypal\Model\Method\Agreement implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory, \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory, \Magento\Payment\Helper\Data $paymentData, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Payment\Model\Method\Logger $logger, \Magento\Paypal\Model\Billing\AgreementFactory $agreementFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Paypal\Model\ProFactory $proFactory, \Magento\Framework\UrlInterface $urlBuilder, \Magento\Paypal\Model\CartFactory $cartFactory, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $paymentData, $scopeConfig, $logger, $agreementFactory, $storeManager, $proFactory, $urlBuilder, $cartFactory, $resource, $resourceCollection, $data);
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
