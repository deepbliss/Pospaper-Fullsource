<?php
namespace Magento\ImportExport\Controller\Adminhtml\Import\Start;

/**
 * Interceptor class for @see \Magento\ImportExport\Controller\Adminhtml\Import\Start
 */
class Interceptor extends \Magento\ImportExport\Controller\Adminhtml\Import\Start implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\ImportExport\Model\Report\ReportProcessorInterface $reportProcessor, \Magento\ImportExport\Model\History $historyModel, \Magento\ImportExport\Helper\Report $reportHelper, \Magento\ImportExport\Model\Import $importModel, \Magento\Framework\Message\ExceptionMessageFactoryInterface $exceptionMessageFactory)
    {
        $this->___init();
        parent::__construct($context, $reportProcessor, $historyModel, $reportHelper, $importModel, $exceptionMessageFactory);
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
