<?php
namespace Magento\TaxImportExport\Controller\Adminhtml\Rate\ExportPost;

/**
 * Interceptor class for @see \Magento\TaxImportExport\Controller\Adminhtml\Rate\ExportPost
 */
class Interceptor extends \Magento\TaxImportExport\Controller\Adminhtml\Rate\ExportPost implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\App\Response\Http\FileFactory $fileFactory)
    {
        $this->___init();
        parent::__construct($context, $fileFactory);
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
