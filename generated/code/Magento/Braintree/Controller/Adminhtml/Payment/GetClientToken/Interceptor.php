<?php
namespace Magento\Braintree\Controller\Adminhtml\Payment\GetClientToken;

/**
 * Interceptor class for @see \Magento\Braintree\Controller\Adminhtml\Payment\GetClientToken
 */
class Interceptor extends \Magento\Braintree\Controller\Adminhtml\Payment\GetClientToken implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Braintree\Gateway\Config\Config $config, \Magento\Braintree\Model\Adapter\BraintreeAdapterFactory $adapterFactory, \Magento\Backend\Model\Session\Quote $quoteSession)
    {
        $this->___init();
        parent::__construct($context, $config, $adapterFactory, $quoteSession);
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
