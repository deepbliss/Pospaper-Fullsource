<?php
namespace Magento\Backend\Model\Auth;

/**
 * Interceptor class for @see \Magento\Backend\Model\Auth
 */
class Interceptor extends \Magento\Backend\Model\Auth implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Event\ManagerInterface $eventManager, \Magento\Backend\Helper\Data $backendData, \Magento\Backend\Model\Auth\StorageInterface $authStorage, \Magento\Backend\Model\Auth\Credential\StorageInterface $credentialStorage, \Magento\Framework\App\Config\ScopeConfigInterface $coreConfig, \Magento\Framework\Data\Collection\ModelFactory $modelFactory)
    {
        $this->___init();
        parent::__construct($eventManager, $backendData, $authStorage, $credentialStorage, $coreConfig, $modelFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function login($username, $password)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'login');
        if (!$pluginInfo) {
            return parent::login($username, $password);
        } else {
            return $this->___callPlugins('login', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function logout()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'logout');
        if (!$pluginInfo) {
            return parent::logout();
        } else {
            return $this->___callPlugins('logout', func_get_args(), $pluginInfo);
        }
    }
}
