<?php
namespace Magento\Shipping\Controller\Adminhtml\Order\Shipment\Save;

/**
 * Interceptor class for @see \Magento\Shipping\Controller\Adminhtml\Order\Shipment\Save
 */
class Interceptor extends \Magento\Shipping\Controller\Adminhtml\Order\Shipment\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Shipping\Controller\Adminhtml\Order\ShipmentLoader $shipmentLoader, \Magento\Shipping\Model\Shipping\LabelGenerator $labelGenerator, \Magento\Sales\Model\Order\Email\Sender\ShipmentSender $shipmentSender)
    {
        $this->___init();
        parent::__construct($context, $shipmentLoader, $labelGenerator, $shipmentSender);
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
