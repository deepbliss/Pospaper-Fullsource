<?php
namespace Magento\Sales\Controller\Guest\Reorder;

/**
 * Interceptor class for @see \Magento\Sales\Controller\Guest\Reorder
 */
class Interceptor extends \Magento\Sales\Controller\Guest\Reorder implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Sales\Controller\Guest\OrderLoader $orderLoader, \Magento\Framework\Registry $registry)
    {
        $this->___init();
        parent::__construct($context, $orderLoader, $registry);
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
