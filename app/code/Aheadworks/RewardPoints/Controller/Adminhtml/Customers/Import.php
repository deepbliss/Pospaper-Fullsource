<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Controller\Adminhtml\Customers;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\Json;
use Magento\Backend\App\Action;
use Magento\Framework\File\Csv;
use Aheadworks\RewardPoints\Api\CustomerRewardPointsManagementInterface;
use Aheadworks\RewardPoints\Controller\Adminhtml\Customers\Upload;
use Aheadworks\RewardPoints\Model\Import\PointsSummary as ImportPointsSummary;
use Aheadworks\RewardPoints\Model\Import\Exception\ImportValidatorException;

/**
 * Class Import
 *
 * @package Aheadworks\RewardPoints\Controller\Adminhtml\Customers;
 */
class Import extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aheadworks_RewardPoints::aw_reward_points_customers';

    /**
     * @var Csv
     */
    private $csvProcessor;

    /**
     * @var CustomerRewardPointsManagementInterface
     */
    private $customerRewardPointsService;

    /**
     * @var ImportPointsSummary
     */
    private $importPointsSummary;

    /**
     * @param Context $context
     * @param Csv $csvProcessor
     * @param CustomerRewardPointsManagementInterface $customerRewardPointsService
     * @param ImportPointsSummary $importPointsSummary
     */
    public function __construct(
        Context $context,
        Csv $csvProcessor,
        CustomerRewardPointsManagementInterface $customerRewardPointsService,
        ImportPointsSummary $importPointsSummary
    ) {
        parent::__construct($context);
        $this->csvProcessor = $csvProcessor;
        $this->customerRewardPointsService = $customerRewardPointsService;
        $this->importPointsSummary = $importPointsSummary;
    }

    /**
     * Image uploader action
     *
     * @return Json
     */
    public function execute()
    {
        /** @var Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $result = [];
        try {
            if ($data = $this->getRequest()->getPostValue()) {
                $result = $this->performSave($data);
            }
        } catch (ImportValidatorException $e) {
            $result = [
                'messages' => $e->getMessage(),
                'error' => true
            ];
        } catch (\Exception $e) {
            $result = [
                'messages' => $e->getMessage(),
                'error' => true
            ];
        }
        return $resultJson->setData($result);
    }

    /**
     * Perform save
     *
     * @param [] $data
     * @return string[]
     */
    private function performSave($data)
    {
        $result = [
            'messages' => __('File is not uploaded')
        ];
        if ($fullPathToFile = $this->getFullPathToFile($data)) {
            $importRawData = $this->getProcessedFileContent($fullPathToFile);
            $importedRecords = $this->customerRewardPointsService->importPointsSummary($importRawData);

            $result['messages'] = $this->getResultMessage($importRawData, $importedRecords);
        }

        return $result;
    }

    /**
     * Retrieves full path to already uploaded file with data for import
     *
     * @param array $data
     * @return string|null
     */
    private function getFullPathToFile($data)
    {
        if (isset($data[Upload::FILE_ID][0])
            && isset($data[Upload::FILE_ID][0]['full_path'])
            && $data[Upload::FILE_ID][0]['full_path']
        ) {
            return $data[Upload::FILE_ID][0]['full_path'];
        } else {
            return null;
        }
    }

    /**
     * Get raw data for import
     *
     * @param string $fullPathToFile
     * @return array
     */
    private function getProcessedFileContent($fullPathToFile)
    {
        return $this->csvProcessor->getData($fullPathToFile);
    }

    /**
     * Retrieves result message
     *
     * @param array $importRawData
     * @param array $importedRecords
     * @return \Magento\Framework\Phrase
     */
    private function getResultMessage($importRawData, $importedRecords)
    {
        $countOfRowsToImport = count($importRawData) - ImportPointsSummary::SERVICE_LINES_AMOUNT;
        $countOfImportedRows = count($importedRecords);

        $resultMessage = __(
            'Import successfully completed! %1 of %2 records have been imported. See details in log file: %3',
            $countOfImportedRows,
            $countOfRowsToImport,
            $this->importPointsSummary->getUrlToLogFile()
        );

        return $resultMessage;
    }
}
