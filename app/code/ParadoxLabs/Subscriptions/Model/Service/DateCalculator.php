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
 * DateCalculator Class
 */
class DateCalculator
{
    const ONE_DAY = 'P1D';
    const ONE_MONTH = 'P1M';

    /**
     * @var int
     */
    protected $storeId;

    /**
     * @var int[]
     */
    protected $daysOfWeek;

    /**
     * @var int[]
     */
    protected $daysOfMonth;

    /**
     * @var int[]
     */
    protected $months;

    /**
     * @var string[]
     */
    protected $blackoutDates;

    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    protected $config;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $localeDate;

    /**
     * DateCalculator constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Model\Config $config,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
    ) {
        $this->config = $config;
        $this->localeDate = $localeDate;
    }

    /**
     * Set store ID for configuration loading.
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId($storeId)
    {
        // If store ID has changed, clear out cached config data, it could differ for the new scope.
        if ($this->storeId !== $storeId) {
            $this->resetProperties();
        }

        $this->storeId = $storeId;

        return $this;
    }

    /**
     * Clear class properties of cached data.
     *
     * @return $this
     */
    protected function resetProperties()
    {
        $this->daysOfWeek = null;
        $this->daysOfMonth = null;
        $this->months = null;
        $this->blackoutDates = null;

        return $this;
    }

    /**
     * Calculate next run date based on a starting time and interval, taking schedule configuration (blackout days,
     * dates, etc.) into account.
     *
     * @param string|int $startingDate Date to calculate forward from (string or unix timestamp)
     * @param string $interval strtotime-compatible relative date (EG "+1 month")
     * @return int
     */
    public function calculateNextRun($startingDate, $interval)
    {
        /**
         * Try to maintain prior schedule. New next_run is current next_run + frequency,
         * by multiple if needed to hit a future date.
         *
         * We use unix timestamps here for speed and strtotime format convenience.
         */
        $nextRunTime = strtotime($startingDate);
        $now = time();

        if ($nextRunTime === false) {
            $nextRunTime = $now;
        }

        do {
            $nextRunTime = strtotime(
                $interval,
                $nextRunTime
            );
        } while ($nextRunTime < $now);

        /**
         * Coerce billing date within configuration parameters.
         */
        $nextRunTime = $this->coerceRunTime($nextRunTime);

        return $nextRunTime;
    }

    /**
     * Coerce a run date to within configuration parameters.
     *
     * Takes date and gives the next allowed day based on allowed days of week, etc.
     *
     * @param int $nextRunTime Unix timestamp
     * @return int
     */
    protected function coerceRunTime($nextRunTime)
    {
        // We use \DateTime here and below for timezone handling.
        $coercedRunTime = $this->localeDate->date($nextRunTime);

        // Until we find a date that's eligible, add and try again.
        $oneDay = new \DateInterval(static::ONE_DAY);
        $oneMonth = new \DateInterval(static::ONE_MONTH);
        while ($this->isDateAvailable($coercedRunTime) !== true) {
            // If the month is not allowed, skip ahead one month. Otherwise, +1 day and recheck.
            if ($this->isMonthAllowed($coercedRunTime) !== true) {
                $coercedRunTime->add($oneMonth);
            } else {
                $coercedRunTime->add($oneDay);
            }
        }

        return $coercedRunTime->getTimestamp();
    }

    /**
     * Check if a specific date is available based on configuration.
     *
     * @param \DateTime $timestamp
     * @return bool
     */
    public function isDateAvailable(\DateTime $timestamp)
    {
        $eligible = $this->isDayOfWeekAllowed($timestamp)
            && $this->isDayOfMonthAllowed($timestamp)
            && $this->isMonthAllowed($timestamp)
            && !$this->isDayBlackedOut($timestamp);

        return $eligible;
    }

    /**
     * Get allowed days of week based on configuration.
     *
     * NB: Careful, Magento weekdays are zero-indexed
     *
     * @return int[]
     */
    protected function getDaysOfWeekAllowed()
    {
        if ($this->daysOfWeek === null) {
            $this->daysOfWeek = $this->config->getSchedulingDaysOfWeekAllowed($this->storeId);
        }

        return $this->daysOfWeek;
    }

    /**
     * Determine whether the given date is allowed based on allowed days of week.
     *
     * @param \DateTime $timestamp
     * @return bool
     */
    public function isDayOfWeekAllowed(\DateTime $timestamp)
    {
        $check = in_array(
            (int)$timestamp->format('w'),
            $this->getDaysOfWeekAllowed(),
            true
        );

        return $check;
    }

    /**
     * Get allowed days of month based on configuration.
     *
     * Does not account for varying month lengths--just raw by the numbers.
     *
     * @return int[]
     */
    protected function getDaysOfMonthAllowed()
    {
        if ($this->daysOfMonth === null) {
            $this->daysOfMonth = $this->config->getSchedulingDaysOfMonthAllowed($this->storeId);
        }

        return $this->daysOfMonth;
    }

    /**
     * Determine whether the given date is allowed based on allowed days of month.
     *
     * @param \DateTime $timestamp
     * @return bool
     */
    public function isDayOfMonthAllowed(\DateTime $timestamp)
    {
        $check = in_array(
            (int)$timestamp->format('j'),
            $this->getDaysOfMonthAllowed(),
            true
        );

        return $check;
    }

    /**
     * Get allowed months based on configuration.
     *
     * @return int[]
     */
    protected function getMonthsAllowed()
    {
        if ($this->months === null) {
            $this->months = $this->config->getSchedulingMonthsAllowed($this->storeId);
        }

        return $this->months;
    }

    /**
     * Determine whether the given date is allowed based on allowed months.
     *
     * @param \DateTime $timestamp
     * @return bool
     */
    public function isMonthAllowed(\DateTime $timestamp)
    {
        $check = in_array(
            (int)$timestamp->format('n'),
            $this->getMonthsAllowed(),
            true
        );

        return $check;
    }

    /**
     * Get configured blackout dates as Y-m-d, if any.
     *
     * Protip: This passes all config values through strtotime. Expected input is a specific date, but it will
     * actually take and parse any strtotime-valid string. Want to disallow the 'second tuesday of next month'?..
     * yeah, you can do that. Just keep in mind each line parses to ONE SPECIFIC DATE. You can't block all second
     * tuesdays like that.
     *
     * @return string[]
     */
    protected function getBlackoutDates()
    {
        if ($this->blackoutDates === null) {
            $this->blackoutDates = $this->config->getSchedulingBlackoutDates($this->storeId);
        }

        return $this->blackoutDates;
    }

    /**
     * Determine whether the given date is blacked out.
     *
     * @param \DateTime $timestamp
     * @return bool
     */
    public function isDayBlackedOut(\DateTime $timestamp)
    {
        $check = in_array(
            $timestamp->format('Y-m-d'),
            $this->getBlackoutDates(),
            true
        );

        return $check;
    }
}
