<?php
namespace Magento\Sales\Model\Order\Config;

/**
 * Interceptor class for @see \Magento\Sales\Model\Order\Config
 */
class Interceptor extends \Magento\Sales\Model\Order\Config implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Sales\Model\Order\StatusFactory $orderStatusFactory, \Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $orderStatusCollectionFactory, \Magento\Framework\App\State $state)
    {
        $this->___init();
        parent::__construct($orderStatusFactory, $orderStatusCollectionFactory, $state);
    }

    /**
     * {@inheritdoc}
     */
    public function getStateStatuses($state, $addLabels = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStateStatuses');
        if (!$pluginInfo) {
            return parent::getStateStatuses($state, $addLabels);
        } else {
            return $this->___callPlugins('getStateStatuses', func_get_args(), $pluginInfo);
        }
    }
}
