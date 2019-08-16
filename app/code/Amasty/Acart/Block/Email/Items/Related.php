<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Block\Email\Items;

use Magento\Framework\View\Element\Template;
use Amasty\Acart\Model\SortManager;

class Related extends Template
{
    /**
     * @var SortManager
     */
    private $sortManager;

    public function __construct(
        Template\Context $context,
        SortManager $sortManager,
        array $data = []
    ) {
        $this->sortManager = $sortManager;
        parent::__construct($context, $data);
    }

    /**
     * @return array
     */
    public function getItems()
    {
        $relatedProducts = [];
        $quote = $this->getData('quote');

        if (!$quote) {
            return $relatedProducts;
        }

        /** @var \Magento\Quote\Model\Quote\Item $item */
        foreach ($quote->getAllVisibleItems() as $item) {
            /** @var \Magento\Catalog\Model\Product $product */
            $product = $item->getProduct();
            $relatedProducts = array_merge($relatedProducts, $product->getRelatedProducts());
            $relatedProducts = $this->sortManager->sortProducts($relatedProducts);
        }

        return $relatedProducts;
    }
}
