<?php

namespace Pos\Custommodule\Block\Html;

use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Framework\Data\TreeFactory;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Topmenu extends \Magento\Theme\Block\Html\Topmenu
{

    protected $categoryRepository;
    protected $productRepository;

    /**
     * @param Template\Context $context
     * @param NodeFactory $nodeFactory
     * @param TreeFactory $treeFactory
     * @param CategoryRepositoryInterface $categoryRepository
     * @param ProductRepositoryInterface $productRepository
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        NodeFactory $nodeFactory,
        TreeFactory $treeFactory,
        CategoryRepositoryInterface $categoryRepository,
        ProductRepositoryInterface $productRepository,
        array $data = []
    ) {
        parent::__construct($context, $nodeFactory, $treeFactory, $data);
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Add sub menu HTML code for current menu item
     *
     * @param \Magento\Framework\Data\Tree\Node $child
     * @param string $childLevel
     * @param string $childrenWrapClass
     * @param int $limit
     * @param array $parentData
     * @return string HTML code
     */
    protected function _addSubMenu($child, $childLevel, $childrenWrapClass, $limit, $parentData = [])
    {
        $html = '';
        if (!$child->hasChildren()) {
            return $html;
        }

        $showLevelTwo = false;
        if(isset($parentData['show_children_images']) && $parentData['show_level_two'] == 1) {
            $showLevelTwo = true;
        }

        if(!$showLevelTwo && $childLevel > 0) {
            return $html;
        }

        if($showLevelTwo && $childLevel > 1) {
            return $html;
        }

        $colStops = null;
        $parentClass = ' submenu-text';
        $showTopSelling = false;
        $topSellingSkus = '';
        $topSellingProducts = '';
        $leftBlockClass = 'menu-center-block';
        if(isset($parentData['show_children_images']) && $parentData['show_children_images'] == 1) {
            $parentClass = ' submenu-with-images';
        }

        if($showLevelTwo) {
            $parentClass .= ' submenu-with-children';
        }

        if(isset($parentData['show_top_selling']) && $parentData['show_top_selling'] == 1) {
            $showTopSelling = true;
            $leftBlockClass = 'menu-left-block';
        }


        if(isset($parentData['top_selling_skus'])) {
            $topSellingSkus = $parentData['top_selling_skus'];
            $skus = explode(',',$topSellingSkus);
            foreach ($skus as $sku) {
                $product = $this->productRepository->get($sku);
                if (!$product->isVisibleInCatalog()) {
                    continue;
                }
                if($product->getThumbnail() != NULL) {
                    $product_image = $this->getBaseUrl() . 'pub/media/catalog/product' . $product->getThumbnail();
                } else {
                    $product_image = $this->getBaseUrl() . 'pub/media/catalog/product/No_Image_Available.jpg';
                }
                $url = $product->getUrlModel()->getUrl($product);
                $topSellingProducts .= '<div class="best-row"><div class="prod-item-img"><a href="'.$url.'" class="product photo product-item-photo"><img src="'.$product_image.'" /></a></div><div class="product name product-item-name"><a class="product-item-link" href="'.$url.'">'.$product->getName().'</a></div></div>';
            }

        }

        $html .= '<ul class="level' . $childLevel . ' ' . $childrenWrapClass . '">';
        $html .= '<li class="'.$leftBlockClass.'">';

        if($showLevelTwo) {
            $html .= '<div class="level-two-container"><div class="level-two">';
            $html .= $this->_getLewelTwoHtml($child, $childrenWrapClass, $limit, $colStops, $parentData, 4);
            $html .= '</div></div>';
        } else {
            $html .= '<ul class="submenu-categories'.$parentClass.'">';
            $html .= $this->_getHtml($child, $childrenWrapClass, $limit, $colStops, $parentData);
            $html .= '</ul>';
        }

        $html .= '</li>';
        if($showTopSelling && $topSellingProducts != '') {
            $html .= '<li class="menu-right-block" data-skus="'.$topSellingSkus.'"><div class="title">Top Selling</div><div class="top-selling-container">'.$topSellingProducts.'</div></li>';
        }
        $html .= '</ul>';

        return $html;
    }

    protected function _addLevelTwoSubMenu($child, $childLevel, $childrenWrapClass, $limit, $parentData = [])
    {
        $html = '';
        if (!$child->hasChildren()) {
            return $html;
        }

        $showLevelTwo = false;
        if(isset($parentData['show_children_images']) && $parentData['show_level_two'] == 1) {
            $showLevelTwo = true;
        }

        if(!$showLevelTwo && $childLevel > 0) {
            return $html;
        }

        if($showLevelTwo && $childLevel > 1) {
            return $html;
        }

        $colStops = null;

        $html .= '<div class="level-three">';
        $html .= $this->_getLewelTwoHtml($child, $childrenWrapClass, $limit, $colStops, $parentData);
        $html .= '</div>';

        return $html;
    }

    protected function _getLewelTwoHtml(
        \Magento\Framework\Data\Tree\Node $menuTree,
        $childrenWrapClass,
        $limit,
        $colBrakes = [],
        $parentData = [],
        $break = false
    ) {
        $html = '';
        $children = $menuTree->getChildren();
        $parentLevel = $menuTree->getLevel();
        $childLevel = $parentLevel === null ? 0 : $parentLevel + 1;

        $counter = 1;
        $column = 1;
        $childrenCount = $children->count();
        if($break) {
            $column = ceil($childrenCount/$break);
        }

        $parentPositionClass = $menuTree->getPositionClass();
        $itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

        /** @var \Magento\Framework\Data\Tree\Node $child */
        foreach ($children as $child) {
            if ($childLevel === 0 && $child->getData('is_parent_active') === false) {
                continue;
            }
            $child->setLevel($childLevel);
            $child->setIsFirst($counter == 1);
            $child->setIsLast($counter == $childrenCount);
            $child->setPositionClass($itemPositionClassPrefix . $counter);

            $outermostClassCode = '';
            $outermostClass = $menuTree->getOutermostClass();

            if ($childLevel == 0 && $outermostClass) {
                $outermostClassCode = ' class="' . $outermostClass . '" ';
                $currentClass = $child->getClass();

                if (empty($currentClass)) {
                    $child->setClass($outermostClass);
                } else {
                    $child->setClass($currentClass . ' ' . $outermostClass);
                }
            }

            $menuId = $child->getId();
            if($child->getLevel() === 0) {
                $parentData = $this->getCategoryData($menuId);
            }

            $data = '';

            if($break && $child->hasChildren()) {
                $data = ' data-level="parent-menu"';
            }

            $html .= '<div ' . $this->_getRenderedMenuItemAttributes($child) . $data . '>';
            if($break && $child->hasChildren()) {
                $html .= '<span></span>';
            }
            $html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . ' id="category-id-'.$menuId.'"><span>' . $this->escapeHtml(
                    $child->getName()
                ) . '</span></a>' . $this->_addLevelTwoSubMenu(
                    $child,
                    $childLevel,
                    $childrenWrapClass,
                    $limit,
                    $parentData
                ) . '</div>';
            $counter++;
            if($break) {
                if($counter > $column) {
                    $html .= '</div><div class="level-two">';
                    $counter = 1;
                }
            }
        }

        return $html;
    }

    /**
     * Recursively generates top menu html from data that is specified in $menuTree
     *
     * @param \Magento\Framework\Data\Tree\Node $menuTree
     * @param string $childrenWrapClass
     * @param int $limit
     * @param array $colBrakes
     * @param array $parentData
     * @return string
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _getHtml(
        \Magento\Framework\Data\Tree\Node $menuTree,
        $childrenWrapClass,
        $limit,
        $colBrakes = [],
        $parentData = []
    ) {
        $html = '';

        $children = $menuTree->getChildren();
        $parentLevel = $menuTree->getLevel();
        $childLevel = $parentLevel === null ? 0 : $parentLevel + 1;

        $counter = 1;
        $itemPosition = 1;
        $childrenCount = $children->count();

        $parentPositionClass = $menuTree->getPositionClass();
        $itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

        /** @var \Magento\Framework\Data\Tree\Node $child */
        foreach ($children as $child) {
            if ($childLevel === 0 && $child->getData('is_parent_active') === false) {
                continue;
            }
            $child->setLevel($childLevel);
            $child->setIsFirst($counter == 1);
            $child->setIsLast($counter == $childrenCount);
            $child->setPositionClass($itemPositionClassPrefix . $counter);

            $outermostClassCode = '';
            $outermostClass = $menuTree->getOutermostClass();

            if ($childLevel == 0 && $outermostClass) {
                $outermostClassCode = ' class="' . $outermostClass . '" ';
                $currentClass = $child->getClass();

                if (empty($currentClass)) {
                    $child->setClass($outermostClass);
                } else {
                    $child->setClass($currentClass . ' ' . $outermostClass);
                }
            }
            $image = null;
            $menuId = $child->getId();
            if($child->getLevel() === 0) {
                $parentData = $this->getCategoryData($menuId);
            }
            if(!isset($parentData['include_top_menu']) || !$parentData['include_top_menu']) {
                continue;
            }
            if($this->isCategory($menuId) && $child->getLevel() == 1 && isset($parentData['show_children_images']) && $parentData['show_children_images'] == 1) {
                $data = $this->getCategoryData($menuId);
                if (isset($data['image']) && is_string($data['image'])) {
                    $image = $this->_storeManager->getStore()->getBaseUrl(
                            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                        ) . 'catalog/category/' . $data['image'];
                }
            }
            $html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '>';
            if($image) {
                $html .= '<a href="' . $child->getUrl() . '" class="image-link" id="image-link-id-'.$menuId.'" data-title="'.htmlspecialchars($child->getName()).'"><img src="'.$image.'" alt="'.addslashes($child->getName()).'"/></a>';
            }
            $html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . ' id="category-id-'.$menuId.'"><span>' . $this->escapeHtml(
                    $child->getName()
                ) . '</span></a>' . $this->_addSubMenu(
                    $child,
                    $childLevel,
                    $childrenWrapClass,
                    $limit,
                    $parentData
                ) . '</li>';
            $itemPosition++;
            $counter++;
        }

        return $html;
    }

    protected function getCategoryData($categoryId)
    {
        $categoryIdElements = explode('-', $categoryId);
        $category           = $this->categoryRepository->get(end($categoryIdElements));
        $categoryData       = $category->getData();

        return $categoryData;
    }

    protected function isCategory($menuId)
    {
        $menuId = explode('-', $menuId);
        return 'category' == array_shift($menuId);
    }
}