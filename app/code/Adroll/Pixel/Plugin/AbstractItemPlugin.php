<?php

namespace Adroll\Pixel\Plugin;

use \Adroll\Pixel\Helper\Payload;

class AbstractItemPlugin
{
    public function __construct(Payload $payloadHelper)
    {
        $this->_payloadHelper = $payloadHelper;
    }

    public function aroundGetItemData($subject, $proceed, $item)
    {
        $result = $proceed($item);
        $productPayload = $this->_payloadHelper->serializeProduct($item->getProduct());
        $productPayload['quantity'] = $result['qty'];
        $result['adroll_product_payload'] = $productPayload;
        return $result;
    }
}
