<?php
namespace Magento\Customer\Controller\Adminhtml\Locks\Unlock;

/**
 * Interceptor class for @see \Magento\Customer\Controller\Adminhtml\Locks\Unlock
 */
class Interceptor extends \Magento\Customer\Controller\Adminhtml\Locks\Unlock implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Customer\Model\AuthenticationInterface $authentication)
    {
        $this->___init();
        parent::__construct($context, $authentication);
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
