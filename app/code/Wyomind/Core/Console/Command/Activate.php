<?php

/* *
 * Copyright Â© 2016 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\Core\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Exception\RuntimeException;

/**
 * $ bin/magento help wyomind:elasticsearch:update:config
 * Usage:
 * wyomind:elasticsearch:update:config
 *
 * Options:
 * --help (-h)           Display this help message
 * --quiet (-q)          Do not output any message
 * --verbose (-v|vv|vvv) Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
 * --version (-V)        Display this application version
 * --ansi                Force ANSI output
 * --no-ansi             Disable ANSI output
 * --no-interaction (-n) Do not ask any interactive question
 */
class Activate extends Command
{

    /**
     * @var \Magento\Framework\Module\ModuleListFactory
     */
    public $state = null;
    public $coreHelperFactory = null;
    public $licenseHelperFactory = null;
    public $configFactory = null;

    public function __construct(
    \Magento\Framework\App\State $state,
        \Wyomind\Core\Helper\DataFactory $coreHelperFactory,
        \Wyomind\Core\Helper\LicenseFactory $licenseHelperFactory,
        \Wyomind\Core\Model\ResourceModel\ConfigFactory $configFactory
    )
    {
        $this->state = $state;
        $this->coreHelperFactory = $coreHelperFactory;
        $this->licenseHelperFactory = $licenseHelperFactory;
        $this->configFactory = $configFactory;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('wyomind:license:activate')
            ->setDescription(__('Activate the license for a Wyomind module'))
            ->setDefinition([
                new InputArgument(
                    "module", InputArgument::REQUIRED, __('The module for which you want to activate the license (eg: Wyomind_SimpleGoogleShopping)')
                ),
                new InputArgument(
                    "activation-key", InputArgument::REQUIRED, __('The activation key to use to activate the license')
                )
        ]);
        parent::configure();
    }

    protected function execute(
    InputInterface $input,
        OutputInterface $output
    )
    {

        $returnValue = \Magento\Framework\Console\Cli::RETURN_SUCCESS;


        try {
            $this->_state->setAreaCode('adminhtml');
        } catch (\Exception $e) {
            
        }


        $coreHelper = $this->coreHelperFactory->create();
        $licenseHelper = $this->licenseHelperFactory->create();
        $config = $this->configFactory->create();

        $coreVersion = $licenseHelper->getCoreVersion();

        $list = $licenseHelper->getModulesList();



        $module = $input->getArgument("module");
        $ak = $input->getArgument("activation-key");

        $found = false;
        foreach ($list as $info) {
            if ($module === $info["name"]) {
                $found = true;
                break;
            }
        }


        if (empty($ak)) {
            throw new \Exception(__("The activation key cannot be empty"));
        }
        if (!$found) {
            $message = __("The module %1 cannot be found", $module);
            $message .= "\n" . __("Available modules are:");
            foreach ($list as $info) {
                $message .= "\n  - " . $info['name'];
            }
            throw new \Exception($message);
        }



        $licensingMethod = 1;
        $licenseCode = "";

        $ext = str_replace("wyomind_", "", strtolower($module));
        $currentVersion = $coreHelper->getDefaultConfig($ext . "/license/extension_version");

        $registeredVersion = $config->getDefaultValueByPath($ext . "/license/version");
        if ($registeredVersion == "") {
            $registeredVersion = $coreHelper->getDefaultConfig($ext . "/license/version");
        }

        $domain = str_replace("{{unsecure_base_url}}", $config->getDefaultValueByPath("web/unsecure/base_url"), $config->getDefaultValueByPath("web/secure/base_url"));
        if ($domain == "") {
            $domain = str_replace("{{unsecure_base_url}}", $coreHelper->getDefaultConfig("web/unsecure/base_url"), $coreHelper->getDefaultConfig("web/secure/base_url"));
        }


        $wsUrl = sprintf(\Wyomind\Core\Helper\License::WS_URL, $coreVersion);
        $wsParam = "&rv=" . $registeredVersion . "&cv=" . $currentVersion . "&namespace=" . $ext . "&activation_key=" . $ak . "&domain=" . $domain . "&magento=" . $coreHelper->getMagentoVersion();
        $soapParams = [
            "method" => "get",
            "rv" => ($registeredVersion != null) ? $registeredVersion : "",
            "cv" => ($currentVersion != null) ? $currentVersion : "",
            "namespace" => $ext,
            "activation_key" => $ak,
            "domain" => $domain,
            "magento" => $coreHelper->getMagentoVersion(),
            "licensemanager" => $coreVersion
        ];

        // licence deleted because wrong ak or ac
        if ($registeredVersion != "" && $registeredVersion != $currentVersion && $licenseCode) { // Extension upgrade
            $coreHelper->setDefaultConfig($ext . "/license/activation_code", "");
            $coreHelper->setDefaultConfig($ext . "/license/version", $currentVersion);
            $output->writeln($licenseHelper->sprintfArray("upgrade", [$registeredVersion, $currentVersion]));
        } elseif ($ak && (!$licenseCode || empty($licenseCode)) && $licensingMethod) { // not yet activated --> automatic activation
            try {
                $options = ['location' => \Wyomind\Core\Helper\License::SOAP_URL, 'uri' => \Wyomind\Core\Helper\License::SOAP_URI];
                if (!class_exists("\SoapClient")) {
                    throw new RuntimeException();
                }
                $api = new \SoapClient(null, $options);
                $ws = $api->checkActivation($soapParams);
                $wsResult = json_decode($ws);

                switch ($wsResult->status) {
                    case "success":
                        $output->writeln("<info>" . strip_tags($licenseHelper->sprintfArray("ws_success", [$wsResult->message])) . "</info>");
                        $coreHelper->setDefaultConfig($ext . "/license/version", $wsResult->version);
                        $coreHelper->setDefaultConfigCrypted($ext . "/license/activation_key", $ak);
                        $coreHelper->setDefaultConfigCrypted($ext . "/license/activation_code", $wsResult->activation);
                        break;
                    case "error":
                        $coreHelper->setDefaultConfig($ext . "/license/activation_code", "");
                        throw new \Exception(strip_tags($licenseHelper->sprintfArray("ws_failure", [$wsResult->message])));
                    default:
                        $output->writeln($licenseHelper->sprintfArray("The license cannot be registered (other error): " . $wsUrl . "method=post" . $wsParam));
                        $coreHelper->setDefaultConfig($ext . "/license/activation_code", "");
                        $coreHelper->setDefaultConfig($ext . "/license/get_online_license", "0");
                        throw new \Exception(strip_tags($licenseHelper->sprintfArray("ws_error", [$wsUrl . "method=post" . $wsParam])));
                }
            } catch (\RuntimeException $e) {
                $coreHelper->setDefaultConfig($ext . "/license/activation_code", "");
                $coreHelper->setDefaultConfig($ext . "/license/get_online_license", "0");
                throw new \Exception(__("Soap request not allowed. Switching to manual activation\nPlease activate the license in the Magento backend"));
            }
        }

        return $returnValue;
    }

}
