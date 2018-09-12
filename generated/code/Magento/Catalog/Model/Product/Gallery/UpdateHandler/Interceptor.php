<?php
namespace Magento\Catalog\Model\Product\Gallery\UpdateHandler;

/**
 * Interceptor class for @see \Magento\Catalog\Model\Product\Gallery\UpdateHandler
 */
class Interceptor extends \Magento\Catalog\Model\Product\Gallery\UpdateHandler implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\EntityManager\MetadataPool $metadataPool, \Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository, \Magento\Catalog\Model\ResourceModel\Product\Gallery $resourceModel, \Magento\Framework\Json\Helper\Data $jsonHelper, \Magento\Catalog\Model\Product\Media\Config $mediaConfig, \Magento\Framework\Filesystem $filesystem, \Magento\MediaStorage\Helper\File\Storage\Database $fileStorageDb)
    {
        $this->___init();
        parent::__construct($metadataPool, $attributeRepository, $resourceModel, $jsonHelper, $mediaConfig, $filesystem, $fileStorageDb);
    }

    /**
     * {@inheritdoc}
     */
    public function execute($product, $arguments = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        if (!$pluginInfo) {
            return parent::execute($product, $arguments);
        } else {
            return $this->___callPlugins('execute', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAttribute');
        if (!$pluginInfo) {
            return parent::getAttribute();
        } else {
            return $this->___callPlugins('getAttribute', func_get_args(), $pluginInfo);
        }
    }
}
