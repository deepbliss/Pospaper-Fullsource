<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_InvisibleCaptcha
 */


namespace Amasty\InvisibleCaptcha\Model;

class Captcha
{
    /**
     * Google URL for checking captcha response
     */
    const GOOGLE_VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * Config path to enable/disable module flag
     */
    const CONFIG_PATH_GENERAL_ENABLE_MODULE = 'aminvisiblecaptcha/general/enabledCaptcha';

    /**
     * Config path to captcha site key
     */
    const CONFIG_PATH_GENERAL_SITE_KEY = 'aminvisiblecaptcha/general/captchaKey';

    /**
     * Config path to captcha secret key
     */
    const CONFIG_PATH_GENERAL_SECRET_KEY = 'aminvisiblecaptcha/general/captchaSecret';

    /**
     * Config path to captcha language code
     */
    const CONFIG_PATH_GENERAL_LANGUAGE = 'aminvisiblecaptcha/general/captchaLanguage';

    /**
     * Config path to captcha badge theme
     */
    const CONFIG_PATH_GENERAL_BADGE_THEME = 'aminvisiblecaptcha/general/badgeTheme';

    /**
     * Config path to captcha badge position
     */
    const CONFIG_PATH_GENERAL_BADGE_POSITION = 'aminvisiblecaptcha/general/badgePosition';

    /**
     * Config path to URLs to validate
     */
    const CONFIG_PATH_ADVANCED_URLS = 'aminvisiblecaptcha/advanced/captchaUrls';

    /**
     * Config path to form selectors
     */
    const CONFIG_PATH_ADVANCED_SELECTORS = 'aminvisiblecaptcha/advanced/captchaSelectors';

    /**
     * Config path to enable/disable invisible captcha just for guest
     */
    const CONFIG_PATH_ADVANCED_ENABLE_FOR_GUESTS_ONLY = 'aminvisiblecaptcha/advanced/enabledCaptchaForGuestsOnly';

    /**
     * Config path to whitelist IP for guest
     */
    const CONFIG_PATH_ADVANCED_WHITELIST_IP = 'aminvisiblecaptcha/advanced/ipWhiteList';

    /**
     * Config path to Amasty extensions
     */
    const CONFIG_PATH_AMASTY = 'aminvisiblecaptcha/amasty/';

    /**
     * Amasty extension URLs to validate
     *
     * @var array
     */
    private $additionalURLs = [];

    /**
     * Amasty extension form selectors
     *
     * @var array
     */
    private $additionalSelectors = [];

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \Amasty\InvisibleCaptcha\Helper\Data
     */
    private $helper;

    /**
     * @var \Magento\Framework\HTTP\Adapter\Curl
     */
    protected $curl;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    private $moduleManager;

    /**
     * @var \Magento\Customer\Model\SessionFactory
     */
    private $sessionFactory;

    /**
     * @var \Amasty\Base\Model\GetCustomerIp
     */
    private $getCustomerIp;

