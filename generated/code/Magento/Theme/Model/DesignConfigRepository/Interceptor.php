<?php
namespace Magento\Theme\Model\DesignConfigRepository;

/**
 * Interceptor class for @see \Magento\Theme\Model\DesignConfigRepository
 */
class Interceptor extends \Magento\Theme\Model\DesignConfigRepository implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Theme\Model\Design\Config\Storage $configStorage, \Magento\Framework\App\Config\ReinitableConfigInterface $reinitableConfig, \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry)
    {
        $this->___init();
        parent::__construct($configStorage, $reinitableConfig, $indexerRegistry);
    }

    /**
     * {@inheritdoc}
     */
    public function getByScope($scope, $scopeId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getByScope');
        if (!$pluginInfo) {
            return parent::getByScope($scope, $scopeId);
        } else {
            return $this->___callPlugins('getByScope', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save(\Magento\Theme\Api\Data\DesignConfigInterface $designConfig)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        if (!$pluginInfo) {
            return parent::save($designConfig);
        } else {
            return $this->___callPlugins('save', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\Magento\Theme\Api\Data\DesignConfigInterface $designConfig)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'delete');
        if (!$pluginInfo) {
            return parent::delete($designConfig);
        } else {
            return $this->___callPlugins('delete', func_get_args(), $pluginInfo);
        }
    }
}
