<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Source\Transaction;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class BalanceUpdateActions
 *
 * @package Aheadworks\RewardPoints\Model\Source
 */
class BalanceUpdateActions implements ArrayInterface
{
    /**
     * @var TransactionType
     */
    private $transactionType;

    /**
     * @param Type $transactionType
     */
    public function __construct(Type $transactionType)
    {
        $this->transactionType = $transactionType;
    }

    /**
     *  {@inheritDoc}
     */
    public function toOptionArray()
    {
        return $this->transactionType->getBalanceUpdateActions();
    }
}
