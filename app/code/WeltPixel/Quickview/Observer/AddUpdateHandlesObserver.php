<?php
namespace WeltPixel\Quickview\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class AddUpdateHandlesObserver implements ObserverInterface
{      
    /**
    * @var \Magento\Framework\App\Config\ScopeConfigInterface
    */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    const XML_PATH_QUICKVIEW_REMOVE_PRODUCT_IMAGE = 'weltpixel_quickview/general/remove_product_image';
    const XML_PATH_QUICKVIEW_REMOVE_PRODUCT_IMAGE_THUMB = 'weltpixel_quickview/general/remove_product_image_thumb';
    const XML_PATH_QUICKVIEW_REMOVE_AVAILABILITY = 'weltpixel_quickview/general/remove_availability';
    
    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
                                \Magento\Framework\App\Request\Http $request,
                                \Magento\Store\Model\StoreManagerInterface $storeManager,
                                ProductRepositoryInterface $productRepository)
    {
        $this->scopeConfig = $scopeConfig;
        $this->request = $request;
        $this->_storeManager = $storeManager;
        $this->productRepository = $productRepository;
    }
    
    /**
     * Add New Layout handle
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return self
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $layout = $observer->getData('layout');
        $fullActionName = $observer->getData('full_action_name');
        
        if ($fullActionName != 'weltpixel_quickview_catalog_product_view') {
            return $this;
        }

        $productId= $this->request->getParam('id');
        if (isset($productId)) {
            try {
                $product = $this->productRepository->getById($productId, false, $this->_storeManager->getStore()->getId());
            } catch (NoSuchEntityException $e) {
                return false;
            }

            $productType = $product->getTypeId();

            $layout->getUpdate()->addHandle('weltpixel_quickview_catalog_product_view_type_' . $productType);

        }
        
        $removeProductImage = $this->scopeConfig->getValue(self::XML_PATH_QUICKVIEW_REMOVE_PRODUCT_IMAGE,  \Magento\Store\Model\ScopeInterface::SCOPE_STORE);        
        if ($removeProductImage) {
            $layout->getUpdate()->addHandle('weltpixel_quickview_removeproduct_image');
        }
        
        $removeProductImageThumb = $this->scopeConfig->getValue(self::XML_PATH_QUICKVIEW_REMOVE_PRODUCT_IMAGE_THUMB,  \Magento\Store\Model\ScopeInterface::SCOPE_STORE);        
        if ($removeProductImageThumb) {
            $layout->getUpdate()->addHandle('weltpixel_quickview_removeproduct_image_thumb');
        }
        
        $removeAvailability = $this->scopeConfig->getValue(self::XML_PATH_QUICKVIEW_REMOVE_AVAILABILITY,  \Magento\Store\Model\ScopeInterface::SCOPE_STORE);        
        if ($removeAvailability) {
            $layout->getUpdate()->addHandle('weltpixel_quickview_removeavailability');
        }
        
        return $this;
    }
}
