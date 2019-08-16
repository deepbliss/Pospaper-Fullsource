<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_CronScheduleList
 */


namespace Amasty\CronScheduleList\Model;

use Magento\Framework\Stdlib\DateTime as DateTimeFormat;
use Magento\Framework\Stdlib\DateTime\DateTime;

class DateTimeBuilder
{
    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var DateTimeFormat
     */
    private $dateTimeFormat;

    public function __construct(
        DateTime $dateTime,
        DateTimeFormat $dateTimeFormat
    ) {
        $this->dateTime = $dateTime;
        $this->dateTimeFormat = $dateTimeFormat;
    }

    public function formatDate($datetime)
    {
        $now = new \DateTime();
        $ago = new \DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = [
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        ];

        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        $hours = isset($string['h']) ? $string['h'] : '';
        $minutes = isset($string['i']) ? $string['i'] : '';
        $seconds = isset($string['s']) && !isset($string['h']) ? $string['s'] : '';

        return __('%1 ago', $hours . ' ' . $minutes . ' ' . $seconds);
    }
}
