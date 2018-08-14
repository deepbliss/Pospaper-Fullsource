<?php
namespace Pos\Custommodule\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\ObjectManagerInterface;

class Bestseller extends \Magento\Framework\View\Element\Template
{
    protected $bestSellerCollection;
    protected $mostViewedCollection;
    protected $objectManager;
    protected $wishListHelper;
    protected $_reviewFactory;
     protected $_storeManager;

    public function __construct(
        Template\Context $context,
        \Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory $bestSellerCollectionFactory,
        ObjectManagerInterface $objectManager,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
         \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Reports\Model\ResourceModel\Product\CollectionFactory $mostViewedCollectionFactory,
        \Magento\Wishlist\Helper\Data $wishListHelper,
        array $data = []
    ) {
        $this->bestSellerCollection = $bestSellerCollectionFactory->create();
        $this->mostViewedCollection = $mostViewedCollectionFactory->create();
        $this->wishListHelper = $wishListHelper;
         $this->_storeManager = $storeManager;  
         $this->_reviewFactory = $reviewFactory;
        $this->objectManager = $objectManager;
        parent::__construct($context, $data);
    }

    public function getBestSellers()
    {
        $productcollection = $this->bestSellerCollection->setModel('Magento\Catalog\Model\Product')
            ->addStoreFilter($this->_storeManager->getStore()->getId())
            ->setPageSize(6);

        return $productcollection;
    }

    public function getRatingSummary($product)
{
    $this->_reviewFactory->create()->getEntitySummary($product, $this->_storeManager->getStore()->getId());
    $ratingSummary = $product->getRatingSummary()->getRatingSummary();
    return $ratingSummary;
}

    public function getProductDetail($product_id)
    {
        $ProductDetail = $this->objectManager->create('Magento\Catalog\Model\Product')
            ->load($product_id);
        return $ProductDetail;
    }

    public function getFormatedPrice($amount)
    {
        return $this->objectManager->create('\Magento\Framework\Pricing\PriceCurrencyInterface')->format($amount,true,2);
    }

    public function getAddtocartlink($collection)
    {
        $listBlock = $this->objectManager->get('\Magento\Catalog\Block\Product\ListProduct');
        return $listBlock->getAddToCartUrl($collection);
    }

    public function getMostViewed()
    {
        $storeId = $this->_storeManager->getStore()->getId();

        $this->mostViewedCollection->addAttributeToSelect(
            '*'
        )->addViewsCount()->setStoreId(
            $storeId
        )->addStoreFilter(
            $storeId
        );

        return $this->mostViewedCollection;
    }

	public function getMediaUrl(){

            $media_dir = $this->objectManager->get('Magento\Store\Model\StoreManagerInterface')
                ->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

            return $media_dir;
        }

    public function getWishlist()
    {
        $collection = $this->wishlistHelper->getWishlist()->getCollection();

        return $collection;
    }
}