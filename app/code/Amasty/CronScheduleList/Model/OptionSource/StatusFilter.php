<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_CronScheduleList
 */


namespace Amasty\CronScheduleList\Model\OptionSource;

use Magento\Framework\Option\ArrayInterface;
use Magento\Cron\Model\Schedule;

class StatusFilter implements ArrayInterface
{
    public function toOptionArray()
    {
        $statuses = [
            ['value' => Schedule::STATUS_SUCCESS, 'label' => __('Success')],
            ['value' => Schedule::STATUS_PENDING, 'label' => __('Pending')],
            ['value' => Schedule::STATUS_RUNNING, 'label' => __('Running')],
            ['value' => Schedule::STATUS_ERROR, 'label' => __('Error')],
            ['value' => Schedule::STATUS_MISSED, 'label' => __('Missed')]
        ];

        return $statuses;
    }
}
