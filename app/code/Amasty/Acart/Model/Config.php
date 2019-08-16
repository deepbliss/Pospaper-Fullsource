<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Acart
 */


namespace Amasty\Acart\Model;

/**
 * TODO: Move to ConfigProvider
 *
 * @deprecated
 */
class Config
{
    const CONFIG_PATH_GENERAL_CONFIG = 'amasty_acart/general/';

    const CONFIG_PATH_EMAIL_TEMPLATES_CONFIG = 'amasty_acart/email_templates/';

    const CONFIG_PATH_TESTING_CONFIG = 'amasty_acart/testing/';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Config constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param int|string $storeId
     *
     * @return string
     */
    public function getSenderName($storeId)
    {
        return $this->getConfigValueByPath(self::CONFIG_PATH_EMAIL_TEMPLATES_CONFIG . 'sender_name', $storeId);
    }

    /**
     * @param int|string $storeId
     *
     * @return string
     */
    public function getSenderEmail($storeId)
    {
        return $this->getConfigValueByPath(self::CONFIG_PATH_EMAIL_TEMPLATES_CONFIG . 'sender_email', $storeId);
    }

    /**
     * @param int|string $storeId
     *
     * @return string
     */
    public function getBcc($storeId)
    {
        return $this->getConfigValueByPath(self::CONFIG_PATH_EMAIL_TEMPLATES_CONFIG . 'bcc', $storeId);
    }

    /**
     * @param int|string $storeId
     *
     * @return string
     */
    public function isSafeMode($storeId)
    {
        return $this->getConfigValueByPath(self::CONFIG_PATH_TESTING_CONFIG . 'safe_mode', $storeId);
    }

    /**
     * @param int|string $storeId
     *
     * @return string
     */
    public function getTestingRecipientEmail($storeId)
    {
        return $this->getConfigValueByPath(self::CONFIG_PATH_TESTING_CONFIG . 'recipient_email', $storeId);
    }

    /**
     * @param int|string $storeId
     *
     * @return string
     */
    public function getReplyToEmail($storeId)
    {
        return trim($this->getConfigValueByPath(self::CONFIG_PATH_EMAIL_TEMPLATES_CONFIG . 'reply_email', $storeId));
    }

    /**
     * @param int|string $storeId
     *
     * @return string
     */
    public function getReplyToName($storeId)
    {
        return trim($this->getConfigValueByPath(self::CONFIG_PATH_EMAIL_TEMPLATES_CONFIG . 'reply_name', $storeId));
    }

    /**
     * @param int|string $storeId
     *
     * @return bool
     */
    public function isEmailsToNewsletterSubscribersOnly($storeId)
    {
        return (bool)$this->getConfigValueByPath(
            self::CONFIG_PATH_EMAIL_TEMPLATES_CONFIG . 'emails_to_newsletter_subscribers_only',
            $storeId
        );
    }

    /**
     * @return string|int
     */
    public function getHistoryAutoCleanDays()
    {
        return $this->getConfigValueByPath(self::CONFIG_PATH_GENERAL_CONFIG . 'history_clean_days');
    }

    /**
     * @param $path
     * @param null $storeId
     * @param string $scope
     *
     * @return mixed
     */
    public function getConfigValueByPath(
        $path,
        $storeId = null,
        $scope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE
    ) {
        return $this->scopeConfig->getValue($path, $scope, $storeId);
    }

    /**
     * @param int|string $storeId
     *
     * @return mixed
     */
    public function isDisableLoggingForGuests($storeId)
    {
        return $this->getConfigValueByPath(self::CONFIG_PATH_GENERAL_CONFIG . 'disable_logging_for_guests', $storeId);
    }

    /**
     * @return mixed
     */
    public function getEEACountries()
    {
        return $this->getConfigValueByPath(self::CONFIG_PATH_GENERAL_CONFIG . 'eea_countries');
    }
}
