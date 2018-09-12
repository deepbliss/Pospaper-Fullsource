<?php
namespace Magento\Sales\Model\Service\OrderService;

/**
 * Interceptor class for @see \Magento\Sales\Model\Service\OrderService
 */
class Interceptor extends \Magento\Sales\Model\Service\OrderService implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Sales\Api\OrderRepositoryInterface $orderRepository, \Magento\Sales\Api\OrderStatusHistoryRepositoryInterface $historyRepository, \Magento\Framework\Api\SearchCriteriaBuilder $criteriaBuilder, \Magento\Framework\Api\FilterBuilder $filterBuilder, \Magento\Sales\Model\OrderNotifier $notifier, \Magento\Framework\Event\ManagerInterface $eventManager, \Magento\Sales\Model\Order\Email\Sender\OrderCommentSender $orderCommentSender)
    {
        $this->___init();
        parent::__construct($orderRepository, $historyRepository, $criteriaBuilder, $filterBuilder, $notifier, $eventManager, $orderCommentSender);
    }

    /**
     * {@inheritdoc}
     */
    public function cancel($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'cancel');
        if (!$pluginInfo) {
            return parent::cancel($id);
        } else {
            return $this->___callPlugins('cancel', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCommentsList($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCommentsList');
        if (!$pluginInfo) {
            return parent::getCommentsList($id);
        } else {
            return $this->___callPlugins('getCommentsList', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addComment($id, \Magento\Sales\Api\Data\OrderStatusHistoryInterface $statusHistory)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addComment');
        if (!$pluginInfo) {
            return parent::addComment($id, $statusHistory);
        } else {
            return $this->___callPlugins('addComment', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function notify($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'notify');
        if (!$pluginInfo) {
            return parent::notify($id);
        } else {
            return $this->___callPlugins('notify', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStatus');
        if (!$pluginInfo) {
            return parent::getStatus($id);
        } else {
            return $this->___callPlugins('getStatus', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hold($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'hold');
        if (!$pluginInfo) {
            return parent::hold($id);
        } else {
            return $this->___callPlugins('hold', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function unHold($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'unHold');
        if (!$pluginInfo) {
            return parent::unHold($id);
        } else {
            return $this->___callPlugins('unHold', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function place(\Magento\Sales\Api\Data\OrderInterface $order)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'place');
        if (!$pluginInfo) {
            return parent::place($order);
        } else {
            return $this->___callPlugins('place', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setState(\Magento\Sales\Api\Data\OrderInterface $order, $state, $status = false, $comment = '', $isCustomerNotified = null, $shouldProtectState = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setState');
        if (!$pluginInfo) {
            return parent::setState($order, $state, $status, $comment, $isCustomerNotified, $shouldProtectState);
        } else {
            return $this->___callPlugins('setState', func_get_args(), $pluginInfo);
        }
    }
}