    /**
     * Captcha constructor
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Amasty\InvisibleCaptcha\Helper\Data      $helper
     * @param \Magento\Framework\HTTP\Adapter\Curl      $curl
     * @param \Magento\Framework\Module\Manager         $moduleManager
     * @param \Magento\Framework\DataObject             $extensionsData
     * @param \Magento\Customer\Model\SessionFactory    $sessionFactory
     * @param \Amasty\Base\Model\GetCustomerIp          $getCustomerIp
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Amasty\InvisibleCaptcha\Helper\Data $helper,
        \Magento\Framework\HTTP\Adapter\Curl $curl,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Framework\DataObject $extensionsData,
        \Magento\Customer\Model\SessionFactory $sessionFactory,
        \Amasty\Base\Model\GetCustomerIp $getCustomerIp
    ) {
        $this->objectManager = $objectManager;
        $this->helper = $helper;
        $this->curl = $curl;
        $this->moduleManager = $moduleManager;
        $this->sessionFactory = $sessionFactory;
        $this->getCustomerIp = $getCustomerIp;

        foreach ($extensionsData->getData() as $configId => $data) {
            $isSettingEnabled = $this->helper->getConfigValueByPath(
                self::CONFIG_PATH_AMASTY . $configId,
                null,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            if ($isSettingEnabled
                && $this->moduleManager->isEnabled($data['name'])
            ) {
                $this->additionalURLs[] = $data['url'];
                $this->additionalSelectors[] = $data['selector'];
            }
        }
    }

    /**
     * Check is module enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return (bool) $this->helper->getConfigValueByPath(
            self::CONFIG_PATH_GENERAL_ENABLE_MODULE,
            null,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check is module enabled just for guest
     * @return bool
     */
    public function isEnabledForGuestsOnly()
    {
        return (bool) $this->helper->getConfigValueByPath(
            self::CONFIG_PATH_ADVANCED_ENABLE_FOR_GUESTS_ONLY,
            null,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check is need to show captcha
     *
     * @return bool
     */
    public function isNeedToShowCaptcha()
    {
        $session = $this->sessionFactory->create();

        if ($this->isEnabled()) {
            if ($this->isEnabledForGuestsOnly() && !$session->isLoggedIn()
                || !$this->isEnabledForGuestsOnly()
            ) {
                if (!in_array($this->getCustomerIp->getCurrentIp(), $this->getWhiteIps())) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Site key getter
     *
     * @return string
     */
    public function getSiteKey()
    {
        return $this->helper->getConfigValueByPath(
            self::CONFIG_PATH_GENERAL_SITE_KEY,
            null,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Form selectors getter
     *
     * @return string
     */
    public function getSelectors()
    {
        $selectors = trim($this->helper->getConfigValueByPath(
            self::CONFIG_PATH_ADVANCED_SELECTORS,
            null,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        ));

        $selectors = $selectors ? $this->helper->stringValidationAndConvertToArray($selectors) : [];

        return implode(',', array_merge($selectors, $this->additionalSelectors));
    }

    /**
     * URLs to validate getter
     *
     * @return array
     */
    public function getUrls()
    {
        $urls = trim($this->helper->getConfigValueByPath(self::CONFIG_PATH_ADVANCED_URLS));

        $urls = $urls ? $this->helper->stringValidationAndConvertToArray($urls) : [];

        return array_merge($urls, $this->additionalURLs);
    }

    /**
     * @return array
     */
    public function getWhiteIps()
    {
        $ips = trim($this->helper->getConfigValueByPath(self::CONFIG_PATH_ADVANCED_WHITELIST_IP));

        $ips = $ips ? $this->helper->stringValidationAndConvertToArray($ips) : [];

        return $ips;
    }

    /**
     * Language code getter
     *
     * @return string
     */
    public function getLanguage()
    {
        $language = $this->helper->getConfigValueByPath(
            self::CONFIG_PATH_GENERAL_LANGUAGE,
            null,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if ($language && 7 > mb_strlen($language)) {
            $language = '&hl=' . $language;
        } else {
            $language = '';
        }

        return $language;
    }

    /**
     * Verification of token by Google
     *
     * @param string $token
     * @return array
     */
    public function verify($token)
    {
        $verification = [
            'success' => false,
            'error' => __('No reCaptcha token.')
        ];
        if ($token) {
            $curlParams = [
                'secret' => $this->helper->getConfigValueByPath(self::CONFIG_PATH_GENERAL_SECRET_KEY),
                'response' => $token
            ];

            try {
                $this->curl->write(
                    \Zend_Http_Client::POST,
                    self::GOOGLE_VERIFY_URL,
                    '1.1',
                    array(),
                    $curlParams
                );
                $googleResponse = $this->curl->read();
                $responseBody = \Zend_Http_Response::extractBody($googleResponse);
                $googleAnswer = \Zend_Json::decode($responseBody);
                if (array_key_exists('success', $googleAnswer)) {
                    if ($googleAnswer['success']) {
                        $verification['success'] = true;
                    } elseif (array_key_exists('error-codes', $googleAnswer)) {
                        $verification['error'] = $this->getErrorMessage($googleAnswer['error-codes'][0]);
                    }
                }
            } catch (\Exception $e) {
                $verification['error'] = __($e->getMessage());
            }
        }

        return $verification;
    }

    private function getErrorMessage($errorCode)
    {
        $errorCodesGoogle = [
            'missing-input-secret' => __('The secret parameter is missing.'),
            'invalid-input-secret' => __('The secret parameter is invalid or malformed.'),
            'missing-input-response' => __('The response parameter is missing.'),
            'invalid-input-response' => __('The response parameter is invalid or malformed.'),
            'bad-request' => __('The request is invalid or malformed.')
        ];

        if (array_key_exists($errorCode, $errorCodesGoogle)) {
            return $errorCodesGoogle[$errorCode];
        }

        return __('Something is wrong.');
    }

    /**
     * Badge theme getter
     *
     * @return string
     */
    public function getBadgeTheme()
    {
        $badgeTheme = $this->helper->getConfigValueByPath(
            self::CONFIG_PATH_GENERAL_BADGE_THEME,
            null,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        return $badgeTheme;
    }

    /**
     * Badge position getter
     *
     * @return string
     */
    public function getBadgePosition()
    {
        $badgeTheme = $this->helper->getConfigValueByPath(
            self::CONFIG_PATH_GENERAL_BADGE_POSITION,
            null,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        return $badgeTheme;
    }
}
