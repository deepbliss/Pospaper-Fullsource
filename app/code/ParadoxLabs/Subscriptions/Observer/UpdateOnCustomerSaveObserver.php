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

use ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\CollectionFactory as SubscriptionCollectionFactory;

/**
 * UpdateOnCustomerSaveObserver Class
 */
class UpdateOnCustomerSaveObserver implements \Magento\Framework\Event\ObserverInterface
{
    const SYNC_ATTRS = [
        'firstname' => 'customer_firstname',
        'lastname' => 'customer_lastname',
        'email' => 'customer_email',
    ];

    /**
     * @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\CollectionFactory
     */
    protected $subscriptionCollectionFactory;

    /**
     * @var \Magento\Framework\DataObject\Copy
     */
    protected $objectCopyService;

    /**
     * @var \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface
     */
    protected $subscriptionRepository;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Log\CollectionFactory
     */
    protected $logCollectionFactory;

    /**
     * UpdateOnCustomerSaveObserver constructor.
     *
     * @param SubscriptionCollectionFactory $subscriptionCollectionFactory
     * @param \Magento\Framework\DataObject\Copy $objectCopyService
     * @param \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface $subscriptionRepository
     * @param \ParadoxLabs\Subscriptions\Model\ResourceModel\Log\CollectionFactory $logCollectionFactory
     */
    public function __construct(
        SubscriptionCollectionFactory $subscriptionCollectionFactory,
        \Magento\Framework\DataObject\Copy $objectCopyService,
        \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface $subscriptionRepository,
        \ParadoxLabs\Subscriptions\Model\ResourceModel\Log\CollectionFactory $logCollectionFactory
    ) {
        $this->subscriptionCollectionFactory = $subscriptionCollectionFactory;
        $this->objectCopyService = $objectCopyService;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->logCollectionFactory = $logCollectionFactory;
    }

    /**
     * On customer save, copy name/email changes to that customer's subscriptions.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Customer\Model\Customer $customer */
        $customer = $observer->getEvent()->getData('customer');

        /**
         * If data has changed
         */
        if ($customer instanceof \Magento\Customer\Model\Customer && $this->customerDataChangedAndWeCare($customer)) {
            /**
             * Load customer's subscriptions
             */
            $subscriptions = $this->subscriptionCollectionFactory->create();
            $subscriptions->addFieldToFilter('customer_id', $customer->getId());

            /** @var \ParadoxLabs\Subscriptions\Model\Subscription $subscription */
            foreach ($subscriptions as $subscription) {
                try {
                    /**
                     * Update quote data
                     */

                    /** @var \Magento\Quote\Model\Quote $quote */
                    $quote = $subscription->getQuote();

                    $this->objectCopyService->copyFieldsetToTarget(
                        'customer_account',
                        'to_quote',
                        $customer,
                        $quote
                    );

                    $subscription->addRelatedObject($quote);

                    /**
                     * Update keyword search data
                     */
                    $subscription->generateKeywordFulltextData();
                    $keywords = $subscription->getKeywordFulltext();

                    $logs = $this->logCollectionFactory->create();
                    $logs->addSubscriptionFilter($subscription);
                    $logs->addFieldToFilter('order_increment_id', ['not_null' => true]);

                    /** @var \ParadoxLabs\Subscriptions\Model\Log $log */
                    foreach ($logs as $log) {
                        $keywords .= ' ' . $log->getOrderIncrementId();
                    }

                    $subscription->setKeywordFulltext($keywords);

                    /**
                     * Save
                     */
                    $this->subscriptionRepository->save($subscription);
                } catch (\Exception $e) {
                    // Noop
                }
            }
        }
    }

    /**
     * Did customer data change that we care about?
     *
     * @param \Magento\Customer\Model\Customer $customer
     * @return bool
     */
    protected function customerDataChangedAndWeCare(\Magento\Customer\Model\Customer $customer)
    {
        if ($customer->isObjectNew()) {
            return false;
        }

        // Do we have orig_data? If no (bugged before 2.2), blindly assume data changed.
        if ($customer->getOrigData() === null) {
            return true;
        }

        // New customer? They can't have existing subscriptions, don't care.
        if (empty($customer->getOrigData('entity_id'))) {
            return false;
        }

        // Data change?
        foreach (static::SYNC_ATTRS as $key => $otherKey) {
            if ($customer->getOrigData($key) !== $customer->getData($key)) {
                return true;
            }
        }

        return false;
    }
}
