<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model\App\Response;

class Redirect extends \Magento\Store\App\Response\Redirect
{
    /**
     * @param string $url
     *
     * @return string
     */
    public function validateRedirectUrl($url)
    {
        if (!$this->_isUrlInternal($url)) {
            $url = $this->_storeManager->getStore()->getBaseUrl();
        } else {
            $url = $this->normalizeRefererUrl($url);
        }

        return $url;
    }
}
