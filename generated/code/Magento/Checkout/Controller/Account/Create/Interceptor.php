<?php
namespace Magento\Checkout\Controller\Account\Create;

/**
 * Interceptor class for @see \Magento\Checkout\Controller\Account\Create
 */
class Interceptor extends \Magento\Checkout\Controller\Account\Create implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Customer\Model\Session $customerSession, \Magento\Sales\Api\OrderCustomerManagementInterface $orderCustomerService)
    {
        $this->___init();
        parent::__construct($context, $checkoutSession, $customerSession, $orderCustomerService);
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
