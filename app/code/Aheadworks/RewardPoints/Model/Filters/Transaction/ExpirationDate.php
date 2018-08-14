<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Filters\Transaction;

use Aheadworks\RewardPoints\Model\Source\Transaction\Expire;
use Aheadworks\RewardPoints\Model\DateTime;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Aheadworks\RewardPoints\Model\Filters\Transaction\ExpirationDate
 */
class ExpirationDate implements \Zend_Filter_Interface
{
    /**#@+
     * Constants for field key for expiration date
     */
    const FIELD_EXPIRE = 'expire';
    const FIELD_EXPIRE_IN_DAYS = 'expire_in_days';
    /**#@-*/

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var string
     */
    private $expireType;

    /**
     * @var int
     */
    private $expireInDays;

    /**
     * @param DateTime $dateTime
     * @param array $config
     */
    public function __construct(DateTime $dateTime, $config = [])
    {
        $this->dateTime = $dateTime;

        if (isset($config[self::FIELD_EXPIRE])) {
            $this->expireType = $config[self::FIELD_EXPIRE];
        }

        if (isset($config[self::FIELD_EXPIRE_IN_DAYS])) {
            $this->expireInDays = (int) $config[self::FIELD_EXPIRE_IN_DAYS];
        }
    }

    /**
     *  {@inheritDoc}
     */
    public function filter($value)
    {
        $value = $this->prepare($value);

        if ($value == null) {
            return $value;
        }

        if ($this->dateTime->getTodayDate() > $this->dateTime->getDate($value)) {
            throw new LocalizedException(__('Expiration date cannot be in the past'));
        }

        try {
            return $this->dateTime->getDate($value, true);
        } catch (\Exception $e) {
            throw new \Exception('Invalid input date format ' . $value);
        }
    }

    /**
     * Convert expire in days value to date if need
     *
     * @param string $value
     * @return string
     */
    private function prepare($value)
    {
        if ($this->expireType != null && $this->expireType == Expire::EXPIRE_IN_X_DAYS) {
            if ((int)$this->expireInDays > 0) {
                $value = $this->dateTime->getExpirationDate($this->expireInDays, false);
            } else {
                $value = null;
            }
        }

        if ($value == '') {
            $value = null;
        }

        return $value;
    }
}
