<?php
namespace Magento\Customer\Controller\Account\LoginPost;

/**
 * Interceptor class for @see \Magento\Customer\Controller\Account\LoginPost
 */
class Interceptor extends \Magento\Customer\Controller\Account\LoginPost implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Customer\Model\Session $customerSession, \Magento\Customer\Api\AccountManagementInterface $customerAccountManagement, \Magento\Customer\Model\Url $customerHelperData, \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator, \Magento\Customer\Model\Account\Redirect $accountRedirect)
    {
        $this->___init();
        parent::__construct($context, $customerSession, $customerAccountManagement, $customerHelperData, $formKeyValidator, $accountRedirect);
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
