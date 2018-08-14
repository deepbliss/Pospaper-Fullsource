<?php

namespace WeltPixel\Quickview\Plugin;

class CartAdd
{

    /**
     * @var  \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     *
     * @var  \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * ResultPage constructor.
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder)
    {
        $this->request = $request;
        $this->jsonEncoder = $jsonEncoder;
    }


    /**
     * @param \Magento\Checkout\Controller\Cart\Add $subject
     * @param $result
     * @return mixed
     */
    public function afterExecute(
        \Magento\Checkout\Controller\Cart\Add $subject, $result
    )
    {
        /** Fix for product redirects, ex. when quantity is out of stock */
        $refererUrl = $this->request->getServer('HTTP_REFERER');
        if (strpos($refererUrl, 'weltpixel_quickview/catalog_product/view') !== false) {
            return $subject->getResponse()->representJson($this->jsonEncoder->encode([]));
        }

        return $result;
    }


}
