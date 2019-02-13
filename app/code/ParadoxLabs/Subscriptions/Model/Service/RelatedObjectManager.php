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

namespace ParadoxLabs\Subscriptions\Model\Service;

/**
 * RelatedObjectManager Class
 */
class RelatedObjectManager
{
    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var \ParadoxLabs\Subscriptions\Api\LogRepositoryInterface
     */
    private $logRepository;

    /**
     * @var \ParadoxLabs\TokenBase\Api\CardRepositoryInterface
     */
    private $cardRepository;

    /**
     * RelatedObjectManager constructor.
     *
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository *Proxy
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository *Proxy
     * @param \ParadoxLabs\Subscriptions\Api\LogRepositoryInterface $logRepository *Proxy
     * @param \ParadoxLabs\TokenBase\Api\CardRepositoryInterface $cardRepository *Proxy
     */
    public function __construct(
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \ParadoxLabs\Subscriptions\Api\LogRepositoryInterface $logRepository,
        \ParadoxLabs\TokenBase\Api\CardRepositoryInterface $cardRepository
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->orderRepository = $orderRepository;
        $this->logRepository = $logRepository;
        $this->cardRepository = $cardRepository;
    }

    /**
     * Handle saving of various data types in conjunction with the associated subscription.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @param \Magento\Framework\Model\AbstractModel[] $relatedObjects
     */
    public function saveRelatedObjects(
        \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription,
        $relatedObjects
    ) {
        if (empty($relatedObjects)) {
            return;
        }

        /** @var \Magento\Framework\Model\AbstractModel $object */
        foreach ($relatedObjects as $object) {
            if ($object->getData('subscription_id') != $subscription->getId()) {
                $object->setData('subscription_id', $subscription->getId());
            }

            if ($object->hasDataChanges()) {
                $this->saveObject($object);
            }
        }
    }

    /**
     * Save the given object via repository. Supports a selection of types via their associated repositories.
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return void
     */
    public function saveObject(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object instanceof \Magento\Quote\Api\Data\CartInterface) {
            $object->setUpdatedAt('2038-01-01 00:00:00');
            $this->quoteRepository->save($object);
        } elseif ($object instanceof \ParadoxLabs\Subscriptions\Api\Data\LogInterface) {
            $this->logRepository->save($object);
        } elseif ($object instanceof \Magento\Sales\Api\Data\OrderInterface) {
            $this->orderRepository->save($object);
        } elseif ($object instanceof \ParadoxLabs\TokenBase\Api\Data\CardInterface) {
            $this->cardRepository->save($object);
        }
    }
}
