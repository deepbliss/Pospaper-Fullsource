<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Scope config Provider model
 */
class ConfigProvider
{
    /**
     * xpath prefix of module
     */
    const PATH_PREFIX = 'amasty_acart';

    /**#@+
     * Constants defined for xpath of system configuration
     */
    const XPATH_ONLY_CUSTOMERS = 'general/only_customers';

    const XPATH_DEBUG_MODE_EMAIL_DOMAINS = 'debug/debug_emails';

    const XPATH_DEBUG_MODE_ENABLE = 'debug/debug_enable';

    const XPATH_TEST_RECIPIENT = 'testing/recipient_email';

    /**#@-*/

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * An alias for scope config with default scope type SCOPE_STORE
     *
     * @param string $key
     * @param string|null $scopeCode
     * @param string $scopeType
     *
     * @return string|null
     */
    public function getValue($key, $scopeCode = null, $scopeType = ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->getValue(self::PATH_PREFIX . '/' . $key, $scopeType, $scopeCode);
    }

    /**
     * An alias for scope config with default scope type SCOPE_STORE
     *
     * @param string $key
     *
     * @return string|null
     */
    public function getGlobalValue($key)
    {
        return $this->getValue($key, null, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }

    /**
     * @return bool
     */
    public function isDebugMode()
    {
        return (bool)$this->getGlobalValue(self::XPATH_DEBUG_MODE_ENABLE);
    }

    /**
     * @return bool
     */
    public function isOnlyCustomers()
    {
        return (bool)$this->getGlobalValue(self::XPATH_ONLY_CUSTOMERS);
    }

    /**
     * @return string
     */
    public function getRecipientEmailForTest()
    {
        return $this->getGlobalValue(self::XPATH_TEST_RECIPIENT);
    }

    /**
     * @return array
     */
    public function getDebugEnabledEmailDomains()
    {
        if ($this->isDebugMode()) {
            return explode(',', $this->getGlobalValue(self::XPATH_DEBUG_MODE_EMAIL_DOMAINS));
        }

        return [];
    }
}
