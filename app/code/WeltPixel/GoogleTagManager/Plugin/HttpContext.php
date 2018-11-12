<?php

namespace WeltPixel\GoogleTagManager\Plugin;

class HttpContext
{

    /**
     * GoogleTagManager context
     */
    const CONTEXT_GTM = 'weltpixel_gtm';

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
     * @param \Magento\Framework\App\Http\Context $subject
     * @return null
     */
    public function beforeGetVaryString(
        \Magento\Framework\App\Http\Context $subject
    ) {
        if ($this->helper->isEnabled()) {
            $subject->setValue(
                self::CONTEXT_GTM,
                'isEnabled',
                ''
            );
        }
        return null;
    }
}