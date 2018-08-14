<?php
namespace Magento\Backend\Controller\Adminhtml\BackendApp\Redirect;

/**
 * Interceptor class for @see \Magento\Backend\Controller\Adminhtml\BackendApp\Redirect
 */
class Interceptor extends \Magento\Backend\Controller\Adminhtml\BackendApp\Redirect implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Backend\App\BackendAppList $backendAppList)
    {
        $this->___init();
        parent::__construct($context, $backendAppList);
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
