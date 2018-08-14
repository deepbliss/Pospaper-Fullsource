<?php
namespace Magento\Sales\Block\Adminhtml\Order\Create\Sidebar\Pviewed;

/**
 * Interceptor class for @see \Magento\Sales\Block\Adminhtml\Order\Create\Sidebar\Pviewed
 */
class Interceptor extends \Magento\Sales\Block\Adminhtml\Order\Create\Sidebar\Pviewed implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\Block\Template\Context $context, \Magento\Backend\Model\Session\Quote $sessionQuote, \Magento\Sales\Model\AdminOrder\Create $orderCreate, \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency, \Magento\Sales\Model\Config $salesConfig, \Magento\Reports\Model\EventFactory $eventFactory, \Magento\Catalog\Model\ProductFactory $productFactory, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $sessionQuote, $orderCreate, $priceCurrency, $salesConfig, $eventFactory, $productFactory, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getItemQty(\Magento\Framework\DataObject $item)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItemQty');
        if (!$pluginInfo) {
            return parent::getItemQty($item);
        } else {
            return $this->___callPlugins('getItemQty', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurationRequired($productType)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isConfigurationRequired');
        if (!$pluginInfo) {
            return parent::isConfigurationRequired($productType);
        } else {
            return $this->___callPlugins('isConfigurationRequired', func_get_args(), $pluginInfo);
        }
    }
}
