<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model\Source\Transaction;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Type
 *
 * @package Aheadworks\RewardPoints\Model\Source
 */
class Type implements ArrayInterface
{
    /**#@+
     * Action values
     */
    const BALANCE_ADJUSTED_BY_ADMIN = 1;
    const ORDER_CANCELED = 2;
    const REFUND_BY_REWARD_POINTS = 3;
    const REIMBURSE_OF_SPENT_REWARD_POINTS = 4;
    const POINTS_REWARDED_FOR_REGISTRATION = 5;
    const POINTS_REWARDED_FOR_REVIEW_APPROVED_BY_ADMIN = 6;
    const POINTS_REWARDED_FOR_NEWSLETTER_SIGNUP = 7;
    const POINTS_SPENT_ON_ORDER = 8;
    const POINTS_REWARDED_FOR_ORDER = 9;
    const POINTS_REWARDED_FOR_SHARES = 10;
    const POINTS_EXPIRED = 11;
    const CANCEL_EARNED_POINTS_FOR_REFUND_ORDER = 12;
    const BALANCE_IMPORTED_BY_ADMIN = 13;
    /**#@-*/

    /**
     * {@inheritDoc}
     */
    public function toOptionArray()
    {
        return array_merge(
            $this->getBalanceUpdateActions(),
            []
        );
    }

    /**
     * Retrieve balance update actions
     *
     * @return []
     */
    public function getBalanceUpdateActions()
    {
        return [
            [
                'value' => self::BALANCE_ADJUSTED_BY_ADMIN,
                'label' => __('Balance adjusted by admin')
            ],
            [
                'value' => self::ORDER_CANCELED,
                'label' => __('Order canceled')
            ],
            [
                'value' => self::REFUND_BY_REWARD_POINTS,
                'label' => __('Refund by Reward Points')
            ],
            [
                'value' => self::REIMBURSE_OF_SPENT_REWARD_POINTS,
                'label' => __('Reimburse of spent Reward Points')
            ],
            [
                'value' => self::POINTS_REWARDED_FOR_REGISTRATION,
                'label' => __('Registration')
            ],
            [
                'value' => self::POINTS_REWARDED_FOR_REVIEW_APPROVED_BY_ADMIN,
                'label' => __('Review approved by admin')
            ],
            [
                'value' => self::POINTS_REWARDED_FOR_NEWSLETTER_SIGNUP,
                'label' => __('Newsletter signup')
            ],
            [
                'value' => self::POINTS_SPENT_ON_ORDER,
                'label' => __('Points spent on an order')
            ],
            [
                'value' => self::POINTS_REWARDED_FOR_ORDER,
                'label' => __('Points rewarded for an order')
            ],
            [
                'value' => self::POINTS_REWARDED_FOR_SHARES,
                'label' => __('Points rewarded for product share')
            ],
            [
                'value' => self::CANCEL_EARNED_POINTS_FOR_REFUND_ORDER,
                'label' => __('Cancel earned points for refund order')
            ],
            [
                'value' => self::POINTS_EXPIRED,
                'label' => __('Points expired')
            ],
            [
                'value' => self::BALANCE_IMPORTED_BY_ADMIN,
                'label' => __('Balance adjusted while import')
            ]
        ];
    }
}
