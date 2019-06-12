<?php

namespace Adroll\Pixel\Helper;

use \Magento\Catalog\Model\ProductFactory;
use \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use \Magento\Checkout\Model\Cart;
use \Magento\Checkout\Model\Session as CheckoutSession;
use \Magento\Customer\Model\Session as CustomerSession;
use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Framework\App\Helper\Context;
use \Magento\Framework\Registry;
use \Magento\Framework\View\LayoutInterface;
use \Magento\Sales\Model\OrderFactory;
use \Magento\Search\Model\QueryFactory;
use \Magento\Store\Model\StoreManagerInterface;

class Payload extends AbstractHelper {
    public function __construct(
        Context $context,
        ProductFactory $productFactory,
        CollectionFactory $categoryCollectionFactory,
        Cart $cart,
        CheckoutSession $checkoutSession,
        CustomerSession $customerSession,
        Registry $registry,
        LayoutInterface $layoutInterface,
        OrderFactory $orderFactory,
        QueryFactory $query,
        StoreManagerInterface $storeManager)
    {
        parent::__construct($context);
        $this->_request = $context->getRequest();
        $this->_productFactory = $productFactory;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_cart = $cart;
        $this->_checkoutSession = $checkoutSession;
        $this->_customerSession = $customerSession;
        $this->_registry = $registry;
        $this->_layoutInterface = $layoutInterface;
        $this->_orderFactory = $orderFactory;
        $this->_query = $query;
        $this->_storeManager = $storeManager;
    }

    private function getProductsInCart()
    {
        $products = array();
        $cartItems = $this->_cart->getQuote()->getAllVisibleItems();
        foreach ($cartItems as $item) {
            $productId = $item->getProductId();
            $product = $this->_productFactory->create()->load($productId);
            $productInfo = $this->serializeProduct($product);
            $productInfo['quantity'] = $item->getQty();
            $products[] = $productInfo;
        }
        return $products;
    }

    private function getLastOrder()
    {
        $lastOrderId = $this->_checkoutSession->getLastRealOrderId();
        return $this->_orderFactory->create()->loadByIncrementId($lastOrderId);
    }

    public function serializeProduct($product)
    {
        $store = $this->_storeManager->getStore();
        $productInfo = array();
        $productInfo['product_id'] = $product->getId();

        // Get product price. Product price is stored in base currency, so we must convert it.
        $baseCurrency = $store->getBaseCurrency();
        $currentCurrency = $store->getCurrentCurrency();
        $productInfo['price'] = round($baseCurrency->convert($product->getFinalPrice(), $currentCurrency), 2);
        $productInfo['product_group'] = $this->getProductGroup();

        // Get product category
        $productCategoryIds = $product->getCategoryIds();
        $productCategoryId = array_pop($productCategoryIds);
        $categoryCollection = $this->_categoryCollectionFactory->create();
        $categoryCollection->addAttributeToSelect('*')->addAttributeToFilter('entity_id', $productCategoryId);
        $productInfo['category'] = $categoryCollection->getFirstItem()->getName();

        return $productInfo;
    }

    public function getProductGroup()
    {
        $store = $this->_storeManager->getStore();
        $currentCurrency = $store->getCurrentCurrency();
        return $store->getCode() . '_' . strtolower($currentCurrency->getCode());
    }

    public function getCustomerEmail()
    {
        if ($this->_request->getFullActionName() == 'checkout_onepage_success') {
            return $this->getLastOrder()->getCustomerEmail();
        }
        return $this->_customerSession->getCustomer()->getEmail();
    }

    public function getProductViewPayload()
    {
        $product = $this->_registry->registry('product');
        $payload = array('products' => array());
        $payload['products'][] = $this->serializeProduct($product);
        return $payload;
    }

    public function getCartViewPayload()
    {
        return array('products' => $this->getProductsInCart());
    }

    public function getCheckoutStartPayload()
    {
        return array('products' => $this->getProductsInCart());
    }

    public function getPurchasePayload()
    {
        $payload = array('products' => array());
        $order = $this->getLastOrder();
        $orderItems = $order->getAllItems();
        foreach ($orderItems as $item) {
            $productInfo = $this->serializeProduct($item->getProduct());
            $productInfo['quantity'] = $item->getQtyOrdered();
            $payload['products'][] = $productInfo;
        }
        // Order total is stored in the current currency, no need to convert
        $payload['conversion_value'] = $order->getGrandTotal();
        $payload['order_id'] = $order->getIncrementId();
        return $payload;
    }

    public function getSimpleSearchPayload()
    {
        $payload = array(
            'product_id' => array(),
            'keywords' => $this->_query->get()->getQueryText()
        );

        $search_block = $this->_layoutInterface->getBlock('search_result_list');
        $productCollection = $search_block->getLoadedProductCollection();
        $counter = 0;
        $limit = 15;
        foreach ($productCollection as $product) {
            if ($counter < $limit) {
                $payload['product_id'][] = $product->getId();
                $counter += 1;
            } else {
                break;
            }
        }

        return $payload;
    }
}
