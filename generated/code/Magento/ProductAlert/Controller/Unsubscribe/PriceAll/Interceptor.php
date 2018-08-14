<?php
namespace Magento\ProductAlert\Controller\Unsubscribe\PriceAll;

/**
 * Interceptor class for @see \Magento\ProductAlert\Controller\Unsubscribe\PriceAll
 */
class Interceptor extends \Magento\ProductAlert\Controller\Unsubscribe\PriceAll implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Customer\Model\Session $customerSession)
    {
        $this->___init();
        parent::__construct($context, $customerSession);
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
