<?php
namespace Magento\Checkout\Block\Checkout\AttributeMerger;

/**
 * Interceptor class for @see \Magento\Checkout\Block\Checkout\AttributeMerger
 */
class Interceptor extends \Magento\Checkout\Block\Checkout\AttributeMerger implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Customer\Helper\Address $addressHelper, \Magento\Customer\Model\Session $customerSession, \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository, \Magento\Directory\Helper\Data $directoryHelper)
    {
        $this->___init();
        parent::__construct($addressHelper, $customerSession, $customerRepository, $directoryHelper);
    }

    /**
     * {@inheritdoc}
     */
    public function merge($elements, $providerName, $dataScopePrefix, array $fields = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'merge');
        if (!$pluginInfo) {
            return parent::merge($elements, $providerName, $dataScopePrefix, $fields);
        } else {
            return $this->___callPlugins('merge', func_get_args(), $pluginInfo);
        }
    }
}
