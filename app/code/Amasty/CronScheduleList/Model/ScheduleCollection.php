<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_CronScheduleList
 */


namespace Amasty\CronScheduleList\Model;

use Magento\Cron\Model\Schedule;

class ScheduleCollection extends \Magento\Cron\Model\ResourceModel\Schedule\Collection
{
    public function getLastActivity()
    {
        $this->addFieldToFilter('job_code', 'amasty_cron_activity')
            ->getSelect()->where('finished_at IS NOT NULL')->order('finished_at');

        return $this->getLastItem();
    }

    public function removeActivitySchedule()
    {
        $this->getSelect()->where('job_code != "amasty_cron_activity"');

        return $this;
    }

    public function getFailedScheduleByJobCode($jobCode)
    {
        return $this->addFieldToFilter('job_code', $jobCode)
            ->addFieldToFilter('status', Schedule::STATUS_ERROR)
            ->getLastItem();
    }

    public function getLastFailedJobs()
    {
        $lastFailedJobs = [];
        $items = $this->addFieldToFilter('status', Schedule::STATUS_ERROR)->getItems();

        foreach ($items as $item) {
            $lastFailedJobs[$item->getJobCode()] = $item;
        }

        return $lastFailedJobs;
    }
}
