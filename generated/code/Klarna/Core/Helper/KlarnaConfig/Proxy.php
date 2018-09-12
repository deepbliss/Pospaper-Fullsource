<?php
namespace Klarna\Core\Helper\KlarnaConfig;

/**
 * Proxy class for @see \Klarna\Core\Helper\KlarnaConfig
 */
class Proxy extends \Klarna\Core\Helper\KlarnaConfig implements \Magento\Framework\ObjectManager\NoninterceptableInterface
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager = null;

    /**
     * Proxied instance name
     *
     * @var string
     */
    protected $_instanceName = null;

    /**
     * Proxied instance
     *
     * @var \Klarna\Core\Helper\KlarnaConfig
     */
    protected $_subject = null;

    /**
     * Instance shareability flag
     *
     * @var bool
     */
    protected $_isShared = null;

    /**
     * Proxy constructor
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param string $instanceName
     * @param bool $shared
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Klarna\\Core\\Helper\\KlarnaConfig', $shared = true)
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
        $this->_isShared = $shared;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return ['_subject', '_isShared', '_instanceName'];
    }

    /**
     * Retrieve ObjectManager from global scope
     */
    public function __wakeup()
    {
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    }

    /**
     * Clone proxied instance
     */
    public function __clone()
    {
        $this->_subject = clone $this->_getSubject();
    }

    /**
     * Get proxied instance
     *
     * @return \Klarna\Core\Helper\KlarnaConfig
     */
    protected function _getSubject()
    {
        if (!$this->_subject) {
            $this->_subject = true === $this->_isShared
                ? $this->_objectManager->get($this->_instanceName)
                : $this->_objectManager->create($this->_instanceName);
        }
        return $this->_subject;
    }

    /**
     * {@inheritdoc}
     */
    public function getCheckoutType($store = null)
    {
        return $this->_getSubject()->getCheckoutType($store);
    }

    /**
     * {@inheritdoc}
     */
    public function getVersionConfig($store)
    {
        return $this->_getSubject()->getVersionConfig($store);
    }

    /**
     * {@inheritdoc}
     */
    public function getCheckoutVersionDetails($code)
    {
        return $this->_getSubject()->getCheckoutVersionDetails($code);
    }

    /**
     * {@inheritdoc}
     */
    public function getApiTypeConfig($code)
    {
        return $this->_getSubject()->getApiTypeConfig($code);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderlines($checkoutType)
    {
        return $this->_getSubject()->getOrderlines($checkoutType);
    }

    /**
     * {@inheritdoc}
     */
    public function getMerchantCheckboxMethodConfig($code)
    {
        return $this->_getSubject()->getMerchantCheckboxMethodConfig($code);
    }

    /**
     * {@inheritdoc}
     */
    public function isShippingCallbackSupport($store = null)
    {
        return $this->_getSubject()->isShippingCallbackSupport($store);
    }

    /**
     * {@inheritdoc}
     */
    public function isMerchantCheckboxSupport($store = null)
    {
        return $this->_getSubject()->isMerchantCheckboxSupport($store);
    }

    /**
     * {@inheritdoc}
     */
    public function isDateOfBirthMandatorySupport($store = null)
    {
        return $this->_getSubject()->isDateOfBirthMandatorySupport($store);
    }

    /**
     * {@inheritdoc}
     */
    public function isPhoneMandatorySupport($store = null)
    {
        return $this->_getSubject()->isPhoneMandatorySupport($store);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderMangagementClass($store = null)
    {
        return $this->_getSubject()->getOrderMangagementClass($store);
    }

    /**
     * {@inheritdoc}
     */
    public function isTitleMandatorySupport($store = null)
    {
        return $this->_getSubject()->isTitleMandatorySupport($store);
    }

    /**
     * {@inheritdoc}
     */
    public function isDelayedPushNotification($store = null)
    {
        return $this->_getSubject()->isDelayedPushNotification($store);
    }

    /**
     * {@inheritdoc}
     */
    public function isPartialPaymentSupport($store = null)
    {
        return $this->_getSubject()->isPartialPaymentSupport($store);
    }

    /**
     * {@inheritdoc}
     */
    public function getOmBuilderType(\Klarna\Core\Api\VersionInterface $versionConfig, $methodCode = 'klarna_kp')
    {
        return $this->_getSubject()->getOmBuilderType($versionConfig, $methodCode);
    }

    /**
     * {@inheritdoc}
     */
    public function isSeparateTaxLine($store)
    {
        return $this->_getSubject()->isSeparateTaxLine($store);
    }

    /**
     * {@inheritdoc}
     */
    public function isShippingInIframe($store)
    {
        return $this->_getSubject()->isShippingInIframe($store);
    }

    /**
     * {@inheritdoc}
     */
    public function getExternalPaymentOptions($code)
    {
        return $this->_getSubject()->getExternalPaymentOptions($code);
    }

    /**
     * {@inheritdoc}
     */
    public function isModuleOutputEnabled($moduleName = null)
    {
        return $this->_getSubject()->isModuleOutputEnabled($moduleName);
    }
}
