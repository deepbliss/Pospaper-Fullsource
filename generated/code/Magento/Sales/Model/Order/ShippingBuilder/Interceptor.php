<?php
namespace Magento\Sales\Model\Order\ShippingBuilder;

/**
 * Interceptor class for @see \Magento\Sales\Model\Order\ShippingBuilder
 */
class Interceptor extends \Magento\Sales\Model\Order\ShippingBuilder implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Sales\Model\OrderFactory $orderFactory, \Magento\Sales\Api\Data\ShippingInterfaceFactory $shippingFactory, \Magento\Sales\Api\Data\TotalInterfaceFactory $totalFactory)
    {
        $this->___init();
        parent::__construct($orderFactory, $shippingFactory, $totalFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function create()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'create');
        if (!$pluginInfo) {
            return parent::create();
        } else {
            return $this->___callPlugins('create', func_get_args(), $pluginInfo);
        }
    }
}
