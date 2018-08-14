<?php
namespace Magento\Cms\Controller\Adminhtml\Wysiwyg\Images\DeleteFolder;

/**
 * Interceptor class for @see \Magento\Cms\Controller\Adminhtml\Wysiwyg\Images\DeleteFolder
 */
class Interceptor extends \Magento\Cms\Controller\Adminhtml\Wysiwyg\Images\DeleteFolder implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Framework\Controller\Result\RawFactory $resultRawFactory, \Magento\Framework\App\Filesystem\DirectoryResolver $directoryResolver = null)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $resultJsonFactory, $resultRawFactory, $directoryResolver);
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
