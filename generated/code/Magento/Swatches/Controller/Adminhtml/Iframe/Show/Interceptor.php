<?php
namespace Magento\Swatches\Controller\Adminhtml\Iframe\Show;

/**
 * Interceptor class for @see \Magento\Swatches\Controller\Adminhtml\Iframe\Show
 */
class Interceptor extends \Magento\Swatches\Controller\Adminhtml\Iframe\Show implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Swatches\Helper\Media $swatchHelper, \Magento\Framework\Image\AdapterFactory $adapterFactory, \Magento\Catalog\Model\Product\Media\Config $config, \Magento\Framework\Filesystem $filesystem, \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory)
    {
        $this->___init();
        parent::__construct($context, $swatchHelper, $adapterFactory, $config, $filesystem, $uploaderFactory);
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
