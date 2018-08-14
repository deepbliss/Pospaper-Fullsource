<?php

namespace Magehit\Bestsellerproducts\Block;

use Magento\Catalog\Api\CategoryRepositoryInterface;

class BestsellerList extends \Magento\Catalog\Block\Product\ListProduct {

    /**
     * Product collection model
     *
     * @var Magento\Catalog\Model\Resource\Product\Collection
     */
    protected $_collection;

    /**
     * Product collection model
     *
     * @var Magento\Catalog\Model\Resource\Product\Collection
     */
    protected $_productCollection;

    /**
     * Image helper
     *
     * @var Magento\Catalog\Helper\Image
     */
    protected $_imageHelper;

    /**
     * Catalog Layer
     *
     * @var \Magento\Catalog\Model\Layer\Resolver
     */
    protected $_catalogLayer;

    /**
     * @var \Magento\Framework\Data\Helper\PostHelper
     */
    protected $_postDataHelper;

    /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    protected $urlHelper;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $_scopeConfig;
    protected $category;

    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * Initialize
     *
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param array $data
     */
    public function __construct(
    \Magento\Catalog\Block\Product\Context $context, \Magento\Framework\Data\Helper\PostHelper $postDataHelper, \Magento\Catalog\Model\Layer\Resolver $layerResolver, CategoryRepositoryInterface $categoryRepository,\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollection, \Magento\Framework\Url\Helper\Data $urlHelper, \Magento\Catalog\Model\ResourceModel\Product\Collection $collection, \Magento\Catalog\Model\Category $category ,\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Catalog\Helper\Image $imageHelper, array $data = []
    ) {
        $this->imageBuilder = $context->getImageBuilder();
        $this->_catalogLayer = $layerResolver->get();
        $this->_postDataHelper = $postDataHelper;
        $this->categoryRepository = $categoryRepository;
        $this->urlHelper = $urlHelper;
        $this->_collection = $collection;
        $this->_imageHelper = $imageHelper;
        $this->_scopeConfig = $scopeConfig;
        $this->category = $category;
        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper, $data);

        //$this->pageConfig->getTitle()->set(__($this->getPageTitle()));
    }

    /**
     * Get product collection
     */
    protected function getProducts() {
		

        $cat_id = $this->getCatId();
/*        $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $category123 = $_objectManager->create('Magento\Catalog\Model\Category')
        ->load($cat_id);*/
        
        
        return $cat_id;
    }

    /*
     * Load and return product collection 
     */

    public function getLoadedProductCollection() {
        return $this->getProducts();
    }

    /*
     * Get product toolbar
     */

    public function getToolbarHtml() {
        return $this->getChildHtml('pager');
    }

    /*
     * Get grid mode
     */

    public function getMode() {
        return 'grid';
    }

    /**
     * Get image helper
     */
    public function getImageHelper() {
        return $this->_imageHelper;
    }

    /* Check module is enabled or not */

    public function getSectionStatus() {
        return $this->_scopeConfig->getValue('bestsellerproducts_settings/bestseller_products/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    /* Check vertical block is enabled or not */

    public function getVerticalStatus() {
        return $this->_scopeConfig->getValue('bestsellerproducts_settings/vertical_setting/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /* Get the configured limit of products */

    public function getProductLimit() {
        return $this->_scopeConfig->getValue('bestsellerproducts_settings/vertical_setting/limit', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /* Get the configured title of section */

    public function getPageTitle() {
        return $this->_scopeConfig->getValue('bestsellerproducts_settings/vertical_setting/title', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
	/**
     * Get the configured slide auto of section
     * @return int
     */
	public function getSlideAuto(){
		return $this->_scopeConfig->getValue('bestsellerproducts_settings/vertical_setting/slide_auto', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}
	/**
     * Get the configured show pagination of section
     * @return int
     */
	public function getPagination(){
		return $this->_scopeConfig->getValue('bestsellerproducts_settings/vertical_setting/slide_pagination', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}
	/**
     * Get the configured show navigation of section
     * @return int
     */
	public function getNavigation(){
		return $this->_scopeConfig->getValue('bestsellerproducts_settings/vertical_setting/slide_navigation', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}
	/**
     * Get the configured sortby of section
     * @return int
     */
	public function getSortbyCollection(){
		return $this->_scopeConfig->getValue('bestsellerproducts_settings/bestseller_products/sortby', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}
	
	public function getStartDate(){
		return $this->_scopeConfig->getValue('bestsellerproducts_settings/bestseller_products/startdate', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}
	public function getEndDate(){
		return $this->_scopeConfig->getValue('bestsellerproducts_settings/bestseller_products/enddate', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

	public function getVisibleStatus(){
		
		$visibleStatus = 4;
		return $visibleStatus;
	}
}
