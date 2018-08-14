<?php
namespace Magento\Shipping\Controller\Adminhtml\Order\Shipment\View;

/**
 * Interceptor class for @see \Magento\Shipping\Controller\Adminhtml\Order\Shipment\View
 */
class Interceptor extends \Magento\Shipping\Controller\Adminhtml\Order\Shipment\View implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Shipping\Controller\Adminhtml\Order\ShipmentLoader $shipmentLoader, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory)
    {
        $this->___init();
        parent::__construct($context, $shipmentLoader, $resultPageFactory, $resultForwardFactory);
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
