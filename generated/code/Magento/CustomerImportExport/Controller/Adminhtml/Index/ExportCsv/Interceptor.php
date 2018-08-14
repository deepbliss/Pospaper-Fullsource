<?php
namespace Magento\CustomerImportExport\Controller\Adminhtml\Index\ExportCsv;

/**
 * Interceptor class for @see \Magento\CustomerImportExport\Controller\Adminhtml\Index\ExportCsv
 */
class Interceptor extends \Magento\CustomerImportExport\Controller\Adminhtml\Index\ExportCsv implements \Magento\Framework\Interception\InterceptorInterface
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
