<?php

/**
 * Magestore.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Megamenu
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */
namespace Magestore\Megamenu\Block\Adminhtml\Megamenu\Category\Chooser;

class FeaturedCategories extends \Magento\Catalog\Block\Adminhtml\Category\Tree{

    /**
     * @var int[]
     */
    protected $_selectedIds = [];

    /**
     * @var array
     */
    protected $_expandedPath = [];

    /**
     * @return void
     */
    protected function _prepareLayout()
    {
        $this->setTemplate('Magestore_Megamenu::category/featuredCategories.phtml');
    }

    /**
     * @return int[]
     */
    public function getCategoryIds()
    {
        return $this->_selectedIds;
    }

    /**
     * @param mixed $ids
     * @return $this
     */
    public function setCategoryIds($ids)
    {
        if (empty($ids)) {
            $ids = [];
        } elseif (!is_array($ids)) {
            $ids = [(int)$ids];
        }
        $this->_selectedIds = $ids;
        return $this;
    }

    /**
     * @return array
     */
    protected function getExpandedPath()
    {
        return $this->_expandedPath;
    }

    /**
     * @param string $path
     * @return $this
     */
    protected function setExpandedPath($path)
    {
        $this->_expandedPath = array_merge($this->_expandedPath, explode('/', $path));
        return $this;
    }

    /**
     * @param array|Node $node
     * @param int $level
     * @return array
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _getNodeJson($node, $level = 1)
    {
        $item = [];
        $item['text'] = $this->escapeHtml($node->getName());
        if ($this->_withProductCount) {
            $item['text'] .= ' (' . $node->getProductCount() . ')';
        }
        $item['id'] = $node->getId();
        $item['path'] = $node->getData('path');
        $item['cls'] = 'folder ' . ($node->getIsActive() ? 'active-category' : 'no-active-category');
        $item['allowDrop'] = false;
        $item['allowDrag'] = false;
        if (in_array($node->getId(), $this->getCategoryIds())) {
            $this->setExpandedPath($node->getData('path'));
            $item['checked'] = true;
        }
        if ($node->getLevel() < 2) {
            $this->setExpandedPath($node->getData('path'));
        }
        if ($node->hasChildren()) {
            $item['children'] = [];
            foreach ($node->getChildren() as $child) {
                $item['children'][] = $this->_getNodeJson($child, $level + 1);
            }
        }
        if (empty($item['children']) && (int)$node->getChildrenCount() > 0) {
            $item['children'] = [];
        }
        $item['expanded'] = in_array($node->getId(), $this->getExpandedPath());
        return $item;
    }
}