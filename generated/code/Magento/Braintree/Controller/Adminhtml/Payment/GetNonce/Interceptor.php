<?php
namespace Magento\Braintree\Controller\Adminhtml\Payment\GetNonce;

/**
 * Interceptor class for @see \Magento\Braintree\Controller\Adminhtml\Payment\GetNonce
 */
class Interceptor extends \Magento\Braintree\Controller\Adminhtml\Payment\GetNonce implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Psr\Log\LoggerInterface $logger, \Magento\Framework\Session\SessionManagerInterface $session, \Magento\Braintree\Gateway\Command\GetPaymentNonceCommand $command)
    {
        $this->___init();
        parent::__construct($context, $logger, $session, $command);
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
