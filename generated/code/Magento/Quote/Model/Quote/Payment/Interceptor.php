<?php
namespace Magento\Quote\Model\Quote\Payment;

/**
 * Interceptor class for @see \Magento\Quote\Model\Quote\Payment
 */
class Interceptor extends \Magento\Quote\Model\Quote\Payment implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory, \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory, \Magento\Payment\Helper\Data $paymentData, \Magento\Framework\Encryption\EncryptorInterface $encryptor, \Magento\Payment\Model\Checks\SpecificationFactory $methodSpecificationFactory, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array(), array $additionalChecks = array(), \Magento\Framework\Serialize\Serializer\Json $serializer = null, \Magento\Framework\Serialize\JsonValidator $jsonValidator = null)
    {
        $this->___init();
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $paymentData, $encryptor, $methodSpecificationFactory, $resource, $resourceCollection, $data, $additionalChecks, $serializer, $jsonValidator);
    }

    /**
     * {@inheritdoc}
     */
    public function setAdditionalInformation($key, $value = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setAdditionalInformation');
        if (!$pluginInfo) {
            return parent::setAdditionalInformation($key, $value);
        } else {
            return $this->___callPlugins('setAdditionalInformation', func_get_args(), $pluginInfo);
        }
    }
}
