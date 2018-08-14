<?php
namespace Magento\Catalog\Model\ResourceModel\Product\Attribute\Backend\Image;

/**
 * Interceptor class for @see \Magento\Catalog\Model\ResourceModel\Product\Attribute\Backend\Image
 */
class Interceptor extends \Magento\Catalog\Model\ResourceModel\Product\Attribute\Backend\Image implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Filesystem $filesystem, \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory)
    {
        $this->___init();
        parent::__construct($filesystem, $fileUploaderFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function validate($object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'validate');
        if (!$pluginInfo) {
            return parent::validate($object);
        } else {
            return $this->___callPlugins('validate', func_get_args(), $pluginInfo);
        }
    }
}
