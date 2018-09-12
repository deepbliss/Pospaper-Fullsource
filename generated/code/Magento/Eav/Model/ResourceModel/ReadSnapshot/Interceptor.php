<?php
namespace Magento\Eav\Model\ResourceModel\ReadSnapshot;

/**
 * Interceptor class for @see \Magento\Eav\Model\ResourceModel\ReadSnapshot
 */
class Interceptor extends \Magento\Eav\Model\ResourceModel\ReadSnapshot implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\EntityManager\MetadataPool $metadataPool, \Magento\Framework\Model\Entity\ScopeResolver $scopeResolver, \Psr\Log\LoggerInterface $logger, \Magento\Eav\Model\Config $config)
    {
        $this->___init();
        parent::__construct($metadataPool, $scopeResolver, $logger, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function execute($entityType, $entityData, $arguments = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        if (!$pluginInfo) {
            return parent::execute($entityType, $entityData, $arguments);
        } else {
            return $this->___callPlugins('execute', func_get_args(), $pluginInfo);
        }
    }
}
