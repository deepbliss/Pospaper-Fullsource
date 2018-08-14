<?php
namespace Magento\Shipping\Controller\Tracking\Popup;

/**
 * Interceptor class for @see \Magento\Shipping\Controller\Tracking\Popup
 */
class Interceptor extends \Magento\Shipping\Controller\Tracking\Popup implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\Shipping\Model\InfoFactory $shippingInfoFactory, \Magento\Sales\Model\OrderFactory $orderFactory)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $shippingInfoFactory, $orderFactory);
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
