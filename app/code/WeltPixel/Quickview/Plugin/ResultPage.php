<?php

namespace WeltPixel\Quickview\Plugin;

class ResultPage
{

    /**
     * @var  \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Magento\Framework\View\Layout
     */
    protected $layout;

    /**
     * ResultPage constructor.
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\View\Layout $layout
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\View\Layout $layout)
    {
        $this->request = $request;
        $this->layout = $layout;
    }

    /**
     * Adding the default catalog_product_view_type_ handles as well
     * 
     * @param \Magento\Framework\View\Result\Page $subject
     * @param array $parameters
     * @param type $defaultHandle
     * @return type
     */
    public function beforeAddPageLayoutHandles(
        \Magento\Framework\View\Result\Page $subject, 
        array $parameters = [], 
        $defaultHandle = null)
    {
        if ($this->request->getFullActionName() == 'weltpixel_quickview_catalog_product_view') {
            return [$parameters, 'catalog_product_view'];
        } else {
            return [$parameters, $defaultHandle];
        }
    }

}
