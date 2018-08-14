<?php
namespace Magento\Sales\Controller\Order\PrintInvoice;

/**
 * Interceptor class for @see \Magento\Sales\Controller\Order\PrintInvoice
 */
class Interceptor extends \Magento\Sales\Controller\Order\PrintInvoice implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Sales\Controller\AbstractController\OrderViewAuthorizationInterface $orderAuthorization, \Magento\Framework\Registry $registry, \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->___init();
        parent::__construct($context, $orderAuthorization, $registry, $resultPageFactory);
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
