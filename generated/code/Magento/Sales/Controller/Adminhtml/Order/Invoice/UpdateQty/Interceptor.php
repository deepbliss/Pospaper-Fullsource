<?php
namespace Magento\Sales\Controller\Adminhtml\Order\Invoice\UpdateQty;

/**
 * Interceptor class for @see \Magento\Sales\Controller\Adminhtml\Order\Invoice\UpdateQty
 */
class Interceptor extends \Magento\Sales\Controller\Adminhtml\Order\Invoice\UpdateQty implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $registry, \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Framework\Controller\Result\RawFactory $resultRawFactory, \Magento\Sales\Model\Service\InvoiceService $invoiceService)
    {
        $this->___init();
        parent::__construct($context, $registry, $resultForwardFactory, $resultPageFactory, $resultJsonFactory, $resultRawFactory, $invoiceService);
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
