<?php
namespace Magento\Config\Model\Config;

/**
 * Interceptor class for @see \Magento\Config\Model\Config
 */
class Interceptor extends \Magento\Config\Model\Config implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Config\ReinitableConfigInterface $config, \Magento\Framework\Event\ManagerInterface $eventManager, \Magento\Config\Model\Config\Structure $configStructure, \Magento\Framework\DB\TransactionFactory $transactionFactory, \Magento\Config\Model\Config\Loader $configLoader, \Magento\Framework\App\Config\ValueFactory $configValueFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, array $data = array())
    {
        $this->___init();
        parent::__construct($config, $eventManager, $configStructure, $transactionFactory, $configLoader, $configValueFactory, $storeManager, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        if (!$pluginInfo) {
            return parent::save();
        } else {
            return $this->___callPlugins('save', func_get_args(), $pluginInfo);
        }
    }
}
