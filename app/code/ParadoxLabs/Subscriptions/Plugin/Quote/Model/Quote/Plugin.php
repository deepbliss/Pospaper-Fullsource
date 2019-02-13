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

namespace ParadoxLabs\Subscriptions\Plugin\Quote\Model\Quote;

/**
 * Plugin Class
 */
class Plugin
{
    /**
     * @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\CollectionFactory
     */
    protected $subCollectionFactory;

    /**
     * Plugin constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\CollectionFactory $subCollectionFactory
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\CollectionFactory $subCollectionFactory
    ) {
        $this->subCollectionFactory = $subCollectionFactory;
    }

    /**
     * @param \Magento\Quote\Model\Quote $subject
     * @param \Closure $proceed
     * @return mixed
     */
    public function aroundDelete(
        \Magento\Quote\Model\Quote $subject,
        \Closure $proceed
    ) {
        if ($subject->getId() > 0) {
            /**
             * If the quote we're trying to delete is attached to a subscription, do not delete that quote. Ever.
             * The quote is data storage for all fulfillment info for a subscription. Deleting it would be... let's
             * go with 'bad'. It would be bad.
             *
             * Oh, also, the subscription has ON DELETE CASCADE, so there would be no trace whatsoever if it went away.
             */

            /** @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\Collection $collection */
            $collection = $this->subCollectionFactory->create();
            $collection->addFieldToFilter('quote_id', $subject->getId());

            if ($collection->getSize() > 0) {
                return $subject;
            }
        }

        return $proceed();
    }
}
