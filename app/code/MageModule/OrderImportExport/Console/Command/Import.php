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
 * @author        MageModule, LLC admin@magemodule.com
 * @copyright     2018 MageModule, LLC
 * @license       http://www.magemodule.com/magento2-ext-license.html
 *
 */

namespace MageModule\OrderImportExport\Console\Command;

use MageModule\OrderImportExport\Exception\ImportException;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\Exception\LocalizedException;

class Import extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \MageModule\Core\Helper\File
     */
    private $fileHelper;

    /**
     * @var \MageModule\Core\Framework\File\Csv
     */
    private $csvProcessor;

    /**
     * @var \MageModule\OrderImportExport\Api\ImporterInterfaceFactory
     */
    private $importerFactory;

    /**
     * @var \MageModule\OrderImportExport\Api\Data\ImportConfigInterfaceFactory
     */
    private $configFactory;

    /**
     * Import constructor.
     *
     * @param \MageModule\Core\Helper\File                                        $fileHelper
     * @param \MageModule\Core\Framework\File\Csv                                 $csvProcessor
     * @param \MageModule\OrderImportExport\Api\ImporterInterfaceFactory          $importerFactory
     * @param \MageModule\OrderImportExport\Api\Data\ImportConfigInterfaceFactory $configFactory
     * @param \Magento\Framework\App\State                                        $state
     * @param null|string                                                         $name
     */
    public function __construct(
        \MageModule\Core\Helper\File $fileHelper,
        \MageModule\Core\Framework\File\Csv $csvProcessor,
        \MageModule\OrderImportExport\Api\ImporterInterfaceFactory $importerFactory,
        \MageModule\OrderImportExport\Api\Data\ImportConfigInterfaceFactory $configFactory,
        \Magento\Framework\App\State $state,
        $name = null
    ) {
        parent::__construct($name);
        $this->fileHelper      = $fileHelper;
        $this->csvProcessor    = $csvProcessor;
        $this->importerFactory = $importerFactory;
        $this->configFactory   = $configFactory;

        try {
            $state->setAreaCode('adminhtml');
        } catch (\Exception $e) {
        }
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $options = [
            new InputOption(
                'filename',
                null,
                InputOption::VALUE_REQUIRED,
                'Relative filepath to file being imported'
            ),
            new InputOption(
                'delimiter',
                null,
                InputOption::VALUE_OPTIONAL,
                'CSV field delimiter'
            ),
            new InputOption(
                'enclosure',
                null,
                InputOption::VALUE_OPTIONAL,
                'CSV field enclosure'
            ),
            new InputOption(
                'create-invoices',
                null,
                InputOption::VALUE_OPTIONAL,
                'Create invoices for order. Enter 1 for yes and 0 for no'
            ),
            new InputOption(
                'create-shipments',
                null,
                InputOption::VALUE_OPTIONAL,
                'Create shipments for order. Enter 1 for yes and 0 for no'
            ),
            new InputOption(
                'create-credit-memos',
                null,
                InputOption::VALUE_OPTIONAL,
                'Create credit memos for order. Enter 1 for yes and 0 for no'
            ),
            new InputOption(
                'import-order-numbers',
                null,
                InputOption::VALUE_OPTIONAL,
                'Import order numbers from csv. Enter 1 for yes and 0 for no'
            ),
            new InputOption(
                'error-limit',
                null,
                InputOption::VALUE_OPTIONAL,
                'Import will be stopped if this limit is reached'
            )
        ];
        $this->setName('sales:orders:import')
            ->setDescription('Import Orders from CSV')
            ->setDefinition($options);
        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $messages = [
            'error'   => [],
            'success' => []
        ];

        $imported    = 0;
        $error       = 0;
        $errorImport = 0;

        try {
            if (!$input->getOption('filename')) {
                throw new LocalizedException(__('You must enter the filepath to import'));
            }

            $filepath = $this->fileHelper->getAbsolutePath($input->getOption('filename'));
            if (!$this->fileHelper->fileExists($filepath)) {
                throw new LocalizedException(__('%1 does not exist on the server', $filepath));
            }

            /** @var \MageModule\OrderImportExport\Api\Data\ImportConfigInterface $config */
            $config = $this->configFactory->create();

            $delimiter = $input->getOption('delimiter');
            if ($delimiter) {
                $config->setDelimiter($delimiter);
            }

            $enclosure = $input->getOption('enclosure');
            if ($enclosure) {
                $config->setEnclosure($enclosure);
            }

            $bool = true;
            if ($input->getOption('create-invoices') !== null) {
                $bool = (bool)$input->getOption('create-invoices');
            }

            $config->setCreateInvoice($bool);

            $bool = true;
            if ($input->getOption('create-shipments') !== null) {
                $bool = (bool)$input->getOption('create-shipments');
            }

            $config->setCreateShipment($bool);

            $bool = true;
            if ($input->getOption('create-credit-memos') !== null) {
                $bool = (bool)$input->getOption('create-credit-memos');
            }

            $config->setCreateCreditMemo($bool);

            $bool = true;
            if ($input->getOption('import-order-numbers') !== null) {
                $bool = (bool)$input->getOption('import-order-numbers');
            }

            $config->setImportOrderNumber($bool);

            $errorLimit = 5;
            if ($input->getOption('error-limit') !== null) {
                $errorLimit = $input->getOption('error-limit');
            }

            $config->setErrorLimit($errorLimit);

            /** @var \MageModule\OrderImportExport\Api\ImporterInterface $model */
            $model = $this->importerFactory->create(['config' => $config]);

            $this->csvProcessor->setDelimiter($config->getDelimiter());
            $this->csvProcessor->setEnclosure($config->getEnclosure());
            $this->csvProcessor->setStream($filepath);

            $headers = [];
            $key     = 0;
            while ($row = $this->csvProcessor->streamReadCsv()) {
                if ($key === 0) {
                    $headers = $row;
                } else {
                    try {
                        $row = array_combine($headers, $row);
                        $model->import($row);
                        $imported++;
                    } catch (ImportException $e) {
                        if ($e->isImported()) {
                            $errorImport++;
                            $messages['error'][] = 'Row #' . $key . ': ' . $e->getMessage();
                        } else {
                            $error++;
                            $messages['error'][] = 'Row #' . $key . ': ' . $e->getMessage();
                        }
                    } catch (\Exception $e) {
                        $error++;
                        $messages['error'][] = 'Row #' . $key . ': ' . $e->getMessage();
                    }

                    if ($errorLimit <= ($error + $errorImport)) {
                        $messages['error'][] = '';
                        $messages['error'][] = sprintf(
                            'Import has been stopped at row #%d because the error limit of %d was reached',
                            $key,
                            $errorLimit
                        );
                        break;
                    }
                }
                $key++;
            }

            $this->csvProcessor->unsetStream();

            if ($key < 1) {
                throw new LocalizedException(
                    __('Your file does not contain any orders.')
                );
            }
        } catch (\Exception $e) {
            $messages['error'][] = $e->getMessage();
        }

        $output->writeln('');
        $output->writeln(sprintf('%d orders imported successfully', $imported));
        if ($error) {
            $output->writeln(sprintf('%d orders were not imported due to error(s)', $error));
        }

        if ($errorImport) {
            $output->writeln(
                sprintf(
                    '%d orders were imported but may have experienced error(s) during the creation of invoices, shipments, or credit memos',
                    $errorImport
                )
            );
        }

        $output->writeln('');

        if ($messages['error']) {
            array_unshift($messages['error'], 'Error Messages:');
            $output->write($messages['error'], true);
            $output->writeln('');
        }

        if ($messages['success']) {
            array_unshift($messages['success'], 'Success Messages:');
            $output->write($messages['success'], true);
        }
    }
}
