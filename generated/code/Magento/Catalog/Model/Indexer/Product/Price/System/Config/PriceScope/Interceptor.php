<?php
namespace Magento\Catalog\Model\Indexer\Product\Price\System\Config\PriceScope;

/**
 * Interceptor class for @see \Magento\Catalog\Model\Indexer\Product\Price\System\Config\PriceScope
 */
class Interceptor extends \Magento\Catalog\Model\Indexer\Product\Price\System\Config\PriceScope implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\App\Config\ScopeConfigInterface $config, \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList, \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $config, $cacheTypeList, $indexerRegistry, $resource, $resourceCollection, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'afterSave');
        if (!$pluginInfo) {
            return parent::afterSave();
        } else {
            return $this->___callPlugins('afterSave', func_get_args(), $pluginInfo);
        }
    }
}
