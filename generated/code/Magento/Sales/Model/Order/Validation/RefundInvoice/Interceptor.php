<?php
namespace Magento\Sales\Model\Order\Validation\RefundInvoice;

/**
 * Interceptor class for @see \Magento\Sales\Model\Order\Validation\RefundInvoice
 */
class Interceptor extends \Magento\Sales\Model\Order\Validation\RefundInvoice implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Sales\Model\Order\OrderValidatorInterface $orderValidator, \Magento\Sales\Model\Order\Creditmemo\CreditmemoValidatorInterface $creditmemoValidator, \Magento\Sales\Model\Order\Creditmemo\ItemCreationValidatorInterface $itemCreationValidator, \Magento\Sales\Model\Order\Invoice\InvoiceValidatorInterface $invoiceValidator, \Magento\Sales\Model\ValidatorResultMerger $validatorResultMerger)
    {
        $this->___init();
        parent::__construct($orderValidator, $creditmemoValidator, $itemCreationValidator, $invoiceValidator, $validatorResultMerger);
    }

    /**
     * {@inheritdoc}
     */
    public function validate(\Magento\Sales\Api\Data\InvoiceInterface $invoice, \Magento\Sales\Api\Data\OrderInterface $order, \Magento\Sales\Api\Data\CreditmemoInterface $creditmemo, array $items = array(), $isOnline = false, $notify = false, $appendComment = false, \Magento\Sales\Api\Data\CreditmemoCommentCreationInterface $comment = null, \Magento\Sales\Api\Data\CreditmemoCreationArgumentsInterface $arguments = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'validate');
        if (!$pluginInfo) {
            return parent::validate($invoice, $order, $creditmemo, $items, $isOnline, $notify, $appendComment, $comment, $arguments);
        } else {
            return $this->___callPlugins('validate', func_get_args(), $pluginInfo);
        }
    }
}
