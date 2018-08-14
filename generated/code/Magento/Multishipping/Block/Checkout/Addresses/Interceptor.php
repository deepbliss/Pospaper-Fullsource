<?php
namespace Magento\Multishipping\Block\Checkout\Addresses;

/**
 * Interceptor class for @see \Magento\Multishipping\Block\Checkout\Addresses
 */
class Interceptor extends \Magento\Multishipping\Block\Checkout\Addresses implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Magento\Framework\Filter\DataObject\GridFactory $filterGridFactory, \Magento\Multishipping\Model\Checkout\Type\Multishipping $multishipping, \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository, \Magento\Customer\Model\Address\Config $addressConfig, \Magento\Customer\Model\Address\Mapper $addressMapper, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $filterGridFactory, $multishipping, $customerRepository, $addressConfig, $addressMapper, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getAddressesHtmlSelect($item, $index)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAddressesHtmlSelect');
        if (!$pluginInfo) {
            return parent::getAddressesHtmlSelect($item, $index);
        } else {
            return $this->___callPlugins('getAddressesHtmlSelect', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toHtml()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toHtml');
        if (!$pluginInfo) {
            return parent::toHtml();
        } else {
            return $this->___callPlugins('toHtml', func_get_args(), $pluginInfo);
        }
    }
}
