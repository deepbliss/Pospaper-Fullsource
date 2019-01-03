<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_InvisibleCaptcha
 */


namespace Amasty\InvisibleCaptcha\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface|null
     */
    protected $_scopeConfig = null;

    /**
     * Data constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
        $this->_scopeConfig = $context->getScopeConfig();
    }

    /**
     * Convert string to array
     *
     * @param $string
     * @return array|false
     */
    public function stringValidationAndConvertToArray($string)
    {
        $validate = function ($urls) {
            return preg_split('|\s*[\r\n]+\s*|', $urls, -1, PREG_SPLIT_NO_EMPTY);
        };

        return $validate($string);
    }

    /**
     * Return config value by path
     *
     * @param $path
     * @param null $storeId
     * @param string $scope
     * @return mixed
     */
    public function getConfigValueByPath(
        $path,
        $storeId = null,
        $scope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE
    ) {
        return $this->_scopeConfig->getValue($path, $scope, $storeId);
    }
}
