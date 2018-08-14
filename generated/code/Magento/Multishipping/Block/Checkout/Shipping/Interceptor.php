<?php
namespace Magento\Multishipping\Block\Checkout\Shipping;

/**
 * Interceptor class for @see \Magento\Multishipping\Block\Checkout\Shipping
 */
class Interceptor extends \Magento\Multishipping\Block\Checkout\Shipping implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Magento\Framework\Filter\DataObject\GridFactory $filterGridFactory, \Magento\Multishipping\Model\Checkout\Type\Multishipping $multishipping, \Magento\Tax\Helper\Data $taxHelper, \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $filterGridFactory, $multishipping, $taxHelper, $priceCurrency, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getItemsBoxTextAfter(\Magento\Framework\DataObject $addressEntity)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemsBoxTextAfter');
        if (!$pluginInfo) {
            return parent::getItemsBoxTextAfter($addressEntity);
        } else {
            return $this->___callPlugins('getItemsBoxTextAfter', func_get_args(), $pluginInfo);
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
