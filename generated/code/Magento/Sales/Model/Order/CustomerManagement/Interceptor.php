<?php
namespace Magento\Sales\Model\Order\CustomerManagement;

/**
 * Interceptor class for @see \Magento\Sales\Model\Order\CustomerManagement
 */
class Interceptor extends \Magento\Sales\Model\Order\CustomerManagement implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\DataObject\Copy $objectCopyService, \Magento\Customer\Api\AccountManagementInterface $accountManagement, \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerFactory, \Magento\Customer\Api\Data\AddressInterfaceFactory $addressFactory, \Magento\Customer\Api\Data\RegionInterfaceFactory $regionFactory, \Magento\Sales\Api\OrderRepositoryInterface $orderRepository, \Magento\Quote\Model\Quote\AddressFactory $quoteAddressFactory = null, \Magento\Sales\Model\Order\OrderCustomerExtractor $orderCustomerExtractor = null)
    {
        $this->___init();
        parent::__construct($objectCopyService, $accountManagement, $customerFactory, $addressFactory, $regionFactory, $orderRepository, $quoteAddressFactory, $orderCustomerExtractor);
    }

    /**
     * {@inheritdoc}
     */
    public function create($orderId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'create');
        if (!$pluginInfo) {
            return parent::create($orderId);
        } else {
            return $this->___callPlugins('create', func_get_args(), $pluginInfo);
        }
    }
}
