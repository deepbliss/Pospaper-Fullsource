<?php

/**
 * Copyright © 2018 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\Core\Helper;

class License
{

    /**
     * Soap url for the license server
     */
    const SOAP_URL = "https://www.wyomind.com/service/license/soap";

    /**
     * Soap uri for the license server
     */
    const SOAP_URI = "https://www.wyomind.com/";

    /**
     * Webservice url for the license server
     */
    const WS_URL = "https://www.wyomind.com/license_activation/?licensemanager=%s&";

    /**
     * @var array
     */
    protected $_messages = [
        "activation_key_warning" => "Your activation key is not yet registered.<br>Go to <a href='%s'>Stores > Configuration > Wyomind > %s</a>.",
        "license_code_warning" => "Your license is not yet activated.<br><a target='_blank' href='%s'>Activate it now !</a>",
        "license_code_updated_warning" => "Your license must be re-activated.<br><a target='_blank' href='%s'>Re-activate it now !</a>",
        "ws_error" => "The Wyomind's license server encountered an error.<br><a target='_blank' href='%s'>Please go to Wyomind license manager</a>",
        "ws_success" => "<b style='color:green'>%s</b>",
        "ws_failure" => "<b style='color:red'>%s</b>",
        "ws_no_allowed" => "Your server doesn't allow remote connections.<br><a target='_blank' href='%s'>Please go to Wyomind license manager</a>",
        "upgrade" => "<u>Extension upgrade from v%s to v%s</u>.<br> Your license must be updated.<br>Please clean all caches and reload this page.",
        "license_warning" => "License Notification"
    ];
    public $modulesList = null;

    public function __construct(
    \Magento\Framework\Module\ModuleListFactory $moduleListFactory)
    {

        $this->modulesList = $moduleListFactory->create();
    }

    /**
     * Print array
     * @param string $format
     * @param array  $arr
     * @return string
     */
    public function sprintfArray(
    $format,
            $arr
    )
    {
        return call_user_func_array("sprintf", array_merge((array) $this->_messages[$format], $arr));
    }

    public function getModulesList()
    {
        $list = $this->modulesList->getAll();
        $list = array_filter($list, function($key) {
            return strpos($key, "Wyomind_") === 0 && $key !== "Wyomind_Core";
        }, ARRAY_FILTER_USE_KEY);
        return $list;
    }

    public function getCoreVersion()
    {
        return $this->modulesList->getOne("Wyomind_Core")['setup_version'];
    }

}
