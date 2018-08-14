<?php
namespace Magento\User\Controller\Adminhtml\User\Role\RoleGrid;

/**
 * Interceptor class for @see \Magento\User\Controller\Adminhtml\User\Role\RoleGrid
 */
class Interceptor extends \Magento\User\Controller\Adminhtml\User\Role\RoleGrid implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\Authorization\Model\RoleFactory $roleFactory, \Magento\User\Model\UserFactory $userFactory, \Magento\Authorization\Model\RulesFactory $rulesFactory, \Magento\Backend\Model\Auth\Session $authSession, \Magento\Framework\Filter\FilterManager $filterManager)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $roleFactory, $userFactory, $rulesFactory, $authSession, $filterManager);
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
