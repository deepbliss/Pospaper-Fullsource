<?php
namespace Magento\Paypal\Controller\Payflowadvanced\SilentPost;

/**
 * Interceptor class for @see \Magento\Paypal\Controller\Payflowadvanced\SilentPost
 */
class Interceptor extends \Magento\Paypal\Controller\Payflowadvanced\SilentPost implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Sales\Model\OrderFactory $orderFactory, \Magento\Paypal\Model\PayflowlinkFactory $payflowModelFactory, \Magento\Paypal\Helper\Checkout $checkoutHelper, \Psr\Log\LoggerInterface $logger)
    {
        $this->___init();
        parent::__construct($context, $checkoutSession, $orderFactory, $payflowModelFactory, $checkoutHelper, $logger);
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
