<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_CronScheduleList
 */


namespace Amasty\CronScheduleList\Plugin;

class ScheduleCollectionPlugin
{
    public function afterGetIdFieldName($subject, $result)
    {
        if ($result === null) {
            $result = 'schedule_id';
        }

        return $result;
    }
}
