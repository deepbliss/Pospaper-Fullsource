<?php
namespace Magento\Sales\Controller\Adminhtml\Order\Edit\Save;

/**
 * Interceptor class for @see \Magento\Sales\Controller\Adminhtml\Order\Edit\Save
 */
class Interceptor extends \Magento\Sales\Controller\Adminhtml\Order\Edit\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Catalog\Helper\Product $productHelper, \Magento\Framework\Escaper $escaper, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory)
    {
        $this->___init();
        parent::__construct($context, $productHelper, $escaper, $resultPageFactory, $resultForwardFactory);
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
