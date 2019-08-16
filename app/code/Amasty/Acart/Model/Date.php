<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model;

use Magento\Framework\Stdlib\DateTime as StdDateTime;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Date
{
    const SECONDS_IN_DAY = 86400;

    /**
     * @var StdDateTime
     */
    private $dateTime;

    /**
     * @var DateTime
     */
    private $date;

    public function __construct(
        StdDateTime $dateTime,
        DateTime $date
    ) {
        $this->dateTime = $dateTime;
        $this->date = $date;
    }

    /**
     * @param $timestamp
     *
     * @return null|string
     */
    public function getFormattedDate($timestamp)
    {
        return $this->dateTime->formatDate($timestamp);
    }

    /**
     * @return int
     */
    public function getCurrentTimestamp()
    {
        return $this->date->gmtTimestamp();
    }

    /**
     * @param int|string $days
     *
     * @return int
     */
    public function convertDaysInSeconds($days)
    {
        return (int)$days * self::SECONDS_IN_DAY;
    }

    /**
     * Return date with $days offset
     *
     * @param int $days
     *
     * @return string
     */
    public function getDateWithOffsetByDays($days)
    {
        $offset = $days * self::SECONDS_IN_DAY;

        return $this->dateTime->formatDate($this->date->gmtTimestamp() + $offset, false);
    }

    /**
     * @param  string $format
     * @param  int|string $input date in GMT timezone
     *
     * @return string
     */
    public function date($format = null, $input = null)
    {
        return $this->date->date($format, $input);
    }
}
