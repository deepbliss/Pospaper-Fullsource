<?php
namespace Magento\Widget\Controller\Adminhtml\Widget\BuildWidget;

/**
 * Interceptor class for @see \Magento\Widget\Controller\Adminhtml\Widget\BuildWidget
 */
class Interceptor extends \Magento\Widget\Controller\Adminhtml\Widget\BuildWidget implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Widget\Model\Widget $widget)
    {
        $this->___init();
        parent::__construct($context, $widget);
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
