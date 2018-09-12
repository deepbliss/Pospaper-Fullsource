<?php
namespace Amasty\Base\Model\AdminNotification\Model\ResourceModel\Inbox\Collection;

/**
 * Factory class for @see \Amasty\Base\Model\AdminNotification\Model\ResourceModel\Inbox\Collection\Expired
 */
class ExpiredFactory
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager = null;

    /**
     * Instance name to create
     *
     * @var string
     */
    protected $_instanceName = null;

    /**
     * Factory constructor
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param string $instanceName
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Amasty\\Base\\Model\\AdminNotification\\Model\\ResourceModel\\Inbox\\Collection\\Expired')
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return \Amasty\Base\Model\AdminNotification\Model\ResourceModel\Inbox\Collection\Expired
     */
    public function create(array $data = array())
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}
