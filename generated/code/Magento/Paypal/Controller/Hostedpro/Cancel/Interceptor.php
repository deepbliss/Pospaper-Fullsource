<?php
namespace Magento\Paypal\Controller\Hostedpro\Cancel;

/**
 * Interceptor class for @see \Magento\Paypal\Controller\Hostedpro\Cancel
 */
class Interceptor extends \Magento\Paypal\Controller\Hostedpro\Cancel implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Paypal\Helper\Checkout $checkoutHelper)
    {
        $this->___init();
        parent::__construct($context, $checkoutHelper);
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
