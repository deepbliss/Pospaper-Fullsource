<?php
/**
 * Paradox Labs, Inc.
 * http://www.paradoxlabs.com
 * 717-431-3330
 *
 * Need help? Open a ticket in our support system:
 *  http://support.paradoxlabs.com
 *
 * @author      Ryan Hoerr <info@paradoxlabs.com>
 * @license     http://store.paradoxlabs.com/license.html
 */

namespace ParadoxLabs\Subscriptions\Model\Service;

/**
 * Payment Class
 */
class Payment
{
    /**
     * @var \ParadoxLabs\Subscriptions\Model\Config
     */
    protected $config;

    /**
     * @var \ParadoxLabs\TokenBase\Helper\Data
     */
    protected $tokenbaseHelper;

    /**
     * Payment constructor.
     *
     * @param \ParadoxLabs\Subscriptions\Model\Config $config
     * @param \ParadoxLabs\TokenBase\Helper\Data $tokenbaseHelper
     */
    public function __construct(
        \ParadoxLabs\Subscriptions\Model\Config $config,
        \ParadoxLabs\TokenBase\Helper\Data $tokenbaseHelper
    ) {
        $this->config = $config;
        $this->tokenbaseHelper = $tokenbaseHelper;
    }

    /**
     * Check whether the given method code is TokenBase.
     *
     * @param string $methodCode
     * @return bool
     */
    public function isTokenBaseMethod($methodCode)
    {
        if (in_array($methodCode, $this->tokenbaseHelper->getActiveMethods(), true)) {
            return true;
        }

        return false;
    }

    /**
     * Check whether the given method code is offline.
     *
     * @param string $methodCode
     * @return bool
     */
    public function isOfflineMethod($methodCode)
    {
        if ($this->config->getPaymentMethodGroup($methodCode) === 'offline') {
            return true;
        }

        return false;
    }

    /**
     * Check whether the given method code is Vault-enabled.
     *
     * @param string $methodCode
     * @return bool
     */
    public function isVaultMethod($methodCode)
    {
        $vaultMethodActive = $this->config->vaultMethodIsActive(
            $methodCode,
            $this->tokenbaseHelper->getCurrentStoreId()
        );

        if ($vaultMethodActive === true) {
            return true;
        }

        return false;
    }

    /**
     * Is the given payment method code allowed for subscriptions?
     *
     * @param string $methodCode
     * @return bool
     */
    public function isAllowedForSubscription($methodCode)
    {
        if ($this->isTokenBaseMethod($methodCode)
            || $this->isOfflineMethod($methodCode)
            || $this->isVaultMethod($methodCode)) {
            return true;
        }

        return false;
    }

    /**
     * Check whether the given method is available for the given quote. Assumes yes if insufficient data.
     *
     * @param string $methodCode
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return bool
     */
    public function isMethodAvailable($methodCode, $quote)
    {
        if (!($quote instanceof \Magento\Quote\Api\Data\CartInterface)) {
            return true;
        }

        try {
            $method = $this->tokenbaseHelper->getMethodInstance($methodCode);

            return $method->isAvailable($quote);
        } catch (\Exception $e) {
            // Noop
        }

        return true;
    }

    /**
     * Get an associative array of enabled offline payment methods (if any).
     *
     * @return \Magento\Payment\Model\MethodInterface[]
     */
    public function getOfflineMethods()
    {
        $methods = [];

        foreach ($this->tokenbaseHelper->getPaymentMethods() as $code => $data) {
            if (isset($data['group']) && $data['group'] === 'offline') {
                try {
                    $method = $this->tokenbaseHelper->getMethodInstance($code);

                    if ((int)$method->getConfigData('active') === 1) {
                        $methods[$code] = $method;
                    }
                } catch (\Exception $e) {
                    // Noop
                }
            }
        }

        return $methods;
    }
}
