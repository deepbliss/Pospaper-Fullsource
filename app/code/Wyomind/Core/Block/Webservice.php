<?php

/**
 * Copyright © 2017 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\Core\Block;

/**
 * 
 */
class Webservice extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Wyomind\Core\Helper\Data 
     */
    protected $_coreHelper = null;

    /**
     * @var
     */
    protected $_cacheManager = null;

    /**
     * @var
     */
    protected $_session = null;

    /**
     * @var string
     */
    protected $_message = "";

    /**
     * @var \Wyomind\Core\Logger\Logger
     */
    protected $_logger = null;

    /**
     * @var boolean
     */
    public $logEnabled = false;

    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $_encryptor = null;

    /**
     * @var \Wyomind\Core\Model\ResourceModel\Config
     */
    protected $_configResourceModel = null;

    /**
     * Class constructor
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Model\Context $contextBis
     * @param \Wyomind\Core\Helper\Data $coreHelper
     * @param \Wyomind\Core\Logger\Logger $logger
     * @param \Magento\Framework\Encryption\EncryptorInterface $encryptor
     * @param \Wyomind\Core\Model\ResourceModel\Config $configResourceModel
     * @param array $data
     */
    public function __construct(
    \Magento\Framework\View\Element\Template\Context $context,
            \Magento\Framework\Model\Context $contextBis,
            \Wyomind\Core\Helper\Data $coreHelper,
            \Wyomind\Core\Logger\Logger $logger,
            \Magento\Framework\Encryption\EncryptorInterface $encryptor,
            \Wyomind\Core\Model\ResourceModel\Config $configResourceModel,
            array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_coreHelper = $coreHelper;
        $this->_cacheManager = $contextBis->getCacheManager();
        $this->_session = $context->getSession();
        $this->_logger = $logger;
        $this->logEnabled = $this->_coreHelper->isLogEnabled();
        $this->_encryptor = $encryptor;
        $this->_configResourceModel = $configResourceModel;

        $activationKey = "";

        $baseUrl = str_replace("{{unsecure_base_url}}", $this->_configResourceModel->getDefaultValueByPath("web/unsecure/base_url"), $this->_configResourceModel->getDefaultValueByPath("web/secure/base_url"));
        if ($baseUrl == "") {
            $baseUrl = str_replace("{{unsecure_base_url}}", $this->_coreHelper->getDefaultConfig("web/unsecure/base_url"), $this->_coreHelper->getDefaultConfig("web/secure/base_url"));
        }

        if ($this->getRequest()->getParam("namespace")) {
            $namespace = $this->getRequest()->getParam("namespace");
            $wgsActivationKey = $this->getRequest()->getParam("wgs_activation_key");
            $wgsStatus = $this->getRequest()->getParam("wgs_status");
            $wgsVersion = $this->getRequest()->getParam("wgs_version");
            $wgsActivation = $this->getRequest()->getParam("wgs_activation");
            $wgsMessage = $this->getRequest()->getParam("wgs_message");

            $activationKey = $this->_encryptor->decrypt($this->_configResourceModel->getDefaultValueByPath("$namespace/license/activation_key"));
            if ($activationKey == "") {
                $activationKey = $this->_coreHelper->getDefaultConfigUncrypted("$namespace/license/activation_key");
            }

            $registeredVersion = $this->_coreHelper->getDefaultConfig("$namespace/license/version");
        } else {
            $this->_message = "<div class='message message-error error'>" . __("Invalid data.") . "</div>";
        }

        if (isset($wgsActivationKey) && $wgsActivationKey == $activationKey) {
            if (isset($wgsStatus)) {
                switch ($wgsStatus) {
                    case "success":
                        $this->notice("---------------------------------");
                        $this->notice("Manual activation for " . $namespace . " (frontend) => success");
                        $this->notice("Activation key: " . $wgsActivationKey);
                        $this->notice("Version: " . $wgsVersion);
                        $this->notice("License code: " . $wgsActivation);
                        $this->_coreHelper->setDefaultConfig("$namespace/license/version", $wgsVersion);
                        $this->_coreHelper->setDefaultConfigCrypted("$namespace/license/activation_code", $wgsActivation);
                        $this->_session->setData("update_" . $namespace, "false");
                        $this->_cacheManager->clean(['config']);
                        $this->_message = "<div class='message message-success success'>" . $wgsMessage . "</div>";
                        break;
                    case "error":
                        $this->notice("---------------------------------");
                        $this->notice("Manual activation for " . $namespace . " (frontend) => error");
                        $this->notice("Activation key: " . $wgsActivationKey);
                        $this->notice("Version: " . $wgsVersion);
                        $this->notice("License code: " . $wgsMessage);
                        $this->_message = "<div class='message message-success success'>" . $wgsMessage . "</div>";
                        $this->_coreHelper->setDefaultConfig("$namespace/license/activation_code", "");
                        $this->_cacheManager->clean(['config']);
                        break;
                    case "uninstall":
                        $this->notice("---------------------------------");
                        $this->notice("Manual uninstallation for " . $namespace . " (frontend)");
                        $this->notice("Message: " . $wgsMessage);
                        $this->_message = "<div class='message message-success success'>" . $wgsMessage . "</div>";
                        $this->_coreHelper->setDefaultConfig("$namespace/license/activation_key", "");
                        $this->_coreHelper->setDefaultConfig("$namespace/license/activation_code", "");
                        $this->_cacheManager->clean(['config']);
                        $this->_message .=
                                "<form action='http://www.wyomind.com/license_activation/?method=post' id='license_uninstall' method='post'>
                                <input type='hidden' type='action' value='uninstall' name='action'>
                                <input type='hidden' value='" . $baseUrl . "' name='domain'>
                                <input type='hidden' value='" . $activationKey . "' name='activation_key'>
                                <input type='hidden' value='" . $registeredVersion . "' name='registered_version'>
                                <button type='submit'>" . __("Click here !") . "</button>
                            </form>"
                        ;
                        break;
                    default:
                        $this->notice("---------------------------------");
                        $this->notice("Frontend");
                        $this->notice("Message: " . __("An error occurs while retrieving the license activation (500)"));
                        $this->_message = __("An error occurs while retrieving the license activation (500)");
                        $this->_coreHelper->setDefaultConfig("$namespace/license/activation_code", "");
                        $this->_cacheManager->clean(['config']);
                        break;
                }
            } else {
                $this->notice("---------------------------------");
                $this->notice("Frontend");
                $this->notice("Message: " . __("An error occurs while retrieving license activation (404)."));
                $this->_message = "<div class='message message-error error'>" . __("An error occurs while retrieving license activation (404).") . "</div>";
            }
        } else {
            $this->notice("---------------------------------");
            $this->notice("Frontend");
            $this->notice("Message: " . __("Invalid activation key."));
            $this->_message = "<div class='message message-error error'>" . __("Invalid activation key.") . "</div>";
        }
    }

    /**
     * Log message in the Wtomind_Core log file
     * @param string $msg
     */
    public function notice($msg)
    {
        if ($this->logEnabled) {
            $this->_logger->notice($msg);
        }
    }

    /**
     * Get the return of the activation process
     * @return string
     */
    public function getMessage()
    {
        return $this->_message;
    }

}
