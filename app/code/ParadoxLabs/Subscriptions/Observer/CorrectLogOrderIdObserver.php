<?php
/**
 * Paradox Labs, Inc.
 * http://www.paradoxlabs.com
 * 717-431-3330
 *
 * Need help? Open a ticket in our support system:
 *  http://support.paradoxlabs.com
 *
 * @author      Ryan Hoerr <info@paradoxlabs.com>
 * @license     http://store.paradoxlabs.com/license.html
 */

namespace ParadoxLabs\Subscriptions\Observer;

use ParadoxLabs\Subscriptions\Api\LogRepositoryInterface;

/**
 * CorrectLogOrderIdObserver Class
 */
class CorrectLogOrderIdObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    protected $config;

    /**
     * @var LogRepositoryInterface
     */
    protected $logRepository;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Log\CollectionFactory
     */
    protected $logCollectionFactory;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\ItemManager
     */
    protected $itemManager;

    /**
     * GenerateSubscriptionsObserver constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     * @param LogRepositoryInterface $logRepository
     * @param \ParadoxLabs\Subscriptions\Model\ResourceModel\Log\CollectionFactory $logCollectionFactory
     * @param \ParadoxLabs\Subscriptions\Model\Service\ItemManager $itemManager
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Model\Config $config,
        \ParadoxLabs\Subscriptions\Api\LogRepositoryInterface $logRepository,
        \ParadoxLabs\Subscriptions\Model\ResourceModel\Log\CollectionFactory $logCollectionFactory,
        \ParadoxLabs\Subscriptions\Model\Service\ItemManager $itemManager
    ) {
        $this->config = $config;
        $this->logRepository = $logRepository;
        $this->logCollectionFactory = $logCollectionFactory;
        $this->itemManager = $itemManager;
    }

    /**
     * Override item price et al when adding subscriptions to the cart.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->config->moduleIsActive() !== true) {
            return;
        }

        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getData('order');

        try {
            /** @var \Magento\Sales\Model\Order\Item $item */
            foreach ($order->getAllVisibleItems() as $item) {
                if ($this->itemManager->isSubscription($item) === true) {
                    $this->fillLogOrderIds($order);
                }
            }
        } catch (\Exception $e) {
            // Do nothing on exception.
        }
    }

    /**
     * Add order ID to any subscription logs associated with the order (since it's not known at time of initial save).
     *
     * @param \Magento\Sales\Model\Order $order
     * @return void
     */
    protected function fillLogOrderIds(\Magento\Sales\Model\Order $order)
    {
        /** @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Log\Collection $logCollection */
        $logCollection = $this->logCollectionFactory->create();
        $logCollection->addFieldToFilter('order_increment_id', $order->getIncrementId());
        $logCollection->addFieldToFilter('order_id', ['null' => true]);

        /** @var \ParadoxLabs\Subscriptions\Api\Data\LogInterface $log */
        foreach ($logCollection as $log) {
            $log->setOrderId($order->getId());
            $this->logRepository->save($log);
        }
    }
}
