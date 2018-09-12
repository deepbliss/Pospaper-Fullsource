<?php
namespace Magento\Tax\Model\Calculation\CalculatorFactory;

/**
 * Interceptor class for @see \Magento\Tax\Model\Calculation\CalculatorFactory
 */
class Interceptor extends \Magento\Tax\Model\Calculation\CalculatorFactory implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->___init();
        parent::__construct($objectManager);
    }

    /**
     * {@inheritdoc}
     */
    public function create($type, $storeId, \Magento\Customer\Api\Data\AddressInterface $billingAddress = null, \Magento\Customer\Api\Data\AddressInterface $shippingAddress = null, $customerTaxClassId = null, $customerId = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'create');
        if (!$pluginInfo) {
            return parent::create($type, $storeId, $billingAddress, $shippingAddress, $customerTaxClassId, $customerId);
        } else {
            return $this->___callPlugins('create', func_get_args(), $pluginInfo);
        }
    }
}
