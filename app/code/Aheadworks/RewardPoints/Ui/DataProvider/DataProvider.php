<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Ui\DataProvider;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\Reporting;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Locale\FormatInterface;
use Aheadworks\AdvancedReports\Model\Filter;

/**
 * Class DataProvider
 *
 * @package Aheadworks\RewardPoints\Ui\DataProvider
 */
class DataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    /**
     * {@inheritdoc}
     */
    protected function searchResultToOutput(SearchResultInterface $searchResult)
    {
        $arrItems = [];
        $arrItems['items'] = [];
        foreach ($searchResult->getItems() as $item) {
            $itemData = [];
            foreach ($item->getCustomAttributes() as $attribute) {
                $itemData[$attribute->getAttributeCode()] = $attribute->getValue();
            }
            $arrItems['items'][] = $itemData;
        }
        $arrItems['totalRecords'] = $searchResult->getTotalCount();
        $arrItems['totals'][] = $searchResult->getTotals();

        return $arrItems;
    }
}
