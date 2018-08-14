<?php
namespace Magento\Indexer\Model\Config\Data;

/**
 * Interceptor class for @see \Magento\Indexer\Model\Config\Data
 */
class Interceptor extends \Magento\Indexer\Model\Config\Data implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Indexer\Config\Reader $reader, \Magento\Framework\Config\CacheInterface $cache, \Magento\Indexer\Model\ResourceModel\Indexer\State\Collection $stateCollection, $cacheId = 'indexer_config', \Magento\Framework\Serialize\SerializerInterface $serializer = null)
    {
        $this->___init();
        parent::__construct($reader, $cache, $stateCollection, $cacheId, $serializer);
    }

    /**
     * {@inheritdoc}
     */
    public function get($path = null, $default = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'get');
        if (!$pluginInfo) {
            return parent::get($path, $default);
        } else {
            return $this->___callPlugins('get', func_get_args(), $pluginInfo);
        }
    }
}
