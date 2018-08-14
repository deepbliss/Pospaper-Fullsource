<?php
namespace Magento\Downloadable\Controller\Adminhtml\Downloadable\File\Upload;

/**
 * Interceptor class for @see \Magento\Downloadable\Controller\Adminhtml\Downloadable\File\Upload
 */
class Interceptor extends \Magento\Downloadable\Controller\Adminhtml\Downloadable\File\Upload implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Downloadable\Model\Link $link, \Magento\Downloadable\Model\Sample $sample, \Magento\Downloadable\Helper\File $fileHelper, \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory, \Magento\MediaStorage\Helper\File\Storage\Database $storageDatabase)
    {
        $this->___init();
        parent::__construct($context, $link, $sample, $fileHelper, $uploaderFactory, $storageDatabase);
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
