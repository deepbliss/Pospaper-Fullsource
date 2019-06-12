<?php
namespace Adroll\Pixel\Helper;

use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\CatalogInventory\Helper\Stock;
use Magento\ConfigurableProduct\Pricing\Price\LowestPriceOptionsProvider;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Api\StoreRepositoryInterface;

class Feed extends AbstractHelper
{
    const FEED_PAGE_SIZE = 100;

    public function __construct(Context $context,
                                Status $productStatus,
                                Visibility $productVisibility,
                                CollectionFactory $productCollectionFactory,
                                StoreRepositoryInterface $storeRepository,
                                CurrencyFactory $currencyFactory,
                                ImageHelper $imageHelper,
                                Stock $stockFilter,
                                LowestPriceOptionsProvider $lowestPriceOptionsProvider)
    {
        $this->_productStatus = $productStatus;
        $this->_productVisibility = $productVisibility;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_storeRepository = $storeRepository;
        $this->_currencyFactory = $currencyFactory;
        $this->_imageHelper = $imageHelper;
        $this->_stockFilter = $stockFilter;
        $this->_lowestPriceOptionsProvider = $lowestPriceOptionsProvider;

        parent::__construct($context);
    }

    private function getBrand($product)
    {
        $brand = $product->getCustomAttribute('brand');

        if (!is_null($brand)) {
            return $brand->getValue();
        }

        $manufacturer = $product->getCustomAttribute('manufacturer');

        if (!is_null($manufacturer)) {
            return $manufacturer->getValue();
        }

        return null;
    }

    private function getPriceInfo($product, $baseCurrency, $destCurrency)
    {
        switch ($product->getTypeId()){
            case 'configurable':
                $price = 0;
                $salePrice = 0;
                foreach ($this->_lowestPriceOptionsProvider->getProducts($product) as $subProduct) {
                    $subProductPrice = $subProduct->getPrice();
                    $subProductSalePrice = $subProduct->getFinalPrice(1);
                    $price = $price ? min($price, $subProductPrice) : $subProductPrice;
                    $salePrice = $salePrice ? min($salePrice, $subProductSalePrice) : $subProductSalePrice;
                }
                break;
            case 'bundle':
                $finalPriceObj = $product->getPriceInfo()->getPrice('final_price');
                $price = $finalPriceObj->getMinimalPrice()->getValue();
                $salePrice = $price;
                break;
            case 'grouped':
                $prices = array();
                $salePrices = array();
                $associatedProducts = $product->getTypeInstance(true)->getAssociatedProducts($product);
                foreach($associatedProducts as $associatedProduct) {
                    $prices[] = $associatedProduct->getPrice();
                    $salePrices[] = $associatedProduct->getFinalPrice(1);
                }
                sort($prices);
                sort($salePrices);
                $price = $prices[0];
                $salePrice = $salePrices[0];
                break;
            default:
                $price = $product->getPrice();
                $salePrice = $product->getFinalPrice(1);
                break;
        }

        return array(
            'price' => $baseCurrency->convert($price, $destCurrency),
            'sale_price' => $baseCurrency->convert($salePrice, $destCurrency)
        );
    }

    private function serializeProduct($product, $baseCurrency, $destCurrency)
    {
        $generalInfo = array(
            'id' => $product->getId(),
            'title' => $product->getName(),
            'description' => $product->getDescription(),
            'url' => $product->getProductUrl(),
            'brand' => $this->getBrand($product),
            'image_url' => $this->_imageHelper->init($product, 'product_base_image')
                ->constrainOnly(FALSE)
                ->keepAspectRatio(TRUE)
                ->keepFrame(FALSE)
                ->getUrl()
        );

        $priceInfo = $this->getPriceInfo($product, $baseCurrency, $destCurrency);

        return array_merge($generalInfo, $priceInfo);
    }

    public function getFeedableProducts($store)
    {
        $productCollection = $this->_productCollectionFactory->create();
        $productCollection->addAttributeToSelect('*');
        // Exclude disabled products
        $productCollection->addAttributeToFilter('status', ['in' => $this->_productStatus->getVisibleStatusIds()]);
        // Exclude products that are not visible individually
        $productCollection->setVisibility($this->_productVisibility->getVisibleInSiteIds());
        // Exclude products not available in this store
        $productCollection->addStoreFilter($store);
        // Exclude out-of-stock products
        $this->_stockFilter->addInStockFilterToCollection($productCollection);

        return $productCollection;
    }

    public function generateProductFeed($destCurrencyCode, $storeCode, $page)
    {
        $productFeed = array('products' => array());
        $store = $this->_storeRepository->get($storeCode);
        $baseCurrency = $store->getBaseCurrency();
        $destCurrency = $this->_currencyFactory->create()->load($destCurrencyCode);
        $products = $this->getFeedableProducts($store);

        $lastPage = ceil($products->getSize() / self::FEED_PAGE_SIZE);

        if ($page <= $lastPage) {
            $products->setPageSize(self::FEED_PAGE_SIZE)->setCurPage($page);
            foreach ($products as $product) {
                $productFeed['products'][] = $this->serializeProduct($product, $baseCurrency, $destCurrency);
            }
        }
        
        return $productFeed;
    }
}
