<?php
namespace Shreeji\ImportExportReviews\Controller\Adminhtml\Manage\Importreviews;

/**
 * Interceptor class for @see \Shreeji\ImportExportReviews\Controller\Adminhtml\Manage\Importreviews
 */
class Interceptor extends \Shreeji\ImportExportReviews\Controller\Adminhtml\Manage\Importreviews implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory, \Magento\Framework\Filesystem $fileSystem, \Magento\Backend\App\Action\Context $context, \Shreeji\ImportExportReviews\Model\ImportReviews $_importModel)
    {
        $this->___init();
        parent::__construct($fileUploaderFactory, $fileSystem, $context, $_importModel);
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
