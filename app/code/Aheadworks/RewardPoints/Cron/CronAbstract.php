<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Cron;

use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Aheadworks\RewardPoints\Model\TransactionRepository;
use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Aheadworks\RewardPoints\Model\Flag;
use Aheadworks\RewardPoints\Model\FlagFactory;

/**
 * Class CronAbstract
 *
 * @package Aheadworks\RewardPoints\Cron
 */
abstract class CronAbstract
{
    /**
     * Cron run interval in seconds
     */
    const RUN_INTERVAL = 50;

    /**
     * @var CustomerRewardPointsManagementInterface
     */
    protected $customerRewardPointsService;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var TransactionRepository
     */
    protected $transactionRepository;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var Flag
     */
    private $flag;

    /**
     * @param DateTime $dateTime
     * @param CustomerRewardPointsManagementInterface $customerRewardPointsService
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param TransactionRepository $transactionRepository
     * @param FlagFactory $flagFactory
     */
    public function __construct(
        DateTime $dateTime,
        CustomerRewardPointsManagementInterface $customerRewardPointsService,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        TransactionRepository $transactionRepository,
        FlagFactory $flagFactory
    ) {
        $this->dateTime = $dateTime;
        $this->customerRewardPointsService = $customerRewardPointsService;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->transactionRepository = $transactionRepository;
        $this->flag = $flagFactory->create();
    }

    /**
     * Main cron job entry point
     *
     * @return $this
     */
    abstract public function execute();

    /**
     * Is cron job locked
     *
     * @param string $flag
     * @param int $interval
     * @return bool
     */
    protected function isLocked($flag, $interval = self::RUN_INTERVAL)
    {
        $now = $this->getCurrentTime();
        $lastExecTime = (int)$this->getFlagData($flag);
        return $now < $lastExecTime + $interval;
    }

    /**
     * Set flag data
     *
     * @param string $param
     * @return $this
     */
    protected function setFlagData($param)
    {
        $this->flag
            ->unsetData()
            ->setRewardPointsFlagCode($param)
            ->loadSelf()
            ->setFlagData($this->getCurrentTime())
            ->save();

        return $this;
    }

    /**
     * Get current time
     *
     * @return int
     */
    private function getCurrentTime()
    {
        $now = $this->dateTime->timestamp();
        return $now;
    }

    /**
     * Get flag data
     *
     * @param string $param
     * @return mixed
     */
    private function getFlagData($param)
    {
        $this->flag
            ->unsetData()
            ->setRewardPointsFlagCode($param)
            ->loadSelf();

        return $this->flag->getFlagData();
    }
}
