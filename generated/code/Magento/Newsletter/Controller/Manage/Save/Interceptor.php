<?php
namespace Magento\Newsletter\Controller\Manage\Save;

/**
 * Interceptor class for @see \Magento\Newsletter\Controller\Manage\Save
 */
class Interceptor extends \Magento\Newsletter\Controller\Manage\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Customer\Model\Session $customerSession, \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository, \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory)
    {
        $this->___init();
        parent::__construct($context, $customerSession, $formKeyValidator, $storeManager, $customerRepository, $subscriberFactory);
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
