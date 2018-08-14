<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Controller\Adminhtml\Customers;

use Magento\Backend\App\Action\Context;
use Aheadworks\RewardPoints\Model\FileUploader;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\Json;
use Magento\Backend\App\Action;

/**
 * Class Upload
 *
 * @package Aheadworks\RewardPoints\Controller\Adminhtml\Customers
 */
class Upload extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aheadworks_RewardPoints::aw_reward_points_customers';

    /**
     * Uploaded file ID
     */
    const FILE_ID = 'csv_file';

    /**
     * @var FileUploader
     */
    private $fileUploader;

    /**
     * @param Context $context
     * @param FileUploader $fileUploader
     */
    public function __construct(
        Context $context,
        FileUploader $fileUploader
    ) {
        parent::__construct($context);
        $this->fileUploader = $fileUploader;
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
        try {
            $result = $this->fileUploader->saveToTmpFolder(self::FILE_ID);
        } catch (\Exception $exception) {
            $result = [
                'error' => $exception->getMessage(),
                'errorcode' => $exception->getCode()
            ];
        }
        return $resultJson->setData($result);
    }
}
