<?php
namespace Magento\Sales\Model\RefundOrder;

/**
 * Interceptor class for @see \Magento\Sales\Model\RefundOrder
 */
class Interceptor extends \Magento\Sales\Model\RefundOrder implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\ResourceConnection $resourceConnection, \Magento\Sales\Model\Order\OrderStateResolverInterface $orderStateResolver, \Magento\Sales\Api\OrderRepositoryInterface $orderRepository, \Magento\Sales\Api\CreditmemoRepositoryInterface $creditmemoRepository, \Magento\Sales\Model\Order\RefundAdapterInterface $refundAdapter, \Magento\Sales\Model\Order\CreditmemoDocumentFactory $creditmemoDocumentFactory, \Magento\Sales\Model\Order\Validation\RefundOrderInterface $validator, \Magento\Sales\Model\Order\Creditmemo\NotifierInterface $notifier, \Magento\Sales\Model\Order\Config $config, \Psr\Log\LoggerInterface $logger)
    {
        $this->___init();
        parent::__construct($resourceConnection, $orderStateResolver, $orderRepository, $creditmemoRepository, $refundAdapter, $creditmemoDocumentFactory, $validator, $notifier, $config, $logger);
    }

    /**
     * {@inheritdoc}
     */
    public function execute($orderId, array $items = array(), $notify = false, $appendComment = false, \Magento\Sales\Api\Data\CreditmemoCommentCreationInterface $comment = null, \Magento\Sales\Api\Data\CreditmemoCreationArgumentsInterface $arguments = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        if (!$pluginInfo) {
            return parent::execute($orderId, $items, $notify, $appendComment, $comment, $arguments);
        } else {
            return $this->___callPlugins('execute', func_get_args(), $pluginInfo);
        }
    }
}
