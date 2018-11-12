<?php
namespace WeltPixel\GoogleTagManager\Block;

/**
 * Class \WeltPixel\GoogleTagManager\Block\Cart
 */
class Cart extends \WeltPixel\GoogleTagManager\Block\Core
{
    /**
     * @return \Magento\Eav\Model\Entity\Collection\AbstractCollection|null
     */
    public function getCrosselProductCollection()
    {
        /** @var \Magento\Checkout\Block\Cart\Crosssell $crosselProductListBlock */
        $crosselProductListBlock = $this->_layout->getBlock('checkout.cart.crosssell');

        if (empty($crosselProductListBlock)) {
            return null;
        }
        $crosselProductListBlock->toHtml();

        $collection = $crosselProductListBlock->getItems();
        return $collection;
    }

    /**
     * @return array
     */
    public function getProductIds() {
        $quote = $this->getQuote();
        $products = [];

        $displayOption = $this->helper->getParentOrChildIdUsage();

        foreach ($quote->getAllVisibleItems() as $item) {
            $product = $item->getProduct();

            if ($displayOption == \WeltPixel\GoogleTagManager\Model\Config\Source\ParentVsChild::CHILD) {
                if ($item->getProductType() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
                    $children = $item->getChildren();
                    foreach ($children as $child) {
                        $product = $child->getProduct();
                    }
                }
            }

            $products[] = $this->helper->getGtmProductId($product);
        }

        return $products;
    }

    /**
     * @return float
     */
    public function getCartTotal() {
        $quote = $this->getQuote();
        return $quote->getGrandTotal();
    }
}