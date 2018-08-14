<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Model;

use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Aheadworks\RewardPoints\Model\Source\Category\AllowSpendingPoints;

/**
 * Class Aheadworks\RewardPoints\Model\CategoryAllowed
 */
class CategoryAllowed
{
    /**
     * @var string
     */
    const AW_RP_ATTRIBUTE_CODE = 'aw_rp_allow_spending_points';

    /**
     * @var CollectionFactory
     */
    private $categoryCollectionFactory;

    /**
     * @var array
     */
    private $categoryIdsNotAllowedForRewardPoints = [];

    /**
     * @var array
     */
    private $categoryCacheChecked = [];

    /**
     * @param CollectionFactory $categoryCollectionFactory
     */
    public function __construct(
        CollectionFactory $categoryCollectionFactory
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * Is allowed categories for spend points
     *
     * @param array $categoryIds
     * @return boolean
     */
    public function isAllowedCategoryForSpendPoints(array $categoryIds)
    {
        $notAllowedCategoriesForPoints = $this->getCategoryIdsNotAllowedForRewardPoints($categoryIds);
        return !$this->hasNotAllowedCategories($categoryIds, $notAllowedCategoriesForPoints);
    }

    /**
     * Return allowed category id's for reward points
     *
     * @param array $categoryIds
     * @return array
     */
    private function getCategoryIdsNotAllowedForRewardPoints(array $categoryIds)
    {
        $categoryIds = $this->extractNewCategoryIds($categoryIds);
        if (!empty($categoryIds)) {
            // Find all categories id
            $categoryCollection = $this->getCategories($categoryIds);

            foreach ($categoryCollection as $category) {
                if ($this->isAllowCategory($this->getAllowSpendingPointsValue($category))) {
                    foreach ($this->getParentCategories($category) as $parentCategory) {
                        if ($this->isNotAllowParentCategory($this->getAllowSpendingPointsValue($parentCategory))) {
                            $this->categoryIdsNotAllowedForRewardPoints[] = $category->getId();
                            $this->categoryCacheChecked[$category->getId()] = true;
                            break;
                        }
                    }
                } else {
                    $this->categoryIdsNotAllowedForRewardPoints[] = $category->getId();
                    $this->categoryCacheChecked[$category->getId()] = true;
                }
            }
        }
        return $this->categoryIdsNotAllowedForRewardPoints;
    }

    /**
     * Retrieve new category ids
     *
     * @param array $categoryIds
     * @return array
     */
    private function extractNewCategoryIds(array $categoryIds)
    {
        $newCategoryIds = [];
        foreach ($categoryIds as $categoryId) {
            if (!isset($this->categoryCacheChecked[$categoryId])) {
                $newCategoryIds[] = $categoryId;
            }
        }

        return $newCategoryIds;
    }

    /**
     * Is allow category for adding points
     *
     * @param string $allowSpendingPointsValue
     * @return boolean
     */
    private function isAllowCategory($allowSpendingPointsValue)
    {
        return ($allowSpendingPointsValue == AllowSpendingPoints::CATEGORY_SPENDING_OPTION_CATEGORY_ONLY)
            || ($allowSpendingPointsValue == AllowSpendingPoints::CATEGORY_SPENDING_OPTION_CATEGORY_WITH_SUB);
    }

    /**
     * Is not allow parent category for adding points
     *
     * @param string $allowSpendingPointsValue
     * @return boolean
     */
    private function isNotAllowParentCategory($allowSpendingPointsValue)
    {
        return $allowSpendingPointsValue == AllowSpendingPoints::CATEGORY_SPENDING_OPTION_NO_CATEGORY_WITH_SUB;
    }

    /**
     * Get allow spending points attribute value
     *
     * @param \Magento\Catalog\Model\Category $category
     * @return string
     */
    private function getAllowSpendingPointsValue($category)
    {
        $allowSpendingPointsAttribute = $category->getCustomAttribute(self::AW_RP_ATTRIBUTE_CODE);
        return $allowSpendingPointsAttribute
            ? $allowSpendingPointsAttribute->getValue()
            : AllowSpendingPoints::CATEGORY_SPENDING_OPTION_CATEGORY_DEFAULT;
    }

    /**
     * Get category collection
     *
     * @param array $categoryIds
     * @return \Magento\Catalog\Model\Category[]
     */
    private function getCategories(array $categoryIds)
    {
        $categories = $this->createCategoryCollection();
        $categories->addIdFilter(array_unique($categoryIds));
        return $categories->getItems();
    }

    /**
     * Retrieve parent category collection
     *
     * @param \Magento\Catalog\Model\Category $category
     * @return \Magento\Catalog\Model\Category[]
     */
    private function getParentCategories($category)
    {
        $pathIds = array_reverse(explode(',', $category->getPathInStore()));
        array_pop($pathIds);
        $categories = $this->createCategoryCollection();
        $categories->addFieldToFilter(
            'entity_id',
            ['in' => $pathIds]
        );

        return $categories->getItems();
    }

    /**
     * Create category collection
     *
     * @return \Magento\Catalog\Model\ResourceModel\Category\Collection
     */
    private function createCategoryCollection()
    {
        $categories = $this->categoryCollectionFactory->create();

        $categories
            ->addAttributeToSelect(self::AW_RP_ATTRIBUTE_CODE)
            ->addAttributeToSelect('path')
            ->addIsActiveFilter();
        return $categories;
    }

    /**
     * Has not allowed categories
     *
     * @param array $categoryIds
     * @param array $notAllowedCategoriesForPoints
     * @return boolean
     */
    private function hasNotAllowedCategories(array $categoryIds, array $notAllowedCategoriesForPoints)
    {
        return !empty(array_intersect($categoryIds, $notAllowedCategoriesForPoints));
    }
}
