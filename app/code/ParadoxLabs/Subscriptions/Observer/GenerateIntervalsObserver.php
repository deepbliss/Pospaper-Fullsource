<?php
/**
 * Paradox Labs, Inc.
 * http://www.paradoxlabs.com
 * 717-431-3330
 */

namespace ParadoxLabs\Subscriptions\Observer;

/**
 * GenerateIntervalsObserver Class
 */
class GenerateIntervalsObserver implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \ParadoxLabs\Subscriptions\Api\IntervalManagerInterface
     */
    protected $intervalManager;

    /**
     * GenerateIntervalsObserver constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Api\IntervalManagerInterface $intervalManager
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Api\IntervalManagerInterface $intervalManager
    ) {
        $this->intervalManager = $intervalManager;
    }

    /**
     * On product save, sync subscription options/metadata to the intervals table.
     *
     * We leave this active even if module is "disabled" so that things don't get totally out of sync by mistake.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @throws \Exception
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $observer->getEvent()->getData('product');

        if ((int)$product->getData('subscription_active') === 1
            && (int)$product->getData('is_duplicate') !== 1) {
            $this->intervalManager->updateProductIntervals($product);
        } else {
            $this->intervalManager->removeProductIntervals($product);
        }
    }
}
