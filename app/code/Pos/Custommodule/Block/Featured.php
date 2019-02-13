<?php

namespace Pos\Custommodule\Block;

use Magento\Framework\ObjectManagerInterface;

class Featured extends \Magento\Framework\View\Element\Template
{

    protected $objectManager;
    protected $_reviewFactory;
    protected $_storeManager;
    protected $_productCollectionFactory;
    protected $catalogProductVisibility;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        ObjectManagerInterface $objectManager,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        array $data = []
    )
    {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->objectManager = $objectManager;
        $this->_storeManager = $storeManager;
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->_reviewFactory = $reviewFactory;
        parent::__construct(
            $context,
            $data
        );
    }

    public function getFormatedPrice($amount)
    {
        return $this->objectManager->create('\Magento\Framework\Pricing\PriceCurrencyInterface')->format($amount, true, 2);
    }

    public function getRatingSummary($product)
    {
        $this->_reviewFactory->create()->getEntitySummary($product, $this->_storeManager->getStore()->getId());
        $ratingSummary = $product->getRatingSummary()->getRatingSummary();
        return $ratingSummary;
    }

    public function getAddtocartlink($collection)
    {
        $listBlock = $this->objectManager->get('\Magento\Catalog\Block\Product\ListProduct');
        return $listBlock->getAddToCartUrl($collection);
    }

    public function getMediaUrl()
    {

        $media_dir = $this->objectManager->get('Magento\Store\Model\StoreManagerInterface')
            ->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        return $media_dir;
    }

    public function getProducts()
    {
        $collection = $this->_productCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('status', '1')
            ->addAttributeToFilter('is_featured', '1')
            ->setPageSize(10);
        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());
        return $collection;
    }

}