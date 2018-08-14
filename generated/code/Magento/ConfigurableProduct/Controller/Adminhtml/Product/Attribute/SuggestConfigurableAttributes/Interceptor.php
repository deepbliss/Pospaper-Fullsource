<?php
namespace Magento\ConfigurableProduct\Controller\Adminhtml\Product\Attribute\SuggestConfigurableAttributes;

/**
 * Interceptor class for @see \Magento\ConfigurableProduct\Controller\Adminhtml\Product\Attribute\SuggestConfigurableAttributes
 */
class Interceptor extends \Magento\ConfigurableProduct\Controller\Adminhtml\Product\Attribute\SuggestConfigurableAttributes implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\ConfigurableProduct\Model\SuggestedAttributeList $attributeList, \Magento\Framework\Json\Helper\Data $jsonHelper, \Magento\Store\Model\StoreManagerInterface $storeManager)
    {
        $this->___init();
        parent::__construct($context, $attributeList, $jsonHelper, $storeManager);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }
}
