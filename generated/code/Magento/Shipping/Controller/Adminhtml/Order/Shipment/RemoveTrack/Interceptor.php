<?php
namespace Magento\Shipping\Controller\Adminhtml\Order\Shipment\RemoveTrack;

/**
 * Interceptor class for @see \Magento\Shipping\Controller\Adminhtml\Order\Shipment\RemoveTrack
 */
class Interceptor extends \Magento\Shipping\Controller\Adminhtml\Order\Shipment\RemoveTrack implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Shipping\Controller\Adminhtml\Order\ShipmentLoader $shipmentLoader)
    {
        $this->___init();
        parent::__construct($context, $shipmentLoader);
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
