<?php
namespace Magento\User\Controller\Adminhtml\User\Validate;

/**
 * Interceptor class for @see \Magento\User\Controller\Adminhtml\User\Validate
 */
class Interceptor extends \Magento\User\Controller\Adminhtml\User\Validate implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\User\Model\UserFactory $userFactory)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $userFactory);
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
