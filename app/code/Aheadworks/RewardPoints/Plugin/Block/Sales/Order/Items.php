<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Plugin\Block\Sales\Order;

use Magento\Bundle\Model\Product\Type as BundleProduct;

/**
 * Class Items
 *
 * @package Aheadworks\RewardPoints\Plugin\Block\Sales\Order
 */
class Items
{
    /**
     * Add reward points column after discount
     *
     * @param \Magento\Sales\Block\Adminhtml\Order\View\Items $subject
     * @param \Closure $proceed
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundGetColumns(
        \Magento\Sales\Block\Adminhtml\Order\View\Items $subject,
        \Closure $proceed
    ) {
        $columns = $proceed();
        foreach ($subject->getOrder()->getAllItems() as $orderItem) {
            if ($orderItem->getProductType() == BundleProduct::TYPE_CODE) {
                return $columns;
            }
        }
        $newColumns = [];
        foreach ($columns as $key => $column) {
            $newColumns[$key] = $column;
            if ($key == 'discont') {
                $newColumns['aw-reward-points'] = __('Reward Points');
            }
        }
        return $newColumns;
    }
}
