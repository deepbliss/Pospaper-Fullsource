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

/**
 * RemoveIntervalsObserver Class
 */
class RemoveIntervalsObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \ParadoxLabs\Subscriptions\Api\IntervalManagerInterface
     */
    protected $intervalManager;

    /**
     * RemoveIntervalsObserver constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Api\IntervalManagerInterface $intervalManager
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Api\IntervalManagerInterface $intervalManager
    ) {
        $this->intervalManager = $intervalManager;
    }

    /**
     * On product delete, remove any intervals that might exist to avoid orphaned data.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @throws \Exception
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $observer->getEvent()->getData('product');

        $this->intervalManager->removeProductIntervals($product);
    }
}
