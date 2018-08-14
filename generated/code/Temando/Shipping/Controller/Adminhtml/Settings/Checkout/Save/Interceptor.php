<?php
namespace Temando\Shipping\Controller\Adminhtml\Settings\Checkout\Save;

/**
 * Interceptor class for @see \Temando\Shipping\Controller\Adminhtml\Settings\Checkout\Save
 */
class Interceptor extends \Temando\Shipping\Controller\Adminhtml\Settings\Checkout\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Serialize\Serializer\Json $decoder, \Temando\Shipping\Model\Config\ModuleConfigInterface $moduleConfig)
    {
        $this->___init();
        parent::__construct($context, $decoder, $moduleConfig);
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
