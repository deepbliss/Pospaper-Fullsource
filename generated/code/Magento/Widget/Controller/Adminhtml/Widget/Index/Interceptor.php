<?php
namespace Magento\Widget\Controller\Adminhtml\Widget\Index;

/**
 * Interceptor class for @see \Magento\Widget\Controller\Adminhtml\Widget\Index
 */
class Interceptor extends \Magento\Widget\Controller\Adminhtml\Widget\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Widget\Model\Widget\Config $widgetConfig, \Magento\Framework\Registry $coreRegistry)
    {
        $this->___init();
        parent::__construct($context, $widgetConfig, $coreRegistry);
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
