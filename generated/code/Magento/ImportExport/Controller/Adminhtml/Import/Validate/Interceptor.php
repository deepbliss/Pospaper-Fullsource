<?php
namespace Magento\ImportExport\Controller\Adminhtml\Import\Validate;

/**
 * Interceptor class for @see \Magento\ImportExport\Controller\Adminhtml\Import\Validate
 */
class Interceptor extends \Magento\ImportExport\Controller\Adminhtml\Import\Validate implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\ImportExport\Model\Report\ReportProcessorInterface $reportProcessor, \Magento\ImportExport\Model\History $historyModel, \Magento\ImportExport\Helper\Report $reportHelper)
    {
        $this->___init();
        parent::__construct($context, $reportProcessor, $historyModel, $reportHelper);
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
