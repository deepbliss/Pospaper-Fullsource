<?php
namespace Magento\Sales\Controller\Adminhtml\Order\Invoice\Save;

/**
 * Interceptor class for @see \Magento\Sales\Controller\Adminhtml\Order\Invoice\Save
 */
class Interceptor extends \Magento\Sales\Controller\Adminhtml\Order\Invoice\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $registry, \Magento\Sales\Model\Order\Email\Sender\InvoiceSender $invoiceSender, \Magento\Sales\Model\Order\Email\Sender\ShipmentSender $shipmentSender, \Magento\Sales\Model\Order\ShipmentFactory $shipmentFactory, \Magento\Sales\Model\Service\InvoiceService $invoiceService)
    {
        $this->___init();
        parent::__construct($context, $registry, $invoiceSender, $shipmentSender, $shipmentFactory, $invoiceService);
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
