<?php
namespace Magento\Sales\Model\Order\Payment\State\OrderCommand;

/**
 * Interceptor class for @see \Magento\Sales\Model\Order\Payment\State\OrderCommand
 */
class Interceptor extends \Magento\Sales\Model\Order\Payment\State\OrderCommand implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Sales\Model\Order\StatusResolver $statusResolver = null)
    {
        $this->___init();
        parent::__construct($statusResolver);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(\Magento\Sales\Api\Data\OrderPaymentInterface $payment, $amount, \Magento\Sales\Api\Data\OrderInterface $order)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        if (!$pluginInfo) {
            return parent::execute($payment, $amount, $order);
        } else {
            return $this->___callPlugins('execute', func_get_args(), $pluginInfo);
        }
    }
}
