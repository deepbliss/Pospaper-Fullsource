<?php
namespace Magento\Backend\Controller\Adminhtml\System\Store\DeleteGroup;

/**
 * Interceptor class for @see \Magento\Backend\Controller\Adminhtml\System\Store\DeleteGroup
 */
class Interceptor extends \Magento\Backend\Controller\Adminhtml\System\Store\DeleteGroup implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\Framework\Filter\FilterManager $filterManager, \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory, \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $filterManager, $resultForwardFactory, $resultPageFactory);
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
