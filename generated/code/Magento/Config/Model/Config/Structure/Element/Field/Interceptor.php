<?php
namespace Magento\Config\Model\Config\Structure\Element\Field;

/**
 * Interceptor class for @see \Magento\Config\Model\Config\Structure\Element\Field
 */
class Interceptor extends \Magento\Config\Model\Config\Structure\Element\Field implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\Module\Manager $moduleManager, \Magento\Config\Model\Config\BackendFactory $backendFactory, \Magento\Config\Model\Config\SourceFactory $sourceFactory, \Magento\Config\Model\Config\CommentFactory $commentFactory, \Magento\Framework\View\Element\BlockFactory $blockFactory, \Magento\Config\Model\Config\Structure\Element\Dependency\Mapper $dependencyMapper)
    {
        $this->___init();
        parent::__construct($storeManager, $moduleManager, $backendFactory, $sourceFactory, $commentFactory, $blockFactory, $dependencyMapper);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigPath()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getConfigPath');
        if (!$pluginInfo) {
            return parent::getConfigPath();
        } else {
            return $this->___callPlugins('getConfigPath', func_get_args(), $pluginInfo);
        }
    }
}
