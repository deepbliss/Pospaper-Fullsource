<?php
namespace Magento\Sales\Model\Service\CreditmemoService;

/**
 * Interceptor class for @see \Magento\Sales\Model\Service\CreditmemoService
 */
class Interceptor extends \Magento\Sales\Model\Service\CreditmemoService implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Sales\Api\CreditmemoRepositoryInterface $creditmemoRepository, \Magento\Sales\Api\CreditmemoCommentRepositoryInterface $creditmemoCommentRepository, \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder, \Magento\Framework\Api\FilterBuilder $filterBuilder, \Magento\Sales\Model\Order\CreditmemoNotifier $creditmemoNotifier, \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency, \Magento\Framework\Event\ManagerInterface $eventManager)
    {
        $this->___init();
        parent::__construct($creditmemoRepository, $creditmemoCommentRepository, $searchCriteriaBuilder, $filterBuilder, $creditmemoNotifier, $priceCurrency, $eventManager);
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
    public function refund(\Magento\Sales\Api\Data\CreditmemoInterface $creditmemo, $offlineRequested = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'refund');
        if (!$pluginInfo) {
            return parent::refund($creditmemo, $offlineRequested);
        } else {
            return $this->___callPlugins('refund', func_get_args(), $pluginInfo);
        }
    }
}
