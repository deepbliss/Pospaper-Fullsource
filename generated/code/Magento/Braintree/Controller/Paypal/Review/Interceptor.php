<?php
namespace Magento\Braintree\Controller\Paypal\Review;

/**
 * Interceptor class for @see \Magento\Braintree\Controller\Paypal\Review
 */
class Interceptor extends \Magento\Braintree\Controller\Paypal\Review implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Braintree\Gateway\Config\PayPal\Config $config, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Braintree\Model\Paypal\Helper\QuoteUpdater $quoteUpdater)
    {
        $this->___init();
        parent::__construct($context, $config, $checkoutSession, $quoteUpdater);
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
