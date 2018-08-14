<?php
namespace Magento\Sales\Controller\Adminhtml\Order\Creditmemo\AddComment;

/**
 * Interceptor class for @see \Magento\Sales\Controller\Adminhtml\Order\Creditmemo\AddComment
 */
class Interceptor extends \Magento\Sales\Controller\Adminhtml\Order\Creditmemo\AddComment implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Sales\Controller\Adminhtml\Order\CreditmemoLoader $creditmemoLoader, \Magento\Sales\Model\Order\Email\Sender\CreditmemoCommentSender $creditmemoCommentSender, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Framework\Controller\Result\RawFactory $resultRawFactory)
    {
        $this->___init();
        parent::__construct($context, $creditmemoLoader, $creditmemoCommentSender, $resultPageFactory, $resultJsonFactory, $resultRawFactory);
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
