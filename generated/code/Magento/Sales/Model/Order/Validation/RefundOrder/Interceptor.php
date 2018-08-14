<?php
namespace Magento\Sales\Model\Order\Validation\RefundOrder;

/**
 * Interceptor class for @see \Magento\Sales\Model\Order\Validation\RefundOrder
 */
class Interceptor extends \Magento\Sales\Model\Order\Validation\RefundOrder implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Sales\Model\Order\OrderValidatorInterface $orderValidator, \Magento\Sales\Model\Order\Creditmemo\CreditmemoValidatorInterface $creditmemoValidator, \Magento\Sales\Model\Order\Creditmemo\ItemCreationValidatorInterface $itemCreationValidator, \Magento\Sales\Model\ValidatorResultMerger $validatorResultMerger)
    {
        $this->___init();
        parent::__construct($orderValidator, $creditmemoValidator, $itemCreationValidator, $validatorResultMerger);
    }

    /**
     * {@inheritdoc}
     */
    public function validate(\Magento\Sales\Api\Data\OrderInterface $order, \Magento\Sales\Api\Data\CreditmemoInterface $creditmemo, array $items = array(), $notify = false, $appendComment = false, \Magento\Sales\Api\Data\CreditmemoCommentCreationInterface $comment = null, \Magento\Sales\Api\Data\CreditmemoCreationArgumentsInterface $arguments = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'validate');
        if (!$pluginInfo) {
            return parent::validate($order, $creditmemo, $items, $notify, $appendComment, $comment, $arguments);
        } else {
            return $this->___callPlugins('validate', func_get_args(), $pluginInfo);
        }
    }
}
