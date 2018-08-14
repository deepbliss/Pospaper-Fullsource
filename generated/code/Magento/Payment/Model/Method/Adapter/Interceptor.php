<?php
namespace Magento\Payment\Model\Method\Adapter;

/**
 * Interceptor class for @see \Magento\Payment\Model\Method\Adapter
 */
class Interceptor extends \Magento\Payment\Model\Method\Adapter implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Event\ManagerInterface $eventManager, \Magento\Payment\Gateway\Config\ValueHandlerPoolInterface $valueHandlerPool, \Magento\Payment\Gateway\Data\PaymentDataObjectFactory $paymentDataObjectFactory, $code, $formBlockType, $infoBlockType, \Magento\Payment\Gateway\Command\CommandPoolInterface $commandPool = null, \Magento\Payment\Gateway\Validator\ValidatorPoolInterface $validatorPool = null, \Magento\Payment\Gateway\Command\CommandManagerInterface $commandExecutor = null, \Psr\Log\LoggerInterface $logger = null)
    {
        $this->___init();
        parent::__construct($eventManager, $valueHandlerPool, $paymentDataObjectFactory, $code, $formBlockType, $infoBlockType, $commandPool, $validatorPool, $commandExecutor, $logger);
    }

    /**
     * {@inheritdoc}
     */
    public function denyPayment(\Magento\Payment\Model\InfoInterface $payment)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'denyPayment');
        if (!$pluginInfo) {
            return parent::denyPayment($payment);
        } else {
            return $this->___callPlugins('denyPayment', func_get_args(), $pluginInfo);
        }
    }
}
