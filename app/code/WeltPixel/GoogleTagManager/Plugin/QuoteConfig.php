<?php

namespace WeltPixel\GoogleTagManager\Plugin;

class QuoteConfig
{
    /**
     * @var \WeltPixel\GoogleTagManager\Helper\Data
     */
    protected $helper;

    /**
     * @param \WeltPixel\GoogleTagManager\Helper\Data $helper
     */
    public function __construct(
        \WeltPixel\GoogleTagManager\Helper\Data $helper
        )
    {
        $this->helper = $helper;
    }


    /**
     * Add the brand attribute to the quote item product collection as attribute
     * @param \Magento\Quote\Model\Quote\Config $config
     * @param $result
     * @return array
     */
    public function afterGetProductAttributes(
        \Magento\Quote\Model\Quote\Config $config,
        $result
    )
    {
        if (!$this->helper->isEnabled()) {
            return $result;
        }

        $brandAttribute =$this->helper->getBrandAttribute();
        $result[] = $brandAttribute;

        return $result;
    }

}
