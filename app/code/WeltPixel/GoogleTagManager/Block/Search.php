<?php
namespace WeltPixel\GoogleTagManager\Block;

/**
 * Class \WeltPixel\GoogleTagManager\Block\Search
 */
class Search extends \WeltPixel\GoogleTagManager\Block\Category
{
    /**
     * @return \Magento\Eav\Model\Entity\Collection\AbstractCollection|null
     */
    public function getProductCollection()
    {
        $searchResultListBlock = $this->_layout->getBlock('search_result_list');

        if (empty($searchResultListBlock)) {
            return null;
        }

        $collection = $searchResultListBlock->getLoadedProductCollection();

        $blockName = $searchResultListBlock->getToolbarBlockName();
        $toolbarLayout = false;

        if ($blockName) {
            $toolbarLayout = $this->_layout->getBlock($blockName);
        }

        if ($toolbarLayout) {
            // use sortable parameters
            $orders = $searchResultListBlock->getAvailableOrders();
            if ($orders) {
                $toolbarLayout->setAvailableOrders($orders);
            }
            $sort = $searchResultListBlock->getSortBy();
            if ($sort) {
                $toolbarLayout->setDefaultOrder($sort);
            }
            $dir = $searchResultListBlock->getDefaultDirection();
            if ($dir) {
                $toolbarLayout->setDefaultDirection($dir);
            }
            $modes = $searchResultListBlock->getModes();
            if ($modes) {
                $toolbarLayout->setModes($modes);
            }
            $toolbarLayout->setCollection($collection);
        } else {
            $collection->setCurPage($this->getCurrentPage())->setPageSize($this->getLimit());
        }

        return $collection;
    }
}
