<?php
namespace Magento\User\Controller\Adminhtml\Auth\Forgotpassword;

/**
 * Interceptor class for @see \Magento\User\Controller\Adminhtml\Auth\Forgotpassword
 */
class Interceptor extends \Magento\User\Controller\Adminhtml\Auth\Forgotpassword implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\User\Model\UserFactory $userFactory, \Magento\Security\Model\SecurityManager $securityManager)
    {
        $this->___init();
        parent::__construct($context, $userFactory, $securityManager);
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
