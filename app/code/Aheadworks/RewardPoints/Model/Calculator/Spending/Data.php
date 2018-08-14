<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Calculator\Spending;

/**
 * Class Data
 *
 * @package Aheadworks\RewardPoints\Model\Calculator\Spending
 */
class Data
{
    /**
     * @var float
     */
    private $baseAvailablePointsAmount;

    /**
     * @var float
     */
    private $availablePointsAmount;

    /**
     * @var int
     */
    private $availablePoints;

    /**
     * @var float
     */
    private $baseUsedPointsAmount;

    /**
     * @var float
     */
    private $usedPointsAmount;

    /**
     * @var int
     */
    private $usedPoints;

    /**
     * @var float
     */
    private $baseItemsTotal;

    /**
     * @var float
     */
    private $itemsTotal;

    /**
     * @var int
     */
    private $itemsCount;

    /**
     * @var float
     */
    private $baseShippingAmount;

    /**
     * @var float
     */
    private $shippingAmount;

    /**
     * Data constructor
     */
    public function __construct()
    {
        $this->setBaseAvailablePointsAmount(0);
        $this->setAvailablePointsAmount(0);
        $this->setAvailablePoints(0);
        $this->setBaseUsedPointsAmount(0);
        $this->setUsedPointsAmount(0);
        $this->setUsedPoints(0);
        $this->setBaseItemsTotal(0);
        $this->setItemsTotal(0);
        $this->setItemsCount(0);
        $this->setBaseShippingAmount(0);
        $this->setShippingAmount(0);
    }

    /**
     * Set base available points amount
     *
     * @param float $amount
     * @return $this
     */
    public function setBaseAvailablePointsAmount($amount)
    {
        $this->baseAvailablePointsAmount = $amount;
        return $this;
    }

    /**
     * Get base available points amount
     *
     * @return float
     */
    public function getBaseAvailablePointsAmount()
    {
        return $this->baseAvailablePointsAmount;
    }

    /**
     * Get base available points amount left
     *
     * @return float
     */
    public function getBaseAvailablePointsAmountLeft()
    {
        return max(0, $this->baseAvailablePointsAmount - $this->baseUsedPointsAmount);
    }

    /**
     * Set available points amount
     *
     * @param float $amount
     * @return $this
     */
    public function setAvailablePointsAmount($amount)
    {
        $this->availablePointsAmount = $amount;
        return $this;
    }

    /**
     * Get available points amount
     *
     * @return float
     */
    public function getAvailablePointsAmount()
    {
        return $this->availablePointsAmount;
    }

    /**
     * Get available points amount left
     *
     * @return float
     */
    public function getAvailablePointsAmountLeft()
    {
        return max(0, $this->availablePointsAmount - $this->usedPointsAmount);
    }

    /**
     * Set available points
     *
     * @param int $points
     * @return $this
     */
    public function setAvailablePoints($points)
    {
        $this->availablePoints = $points;
        return $this;
    }

    /**
     * Get available points
     *
     * @return int
     */
    public function getAvailablePoints()
    {
        return $this->availablePoints;
    }

    /**
     * Get available points left
     *
     * @return int
     */
    public function getAvailablePointsLeft()
    {
        return max(0, $this->availablePoints - $this->usedPoints);
    }

    /**
     * Set base used points amount
     *
     * @param float $amount
     * @return $this
     */
    public function setBaseUsedPointsAmount($amount)
    {
        $this->baseUsedPointsAmount = $amount;
        return $this;
    }

    /**
     * Get base used points amount
     *
     * @return float
     */
    public function getBaseUsedPointsAmount()
    {
        return $this->baseUsedPointsAmount;
    }

    /**
     * Set used points amount
     *
     * @param float $amount
     * @return $this
     */
    public function setUsedPointsAmount($amount)
    {
        $this->usedPointsAmount = $amount;
        return $this;
    }

    /**
     * Get used points amount
     *
     * @return float
     */
    public function getUsedPointsAmount()
    {
        return $this->usedPointsAmount;
    }

    /**
     * Set used points
     *
     * @param int $points
     * @return $this
     */
    public function setUsedPoints($points)
    {
        $this->usedPoints = $points;
        return $this;
    }

    /**
     * Get used points
     *
     * @return int
     */
    public function getUsedPoints()
    {
        return $this->usedPoints;
    }

    /**
     * Set base items total
     *
     * @param float $amount
     * @return $this
     */
    public function setBaseItemsTotal($amount)
    {
        $this->baseItemsTotal = $amount;
        return $this;
    }

    /**
     * Get base items total
     *
     * @return float
     */
    public function getBaseItemsTotal()
    {
        return $this->baseItemsTotal;
    }

    /**
     * Set used items total
     *
     * @param float $amount
     * @return $this
     */
    public function setItemsTotal($amount)
    {
        $this->itemsTotal = $amount;
        return $this;
    }

    /**
     * Get used items total
     *
     * @return float
     */
    public function getItemsTotal()
    {
        return $this->itemsTotal;
    }

    /**
     * Set used items total
     *
     * @param int $count
     * @return $this
     */
    public function setItemsCount($count)
    {
        $this->itemsCount = $count;
        return $this;
    }

    /**
     * Get used points
     *
     * @return int
     */
    public function getItemsCount()
    {
        return $this->itemsCount;
    }

    /**
     * Set base shipping amount
     *
     * @param float $amount
     * @return $this
     */
    public function setBaseShippingAmount($amount)
    {
        $this->baseShippingAmount = $amount;
        return $this;
    }

    /**
     * Get base shipping amount
     *
     * @return float
     */
    public function getBaseShippingAmount()
    {
        return $this->baseShippingAmount;
    }

    /**
     * Set shipping amount
     *
     * @param float $amount
     * @return $this
     */
    public function setShippingAmount($amount)
    {
        $this->shippingAmount = $amount;
        return $this;
    }

    /**
     * Get shipping amount
     *
     * @return float
     */
    public function getShippingAmount()
    {
        return $this->shippingAmount;
    }
}
