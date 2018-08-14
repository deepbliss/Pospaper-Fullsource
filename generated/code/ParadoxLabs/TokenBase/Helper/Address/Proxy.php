<?php
namespace ParadoxLabs\TokenBase\Helper\Address;

/**
 * Proxy class for @see \ParadoxLabs\TokenBase\Helper\Address
 */
class Proxy extends \ParadoxLabs\TokenBase\Helper\Address implements \Magento\Framework\ObjectManager\NoninterceptableInterface
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
     * @var \ParadoxLabs\TokenBase\Helper\Address
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
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\ParadoxLabs\\TokenBase\\Helper\\Address', $shared = true)
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
     * @return \ParadoxLabs\TokenBase\Helper\Address
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
    public function buildAddressFromInput($addressData, $origAddressData = array(), $validate = false)
    {
        return $this->_getSubject()->buildAddressFromInput($addressData, $origAddressData, $validate);
    }

    /**
     * {@inheritdoc}
     */
    public function processRegionData($addressArray)
    {
        return $this->_getSubject()->processRegionData($addressArray);
    }

    /**
     * {@inheritdoc}
     */
    public function repository()
    {
        return $this->_getSubject()->repository();
    }

    /**
     * {@inheritdoc}
     */
    public function compareAddresses($address1, $address2)
    {
        return $this->_getSubject()->compareAddresses($address1, $address2);
    }

    /**
     * {@inheritdoc}
     */
    public function addressToArray($address)
    {
        return $this->_getSubject()->addressToArray($address);
    }

    /**
     * {@inheritdoc}
     */
    public function getFormattedAddress(\Magento\Customer\Api\Data\AddressInterface $address, $format = 'html')
    {
        return $this->_getSubject()->getFormattedAddress($address, $format);
    }

    /**
     * {@inheritdoc}
     */
    public function isModuleOutputEnabled($moduleName = null)
    {
        return $this->_getSubject()->isModuleOutputEnabled($moduleName);
    }
}
