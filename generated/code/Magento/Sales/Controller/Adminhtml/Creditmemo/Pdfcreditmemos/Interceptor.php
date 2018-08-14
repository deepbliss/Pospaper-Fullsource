<?php
namespace Magento\Sales\Controller\Adminhtml\Creditmemo\Pdfcreditmemos;

/**
 * Interceptor class for @see \Magento\Sales\Controller\Adminhtml\Creditmemo\Pdfcreditmemos
 */
class Interceptor extends \Magento\Sales\Controller\Adminhtml\Creditmemo\Pdfcreditmemos implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Ui\Component\MassAction\Filter $filter, \Magento\Sales\Model\Order\Pdf\Creditmemo $pdfCreditmemo, \Magento\Framework\Stdlib\DateTime\DateTime $dateTime, \Magento\Framework\App\Response\Http\FileFactory $fileFactory, \Magento\Sales\Model\ResourceModel\Order\Creditmemo\CollectionFactory $collectionFactory)
    {
        $this->___init();
        parent::__construct($context, $filter, $pdfCreditmemo, $dateTime, $fileFactory, $collectionFactory);
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
