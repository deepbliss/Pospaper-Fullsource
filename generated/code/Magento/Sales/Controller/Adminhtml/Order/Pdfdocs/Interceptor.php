<?php
namespace Magento\Sales\Controller\Adminhtml\Order\Pdfdocs;

/**
 * Interceptor class for @see \Magento\Sales\Controller\Adminhtml\Order\Pdfdocs
 */
class Interceptor extends \Magento\Sales\Controller\Adminhtml\Order\Pdfdocs implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Ui\Component\MassAction\Filter $filter, \Magento\Framework\App\Response\Http\FileFactory $fileFactory, \Magento\Sales\Model\Order\Pdf\Invoice $pdfInvoice, \Magento\Sales\Model\Order\Pdf\Shipment $pdfShipment, \Magento\Sales\Model\Order\Pdf\Creditmemo $pdfCreditmemo, \Magento\Framework\Stdlib\DateTime\DateTime $dateTime, \Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory $shipmentCollectionFactory, \Magento\Sales\Model\ResourceModel\Order\Invoice\CollectionFactory $invoiceCollectionFactory, \Magento\Sales\Model\ResourceModel\Order\Creditmemo\CollectionFactory $creditmemoCollectionFactory, \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory)
    {
        $this->___init();
        parent::__construct($context, $filter, $fileFactory, $pdfInvoice, $pdfShipment, $pdfCreditmemo, $dateTime, $shipmentCollectionFactory, $invoiceCollectionFactory, $creditmemoCollectionFactory, $orderCollectionFactory);
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
