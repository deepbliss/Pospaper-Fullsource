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
    public function getStateDefaultStatus($state)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStateDefaultStatus');
        if (!$pluginInfo) {
            return parent::getStateDefaultStatus($state);
        } else {
            return $this->___callPlugins('getStateDefaultStatus', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusLabel($code)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStatusLabel');
        if (!$pluginInfo) {
            return parent::getStatusLabel($code);
        } else {
            return $this->___callPlugins('getStatusLabel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStateLabel($state)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStateLabel');
        if (!$pluginInfo) {
            return parent::getStateLabel($state);
        } else {
            return $this->___callPlugins('getStateLabel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStatuses()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStatuses');
        if (!$pluginInfo) {
            return parent::getStatuses();
        } else {
            return $this->___callPlugins('getStatuses', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStates()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStates');
        if (!$pluginInfo) {
            return parent::getStates();
        } else {
            return $this->___callPlugins('getStates', func_get_args(), $pluginInfo);
        }
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

    /**
     * {@inheritdoc}
     */
    public function getVisibleOnFrontStatuses()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getVisibleOnFrontStatuses');
        if (!$pluginInfo) {
            return parent::getVisibleOnFrontStatuses();
        } else {
            return $this->___callPlugins('getVisibleOnFrontStatuses', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getInvisibleOnFrontStatuses()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getInvisibleOnFrontStatuses');
        if (!$pluginInfo) {
            return parent::getInvisibleOnFrontStatuses();
        } else {
            return $this->___callPlugins('getInvisibleOnFrontStatuses', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStateLabelByStateAndStatus($state, $status)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStateLabelByStateAndStatus');
        if (!$pluginInfo) {
            return parent::getStateLabelByStateAndStatus($state, $status);
        } else {
            return $this->___callPlugins('getStateLabelByStateAndStatus', func_get_args(), $pluginInfo);
        }
    }
}
