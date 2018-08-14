<?php
namespace Magento\Shipping\Controller\Adminhtml\Shipment\MassPrintShippingLabel;

/**
 * Interceptor class for @see \Magento\Shipping\Controller\Adminhtml\Shipment\MassPrintShippingLabel
 */
class Interceptor extends \Magento\Shipping\Controller\Adminhtml\Shipment\MassPrintShippingLabel implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Ui\Component\MassAction\Filter $filter, \Magento\Framework\App\Response\Http\FileFactory $fileFactory, \Magento\Shipping\Model\Shipping\LabelGenerator $labelGenerator, \Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory $collectionFactory)
    {
        $this->___init();
        parent::__construct($context, $filter, $fileFactory, $labelGenerator, $collectionFactory);
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
