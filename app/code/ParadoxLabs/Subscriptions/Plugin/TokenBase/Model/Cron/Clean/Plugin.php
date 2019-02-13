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

namespace ParadoxLabs\Subscriptions\Plugin\TokenBase\Model\Cron\Clean;

use ParadoxLabs\Subscriptions\Model\Source\Status;

/**
 * Plugin Class
 */
class Plugin
{
    /**
     * @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\CollectionFactory
     */
    protected $subsCollectionFactory;

    /**
     * Plugin constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\CollectionFactory $subsCollectionFactory
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\CollectionFactory $subsCollectionFactory
    ) {
        $this->subsCollectionFactory = $subsCollectionFactory;
    }

    /**
     * Prevent automatic (120-day) pruning of hidden cards associated with subscriptions.
     *
     * @param \ParadoxLabs\TokenBase\Model\Cron\Clean $subject
     * @param \ParadoxLabs\TokenBase\Model\ResourceModel\Card\Collection $cards
     * @return array
     */
    public function beforeDeleteCards(
        \ParadoxLabs\TokenBase\Model\Cron\Clean $subject,
        \ParadoxLabs\TokenBase\Model\ResourceModel\Card\Collection $cards
    ) {
        /**
         * Fetch all active subscriptions having any of the given card IDs
         */
        $subscriptions = $this->subsCollectionFactory->create();
        $subscriptions->joinPaymentCard();
        $subscriptions->addFieldToFilter(
            'status',
            [
                'nin' => [
                    Status::STATUS_CANCELED,
                    Status::STATUS_COMPLETE,
                ],
            ]
        );
        $subscriptions->addFieldToFilter(
            'tokenbase_id',
            [
                'in' => $cards->getAllIds(),
            ]
        );

        $undeletableCards = [];
        foreach ($subscriptions as $subscription) {
            $undeletableCards[$subscription->getData('tokenbase_id')] = 1;
        }

        /**
         * If any exist, remove them from the card collection before we go and actually ... remove them.
         */
        if (!empty($undeletableCards)) {
            /** @var \ParadoxLabs\TokenBase\Model\Card $card */
            foreach ($cards as $key => $card) {
                if (isset($undeletableCards[$card->getId()])) {
                    $cards->removeItemByKey($key);
                }
            }
        }

        return [
            $cards
        ];
    }
}
