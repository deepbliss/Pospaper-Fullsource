<?php
namespace Magento\Checkout\Block\Cart\Sidebar;

/**
 * Interceptor class for @see \Magento\Checkout\Block\Cart\Sidebar
 */
class Interceptor extends \Magento\Checkout\Block\Cart\Sidebar implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Magento\Customer\Model\Session $customerSession, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Catalog\Helper\Image $imageHelper, \Magento\Customer\CustomerData\JsLayoutDataProviderPoolInterface $jsLayoutDataProvider, array $data = array(), \Magento\Framework\Serialize\Serializer\Json $serializer = null)
    {
        $this->___init();
        parent::__construct($context, $customerSession, $checkoutSession, $imageHelper, $jsLayoutDataProvider, $data, $serializer);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getConfig');
        if (!$pluginInfo) {
            return parent::getConfig();
        } else {
            return $this->___callPlugins('getConfig', func_get_args(), $pluginInfo);
        }
    }
}
