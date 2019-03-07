<?php

namespace Pos\Custommodule\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class Bestseller extends Template
{
    protected $bestSellerCollection;
    protected $productCollectionFactory;
    protected $catalogProductVisibility;
    protected $objectManager;
    protected $wishListHelper;
    protected $cartHelper;
    protected $imageBuilder;
    protected $_reviewFactory;
    protected $_storeManager;
    protected $priceCurrency;

    public function __construct(
        Template\Context $context,
        \Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory $bestSellerCollectionFactory,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        PriceCurrencyInterface $priceCurrency,
        \Magento\Checkout\Helper\Cart $cartHelper,
        \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder,
        array $data = []
    )
    {
        $this->bestSellerCollection = $bestSellerCollectionFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->priceCurrency = $priceCurrency;
        $this->cartHelper = $cartHelper;
        $this->imageBuilder = $imageBuilder;
        $this->_storeManager = $storeManager;
        $this->_reviewFactory = $reviewFactory;
        parent::__construct($context, $data);
    }

    public function getProductCollection()
    {
        $collection = array();

        switch($this->getCollectionType()) {
            case 'bestsellers';
                $collection = $this->getBestSellers();
                break;

            case 'featured';
                $collection = $this->getFeaturedProducts();
                break;
        }

        return $collection;
    }

    public function getBestSellers()
    {
        $collection = array();
        $productcollection = $this->bestSellerCollection->create();
        $productcollection->setPeriod('year');
        $productcollection->setDateRange('2018-01-01', null);

        $productIds = array();
        foreach($productcollection as $product) {
            $productIds[] = $product->getData('product_id');
        }

        if(!empty($productIds)) {
            $collection = $this->productCollectionFactory->create()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('status', '1')
                ->addAttributeToFilter('entity_id', array('in' => $productIds))
                ->setPageSize(4);
            $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());
        }

        return $collection;
    }

    public function getFeaturedProducts()
    {
        $collection = $this->productCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('status', '1')
            ->addAttributeToFilter('is_featured', '1')
            ->setPageSize(4);
        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());
        return $collection;
    }

    public function getRatingSummary($product)
    {
        $this->_reviewFactory->create()->getEntitySummary($product, $this->_storeManager->getStore()->getId());
        $ratingSummary = $product->getRatingSummary()->getRatingSummary();
        return $ratingSummary;
    }

    public function getFormatedPrice($amount)
    {
        return $this->priceCurrency->format($amount, true, 2);
    }

    public function getAddToCartUrl($product, $additional = [])
    {
        if (!$product->getTypeInstance()->isPossibleBuyFromList($product)) {
            if (!isset($additional['_escape'])) {
                $additional['_escape'] = true;
            }
            if (!isset($additional['_query'])) {
                $additional['_query'] = [];
            }
            $additional['_query']['options'] = 'cart';

            return $this->getProductUrl($product, $additional);
        }
        return $this->cartHelper->getAddUrl($product, $additional);
    }

    public function getProductUrl($product, $additional = [])
    {
        if ($this->hasProductUrl($product)) {
            if (!isset($additional['_escape'])) {
                $additional['_escape'] = true;
            }
            return $product->getUrlModel()->getUrl($product, $additional);
        }

        return '#';
    }

    public function getProductName($str)
    {
        $out = strlen($str) > 104 ? substr($str, 0, 104) . "..." : $str;
        return $out;
    }

    public function hasProductUrl($product)
    {
        if ($product->getVisibleInSiteVisibilities()) {
            return true;
        }
        if ($product->hasUrlDataObject()) {
            if (in_array($product->hasUrlDataObject()->getVisibility(), $product->getVisibleInSiteVisibilities())) {
                return true;
            }
        }

        return false;
    }

    public function getImage($product, $imageId, $attributes = [])
    {
        return $this->imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->setAttributes($attributes)
            ->create();
    }
}