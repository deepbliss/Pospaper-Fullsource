<?php
namespace Amasty\Checkout\Helper\Onepage;

/**
 * Proxy class for @see \Amasty\Checkout\Helper\Onepage
 */
class Proxy extends \Amasty\Checkout\Helper\Onepage implements \Magento\Framework\ObjectManager\NoninterceptableInterface
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
     * @var \Amasty\Checkout\Helper\Onepage
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
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Amasty\\Checkout\\Helper\\Onepage', $shared = true)
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
     * @return \Amasty\Checkout\Helper\Onepage
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
    public function getTitle()
    {
        return $this->_getSubject()->getTitle();
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->_getSubject()->getDescription();
    }

    /**
     * {@inheritdoc}
     */
    public function isAddressSuggestionEnabled()
    {
        return $this->_getSubject()->isAddressSuggestionEnabled();
    }

    /**
     * {@inheritdoc}
     */
    public function getGoogleMapsKey()
    {
        return $this->_getSubject()->getGoogleMapsKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getRegionsJson()
    {
        return $this->_getSubject()->getRegionsJson();
    }

    /**
     * {@inheritdoc}
     */
    public function getRegions()
    {
        return $this->_getSubject()->getRegions();
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultShippingMethod()
    {
        return $this->_getSubject()->getDefaultShippingMethod();
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultPaymentMethod()
    {
        return $this->_getSubject()->getDefaultPaymentMethod();
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultAddress()
    {
        return $this->_getSubject()->getDefaultAddress();
    }

    /**
     * {@inheritdoc}
     */
    public function getAdditionalOptions()
    {
        return $this->_getSubject()->getAdditionalOptions();
    }

    /**
     * {@inheritdoc}
     */
    public function isModuleOutputEnabled($moduleName = null)
    {
        return $this->_getSubject()->isModuleOutputEnabled($moduleName);
    }
}
