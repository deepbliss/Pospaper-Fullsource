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
 * Upcoming Class
 */
class Upcoming
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
     * @var \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface
     */
    protected $subscriptionRepository;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    protected $config;

    /**
     * Upcoming constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\CollectionFactory $collectionFactory
     * @param \ParadoxLabs\Subscriptions\Helper\Data $helper
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $dateProcessor
     * @param \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface $subscriptionRepository
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\CollectionFactory $collectionFactory,
        \ParadoxLabs\Subscriptions\Helper\Data $helper,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $dateProcessor,
        \ParadoxLabs\Subscriptions\Api\SubscriptionRepositoryInterface $subscriptionRepository,
        \ParadoxLabs\Subscriptions\Model\Config $config
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->helper = $helper;
        $this->dateProcessor = $dateProcessor;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->config = $config;
    }

    /**
     * Display any upcoming subscriptions without running any billing.
     *
     * @return void
     */
    public function displayUpcomingSubscriptions()
    {
        if ($this->config->moduleIsActive() !== true) {
            return;
        }

        $subscriptions = $this->fetchUpcomingSubscriptions();

        if (count($subscriptions)) {
            foreach ($subscriptions as $subscription) {
                $this->display($subscription);
            }
        }
    }

    /**
     * Load upcoming subscriptions for notification.
     *
     * @return \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\Collection
     */
    protected function fetchUpcomingSubscriptions()
    {
        $now = $this->dateProcessor->date(null, null, false);

        /** @var \ParadoxLabs\Subscriptions\Model\ResourceModel\Subscription\Collection $subscriptions */
        $subscriptions = $this->collectionFactory->create();
        $subscriptions->addFieldToFilter(
            'next_run',
            [
                'lteq' => $now->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT),
            ]
        );
        $subscriptions->setOrder('next_run', \Magento\Framework\Api\SortOrder::SORT_ASC);

        return $subscriptions;
    }

    /**
     * Display a given subscription
     *
     * @param \ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription
     * @return void
     */
    protected function display(\ParadoxLabs\Subscriptions\Api\Data\SubscriptionInterface $subscription)
    {
        $this->log(
            __(
                "\n<info>%1</info> [<comment>%2</comment>] for customer ID %3"
                . "\n<comment>Last installment:</comment> %4"
                . "\n<comment>Next scheduled:</comment>   %5"
                . "\n<comment>Subtotal:</comment>         %6",
                $subscription->getDescription(),
                $subscription->getIncrementId(),
                $subscription->getCustomerId(),
                $subscription->getLastRun(),
                $subscription->getNextRun(),
                $subscription->getSubtotal()
            )
        );
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
        $this->helper->log('subscriptions', strip_tags($message));

        if ($this->consoleOutputStream !== null) {
            $this->consoleOutputStream->writeln((string)$message);
        }

        return $this;
    }
}
