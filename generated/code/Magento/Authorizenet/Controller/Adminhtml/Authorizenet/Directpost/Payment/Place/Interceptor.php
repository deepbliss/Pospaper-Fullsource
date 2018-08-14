<?php
namespace Magento\Authorizenet\Controller\Adminhtml\Authorizenet\Directpost\Payment\Place;

/**
 * Interceptor class for @see \Magento\Authorizenet\Controller\Adminhtml\Authorizenet\Directpost\Payment\Place
 */
class Interceptor extends \Magento\Authorizenet\Controller\Adminhtml\Authorizenet\Directpost\Payment\Place implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Catalog\Helper\Product $productHelper, \Magento\Framework\Escaper $escaper, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory, \Magento\Authorizenet\Helper\Backend\Data $helper)
    {
        $this->___init();
        parent::__construct($context, $productHelper, $escaper, $resultPageFactory, $resultForwardFactory, $helper);
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
