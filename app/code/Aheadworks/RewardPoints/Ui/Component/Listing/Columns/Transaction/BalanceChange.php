<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Ui\Component\Listing\Columns\Transaction;

use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Price
 *
 * @package Aheadworks\RewardPoints\Ui\Component\Listing\Columns\Transaction
 */
class BalanceChange extends Column
{
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $showPlus = $item[$this->getData('name')] > 0;

                $item['row_сlass_' . $this->getData('name')] = $item[$this->getData('name')] > 0
                    ? 'aw_reward_points__balance-green'
                    : 'aw_reward_points__balance-red';
                $item[$this->getData('name')] = ($showPlus ? '+' : '') . $item[$this->getData('name')];
            }
        }

        return $dataSource;
    }
}
