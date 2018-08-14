<?php

namespace Mconnectsolutions\Trackorder\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Functionality to get configuration values of plugin
     *
     * @param $configPath: System xml config path
     * @return value of requested configuration
     */
    public function getConfig($configPath)
    {
        return $this->scopeConfig->getValue(
            $configPath,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
