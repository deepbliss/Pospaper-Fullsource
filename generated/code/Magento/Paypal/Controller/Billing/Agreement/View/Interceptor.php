<?php
namespace Magento\Paypal\Controller\Billing\Agreement\View;

/**
 * Interceptor class for @see \Magento\Paypal\Controller\Billing\Agreement\View
 */
class Interceptor extends \Magento\Paypal\Controller\Billing\Agreement\View implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry);
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
