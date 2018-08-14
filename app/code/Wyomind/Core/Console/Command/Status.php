<?php

/* *
 * Copyright Â© 2016 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\Core\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Status extends Command
{

    public $state = null;
    public $licenseHelperFactory = null;
    public $coreHelperFactory = null;

    public function __construct(
    \Magento\Framework\App\State $state,
            \Wyomind\Core\Helper\LicenseFactory $licenseHelperFactory,
            \Wyomind\Core\Helper\DataFactory $coreHelperFactory
    )
    {
        $this->state = $state;
        $this->licenseHelperFactory = $licenseHelperFactory;
        $this->coreHelperFactory = $coreHelperFactory;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('wyomind:license:status')
                ->setDescription(__('Check the status of the licenses for the Wyomind modules'))
                ->setDefinition([]);
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
        
        $licenseHelper = $this->licenseHelperFactory->create();
        $coreHelper = $this->coreHelperFactory->create();
        
        $list = $licenseHelper->getModulesList();

        $table = $this->getHelperSet()->get('table');
        $table->setHeaders(["Module", "Version", "Activation key", "License code"]);
        $table->setRows([]);
        foreach ($list as $info) {
            $data = [
                $info["name"],
                $info["setup_version"],
                $coreHelper->getDefaultConfigUncrypted(str_replace("wyomind_", "", strtolower($info['name'])) . "/license/activation_key"),
                $coreHelper->getDefaultConfigUncrypted(str_replace("wyomind_", "", strtolower($info['name'])) . "/license/activation_code"),
            ];
            $table->addRow($data);
        }
        $output->writeln("");
        $table->render($output);
        $output->writeln("");

        return $returnValue;
    }

}
