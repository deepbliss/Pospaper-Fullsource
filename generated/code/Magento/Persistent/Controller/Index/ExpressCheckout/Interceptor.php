<?php
namespace Magento\Persistent\Controller\Index\ExpressCheckout;

/**
 * Interceptor class for @see \Magento\Persistent\Controller\Index\ExpressCheckout
 */
class Interceptor extends \Magento\Persistent\Controller\Index\ExpressCheckout implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Persistent\Model\QuoteManager $quoteManager, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Customer\Model\Session $customerSession, \Magento\Persistent\Helper\Session $sessionHelper)
    {
        $this->___init();
        parent::__construct($context, $quoteManager, $checkoutSession, $customerSession, $sessionHelper);
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
