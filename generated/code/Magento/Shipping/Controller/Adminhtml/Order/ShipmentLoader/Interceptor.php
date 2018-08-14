<?php
namespace Magento\Shipping\Controller\Adminhtml\Order\ShipmentLoader;

/**
 * Interceptor class for @see \Magento\Shipping\Controller\Adminhtml\Order\ShipmentLoader
 */
class Interceptor extends \Magento\Shipping\Controller\Adminhtml\Order\ShipmentLoader implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Message\ManagerInterface $messageManager, \Magento\Framework\Registry $registry, \Magento\Sales\Api\ShipmentRepositoryInterface $shipmentRepository, \Magento\Sales\Api\OrderRepositoryInterface $orderRepository, \Magento\Sales\Model\Order\ShipmentDocumentFactory $documentFactory, \Magento\Sales\Api\Data\ShipmentTrackCreationInterfaceFactory $trackFactory, \Magento\Sales\Api\Data\ShipmentItemCreationInterfaceFactory $itemFactory, array $data = array())
    {
        $this->___init();
        parent::__construct($messageManager, $registry, $shipmentRepository, $orderRepository, $documentFactory, $trackFactory, $itemFactory, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function load()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'load');
        if (!$pluginInfo) {
            return parent::load();
        } else {
            return $this->___callPlugins('load', func_get_args(), $pluginInfo);
        }
    }
}
