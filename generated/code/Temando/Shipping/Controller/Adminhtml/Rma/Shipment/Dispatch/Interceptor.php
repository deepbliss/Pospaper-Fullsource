<?php
namespace Temando\Shipping\Controller\Adminhtml\Rma\Shipment\Dispatch;

/**
 * Interceptor class for @see \Temando\Shipping\Controller\Adminhtml\Rma\Shipment\Dispatch
 */
class Interceptor extends \Temando\Shipping\Controller\Adminhtml\Rma\Shipment\Dispatch implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Temando\Shipping\Model\ResourceModel\Rma\RmaAccess $rmaAccess, \Temando\Shipping\Model\ResourceModel\Repository\ShipmentRepositoryInterface $shipmentRepository)
    {
        $this->___init();
        parent::__construct($context, $rmaAccess, $shipmentRepository);
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
