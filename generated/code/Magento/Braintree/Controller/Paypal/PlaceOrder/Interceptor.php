<?php
namespace Magento\Braintree\Controller\Paypal\PlaceOrder;

/**
 * Interceptor class for @see \Magento\Braintree\Controller\Paypal\PlaceOrder
 */
class Interceptor extends \Magento\Braintree\Controller\Paypal\PlaceOrder implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Braintree\Gateway\Config\PayPal\Config $config, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Braintree\Model\Paypal\Helper\OrderPlace $orderPlace, \Psr\Log\LoggerInterface $logger = null)
    {
        $this->___init();
        parent::__construct($context, $config, $checkoutSession, $orderPlace, $logger);
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
