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

namespace ParadoxLabs\Subscriptions\Model\Cron;

/**
 * Notify Class
 */
class Notify
{
    /**
     * @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \ParadoxLabs\Subscriptions\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $dateProcessor;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $consoleOutputStream;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Service\EmailSender
     */
    protected $emailSender;

    /**
     * @var \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface
     */
    protected $subscriptionRepository;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    protected $config;

    /**
     * Bill constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\CollectionFactory $collectionFactory
     * @param \ParadoxLabs\Subscriptions\Helper\Data $helper
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $dateProcessor
     * @param \ParadoxLabs\Subscriptions\Model\Service\EmailSender $emailSender *Proxy
     * @param \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface $subscriptionRepository
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\CollectionFactory $collectionFactory,
        \ParadoxLabs\Subscriptions\Helper\Data $helper,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $dateProcessor,
        \ParadoxLabs\Subscriptions\Model\Service\EmailSender $emailSender,
        \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface $subscriptionRepository,
        \ParadoxLabs\Subscriptions\Model\Config $config
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->helper = $helper;
        $this->dateProcessor = $dateProcessor;
        $this->emailSender = $emailSender;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->config = $config;
    }

    /**
     * Send notification emails to any upcoming subscriptions within the notify threshold.
     *
     * @return void
     */
    public function notifyUpcomingSubscriptions()
    {
        if ($this->config->billingNoticesAreEnabled() !== true || $this->config->moduleIsActive() !== true) {
            return;
        }

        $subscriptions = $this->fetchUpcomingSubscriptions();

        if (count($subscriptions)) {
            $this->log(
                __(
                    'CRON-notify: Emailing %1 subscriptions.',
                    count($subscriptions)
                )
            );

            $notified = 0;
            $failed = 0;

            foreach ($subscriptions as $subscription) {
                try {
                    $this->notifySubscription($subscription);

                    $success = true;
                } catch (\Exception $e) {
                    $success = false;

                    $this->log(
                        __(
                            'CRON-notify: Subscription %1 failed. Error: %2',
                            $subscription->getIncrementId(),
                            $e->getMessage()
                        )
                    );
                }

                if ($success === true) {
                    $notified++;
                } else {
                    $failed++;
                }
            }

            $this->log(
                __(
                    'CRON-notify: Completed; %1 notified, %2 failed.',
                    $notified,
                    $failed
                )
            );
        }
    }

    /**
     * Load upcoming subscriptions for notification.
     *
     * Fetch any subscriptions that will be due in threshold-1 to threshold days, which have not been notified in
     * the last day. If run hourly or less, this should ensure notifications are sent around the same time as the
     * subscription was originally purchased.
     *
     * @return \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\Collection
     */
    protected function fetchUpcomingSubscriptions()
    {
        $threshold = $this->config->getBillingNoticeAdvance();

        $start        = $this->dateProcessor->date('+' . ($threshold - 1) . ' days', null, false);
        $end          = $this->dateProcessor->date('+' . $threshold . ' days', null, false);
        $lastNotified = $this->dateProcessor->date('-1 days', null, false);

        $subscriptions = $this->collectionFactory->create();

        // Enabled
        $subscriptions->addFieldToFilter(
            'status',
            \ParadoxLabs\Subscriptions\Model\Source\Status::STATUS_ACTIVE
        );

        // threshold-1 <= next_run <= threshold
        $subscriptions->addFieldToFilter(
            'next_run',
            [
                'gteq' => $start->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT),
            ]
        );
        $subscriptions->addFieldToFilter(
            'next_run',
            [
                'lteq' => $end->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT),
            ]
        );

        // last_notified < day-1
        $subscriptions->addFieldToFilter(
            'last_notified',
            [
                'lt' => $lastNotified->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT),
            ]
        );

        return $subscriptions;
    }

    /**
     * Send notification to the given subscription, and mark as notified.
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @return void
     */
    protected function notifySubscription(\ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription)
    {
        // Send notification email
        $this->emailSender->sendBillingNoticeEmail($subscription);

        // update notified date
        $subscription->setLastNotified(time());
        $this->subscriptionRepository->save($subscription);
    }

    /**
     * Set console output stream. Used when run from command line.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return $this
     */
    public function setConsoleOutput(\Symfony\Component\Console\Output\OutputInterface $output)
    {
        $this->consoleOutputStream = $output;

        return $this;
    }

    /**
     * Write to log, and to screen if CLI.
     *
     * @param mixed $message
     * @return $this
     */
    protected function log($message)
    {
        $this->helper->log('subscriptions', $message);

        if ($this->consoleOutputStream !== null) {
            $this->consoleOutputStream->writeln((string)$message);
        }

        return $this;
    }
}
