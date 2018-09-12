<?php
/**
 * Copyright (c) 2018 MageModule, LLC: All rights reserved
 *
 * LICENSE: This source file is subject to our standard End User License 
 * Agreeement (EULA) that is available through the world-wide-web at the 
 * following URI: http://www.magemodule.com/magento2-ext-license.html.  
 *
 * If you did not receive a copy of the EULA and are unable to obtain it through 
 * the web, please send a note to admin@magemodule.com so that we can mail 
 * you a copy immediately.
 *
 * @author       MageModule, LLC admin@magemodule.com 
 * @copyright   2018 MageModule, LLC
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\OrderImportExport\Console\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Export extends \MageModule\Core\Console\Command\AbstractCommand
{
    /**
     * @var \MageModule\OrderImportExport\Api\ExporterInterfaceFactory
     */
    private $modelFactory;

    /**
     * @var \MageModule\OrderImportExport\Api\Data\ExportConfigInterfaceFactory
     */
    private $configFactory;

    /**
     * Export constructor.
     *
     * @param \MageModule\OrderImportExport\Api\ExporterInterfaceFactory          $modelfactory
     * @param \MageModule\OrderImportExport\Api\Data\ExportConfigInterfaceFactory $configFactory
     * @param \Magento\Framework\App\State                                        $state
     * @param array                                                               $defaultOptions
     * @param null|string                                                         $name
     */
    public function __construct(
        \MageModule\OrderImportExport\Api\ExporterInterfaceFactory $modelfactory,
        \MageModule\OrderImportExport\Api\Data\ExportConfigInterfaceFactory $configFactory,
        \Magento\Framework\App\State $state,
        $defaultOptions = [],
        $name = null
    ) {
        parent::__construct($defaultOptions, $name);
        $this->modelFactory  = $modelfactory;
        $this->configFactory = $configFactory;

        try {
            $state->setAreaCode('adminhtml');
        } catch (\Exception $e) {}
    }

    protected function configure()
    {
        $options = [
            new InputOption(
                'from',
                null,
                InputOption::VALUE_OPTIONAL,
                'Filter by date from in yyyy-mm-dd format'
            ),
            new InputOption(
                'to',
                null,
                InputOption::VALUE_OPTIONAL,
                'Filter by date to in yyyy-mm-dd format'
            ),
            new InputOption(
                'directory',
                null,
                InputOption::VALUE_OPTIONAL,
                'Desired export directory, relative to Magento root. Default output directory is ' .
                $this->getDefaultOption('directory')
            ),
            new InputOption(
                'filename',
                null,
                InputOption::VALUE_OPTIONAL,
                'Desired output filename. Default filename is ' . $this->getDefaultOption('filename')
            ),
            new InputOption(
                'delimiter',
                null,
                InputOption::VALUE_OPTIONAL,
                'CSV field delimiter. Default field delimiter is ' . $this->getDefaultOption('delimiter')
            ),
            new InputOption(
                'enclosure',
                null,
                InputOption::VALUE_OPTIONAL,
                'CSV field enclosure. Default field enclosure is ' . $this->getDefaultOption('enclosure')
            ),
        ];
        $this->setName('sales:orders:export')
            ->setDescription('Export Orders to CSV')
            ->setDefinition($options);
        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            /** @var \MageModule\OrderImportExport\Api\Data\ExportConfigInterface $config */
            $config = $this->configFactory->create();
            $config->setDirectory($this->getDefaultOption('directory'));
            if ($input->getOption('directory')) {
                $config->setDirectory($input->getOption('directory'));
            }

            $config->setFilename($this->getDefaultOption('filename'));
            if ($input->getOption('filename')) {
                $config->setFilename($input->getOption('filename'));
            }

            $config->setDelimiter($this->getDefaultOption('delimiter'));
            if ($input->getOption('delimiter')) {
                $config->setDelimiter($input->getOption('delimiter'));
            }

            $config->setEnclosure($this->getDefaultOption('enclosure'));
            if ($input->getOption('enclosure')) {
                $config->setEnclosure($input->getOption('enclosure'));
            }

            $config->setFrom($input->getOption('from'));
            $config->setTo($input->getOption('to'));

            /** @var \MageModule\OrderImportExport\Api\ExporterInterface $exporter */
            $exporter = $this->modelFactory->create(['config' => $config]);
            $result   = $exporter->export(true);
        } catch (\Exception $e) {
            $result = $e->getMessage();
        }

        $output->writeln($result);
    }
}
