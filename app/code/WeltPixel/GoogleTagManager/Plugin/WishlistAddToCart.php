<?php

namespace WeltPixel\GoogleTagManager\Plugin;

class WishlistAddToCart
{
    /**
     * @var \WeltPixel\GoogleTagManager\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;
    
    /**
     * @param \WeltPixel\GoogleTagManager\Helper\Data $helper
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        \WeltPixel\GoogleTagManager\Helper\Data $helper,
        \Magento\Checkout\Model\Session $checkoutSession
        )
    {
        $this->helper = $helper;
        $this->_checkoutSession = $checkoutSession;
    }

    /**
     * @param \Magento\Wishlist\Model\Item $subject
     * @param $result
     * @return bool
     * @throws \Magento\Catalog\Model\Product\Exception
     */
    public function afterAddToCart(
        \Magento\Wishlist\Model\Item $subject,
        $result)
    {
        if (!$this->helper->isEnabled()) {
            return $result;
        }

        if ($result) {
            $buyRequest = $subject->getBuyRequest();
            $qty = $buyRequest->getData('qty');
            $product = $subject->getProduct();


            /** multiple products can be added at once, so they are merged */
            $currentAddToCartData = $this->_checkoutSession->getAddToCartData();
            $addToCartPushData = $this->helper->addToCartPushData($qty, $product);

            $newAddToCartPushData = $this->helper->mergeAddToCartPushData($currentAddToCartData, $addToCartPushData);
            $this->_checkoutSession->setAddToCartData($newAddToCartPushData);
        }

        return $result;
    }


}
