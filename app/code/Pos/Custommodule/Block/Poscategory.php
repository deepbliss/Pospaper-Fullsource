<?php
namespace Pos\Custommodule\Block;
 
class Poscategory extends \Magento\Framework\View\Element\Template {
    protected $_categoryHelper;
     protected $categoryFlatConfig;
     protected $topMenu;
     protected $categoryRepository;
     protected $categoryView;
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Helper\Category $categoryHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Magento\Catalog\Model\Indexer\Category\Flat\State $categoryFlatState,
        \Magento\Theme\Block\Html\Topmenu $topMenu,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,
        \Magento\Catalog\Model\Category $categoryView
    ) {
        $this->_categoryHelper = $categoryHelper;
        $this->categoryFlatConfig = $categoryFlatState;
        $this->topMenu = $topMenu;
         $this->categoryRepository = $categoryRepository;
        $this->categoryView = $categoryView;
        parent::__construct($context);
    }
    /**
     * Return categories helper
     */   
    public function getCategoryHelper()
    {
        return $this->_categoryHelper;
    }
    /**
     * Return top menu html
     * getHtml($outermostClass = '', $childrenWrapClass = '', $limit = 0)
     * example getHtml('level-top', 'submenu', 0)
     */   
    public function getHtml()
    {
        return $this->topMenu->getHtml();
    }
   
    public function getcustomcatUrl($catid = true)
    {
        $categoryObj = $this->categoryRepository->get($catid);
        $cat_url =  $this->_categoryHelper->getCategoryUrl($categoryObj);
        return $cat_url;
    }

    /**
     * Retrieve current store categories
     *
     * @param bool|string $sorted
     * @param bool $asCollection
     * @param bool $toLoad
     * @return \Magento\Framework\Data\Tree\Node\Collection|\Magento\Catalog\Model\Resource\Category\Collection|array
     */    
   public function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        return $this->_categoryHelper->getStoreCategories($sorted , $asCollection, $toLoad);
    }
    /**
     * Retrieve child store categories
     *
     */ 
    public function getChildCategories($category)
    {
           $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();        
            $categoryFactory = $objectManager->get('\Magento\Catalog\Model\CategoryFactory');
            $categoryHelper = $objectManager->get('\Magento\Catalog\Helper\Category');
            $categoryRepository = $objectManager->get('\Magento\Catalog\Model\CategoryRepository');
             
            $categoryId = 217; // YOUR CATEGORY ID
            $category = $categoryFactory->create()->load($categoryId);
            $childrenCategories = $category->getChildrenCategories();

            return $childrenCategories;
    }

     public function getCategoryView() {
    return $this->categoryView;
}


function imageurl($id)
{
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $category_img = $objectManager->create('Magento\Catalog\Model\Category')->load($id);
    $_imgHtml   = '';
    if ($_imgUrl = $category_img->getImageUrl()) 
    {
        $_imgHtml = '<img src="' . $_imgUrl . '" />';
        return $_imgHtml;
    }
}

  }