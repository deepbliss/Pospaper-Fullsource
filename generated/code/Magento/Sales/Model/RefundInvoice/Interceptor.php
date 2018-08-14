<?php
namespace Magento\Sales\Model\RefundInvoice;

/**
 * Interceptor class for @see \Magento\Sales\Model\RefundInvoice
 */
class Interceptor extends \Magento\Sales\Model\RefundInvoice implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\ResourceConnection $resourceConnection, \Magento\Sales\Model\Order\OrderStateResolverInterface $orderStateResolver, \Magento\Sales\Api\OrderRepositoryInterface $orderRepository, \Magento\Sales\Api\InvoiceRepositoryInterface $invoiceRepository, \Magento\Sales\Model\Order\Validation\RefundInvoiceInterface $validator, \Magento\Sales\Api\CreditmemoRepositoryInterface $creditmemoRepository, \Magento\Sales\Model\Order\RefundAdapterInterface $refundAdapter, \Magento\Sales\Model\Order\CreditmemoDocumentFactory $creditmemoDocumentFactory, \Magento\Sales\Model\Order\Creditmemo\NotifierInterface $notifier, \Magento\Sales\Model\Order\Config $config, \Psr\Log\LoggerInterface $logger)
    {
        $this->___init();
        parent::__construct($resourceConnection, $orderStateResolver, $orderRepository, $invoiceRepository, $validator, $creditmemoRepository, $refundAdapter, $creditmemoDocumentFactory, $notifier, $config, $logger);
    }

    /**
     * {@inheritdoc}
     */
    public function execute($invoiceId, array $items = array(), $isOnline = false, $notify = false, $appendComment = false, \Magento\Sales\Api\Data\CreditmemoCommentCreationInterface $comment = null, \Magento\Sales\Api\Data\CreditmemoCreationArgumentsInterface $arguments = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        if (!$pluginInfo) {
            return parent::execute($invoiceId, $items, $isOnline, $notify, $appendComment, $comment, $arguments);
        } else {
            return $this->___callPlugins('execute', func_get_args(), $pluginInfo);
        }
    }
}
