<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Block\Email\Items;

use Magento\Framework\View\Element\Template;

class Wishlist extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Wishlist\Model\WishlistFactory
     */
    private $wishlistFactory;

    public function __construct(
        Template\Context $context,
        \Magento\Wishlist\Model\WishlistFactory $wishlistFactory,
        array $data = []
    ) {
        $this->wishlistFactory = $wishlistFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return array
     */
    public function getItems()
    {
        $wishlistProducts = [];
        $quote = $this->getData('quote');

        if (!$quote) {
            return $wishlistProducts;
        }

        if (!empty($quote->getCustomerId())) {
            /** @var \Magento\Wishlist\Model\Wishlist $wishlist */
            $wishlist = $this->wishlistFactory->create()->loadByCustomerId($quote->getCustomerId());

            $wishlistItems = $wishlist->getItemCollection()->getItems();

            /** @var \Magento\Wishlist\Model\Item $wishlistItem */
            foreach ($wishlistItems as $wishlistItem) {
                /** @var \Magento\Catalog\Model\Product $product */
                $product = $wishlistItem->getProduct();
                $wishlistProducts[] = $product;
            }
        }

        return $wishlistProducts;
    }
}
