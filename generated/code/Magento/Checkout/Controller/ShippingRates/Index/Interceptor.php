<?php
namespace Magento\Checkout\Controller\ShippingRates\Index;

/**
 * Interceptor class for @see \Magento\Checkout\Controller\ShippingRates\Index
 */
class Interceptor extends \Magento\Checkout\Controller\ShippingRates\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Checkout\Model\Session $session)
    {
        $this->___init();
        parent::__construct($context, $session);
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
