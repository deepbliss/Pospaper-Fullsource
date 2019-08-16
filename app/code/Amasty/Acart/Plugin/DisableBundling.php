<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Plugin;

use Magento\Framework\View\Asset\Config as AssetConfig;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;

class DisableBundling
{
    const ACTION_NAME = 'amasty_acart_reports_index';

    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(RequestInterface $httpRequest)
    {
        $this->request = $httpRequest;
    }

    /**
     * @param AssetConfig $subject
     * @param bool $result
     *
     * @return bool
     */
    public function afterIsBundlingJsFiles(AssetConfig $subject, $result)
    {
        if ($this->request->getFullActionName() == self::ACTION_NAME) {

            return false;
        }

        return $result;
    }
}
